<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
	This controller will contain features for Settings
*/

class Settings extends CI_Controller {

	public function index()
    {
    	$data['page_title'] = "Settings";

		$this->load->view('interface_assets/header', $data);
		$this->load->view('settings/index');
		$this->load->view('interface_assets/footer');
	}
}