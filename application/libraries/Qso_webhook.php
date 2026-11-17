<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Optional per-user QSO webhooks. Delivery is queued and flushed on shutdown
 * (after fastcgi_finish_request when available) so QSO saves stay fast.
 */
class Qso_webhook {

	const EVENT_CREATED = 'qso.created';
	const EVENT_UPDATED = 'qso.updated';
	const EVENT_TEST = 'webhook.test';

	const SIGNATURE_HEADER = 'X-Cloudlog-Signature';

	const CONNECT_TIMEOUT = 2;
	const TOTAL_TIMEOUT = 2;

	/** @var array<int, array{user_id:int, qso_id:int, event:string}> */
	private static $pending = array();

	/** @var bool */
	private static $shutdown_registered = false;

	/** @var CI_Controller */
	private $CI;

	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->model('user_options_model');
	}

	/**
	 * Queue a webhook for delivery after the HTTP response (when possible).
	 */
	public function queue_qso_event($user_id, $qso_id, $event_type)
	{
		$user_id = (int) $user_id;
		$qso_id = (int) $qso_id;
		if ($user_id <= 0 || $qso_id <= 0) {
			return;
		}

		$config = $this->get_user_webhook_config($user_id);
		if (!$config['enabled'] || $config['url'] === '') {
			return;
		}

		self::$pending[] = array(
			'user_id' => $user_id,
			'qso_id' => $qso_id,
			'event' => $event_type,
		);

		$this->register_shutdown_flush();
	}

	/**
	 * Build JSON payload from a QSO row (object or array).
	 *
	 * @param object|array $qso
	 * @return array<string, mixed>
	 */
	public function build_payload($qso, $event_type)
	{
		if (is_array($qso)) {
			$qso = (object) $qso;
		}

		$datetime_utc = '';
		if (!empty($qso->COL_TIME_ON)) {
			$ts = strtotime((string) $qso->COL_TIME_ON . ' UTC');
			if ($ts !== false) {
				$datetime_utc = gmdate('Y-m-d\TH:i:s\Z', $ts);
			}
		}

		$gridsquare = '';
		if (!empty($qso->COL_GRIDSQUARE)) {
			$gridsquare = (string) $qso->COL_GRIDSQUARE;
		} elseif (!empty($qso->COL_VUCC_GRIDS)) {
			$gridsquare = (string) $qso->COL_VUCC_GRIDS;
		}

		$freq = null;
		if (isset($qso->COL_FREQ) && $qso->COL_FREQ !== '' && $qso->COL_FREQ !== null) {
			$freq = (float) $qso->COL_FREQ;
		}

		$dxcc = null;
		if (isset($qso->COL_DXCC) && $qso->COL_DXCC !== '' && $qso->COL_DXCC !== null) {
			$dxcc = is_numeric($qso->COL_DXCC) ? (int) $qso->COL_DXCC : null;
		}

		$dxcc_name = null;
		if (!empty($qso->name)) {
			$dxcc_name = (string) $qso->name;
		} elseif (!empty($qso->COL_COUNTRY)) {
			$dxcc_name = (string) $qso->COL_COUNTRY;
		}

		$satellite_name = null;
		$satellite_mode = null;
		if (isset($qso->COL_PROP_MODE) && strtoupper((string) $qso->COL_PROP_MODE) === 'SAT') {
			if (!empty($qso->COL_SAT_NAME)) {
				$satellite_name = (string) $qso->COL_SAT_NAME;
			}
			if (!empty($qso->COL_SAT_MODE)) {
				$satellite_mode = (string) $qso->COL_SAT_MODE;
			}
		}

		return array(
			'event' => $event_type,
			'qso_id' => isset($qso->COL_PRIMARY_KEY) ? (int) $qso->COL_PRIMARY_KEY : null,
			'callsign' => isset($qso->COL_CALL) ? (string) $qso->COL_CALL : '',
			'datetime_utc' => $datetime_utc,
			'band' => isset($qso->COL_BAND) ? (string) $qso->COL_BAND : '',
			'mode' => isset($qso->COL_MODE) ? (string) $qso->COL_MODE : '',
			'frequency' => $freq,
			'rst_sent' => isset($qso->COL_RST_SENT) ? (string) $qso->COL_RST_SENT : '',
			'rst_received' => isset($qso->COL_RST_RCVD) ? (string) $qso->COL_RST_RCVD : '',
			'gridsquare' => $gridsquare,
			'dxcc' => $dxcc,
			'dxcc_name' => $dxcc_name,
			'station_callsign' => isset($qso->COL_STATION_CALLSIGN) ? (string) $qso->COL_STATION_CALLSIGN : '',
			'station_profile_name' => isset($qso->station_profile_name) ? (string) $qso->station_profile_name : '',
			'satellite_name' => $satellite_name,
			'satellite_mode' => $satellite_mode,
		);
	}

	/**
	 * Encode, sign, and POST the payload. Single delivery entry point for a future queue worker.
	 *
	 * @return array{ok:bool, http_code:int, error:string}
	 */
	public function send_webhook($url, $secret, array $payload)
	{
		if (!$this->is_allowed_webhook_url($url)) {
			return array('ok' => false, 'http_code' => 0, 'error' => 'Invalid webhook URL');
		}

		if (!function_exists('curl_init')) {
			log_message('error', 'QSO webhook: cURL is not available');
			return array('ok' => false, 'http_code' => 0, 'error' => 'cURL not available');
		}

		$body = json_encode($payload, JSON_UNESCAPED_UNICODE);
		if ($body === false) {
			log_message('error', 'QSO webhook: failed to encode JSON payload');
			return array('ok' => false, 'http_code' => 0, 'error' => 'JSON encode failed');
		}

		$headers = array(
			'Content-Type: application/json',
			'User-Agent: Cloudlog-QSO-Webhook',
		);

		$secret = trim((string) $secret);
		if ($secret !== '') {
			$headers[] = self::SIGNATURE_HEADER . ': ' . $this->sign_body($body, $secret);
		}

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, self::CONNECT_TIMEOUT);
		curl_setopt($ch, CURLOPT_TIMEOUT, self::TOTAL_TIMEOUT);
		curl_setopt($ch, CURLOPT_PROTOCOLS, CURLPROTO_HTTP | CURLPROTO_HTTPS);
		curl_setopt($ch, CURLOPT_REDIR_PROTOCOLS, CURLPROTO_HTTP | CURLPROTO_HTTPS);

		$response = curl_exec($ch);
		$http_code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$curl_errno = curl_errno($ch);
		$curl_error = $curl_errno ? curl_error($ch) : '';
		curl_close($ch);

		if ($curl_errno !== 0) {
			log_message('error', 'QSO webhook: request failed (' . $curl_error . ') url=' . $url);
			return array('ok' => false, 'http_code' => $http_code, 'error' => $curl_error);
		}

		if ($http_code < 200 || $http_code >= 300) {
			log_message('error', 'QSO webhook: HTTP ' . $http_code . ' url=' . $url . ' response=' . substr((string) $response, 0, 200));
			return array('ok' => false, 'http_code' => $http_code, 'error' => 'HTTP ' . $http_code);
		}

		return array('ok' => true, 'http_code' => $http_code, 'error' => '');
	}

	public function sign_body($body, $secret)
	{
		return hash_hmac('sha256', $body, (string) $secret);
	}

	public function is_allowed_webhook_url($url)
	{
		$url = trim((string) $url);
		if ($url === '') {
			return false;
		}

		if (!filter_var($url, FILTER_VALIDATE_URL)) {
			return false;
		}

		$parts = parse_url($url);
		if (!is_array($parts) || empty($parts['scheme'])) {
			return false;
		}

		$scheme = strtolower((string) $parts['scheme']);
		return $scheme === 'http' || $scheme === 'https';
	}

	/**
	 * @return array{enabled:bool, url:string, secret:string}
	 */
	public function get_user_webhook_config($user_id)
	{
		$user_id = (int) $user_id;
		$config = array(
			'enabled' => false,
			'url' => '',
			'secret' => '',
		);

		$rows = $this->CI->user_options_model->get_options('qso_webhook', null, $user_id)->result();
		foreach ($rows as $row) {
			if ($row->option_name !== 'settings') {
				continue;
			}
			if ($row->option_key === 'enabled') {
				$config['enabled'] = ((string) $row->option_value === '1' || (string) $row->option_value === 'true');
			} elseif ($row->option_key === 'url') {
				$config['url'] = trim((string) $row->option_value);
			} elseif ($row->option_key === 'secret') {
				$config['secret'] = (string) $row->option_value;
			}
		}

		return $config;
	}

	public function save_user_webhook_config($user_id, $enabled, $url, $secret, $update_secret)
	{
		$user_id = (int) $user_id;
		$url = trim((string) $url);

		if ($url !== '' && !$this->is_allowed_webhook_url($url)) {
			return false;
		}

		$enabled_value = $enabled ? '1' : '0';
		$options = array(
			'enabled' => $enabled_value,
			'url' => $url,
		);

		if ($update_secret) {
			$options['secret'] = trim((string) $secret);
		}

		$this->CI->user_options_model->set_option('qso_webhook', 'settings', $options, $user_id);

		return true;
	}

	public function build_test_payload()
	{
		$sample = (object) array(
			'COL_PRIMARY_KEY' => 0,
			'COL_CALL' => 'G0VGS',
			'COL_TIME_ON' => gmdate('Y-m-d H:i:s'),
			'COL_BAND' => '20m',
			'COL_MODE' => 'SSB',
			'COL_FREQ' => 14.200,
			'COL_RST_SENT' => '59',
			'COL_RST_RCVD' => '59',
			'COL_GRIDSQUARE' => 'IO91wm',
			'COL_DXCC' => 223,
			'name' => 'England',
			'COL_STATION_CALLSIGN' => 'G0VGS',
			'station_profile_name' => 'Test profile',
			'COL_PROP_MODE' => '',
		);

		return $this->build_payload($sample, self::EVENT_TEST);
	}

	public function flush_pending()
	{
		if (empty(self::$pending)) {
			return;
		}

		$jobs = self::$pending;
		self::$pending = array();

		$this->CI->load->model('logbook_model');

		foreach ($jobs as $job) {
			$config = $this->get_user_webhook_config($job['user_id']);
			if (!$config['enabled'] || $config['url'] === '') {
				continue;
			}

			$query = $this->CI->logbook_model->get_qso($job['qso_id'], true);
			if (!$query || $query->num_rows() < 1) {
				log_message('error', 'QSO webhook: QSO not found id=' . $job['qso_id']);
				continue;
			}

			$payload = $this->build_payload($query->row(), $job['event']);
			$this->send_webhook($config['url'], $config['secret'], $payload);
		}
	}

	/**
	 * Resolve the Cloudlog user that owns a station profile.
	 */
	public function user_id_for_station($station_id)
	{
		$station_id = (int) $station_id;
		if ($station_id <= 0) {
			return 0;
		}

		$row = $this->CI->db->select('user_id')
			->where('station_id', $station_id)
			->get('station_profile')
			->row();

		if (!$row || !isset($row->user_id)) {
			return 0;
		}

		return (int) $row->user_id;
	}

	private function register_shutdown_flush()
	{
		if (self::$shutdown_registered) {
			return;
		}
		self::$shutdown_registered = true;

		register_shutdown_function(array(__CLASS__, 'shutdown_flush'));
	}

	public static function shutdown_flush()
	{
		if (function_exists('fastcgi_finish_request')) {
			@fastcgi_finish_request();
		}

		if (empty(self::$pending)) {
			return;
		}

		$CI =& get_instance();
		if (!$CI) {
			return;
		}

		$CI->load->library('qso_webhook');
		$CI->qso_webhook->flush_pending();
	}
}
