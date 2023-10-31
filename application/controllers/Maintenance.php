<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Maintenance extends CI_Controller {
	function __construct()
	{
		parent::__construct();
	}
	
	/* User Facing Links to Maintenance URLs */
	public function index() {
		$this->load->model('user_model');
		if(!$this->user_model->authorize(99)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
		$this->load->model('Logbook_model');
		$this->load->model('Stations');
		$data['stations']=$this->Stations->all();
		$data['page_title'] = "Maintenance";
		$data['qsos_with_no_station_id'] = $this->Logbook_model->check_for_station_id();
		if ($data['qsos_with_no_station_id']) {
			$data['calls_wo_sid']=$this->Logbook_model->calls_without_station_id();
		}
		$this->load->view('interface_assets/header', $data);
		$this->load->view('maintenance/main');
		$this->load->view('interface_assets/footer');
	}

	public function reassign() {
		$this->load->model('user_model');
		if(!$this->user_model->authorize(99)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
		$this->load->model('Logbook_model');
		$this->load->model('Stations');
		$call = xss_clean(($this->input->post('call')));
		$qsoids = xss_clean(($this->input->post('qsoids')));
		$station_profile_id = xss_clean(($this->input->post('station_id')));

		// Check if target-station-id exists
		$allowed=false;
		$status=false;
		$stations=$this->Stations->all();
		foreach ($stations->result() as $station) {
			if ($station->station_id == $station_profile_id) { $allowed=true; }
		}
		if ($allowed) {
			$status=$this->Logbook_model->update_station_ids($station_profile_id,$call,$qsoids);
		} else {
			$status=false;
		}

		header('Content-Type: application/json');
		echo json_encode(array('status' => $status));
		return;

	}

}

/* End of file Backup.php */
