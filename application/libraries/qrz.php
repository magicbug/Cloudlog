<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/*
	Controls the interaction with the QRZ.com Subscription based XML API.
*/


class Qrz {

	// Return session key
	public function session($username, $password) {
		// URL to the XML Source
		$xml_feed_url = 'http://xmldata.qrz.com/xml/current/?username='.$username.';password='.$password.';agent=cloudlog';
		
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
		$xml_feed_url = 'http://xmldata.qrz.com/xml/current/?username='.$username.';password='.$password.';agent=cloudlog';
		
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


	public function search($callsign, $key)
	{

		// URL to the XML Source
		$xml_feed_url = 'http://xmldata.qrz.com/xml/current/?s='.$key.';callsign='.$callsign.'';
		
		// CURL Functions
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $xml_feed_url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$xml = curl_exec($ch);
		curl_close($ch);
		
		// Create XML object
		$xml = simplexml_load_string($xml);
	
		// Return Required Fields
		$data['callsign'] = (string) $xml->Callsign->call;
		$data['name'] = (string) $xml->Callsign->fname;
		$data['gridsquare'] = (string) $xml->Callsign->grid;	
		$data['city'] = (string) $xml->Callsign->addr2;
		$data['lat'] = (string) $xml->Callsign->lat;
		$data['long'] = (string) $xml->Callsign->lon;
		$data['iota'] = (string) $xml->Callsign->iota;
		
		return $data;
	}
}
