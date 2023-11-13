<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/*
	Controls the interaction with the QRZ.com Subscription based XML API.
*/


class Qrz {

	// Return session key
	public function session($username, $password) {
		// URL to the XML Source
		$xml_feed_url = 'http://xmldata.qrz.com/xml/current/?username='.$username.';password='.urlencode($password).';agent=cloudlog';
		
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
		return (string) $xml->Session->Key;
	}
	
	// Set Session Key session.
	public function set_session($username, $password) {
	
		$ci = & get_instance();
		
		// URL to the XML Source
		$xml_feed_url = 'http://xmldata.qrz.com/xml/current/?username='.$username.';password='.urlencode($password).';agent=cloudlog';
		
		// CURL Functions
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $xml_feed_url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$xml = curl_exec($ch);
		curl_close($ch);
		
		// Create XML object
		$xml = simplexml_load_string($xml);
		
		$key = (string) $xml->Session->Key;
	
		$ci->session->set_userdata('qrz_session_key', $key);
		
		return true;
	}


	public function search($callsign, $key, $use_fullname = false)
	{
        $data = null;
        try {
            // URL to the XML Source
            $xml_feed_url = 'http://xmldata.qrz.com/xml/current/?s=' . $key . ';callsign=' . $callsign . '';

            // CURL Functions
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $xml_feed_url);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $xml = curl_exec($ch);
            curl_close($ch);

            // Create XML object
            $xml = simplexml_load_string($xml);
            if (!empty($xml->Session->Error)) return $data['error'] = $xml->Session->Error;

            // Return Required Fields
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
        } finally {

            return $data;
        }
	}
}
