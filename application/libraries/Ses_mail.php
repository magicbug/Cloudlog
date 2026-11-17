<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
	Amazon SES SendEmail API (Signature Version 4).
	Uses IAM access keys. These are separate from the SES SMTP username and password.
	The sender address must be verified in the same region.
*/

class Ses_mail {

	public function send($settings, $message, $transport = null, $now = null) {
		$region = strtolower(trim((string) (isset($settings['region']) ? $settings['region'] : '')));
		$access_key = trim((string) (isset($settings['access_key']) ? $settings['access_key'] : ''));
		$secret_key = trim((string) (isset($settings['secret_key']) ? $settings['secret_key'] : ''));
		$from = trim((string) (isset($settings['from']) ? $settings['from'] : ''));
		$from_name = isset($settings['from_name']) ? $settings['from_name'] : '';

		if ($region === '' || $access_key === '' || $secret_key === '') {
			return $this->failure('Amazon SES region, access key, and secret key are required.');
		}
		if (!preg_match('/^[a-z0-9-]{1,32}$/', $region)) {
			return $this->failure('Amazon SES region is not valid.');
		}
		if (!filter_var($from, FILTER_VALIDATE_EMAIL)) {
			return $this->failure('The sender email address is not valid.');
		}

		$recipients = $this->addresses(isset($message['to']) ? $message['to'] : '');
		if (count($recipients) === 0) {
			return $this->failure('The recipient email address is missing.');
		}
		foreach ($recipients as $recipient) {
			if (!filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
				return $this->failure('A recipient email address is not valid.');
			}
		}

		$reply_to = trim((string) (isset($message['reply_to']) ? $message['reply_to'] : ''));
		if ($reply_to !== '' && !filter_var($reply_to, FILTER_VALIDATE_EMAIL)) {
			return $this->failure('The reply-to email address is not valid.');
		}

		$subject = str_replace(array("\r", "\n"), ' ', (string) (isset($message['subject']) ? $message['subject'] : ''));
		$body = (string) (isset($message['body']) ? $message['body'] : '');
		$amz_date = ($now !== null && $now !== '') ? (string) $now : gmdate('Ymd\THis\Z');
		$host = 'email.'.$region.'.amazonaws.com';

		$fields = array(
			'Action' => 'SendEmail',
			'Source' => $this->format_address($from, $from_name),
		);
		foreach ($recipients as $index => $recipient) {
			$fields['Destination.ToAddresses.member.'.($index + 1)] = $recipient;
		}
		if ($reply_to !== '') {
			$reply_name = isset($message['reply_to_name']) ? $message['reply_to_name'] : '';
			$fields['ReplyToAddresses.member.1'] = $this->format_address($reply_to, $reply_name);
		}
		$fields['Message.Subject.Data'] = $subject;
		$fields['Message.Subject.Charset'] = 'UTF-8';
		$fields['Message.Body.Text.Data'] = $body;
		$fields['Message.Body.Text.Charset'] = 'UTF-8';

		$payload = $this->form_body($fields);
		$headers = array(
			'content-type' => 'application/x-www-form-urlencoded',
			'host' => $host,
			'x-amz-date' => $amz_date,
		);
		$signed = $this->sign('POST', $host, '/', '', $headers, $payload, $access_key, $secret_key, $region, 'ses', $amz_date);

		$request = array(
			'url' => 'https://'.$host.'/',
			'body' => $payload,
			'headers' => array(
				'Content-Type: application/x-www-form-urlencoded',
				'X-Amz-Date: '.$amz_date,
				'Authorization: '.$signed['authorization'],
			),
		);

		if ($transport === null) {
			$response = $this->http_post($request);
		} else {
			$response = call_user_func($transport, $request);
		}

		return $this->parse_response($response);
	}

