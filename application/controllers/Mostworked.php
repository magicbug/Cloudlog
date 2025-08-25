<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mostworked extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }

		// Load language files
		$this->lang->load('most_worked');
	}

	public function index()
	{
		// Check if users logged in
		if ($this->user_model->validate_session() == 0) {
			// user is not logged in
			redirect('user/login');
		}

		$this->load->model('mostworked_model');
		$this->load->model('logbooks_model');

		// Get filter parameters
		$filters = array(
			'band' => $this->input->post('band') ?: 'all',
			'mode' => $this->input->post('mode') ?: 'all',
			'satellite' => $this->input->post('satellite') ?: 'all',
			'fromdate' => $this->input->post('fromdate') ?: '',
			'todate' => $this->input->post('todate') ?: '',
			'min_qsos' => $this->input->post('min_qsos') ?: 5
		);

		// Get active station logbook
		$logbooks_locations_array = $this->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		if (!$logbooks_locations_array) {
			$data['mostworked_callsigns'] = array();
		} else {
			$data['mostworked_callsigns'] = $this->mostworked_model->get_most_worked_callsigns($filters);
		}

		// Get filter dropdown data
		$data['bands'] = $this->mostworked_model->get_bands();
		$data['modes'] = $this->mostworked_model->get_modes();
		$data['satellites'] = $this->mostworked_model->get_satellites();
		$data['filters'] = $filters;

		$data['page_title'] = "Most Worked Callsigns";

		$this->load->view('interface_assets/header', $data);
		$this->load->view('mostworked/index', $data);
		$this->load->view('interface_assets/footer');
	}
}