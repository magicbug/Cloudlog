<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup extends CI_Controller {

	/* Default setup page*/
	public function index()
	{
		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
	
		$this->load->view('layout/header');
		$this->load->view('setup/index');
		$this->load->view('layout/footer');
	}
}