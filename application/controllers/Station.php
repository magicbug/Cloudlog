<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
	Handles Displaying of information for station tools.
*/

class Station extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));

		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
	}

	public function index()
	{
		$this->load->model('stations');
		$data['stations'] = $this->stations->all();

		// Render Page
		$data['page_title'] = "Station Profiles";
		$this->load->view('interface_assets/header', $data);
		$this->load->view('station_profile/index');
		$this->load->view('interface_assets/footer');
	}

	public function create() 
	{
		$this->load->model('stations');
		$this->load->model('dxcc');
		$data['dxcc_list'] = $this->dxcc->list();


		$this->load->library('form_validation');

		$this->form_validation->set_rules('station_profile_name', 'Station Profile Name', 'required');

		if ($this->form_validation->run() == FALSE)
		{
			$data['page_title'] = "Create Station Profile";
			$this->load->view('interface_assets/header', $data);
			$this->load->view('station_profile/create');
			$this->load->view('interface_assets/footer');
		}
		else
		{	
			$this->stations->add();
			
			redirect('station');
		}
	}

	public function edit()
	{

	}

	public function delete($id) {
		$this->load->model('stations');
		$this->stations->delete($id);
		
		redirect('station');
	}

}