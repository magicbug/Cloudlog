<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
	This controller contains features for oqrs (Online QSL Request System)
*/

class Oqrs extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
	}

    public function index() {
		$this->load->model('oqrs_model');

		$data['stations'] = $this->oqrs_model->get_oqrs_stations();
		$data['page_title'] = "Log Search & OQRS";

		$this->load->view('interface_assets/header', $data);
		$this->load->view('oqrs/index');
		$this->load->view('interface_assets/footer');
    }

	public function get_station_info() {
		$this->load->model('oqrs_model');
		$result = $this->oqrs_model->get_station_info($this->input->post('station_id'));

		header('Content-Type: application/json');
		echo json_encode($result);
	}

	public function get_qsos() {
		$this->load->model('bands');
		$data['bands'] = $this->bands->get_worked_bands_oqrs($this->security->xss_clean($this->input->post('station_id')));
		
		$this->load->model('oqrs_model');
		$data['result'] = $this->oqrs_model->get_qsos($this->input->post('station_id'), $this->input->post('callsign'), $data['bands']);
		$data['callsign'] = $this->security->xss_clean($this->input->post('callsign'));

		$this->load->view('oqrs/result', $data);
	}

	public function not_in_log() {
		$data['page_title'] = "Log Search & OQRS";
		
		$this->load->model('bands');
		$data['bands'] = $this->bands->get_worked_bands_oqrs($this->security->xss_clean($this->input->post('station_id')));

		$this->load->view('oqrs/notinlogform', $data);
	}

	public function save_not_in_log() {
		$this->load->model('oqrs_model');
	}

	/*
	* Fetches data when the user wants to make a request form, and loads info via the view
	*/
	public function request_form() {
		$this->load->model('oqrs_model');
		$data['result'] = $this->oqrs_model->getQueryData($this->input->post('station_id'), $this->input->post('callsign'));
		$data['callsign'] = $this->security->xss_clean($this->input->post('callsign'));

		$this->load->view('oqrs/request', $data);
	}
}
