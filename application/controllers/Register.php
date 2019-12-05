<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
		This controller "Register" handles all things todo with creating an account within Cloudlog
*/

class Register extends CI_Controller {

	public function index()
	{
		$data['page_title'] = "Register";

		$this->load->view('interface_assets/mini_header.php', $data);
		$this->load->view('authentication/register/register.php');
	}
	
}