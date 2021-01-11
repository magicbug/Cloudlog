<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notes extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }

		// Load language files
		$this->lang->load('notes');
	}


	/* Displays all notes in a list */
	public function index()
	{
		$this->load->model('note');
		$data['notes'] = $this->note->list_all();
		$data['page_title'] = "Notes";
		$this->load->view('interface_assets/header', $data);
		$this->load->view('notes/main');
		$this->load->view('interface_assets/footer');
	}
	
	/* Provides function for adding notes to the system. */
	function add() {
	
		$this->load->model('note');
	
		$this->load->library('form_validation');

		$this->form_validation->set_rules('title', 'Note Title', 'required');
		$this->form_validation->set_rules('content', 'Content', 'required');


		if ($this->form_validation->run() == FALSE)
		{
			$data['page_title'] = "Add Notes";
			$this->load->view('interface_assets/header', $data);
			$this->load->view('notes/add');
			$this->load->view('interface_assets/footer');
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
		$data['page_title'] = "Note";
		$this->load->view('interface_assets/header', $data);
		$this->load->view('notes/view');
		$this->load->view('interface_assets/footer');
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
			$data['page_title'] = "Edit Note";
			$this->load->view('interface_assets/header', $data);
			$this->load->view('notes/edit');
			$this->load->view('interface_assets/footer');
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