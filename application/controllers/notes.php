<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notes extends CI_Controller {

	/* Displays all notes in a list */
	public function index()
	{
		$this->load->model('note');
		$data['notes'] = $this->note->list_all();
	
		$this->load->view('layout/header');
		$this->load->view('notes/main', $data);
		$this->load->view('layout/footer');
	}
	
	/* Provides function for adding notes to the system. */
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
	
	/* View Notes */
	function view($id) {
		$this->load->model('note');
		
		$data['note'] = $this->note->view($id);
		
		// Display
		$this->load->view('layout/header');
		$this->load->view('notes/view',$data);
		$this->load->view('layout/footer');
	}
	
	/* Edit Notes */
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
	
	/* Delete Note */
	function delete($id) {
		$this->load->model('note');
		$this->note->delete($id);
		
		redirect('notes');
	}
}