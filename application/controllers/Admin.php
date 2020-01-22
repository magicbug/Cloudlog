<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/*
	This controller will contain features for Admin only
*/

class Admin extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));

		$this->load->model('user_model');

		if($this->user_model->has_role(1) != true) { 
			$this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
	}

	public function index()
    {
    	$data['page_title'] = "Admin Area";

    	$this->load->view('interface_assets/header', $data);
		$this->load->view('admin/index');
		$this->load->view('interface_assets/footer');
	}


	// Functions for dealing with users
	public function users() {
		$data['results'] = $this->user_model->users();

		$data['page_title'] = "Users";

		$this->load->view('interface_assets/header', $data);
		$this->load->view('admin/users/index');
		$this->load->view('interface_assets/footer');
	}

	public function delete_user($user_id) {
		if($this->user_model->delete($user_id))
		{
			$this->session->set_flashdata('notice', 'User deleted');
			redirect('admin/users');
		} else {
			$this->session->set_flashdata('notice', '<b>Database error:</b> Could not delete user!');
			redirect('admin/users');
		}
	}


}