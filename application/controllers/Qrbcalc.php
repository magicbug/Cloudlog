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

		$this->load->model('stations');
        $data['station_locator'] = $this->stations->find_gridsquare();
		
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

		switch ($measurement_base) {
			case 'M':
				$var_dist = " miles";
				break;
			case 'N':
				$var_dist = " nautic miles";
				break;
			case 'K':
				$var_dist = " kilometers";
				break;
		}

		$this->load->library('Qra');

		$data['result'] = $this->qra->bearing($locator1, $locator2, $measurement_base);
		$data['distance'] = $this->qra->distance($locator1, $locator2, $measurement_base) . $var_dist;
		$data['bearing'] = $this->qra->get_bearing($locator1, $locator2) . "&#186; ";
		$data['latlng1'] = $this->qra->qra2latlong($locator1);
		$data['latlng2'] = $this->qra->qra2latlong($locator2);
		header('Content-Type: application/json');
		echo json_encode($data);
	}
}