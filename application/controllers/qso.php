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
		$data['notice'] = false;
		
		  $this->db->select('COL_CALL, COL_BAND, COL_TIME_ON, COL_RST_RCVD, COL_RST_SENT, COL_MODE, COL_NAME, COL_COUNTRY, COL_PRIMARY_KEY');
  			$this->db->order_by("COL_TIME_ON", "desc"); 
			$this->db->limit(10);
			$data['query'] = $this->db->get($this->config->item('table_name'));
		
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
			// Join date+time
			$datetime = date('Y-m-d') ." ". $this->input->post('start_time');
			
			// Create array with QSO Data
			$data = array(
			   'COL_TIME_ON' => $datetime,
			   'COL_TIME_OFF' => $datetime,
			   'COL_CALL' => strtoupper($this->input->post('callsign')),
			   'COL_BAND' => $this->input->post('band'),
			   'COL_FREQ' => $this->input->post('freq'),
			   'COL_MODE' => $this->input->post('mode'),
			   'COL_RST_RCVD' => $this->input->post('rst_recv'),
			   'COL_RST_SENT' => $this->input->post('rst_sent'),
			   'COL_COMMENT' => $this->input->post('comment'),
			   'COL_SAT_NAME' => $this->input->post('sat_name'),
			   'COL_SAT_MODE' => $this->input->post('sat_mode'),
			   'COL_GRIDSQUARE' => $this->input->post('locator'),
			   'COL_COUNTRY' => $this->input->post('country'),
			   'COL_MY_RIG' => $this->input->post('equipment'),
			);
	
			// Add QSO to database
			$this->db->insert($this->config->item('table_name'), $data);
			$this->session->set_userdata('band', $this->input->post('band'));
			$this->session->set_userdata('freq', $this->input->post('freq'));
			$this->session->set_userdata('mode', $this->input->post('mode'));
			 
			 		  $this->db->select('COL_CALL, COL_BAND, COL_TIME_ON, COL_RST_RCVD, COL_RST_SENT, COL_MODE, COL_NAME, COL_COUNTRY, COL_PRIMARY_KEY');
			$this->db->order_by("COL_TIME_ON", "desc"); 
			$this->db->limit(10);
			$data['query'] = $this->db->get($this->config->item('table_name'));
			 
			$data['notice'] = "QSO Added";
			// Load view to create another contact
			$this->load->view('layout/header');
			$this->load->view('qso/index', $data);
			$this->load->view('layout/footer');
		}
	}
	
	function edit() {
		$this->db->where('COL_PRIMARY_KEY', $this->uri->segment(3)); 
		$query = $this->db->get($this->config->item('table_name'));
		
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
		
			$data = array(
			   'COL_TIME_ON' => $this->input->post('time_on'),
			   'COL_TIME_OFF' => $this->input->post('time_off'),
			   'COL_CALL' => strtoupper($this->input->post('callsign')),
			   'COL_BAND' => $this->input->post('band'),
			   'COL_FREQ' => $this->input->post('freq'),
			   'COL_MODE' => $this->input->post('mode'),
			   'COL_RST_RCVD' => $this->input->post('rst_recv'),
			   'COL_RST_SENT' => $this->input->post('rst_sent'),
			   'COL_COMMENT' => $this->input->post('comment'),
			   'COL_NAME' => $this->input->post('name'),
			   'COL_SAT_NAME' => $this->input->post('sat_name'),
			   'COL_SAT_MODE' => $this->input->post('sat_mode'),
			);
		
			$this->db->where('COL_PRIMARY_KEY', $this->input->post('id'));
			$this->db->update($this->config->item('table_name'), $data); 
			$this->session->set_flashdata('notice', 'Record Updated');
			redirect('logbook');
		}
		
	}
}