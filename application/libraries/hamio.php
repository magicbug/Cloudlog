<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Hamio {

  /*
    Communicates with the ham.io API functions
  */

  public function callsign($callsign)
  {
    ini_set ('display_errors', 1);
    $jsonurl = "http://search.ham.io/api/call/".$callsign;
    
    $json = @file_get_contents($jsonurl,0,null,null);
    $json_output = json_decode($json);

    if(isset($json_output)) {
      $data['callsign'] = $json_output->calls->callsign;
      $data['name'] = "$json_output->calls->first_name $json_output->calls->last_name";
      $data['gridsquare'] = $json_output->calls->gridsquare;
      $data['city'] = ucfirst(strtolower(($json_output->calls->city)));
      $data['lat'] = ucfirst($json_output->calls->latitude);
      $data['long'] = ucfirst($json_output->calls->longitude);
      
      return $data;
    }
  }
}

/* End of file hamio.php */