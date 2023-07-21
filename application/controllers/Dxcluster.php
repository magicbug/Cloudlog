<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dxcluster extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
		$this->load->model('logbook_model');
	}


	function qrg_lookup($qrg) {
		$call_found=$this->logbook_model->qrg_lookup($qrg);
		if ($call_found) {
        		header('Content-Type: application/json');
			echo json_encode($call_found, JSON_PRETTY_PRINT);
		} else {
			echo '{ error: "not found" }';
		}
	}

	function call($call) {

		$date = date('Ymd', time());
		$dxcc = $this->logbook_model->dxcc_lookup($call, $date);

		if ($dxcc) {
        		header('Content-Type: application/json');
			echo json_encode($dxcc, JSON_PRETTY_PRINT);
		} else {
			echo '{ error: "not found" }';
		}
	}
}
