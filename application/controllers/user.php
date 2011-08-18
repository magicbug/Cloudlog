<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

	/* Displays all notes in a list */
	public function index()
	{
		$this->load->model('user_model');

		$data['results'] = $this->user_model->users();

		$this->load->view('layout/header');
		$this->load->view('user/main', $data);
		$this->load->view('layout/footer');
	}

	function add() {
		$this->load->model('user_model');
		
		$this->load->library('form_validation');

		$this->form_validation->set_rules('user_name', 'Username', 'required');
		$this->form_validation->set_rules('user_email', 'E-mail', 'required');
		$this->form_validation->set_rules('user_password', 'Password', 'required');
		$this->form_validation->set_rules('user_type', 'Type', 'required');

		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('layout/header');
			if($this->input->post('user_name'))
			{
				$data['user_name'] = $this->input->post('user_name');
				$data['user_email'] = $this->input->post('user_email');
				$data['user_password'] = $this->input->post('user_password');
				$data['user_type'] = $this->input->post('user_type');
				$this->load->view('user/add', $data);
			} else {
				$this->load->view('user/add');
			}
			$this->load->view('layout/footer');
		}
		else
		{
			if($this->user_model->add($this->input->post('user_name'), $this->input->post('user_password'), $this->input->post('user_email'), $this->input->post('user_type'))) {
				$this->session->set_flashdata('notice', 'User '.$this->input->post('user_name').' added');
				redirect('user');
			} else {
				$this->load->view('layout/header');
				$this->session->set_flashdata('notice', 'Problem adding user');
				$data['user_name'] = $this->input->post('user_name');
				$data['user_email'] = $this->input->post('user_email');
				$data['user_password'] = $this->input->post('user_password');
				$data['user_type'] = $this->input->post('user_type');
				$this->load->view('user/add', $data);
				$this->load->view('layout/footer');
			}
		}
	}

	function edit() {
		$this->load->model('user_model');
		$query = $this->user_model->get_by_id($this->uri->segment(3));
		
		$this->load->library('form_validation');

		$this->form_validation->set_rules('user_name', 'Username', 'required');
		$this->form_validation->set_rules('user_email', 'E-mail', 'required');
		$this->form_validation->set_rules('user_type', 'Type', 'required');

		$data = $query->row();

		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('layout/header');
			$this->load->view('user/edit', $data);
			$this->load->view('layout/footer');
		}
		else
		{
			$this->user_model->edit();
			$this->session->set_flashdata('notice', 'User updated');
			redirect('user');
		}
	}

	function login() {
		$this->load->model('user_model');
		$query = $this->user_model->get($this->input->post('user_name'));

		$this->load->library('form_validation');

		$this->form_validation->set_rules('user_name', 'Username', 'required');
		$this->form_validation->set_rules('user_password', 'Password', 'required');

		$data = $query->row();

		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('layout/header');
			$this->load->view('user/login', $data);
			$this->load->view('layout/footer');
		}
		else
		{
			if($this->user_model->login() == 1) {
				$this->session->set_flashdata('notice', 'User logged in');
				$this->user_model->update_session($data->user_id);
				redirect('dashboard');
			} else {
				$this->session->set_flashdata('notice', 'Incorrect username or password!');
				redirect('user/login');
			}
		}
	}

	function logout() {
		$this->load->model('user_model');

		$user_name = $this->session->userdata('user_name');

		$this->user_model->clear_session();

		$this->session->set_flashdata('notice', 'User '.$user_name.' logged out.');
		redirect('dashboard');
	}

	/*
	function delete($id) {
		$this->load->model('note');
		$this->note->delete($id);
		
		redirect('notes');
	}
*/
}
