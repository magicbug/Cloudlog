<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* 

	Handles all functions todo with QSLing.

*/

class qsl extends CI_Controller {


	public function index()
	{
		$this->load->view('layout/header');
		$this->load->view('qsl/main');
		$this->load->view('layout/footer');
	}
}