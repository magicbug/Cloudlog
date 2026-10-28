<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
	Controls the interaction with the HamQTH Callbook API
*/


class Hamqth {

	private $connect_timeout = 3;
	private $timeout = 5;
	private $down_for = 60;

	// Return session key
	public function session($username, $password) {
		// URL to the XML Source
		$xml_feed_url = 'https://www.hamqth.com/xml.php?u='.$username.'&p='.urlencode($password);
		$xml = $this->fetch($xml_feed_url);
		if ($xml === false) {
			return '';
		}

		$parsed = simplexml_load_string($xml);
		if ($parsed === false || !isset($parsed->session->session_id)) {
			return '';
		}

		return (string) $parsed->session->session_id;
	}

	// Set Session Key session.
	public function set_session($username, $password) {

		$ci = & get_instance();
		$key = $this->session($username, $password);
		if ($key === '') {
			return false;
		}

		$ci->session->set_userdata('hamqth_session_key', $key);

		return true;
	}


	public function search($callsign, $key)
	{
		$xml_feed_url = 'https://www.hamqth.com/xml.php?id=' . $key . '&callsign=' . $callsign . '&prg=cloudlog';
		$xml_raw = $this->fetch($xml_feed_url);
		if ($xml_raw === false) {
			return array('error' => 'request_failed');
		}

		$xml = simplexml_load_string($xml_raw);
		if ($xml === false) {
			return array('error' => 'request_failed');
		}
		if (!empty($xml->session->error)) {
			return array('error' => (string) $xml->session->error);
		}

		// Return Required Fields
		$data = array();
		$data['callsign'] = (string)$xml->search->callsign;
		$data['name'] = (string)$xml->search->nick;
		$data['gridsquare'] = (string)$xml->search->grid;
		$data['city'] = (string)$xml->search->adr_city;
		$data['lat'] = (string)$xml->search->latitude;
		$data['long'] = (string)$xml->search->longitude;
		$data['dxcc'] = (string)$xml->search->adif;
		$data['iota'] = (string)$xml->search->iota;
		$data['image'] = (string)$xml->search->picture;
		$data['us_state'] = (string)$xml->search->us_state;
		$data['us_county'] = (string)$xml->search->us_county;
		$data['error'] = (string)$xml->session->error;

		if ($xml->search->country == "United States") {
			$data['state'] = (string)$xml->search->us_state;
			$data['us_county'] = (string)$xml->search->us_county;
		} else {
			$data['state'] = null;
			$data['us_county'] = null;
		}

		return $data;
	}

	public function request_failed($result) {
		return is_array($result) && isset($result['error']) && $result['error'] === 'request_failed';
	}

	private function fetch($url) {
		if ($this->is_down()) {
			return false;
		}

		// Drop the session lock while waiting on HamQTH so the rest of the app can keep running.
		$paused = $this->pause_session();

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->connect_timeout);
		curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
		$xml = curl_exec($ch);
		$errno = curl_errno($ch);
		curl_close($ch);

		$this->resume_session($paused);

		if ($xml === false || $errno !== 0) {
			$this->mark_down();
			return false;
		}

		$this->clear_down();
		return $xml;
	}

	private function down_flag_path() {
		return rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'cloudlog_hamqth_callbook_down';
	}

	private function is_down() {
		$path = $this->down_flag_path();
		if (!is_file($path)) {
			return false;
		}
		if ((time() - (int) filemtime($path)) < $this->down_for) {
			return true;
		}
		@unlink($path);
		return false;
	}

	private function mark_down() {
		@touch($this->down_flag_path());
	}

	private function clear_down() {
		$path = $this->down_flag_path();
		if (is_file($path)) {
			@unlink($path);
		}
	}

	private function pause_session() {
		if (session_status() !== PHP_SESSION_ACTIVE) {
			return false;
		}
		session_write_close();
		return true;
	}

	private function resume_session($paused) {
		if (!$paused || session_status() === PHP_SESSION_ACTIVE) {
			return;
		}
		@session_start();
	}
}
