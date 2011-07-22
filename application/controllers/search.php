<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends CI_Controller {

	public function index()
	{
		$this->load->view('layout/header');
		$this->load->view('search/main');
		$this->load->view('layout/footer');
	}
}