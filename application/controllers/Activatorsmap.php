<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*

	Data lookup functions used within Cloudlog

*/
class Activatorsmap extends CI_Controller {

	function __construct() {
		parent::__construct();

		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
	}

	public function index() {
		$data['page_title'] = "Activators Map";

		$this->load->model('stations');
        $data['station_locator'] = $this->stations->find_gridsquare();

		$this->load->view('activatorsmap/index', $data);
	}

	public function calculate() {
		$data['grids'] = "test";
		header('Content-Type: application/json');
		echo json_encode($data);
	}
}
