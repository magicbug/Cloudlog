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
	
		$this->load->model('cat');
		$this->load->model('stations');
		$this->load->model('logbook_model');
		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
		
		
		$data['notice'] = false;
		$data['stations'] = $this->stations->all();
		$data['radios'] = $this->cat->radios();
		$data['query'] = $this->logbook_model->last_custom('16');
		
		$this->load->library('form_validation');

		$this->form_validation->set_rules('start_date', 'Date', 'required');
		$this->form_validation->set_rules('start_time', 'Time', 'required');
		$this->form_validation->set_rules('callsign', 'Callsign', 'required');

		if ($this->form_validation->run() == FALSE)
		{
			$data['page_title'] = "Add QSO";

			$this->load->view('layout/header', $data);
			$this->load->view('qso/index');
			$this->load->view('layout/footer');
		}
		else
		{
			// Add QSO
			// $this->logbook_model->add();
		//change to create_qso function as add and create_qso duplicate functionality
		$this->logbook_model->create_qso();
			
			// Store Basic QSO Info for reuse
      // Put data in an array first, then call set_userdata once.
      // This solves the problem of CI dumping out the session
      // cookie each time set_userdata is called.
      // For more info, see http://bizhole.com/codeigniter-nginx-error-502-bad-gateway/
      // $qso_data = [
      // 18-Jan-2016 - make php v5.3 friendly!
      $qso_data = array(
        'band' => $this->input->post('band'),
        'freq' => $this->input->post('freq'),
        'mode' => $this->input->post('mode'),
        'sat_name' => $this->input->post('sat_name'),
        'sat_mode' => $this->input->post('sat_mode'),
        'radio' => $this->input->post('radio'),
        'station_profile_id' => $this->input->post('station_profile')
      );
      // ];

      $this->session->set_userdata($qso_data);
			
			// Get last 16 qsos
			$data['query'] = $this->logbook_model->last_custom('16');
			 
			// Set Any Notice Messages
			$data['notice'] = "QSO Added";
			
			// Load view to create another contact
			$data['page_title'] = "Add QSO";

			$this->load->view('layout/header', $data);
			$this->load->view('qso/index');
			$this->load->view('layout/footer');
		}
	}
	
	/* 
		Function:	manual
		Usage:		Post QSO Logging 
	*/
	
	public function manual()
	{
	
		$this->load->model('cat');
		$this->load->model('logbook_model');
		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
		
		
		$data['notice'] = false;
		$data['radios'] = $this->cat->radios();
		$data['query'] = $this->logbook_model->last_custom('16');
		
		$this->load->library('form_validation');

		$this->form_validation->set_rules('start_date', 'Date', 'required');
		$this->form_validation->set_rules('start_time', 'Time', 'required');
		$this->form_validation->set_rules('callsign', 'Callsign', 'required');

		if ($this->form_validation->run() == FALSE)
		{
			$data['page_title'] = "Add QSO";

			$this->load->view('layout/header', $data);
			$this->load->view('qso/manual');
			$this->load->view('layout/footer');
		}
		else
		{
			// Add QSO
			$this->logbook_model->create_qso();
			
			// Store Basic QSO Info for reuse
      //$qso_data = [
      // 18-Jan-2016 - make php v5.3 friendly!
      $qso_data = array(
        'band' => $this->input->post('band'),
        'freq' => $this->input->post('freq'),
        'mode' => $this->input->post('mode'),
        'sat_name' => $this->input->post('sat_name'),
        'sat_mode' => $this->input->post('sat_mode'),
        'radio' => $this->input->post('radio')
      );
      //];

      $this->session->set_userdata($qso_data);
			
			// Get last Ten QSOs
			$data['query'] = $this->logbook_model->last_ten();
			 
			// Set Any Notice Messages
			$data['notice'] = "QSO Added";
			
			// Load view to create another contact
			$data['page_title'] = "Manual QSO";

			$this->load->view('layout/header', $data);
			$this->load->view('qso/manual');
			$this->load->view('layout/footer');
		}
	}
	
	
	function edit() {
	
		$this->load->model('logbook_model');
		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
		$query = $this->logbook_model->qso_info($this->uri->segment(3));
		
		$this->load->library('form_validation');

		$this->form_validation->set_rules('time_on', 'Start Date', 'required');
		$this->form_validation->set_rules('time_off', 'End Date', 'required');
		$this->form_validation->set_rules('callsign', 'Callsign', 'required');

		$data = $query->row(); 
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('layout/mini_header', $data);
			$this->load->view('qso/edit');
			$this->load->view('layout/mini_footer');
		}
		else
		{
			$this->logbook_model->edit();
			$this->session->set_flashdata('notice', 'Record Updated');
			$this->load->view('qso/edit_done');
		}
	}
	
	/* Delete QSO */
	function delete($id) {
		$this->load->model('logbook_model');
		
		$this->logbook_model->delete($id);
		
		$this->session->set_flashdata('notice', 'QSO Deleted Successfully');
		$data['message_title'] = "Deleted";
		$data['message_contents'] = "QSO Deleted Successfully";
		$this->load->view('messages/message', $data);

	}
	
	
	function band_to_freq($band, $mode) {
		
		$this->load->library('frequency');
		
		echo $this->frequency->convent_band($band, $mode);
	}
}
