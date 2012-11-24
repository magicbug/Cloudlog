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
    
    //print_r($json_output->$callsign);
    
    
    
    if(isset($json_output)) {
    
      foreach ($json_output as $name => $callsign) {
      
        $data['callsign'] = (string) strtoupper($name);
        $data['name'] = $callsign->first_name;
        $data['gridsquare'] = $callsign->gridsquare;
        $data['city'] = ucfirst(strtolower(($callsign->city)));
        $data['lat'] = ucfirst($callsign->latitude);
        $data['long'] = ucfirst($callsign->longitude);
        
        return $data;
    }
   
    }
  }
}

/* End of file hamio.php */