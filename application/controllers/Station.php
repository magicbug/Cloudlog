<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
	Handles Displaying of information for awards.
	
	These are taken from comments fields or ADIF fields 
*/

class Station extends CI_Controller {

	public function index()
	{
		$this->load->model('stations');
		$data['stations'] = $this->stations->all();

		// Render Page
		$data['page_title'] = "Station Profiles";
		$this->load->view('layout/header', $data);
		$this->load->view('station_profile/index');
		$this->load->view('layout/footer');
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
			$this->load->view('layout/header', $data);
			$this->load->view('station_profile/create');
			$this->load->view('layout/footer');
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