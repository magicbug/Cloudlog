<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*

	Data lookup functions used within Cloudlog

*/
class Qrbcalc extends CI_Controller {

	function __construct() {
		parent::__construct();

		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
	}

	public function index() {
		$data['page_title'] = "QRB Calculator";
		$this->load->view('qrbcalc/index', $data);
	}

	public function calculate() {
		$locator1 = $this->input->post("locator1");
		$locator2 = $this->input->post("locator2");

		if ($this->session->userdata('user_measurement_base') == NULL) {
			$measurement_base = $this->config->item('measurement_base');
		}
		else {
			$measurement_base = $this->session->userdata('user_measurement_base');
		}

		$this->load->library('Qra');

		$data['result'] = $this->qra->bearing($locator1, $locator2, $measurement_base);
		$data['latlng1'] = $this->qra->qra2latlong($locator1);
		$data['latlng2'] = $this->qra->qra2latlong($locator2);
		header('Content-Type: application/json');
		echo json_encode($data);
	}
}