<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {


	public function index()
	{
		//$this->load->view('welcome_message');
		
		
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
		
		print_r($xml);

	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */