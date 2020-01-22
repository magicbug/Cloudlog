<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/*
	This controller will contain features for logging out
*/

class Logout extends CI_Controller {

	function index() {
		$this->load->model('user_model');

		$user_name = $this->session->userdata('user_name');

		$this->user_model->clear_session();

		$this->session->set_flashdata('notice', 'User '.$user_name.' logged out.');
		redirect('login');
	}
}