<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class File_manager extends CI_Controller
{

	/* Controls who can access the controller and its functions */
	function __construct()
	{
		parent::__construct();

		$this->load->model('user_model');
		if (!$this->user_model->authorize(2)) {
			$this->session->set_flashdata('notice', 'You\'re not allowed to do that!');
			redirect('dashboard');
		}
	}

	public function index()
	{
		$data['page_title'] = "File Manager";

		$this->load->view('interface_assets/header', $data);
		$this->load->view('file_manager/index');
		$this->load->view('interface_assets/footer');
	}
}
