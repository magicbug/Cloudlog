<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Version_Dialog extends CI_Controller {
    
	public function displayVersionDialog() {
		$this->load->view('version_dialog/index');
	}
}
?>