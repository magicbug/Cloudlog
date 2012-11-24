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
    
      foreach ($json_output as $name => $callsign) {
      
        if(isset($name)) {
          $data['callsign'] = strtoupper($name);
        } else {
           $data['callsign'] = $callsign;
        }
        
        if (isset($callsign->first_name)) { 
          $data['name'] = $callsign->first_name;        
        } else {
          $data['name'] = "";
        }

        
        if(isset($callsign->gridsquare)) {
          $data['gridsquare'] = $callsign->gridsquare;
        } else {
          $data['gridsquare'] = "";
        }
        
        if(isset($callsign->city)) {
           $data['city'] = ucfirst(strtolower(($callsign->city)));
        } else {
           $data['city'] = "";
        }
  
        if (isset($callsign->latitude)) {
          $data['lat'] = ucfirst($callsign->latitude);
        } else {
          $data['lat'] = "";
        }
        
        if (isset($callsign->longitude)) {
          $data['long'] = ucfirst($callsign->longitude);
        } else {
           $data['long'] = "";
        }
        
        return $data;
    }
   
    }
  }
}

/* End of file hamio.php */