	/*
		Returns authorization, signature, and the canonical request.
		$header values are the headers included in the signature, keyed by name.
	*/
	public function sign($method, $host, $uri, $query, $headers, $payload, $access_key, $secret, $region, $service, $amz_datetime) {
		$canonical_headers = '';
		$signed_names = array();
		$normalised = array();
		foreach ($headers as $name => $value) {
			$normalised[strtolower(trim($name))] = preg_replace('/\s+/', ' ', trim((string) $value));
		}
		ksort($normalised);
		foreach ($normalised as $name => $value) {
			$canonical_headers .= $name.':'.$value."\n";
			$signed_names[] = $name;
		}
		$signed_headers = implode(';', $signed_names);
		$canonical = strtoupper($method)."\n"
			.$uri."\n"
			.$query."\n"
			.$canonical_headers."\n"
			.$signed_headers."\n"
			.hash('sha256', (string) $payload);

		$datestamp = substr($amz_datetime, 0, 8);
		$scope = $datestamp.'/'.$region.'/'.$service.'/aws4_request';
		$string_to_sign = "AWS4-HMAC-SHA256\n".$amz_datetime."\n".$scope."\n".hash('sha256', $canonical);
		$signature = hash_hmac('sha256', $string_to_sign, $this->signing_key($secret, $datestamp, $region, $service));

		return array(
			'canonical' => $canonical,
			'signature' => $signature,
			'authorization' => 'AWS4-HMAC-SHA256 Credential='.$access_key.'/'.$scope.', SignedHeaders='.$signed_headers.', Signature='.$signature,
		);
	}

	private function signing_key($secret, $datestamp, $region, $service) {
		$date_key = hash_hmac('sha256', $datestamp, 'AWS4'.$secret, true);
		$region_key = hash_hmac('sha256', $region, $date_key, true);
		$service_key = hash_hmac('sha256', $service, $region_key, true);
		return hash_hmac('sha256', 'aws4_request', $service_key, true);
	}

	private function form_body($fields) {
		$parts = array();
		foreach ($fields as $key => $value) {
			$parts[] = rawurlencode($key).'='.rawurlencode($value);
		}
		return implode('&', $parts);
	}

	private function addresses($to) {
		if (is_array($to)) {
			$list = $to;
		} else {
			$list = preg_split('/\s*,\s*/', (string) $to);
		}
		$out = array();
		foreach ($list as $item) {
			$item = trim((string) $item);
			if ($item !== '') {
				$out[] = $item;
			}
		}
		return $out;
	}

	private function format_address($email, $name) {
		$email = trim(str_replace(array("\r", "\n"), '', (string) $email));
		$name = trim(str_replace(array("\r", "\n"), '', (string) $name));
		if ($name === '') {
			return $email;
		}
		if (preg_match('/[^\x20-\x7E]/', $name)) {
			return '=?UTF-8?B?'.base64_encode($name).'?= <'.$email.'>';
		}
		$name = str_replace(array('\\', '"'), array('\\\\', '\\"'), $name);
		return '"'.$name.'" <'.$email.'>';
	}

	private function http_post($request) {
		if (!function_exists('curl_init')) {
			return array('status' => 0, 'body' => '', 'error' => 'PHP cURL is not available.');
		}
		$ch = curl_init($request['url']);
		curl_setopt_array($ch, array(
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => $request['body'],
			CURLOPT_HTTPHEADER => $request['headers'],
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_TIMEOUT => 20,
			CURLOPT_CONNECTTIMEOUT => 10,
		));
		$body = curl_exec($ch);
		$error = curl_error($ch);
		$status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		if ($body === false) {
			$body = '';
		}
		return array('status' => $status, 'body' => $body, 'error' => $error);
	}

	private function parse_response($response) {
		if (!is_array($response)) {
			return $this->failure('Amazon SES did not respond.');
		}
		$status = isset($response['status']) ? (int) $response['status'] : 0;
		$body = isset($response['body']) ? (string) $response['body'] : '';
		$transport_error = isset($response['error']) ? trim((string) $response['error']) : '';
		if ($status === 0) {
			$detail = $transport_error !== '' ? $transport_error : 'Amazon SES did not respond.';
			return $this->failure($detail);
		}
		if (preg_match('/<MessageId>([^<]+)<\/MessageId>/', $body, $match) && $status >= 200 && $status < 300) {
			return array('sent' => true, 'error' => '', 'message_id' => html_entity_decode($match[1], ENT_QUOTES, 'UTF-8'));
		}
		$code = '';
		$detail = '';
		if (preg_match('/<Code>([^<]+)<\/Code>/', $body, $match)) {
			$code = html_entity_decode($match[1], ENT_QUOTES, 'UTF-8');
		}
		if (preg_match('/<Message>([^<]*)<\/Message>/', $body, $match)) {
			$detail = html_entity_decode($match[1], ENT_QUOTES, 'UTF-8');
		}
		if ($code !== '' && $detail !== '') {
			return $this->failure($code.': '.$detail);
		}
		if ($detail !== '') {
			return $this->failure($detail);
		}
		if ($code !== '') {
			return $this->failure($code);
		}
		return $this->failure('Amazon SES rejected the message (HTTP '.$status.').');
	}

	private function failure($error) {
		return array('sent' => false, 'error' => $error, 'message_id' => null);
	}
}
