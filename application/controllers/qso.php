<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class QSO extends CI_Controller {

	public function index()
	{
		$this->load->view('layout/header');
		$this->load->view('qso/index');
		$this->load->view('layout/footer');
	}
}