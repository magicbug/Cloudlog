<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function index()
	{
		$this->load->view('layout/header');
		$this->load->view('dashboard/index');
		$this->load->view('layout/footer');
	}
}