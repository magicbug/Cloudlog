<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dxcluster extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
		$this->load->model('dxcluster_model');
	}


	function spots($band,$age = '', $de = '') {
		if ($age == '') {
			$age = $this->optionslib->get_option('dxcluster_maxage');
		}
		if ($de == '') {
			$de = $this->optionslib->get_option('dxcluster_decont');
		}
		$calls_found=$this->dxcluster_model->dxc_spotlist($band, $age, $de);
			header('Content-Type: application/json');
		if ($calls_found) {
			echo json_encode($calls_found, JSON_PRETTY_PRINT);
		} else {
			echo '{ "error": "not found" }';
		}
	}

	function qrg_lookup($qrg) {
		$call_found=$this->dxcluster_model->dxc_qrg_lookup($this->security->xss_clean($qrg));
			header('Content-Type: application/json');
		if ($call_found) {
			echo json_encode($call_found, JSON_PRETTY_PRINT);
		} else {
			echo '{ "error": "not found" }';
		}
	}

	function call($call) {
		$this->load->model('logbook_model');

		$date = date('Ymd', time());
		$dxcc = $this->logbook_model->dxcc_lookup($call, $date);

		if ($dxcc) {
			header('Content-Type: application/json');
			echo json_encode($dxcc, JSON_PRETTY_PRINT);
		} else {
			echo '{ "error": "not found" }';
		}
	}
}
