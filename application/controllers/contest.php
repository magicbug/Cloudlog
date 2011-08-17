<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contest extends CI_Controller {


	// Displays available contests
	public function index()
	{
		$this->load->model('contests');
		$data['contests'] = $this->contests->list_contests();
		
		$this->load->view('layout/header');
		$this->load->view('contest/main', $data);
		$this->load->view('layout/footer');
	}
	
	public function view($id) {
		$this->load->model('contests');
		$data['info'] = $this->contests->information($id);
			
		$this->load->view('layout/header');
		$this->load->view('contest/log', $data);
		$this->load->view('layout/footer');
	}
	
	// Create a contest
	public function create() {
		$this->load->model('contests');
		$data['templates'] = $this->contests->list_templates();
		
		$this->load->helper(array('form', 'url'));
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
	
	public function add_template() {
	
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