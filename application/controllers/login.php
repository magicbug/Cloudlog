<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
		
*/

class Login extends CI_Controller {

	public function index()
	{
		$data['page_title'] = "Login";

		$this->load->view('interface_assets/mini_header.php', $data);
		$this->load->view('authentication/login/login.php');
	}
	
}