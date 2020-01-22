<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
		
*/

class Login extends CI_Controller {

	public function old()
	{
		$data['page_title'] = "Login";

		$this->load->view('interface_assets/mini_header.php', $data);
		$this->load->view('authentication/login/login.php');
	}


	function index() {
		$this->load->model('user_model');
		$query = $this->user_model->get($this->input->post('user_name', true));

		$this->load->library('form_validation');

		$this->form_validation->set_rules('user_name', 'Username', 'required');
		$this->form_validation->set_rules('user_password', 'Password', 'required');

		$data['user'] = $query->row();

		if ($this->form_validation->run() == FALSE)
		{
			$data['page_title'] = "Login";

			$this->load->view('interface_assets/mini_header.php', $data);
			$this->load->view('authentication/login/login.php');

		}
		else
		{
			if($this->user_model->login() == 1) {
				$this->session->set_flashdata('notice', 'User logged in');
				$this->user_model->update_session($data['user']->user_id);
				redirect('dashboard');
			} else {
				$this->session->set_flashdata('error', 'Incorrect username or password!');
				redirect('login');
			}
		}
	}
	
}