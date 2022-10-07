<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
    Path: application\controllers\Welcome.php

    Displays the welcome to Cloudlog version 2.0 view to help users with migration from version 1.0
*/ 


class Welcome extends CI_Controller {

	public function index()
	{
        $data['page_title'] = "Welcome to Cloudlog Version 2.0";

        $this->load->view('interface_assets/header', $data);
        $this->load->view('welcome/index');
        $this->load->view('interface_assets/footer');
    }
}