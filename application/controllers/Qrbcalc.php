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
		$data['bearing'] = $this->qra->get_bearing($locator1, $locator2) . "&#186;";
		$latlng1 = $this->qra->qra2latlong($locator1);
		$latlng2 = $this->qra->qra2latlong($locator2);
		$latlng1[0] = number_format((float)$latlng1[0], 3, '.', '');;
		$latlng1[1] = number_format((float)$latlng1[1], 3, '.', '');;
		$latlng2[0] = number_format((float)$latlng2[0], 3, '.', '');;
		$latlng2[1] = number_format((float)$latlng2[1], 3, '.', '');;

		$data['latlng1'] = $latlng1;
		$data['latlng2'] = $latlng2;
		header('Content-Type: application/json');
		echo json_encode($data);
	}
}
