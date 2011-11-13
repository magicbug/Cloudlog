<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Callbytxt {

	/*
		Communicates with the Callbytxt API functions
	*/

	public function callsign($callsign)
	{
		ini_set ('display_errors', 1);
		$jsonurl = "http://callbytxt.org/db/".$callsign.".json";
		
		$json = @file_get_contents($jsonurl,0,null,null);
		$json_output = json_decode($json);

		if(isset($json_output)) {
			$data['callsign'] = $json_output->calls->callsign;
			$data['name'] = ucfirst(strtolower((current(explode(' ', $json_output->calls->first_name)))));
			$data['gridsquare'] = ucfirst($json_output->calls->gridsquare);
			
			$data['city'] = ucfirst(strtolower(($json_output->calls->city)));
			
			$data['lat'] = ucfirst($json_output->calls->lat);
			$data['long'] = ucfirst($json_output->calls->long);
			
			return $data;
		}
	}
}

/* End of file Callbytxt.php */