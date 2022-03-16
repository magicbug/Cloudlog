<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Csv extends CI_Controller {

	public function index()	{
		$this->load->model('user_model');

		if(!$this->user_model->authorize(99)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }

		$this->load->model('modes');
		$this->load->model('logbook_model');
		$this->load->model('stations');
		$this->load->model('bands');

		$data['station_profile'] = $this->stations->all_of_user();			// Used in the view for station location select
		$data['worked_bands'] = $this->bands->get_worked_bands(); 	// Used in the view for band select
		$data['modes'] = $this->modes->active(); 					// Used in the view for mode select
		$data['dxcc'] = $this->logbook_model->fetchDxcc(); 			// Used in the view for dxcc select

		$data['page_title'] = "SOTA CSV Export";

		$this->load->view('interface_assets/header', $data);
		$this->load->view('csv/index');
		$this->load->view('interface_assets/footer');

	}

	public function export()  {
		$this->load->model('csv_model');

		// Parameters
		$station_id = $this->security->xss_clean($this->input->post('station_profile'));
		$band = $this->security->xss_clean($this->input->post('band'));
		$mode = $this->security->xss_clean($this->input->post('mode'));
		$dxcc = $this->security->xss_clean($this->input->post('dxcc_id'));
		$cqz = $this->security->xss_clean($this->input->post('cqz'));
		$propagation = $this->security->xss_clean($this->input->post('prop_mode'));
		$fromdate = $this->security->xss_clean($this->input->post('fromdate'));
		$todate = $this->security->xss_clean($this->input->post('todate'));

		// Get QSOs with valid SOTA info
		$data['qsos'] = $this->csv_model->get_qsos($station_id, $band, $mode, $dxcc, $cqz, $propagation, $fromdate, $todate);

		$this->load->view('csv/data/export', $data);
	}

}
