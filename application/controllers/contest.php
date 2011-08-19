<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contest extends CI_Controller {


	// Displays available contests
	public function index()
	{
		// Load database items
		$this->load->model('contests');
		$data['contests'] = $this->contests->list_contests();
		
		// Load views
		$this->load->view('layout/header');
		$this->load->view('contest/main', $data);
		$this->load->view('layout/footer');
	}
	
	/*
		Displays contest logging view based on the ID provided, allowing users to log in contest mode giving them serial numbers and scoring information.
	*/
	public function view($id) {
		
		// Load database information
		$this->load->model('contests');
		
		$data['info'] = $this->contests->information($id);
		$data['log'] = $this->contests->contest_log_view($data['info']->start, $data['info']->end, $data['info']);
		$data['summary'] = $this->contests->contest_summary_bands($data['info']->start, $data['info']->end, $data['info']);
		

		
		// Run validation checks on QSO submission
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('start_time', 'Time', 'required');
		$this->form_validation->set_rules('callsign', 'Callsign', 'required');
		$this->form_validation->set_rules('sent_serial', 'Sent Serial Number', 'required');
		$this->form_validation->set_rules('rst_recv', 'Recevied RST', 'required');
		$this->form_validation->set_rules('recv_serial', 'Received Serial Number', 'required');
		
		if($data['info']->qra == "Y") {
			$this->form_validation->set_rules('locator', 'Received QRA', 'required');
		}
		
		// Load Views
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('layout/header');
			$this->load->view('contest/log', $data);
			$this->load->view('layout/footer');
		} else {
			$contest_id = $id;
			// Add QSO
			$this->contests->add($contest_id);

			// Store Basic QSO Info for reuse
			$this->session->set_userdata('band', $this->input->post('band'));
			$this->session->set_userdata('freq', $this->input->post('freq'));
			$this->session->set_userdata('mode', $this->input->post('mode'));
		
			$data['info'] = $this->contests->information($id);
			$data['log'] = $this->contests->contest_log_view($data['info']->start, $data['info']->end, $data['info']);
			
			// Set Any Notice Messages
			$data['notice'] = "QSO Added";
		
			$this->load->view('layout/header');
			$this->load->view('contest/log', $data);
			$this->load->view('layout/footer');
		}
	}
	
	/*
		Create a contest, these are linked to templates for scoring information. contests are per entry like a weekly RSGB Club contest etc.
	*/
	public function create() {
	
		// Load database items
		$this->load->model('contests');
		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
		$data['templates'] = $this->contests->list_templates();
		
		$this->load->helper(array('form', 'url'));
		
		// Run Validation
		$this->load->library('form_validation');

		$this->form_validation->set_rules('contest_name', 'Contest Name', 'required');

		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('layout/header');
			$this->load->view('contest/create', $data);
			$this->load->view('layout/footer');
		} else {
			$this->load->model('contests');
			$this->contests->create_contest();
			redirect('contest');
		}
	}
	
	/*
		Create a template, Contest Templates are sets of parameters for a series or single contest selecting items like scoring, bands and modes available.
	*/
	public function add_template() {
	
		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
	
		$this->form_validation->set_rules('contest_name', 'Contest Name', 'required');
	
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('layout/header');
			$this->load->view('contest/add_template');
			$this->load->view('layout/footer');
		} else {
			$this->load->model('contests');
			$this->contests->create_template();
			redirect('contest');
		}
	}
}
