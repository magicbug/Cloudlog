<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*

TODO
	- Update Edit
	- Store Radio Information
	- Upload to clublog (request api key)
*/

class QSO extends CI_Controller {

	public function index()
	{
		$this->load->model('logbook_model');
		
		$data['notice'] = false;
		
		$data['query'] = $this->logbook_model->last_ten();
		
		$this->load->library('form_validation');

		$this->form_validation->set_rules('start_date', 'Date', 'required');
		$this->form_validation->set_rules('start_time', 'Time', 'required');
		$this->form_validation->set_rules('callsign', 'Callsign', 'required');

		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('layout/header');
			$this->load->view('qso/index', $data);
			$this->load->view('layout/footer');
		}
		else
		{
			// Add QSO
			$this->logbook_model->add();
			
			// Store Basic QSO Info for reuse
			$this->session->set_userdata('band', $this->input->post('band'));
			$this->session->set_userdata('freq', $this->input->post('freq'));
			$this->session->set_userdata('mode', $this->input->post('mode'));
			$this->session->set_userdata('sat_name', $this->input->post('sat_name'));
			$this->session->set_userdata('sat_mode', $this->input->post('sat_mode'));
			
			// Get last Ten QSOs
			$data['query'] = $this->logbook_model->last_ten();
			 
			// Set Any Notice Messages
			$data['notice'] = "QSO Added";
			
			// Load view to create another contact
			$this->load->view('layout/header');
			$this->load->view('qso/index', $data);
			$this->load->view('layout/footer');
		}
	}
	
	function edit() {
	
		$this->load->model('logbook_model');
		$query = $this->logbook_model->qso_info($this->uri->segment(3));
		
		$this->load->library('form_validation');

		$this->form_validation->set_rules('time_on', 'Start Date', 'required');
		$this->form_validation->set_rules('time_off', 'End Date', 'required');
		$this->form_validation->set_rules('callsign', 'Callsign', 'required');

		$data = $query->row(); 
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('layout/header');
			$this->load->view('qso/edit', $data);
			$this->load->view('layout/footer');
		}
		else
		{
			$this->logbook_model->edit();
			$this->session->set_flashdata('notice', 'Record Updated');
			redirect('logbook');
		}
		
	}
}