<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Controller {

	/* Displays all notes in a list */
	public function index()
	{
		$this->load->model('auth_model');

		echo "<pre>";
		echo "Querying for user...\n";
		$u = $this->auth_model->get("m0vkga");
		print_r($u);
		echo "Test hashing\n";
		echo $this->auth_model->test();

	}
	/*
		$this->load->model('note');
		$data['notes'] = $this->note->list_all();
	
		$this->load->view('layout/header');
		$this->load->view('notes/main', $data);
		$this->load->view('layout/footer');
	}
	
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
	
	function edit($id) {
		$this->load->model('note');
		$data['id'] = $id;
		
		$data['note'] = $this->note->view($id);
			
		$this->load->library('form_validation');

		$this->form_validation->set_rules('title', 'Note Title', 'required');
		$this->form_validation->set_rules('content', 'Content', 'required');


		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('layout/header');
			$this->load->view('notes/edit', $data);
			$this->load->view('layout/footer');
		}
		else
		{
			$this->note->edit();
			
			redirect('notes');
		}
	}
	
	function delete($id) {
		$this->load->model('note');
		$this->note->delete($id);
		
		redirect('notes');
	}
*/
}
