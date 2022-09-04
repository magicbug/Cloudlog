<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
	Handles Displaying of band information
*/

class Band extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));

		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
	}

	public function index()
	{
		$this->load->model('bands');

		$data['bands'] = $this->bands->all();
		
		// Render Page
		$data['page_title'] = "Bands";
		$this->load->view('interface_assets/header', $data);
		$this->load->view('bands/index');
		$this->load->view('interface_assets/footer');
	}
}