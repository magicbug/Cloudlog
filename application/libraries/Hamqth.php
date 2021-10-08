<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
	Controls the interaction with the HamQTH Callbook API
*/


class Hamqth {

	// Return session key
	public function session($username, $password) {
		// URL to the XML Source
		$xml_feed_url = 'https://www.hamqth.com/xml.php?u='.$username.'&p='.urlencode($password);

		// CURL Functions
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $xml_feed_url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$xml = curl_exec($ch);
		curl_close($ch);

		// Create XML object
		$xml = simplexml_load_string($xml);

		// Return Session Key
		return (string) $xml->session->session_id;
	}

	// Set Session Key session.
	public function set_session($username, $password) {

		$ci = & get_instance();

		// URL to the XML Source
		$xml_feed_url = 'https://www.hamqth.com/xml.php?u='.$username.'&p='.urlencode($password);

		// CURL Functions
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $xml_feed_url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$xml = curl_exec($ch);
		curl_close($ch);

		// Create XML object
		$xml = simplexml_load_string($xml);

		$key = (string) $xml->session->session_id;

		$ci->session->set_userdata('hamqth_session_key', $key);

		return true;
	}


	public function search($callsign, $key)
	{
	    $data = null;
        try {
            // URL to the XML Source
            $xml_feed_url = 'https://www.hamqth.com/xml.php?id=' . $key . '&callsign=' . $callsign . '&prg=cloudlog';

            // CURL Functions
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $xml_feed_url);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $xml = curl_exec($ch);
            curl_close($ch);

            // Create XML object
            $xml = simplexml_load_string($xml);
            if (empty($xml)) return;

            // Return Required Fields
            $data['callsign'] = (string)$xml->search->callsign;
            $data['name'] = (string)$xml->search->nick;
            $data['gridsquare'] = (string)$xml->search->grid;
            $data['city'] = (string)$xml->search->adr_city;
            $data['lat'] = (string)$xml->search->latitude;
            $data['long'] = (string)$xml->search->longitude;
            $data['iota'] = (string)$xml->search->iota;
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
        } finally {
            return $data;
        }
	}
}
