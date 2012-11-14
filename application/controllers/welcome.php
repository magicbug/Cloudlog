<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {


  public function index()
  {
        $this->load->library('hamio');
    
        $callbook = $this->hamio->callsign('m3php');
        
        print_r($callbook);

  }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */