<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
	
	Widgets are designed to be addons to use around the internet.
		
*/

class Widgets extends CI_Controller {

	public function index()
	{
		// Show a help page
	}
	
	
	// Can be used to embed last 11 QSOs in a iframe or javascript include.
	public function qsos() {
		$this->load->model('logbook_model');
		
		$data['last_five_qsos'] = $this->logbook_model->get_last_qsos('11');
		
		$this->load->view('widgets/qsos', $data);
	}
}