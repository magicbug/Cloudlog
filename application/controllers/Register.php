<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
		This controller "Register" handles all things todo with creating an account within Cloudlog
*/

class Register extends CI_Controller {

	public function index()
	{
		// Load DXCC Model
		$this->load->model('dxcc');

		$data['page_title'] = "Register";
		$data['dxcc_list'] = $this->dxcc->list();

		$this->load->view('interface_assets/mini_header.php', $data);
		$this->load->view('authentication/register/register.php');
		$this->load->view('interface_assets/mini_footer.php', $data);
	}
	
}