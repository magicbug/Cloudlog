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

	/*
	function add() {
	
		$this->load->model('note');
	
		$this->load->library('form_validation');

		$this->form_validation->set_rules('title', 'Note Title', 'required');
		$this->form_validation->set_rules('content', 'Content', 'required');


		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('layout/header');
			$this->load->view('notes/add');
			$this->load->view('layout/footer');
		}
		else
		{	
			$this->note->add();
			
			redirect('notes');
		}
	}
	
	function view($id) {
		$this->load->model('note');
		
		$data['note'] = $this->note->view($id);
		
		// Display
		$this->load->view('layout/header');
		$this->load->view('notes/view',$data);
		$this->load->view('layout/footer');
	}
	*/

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
