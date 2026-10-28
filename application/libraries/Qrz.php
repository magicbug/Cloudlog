<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/*
	Controls the interaction with the QRZ.com Subscription based XML API.
*/


class Qrz {

	private $connect_timeout = 3;
	private $timeout = 5;
	private $down_for = 60;

	// Return session key
	public function session($username, $password) {
		// URL to the XML Source
		$xml_feed_url = 'http://xmldata.qrz.com/xml/current/?username='.$username.';password='.urlencode($password).';agent=cloudlog';
		$xml = $this->fetch($xml_feed_url);
		if ($xml === false) {
			return '';
		}

		$parsed = simplexml_load_string($xml);
		if ($parsed === false || !isset($parsed->Session->Key)) {
			return '';
		}

		return (string) $parsed->Session->Key;
	}
	
	// Set Session Key session.
	public function set_session($username, $password) {
	
		$ci = & get_instance();
		$key = $this->session($username, $password);
		if ($key === '') {
			return false;
		}

		$ci->session->set_userdata('qrz_session_key', $key);
		
		return true;
	}


	public function search($callsign, $key, $use_fullname = false)
	{
		$xml_feed_url = 'http://xmldata.qrz.com/xml/current/?s=' . $key . ';callsign=' . $callsign . '';
		$xml_raw = $this->fetch($xml_feed_url);
		if ($xml_raw === false) {
			return array('error' => 'request_failed');
		}

		$xml = simplexml_load_string($xml_raw);
		if ($xml === false) {
			return array('error' => 'request_failed');
		}
		if (!empty($xml->Session->Error)) {
			return array('error' => (string) $xml->Session->Error);
		}

		// Return Required Fields
		$data = array();
		$data['callsign'] = (string)$xml->Callsign->call;

		if ($use_fullname === true) {
			$data['name'] =  (string)$xml->Callsign->fname. ' ' . (string)$xml->Callsign->name;
		} else {
			$data['name'] = (string)$xml->Callsign->fname;
		}
		$data['name'] = trim($data['name']);

		// Sanitise gridsquare to only allow up to 8 characters
		$unclean_gridsquare = (string)$xml->Callsign->grid; // Get the gridsquare from QRZ convert to string
		$clean_gridsquare = strlen($unclean_gridsquare) > 8 ? substr($unclean_gridsquare,0,8) : $unclean_gridsquare; // Trim gridsquare to 8 characters max
		$data['gridsquare'] = $clean_gridsquare;

		$data['city'] = (string)$xml->Callsign->addr2;
		$data['lat'] = (string)$xml->Callsign->lat;
		$data['long'] = (string)$xml->Callsign->lon;
		$data['dxcc'] = (string)$xml->Callsign->dxcc;
		$data['iota'] = (string)$xml->Callsign->iota;
		$data['qslmgr'] = (string)$xml->Callsign->qslmgr;
		$data['image'] = (string)$xml->Callsign->image;

		if ($xml->Callsign->country == "United States") {
			$data['state'] = (string)$xml->Callsign->state;
			$data['us_county'] = (string)$xml->Callsign->county;
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

		// Drop the session lock while waiting on QRZ so the rest of the app can keep running.
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
		return rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'cloudlog_qrz_callbook_down';
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
