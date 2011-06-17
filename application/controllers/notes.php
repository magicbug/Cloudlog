<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notes extends CI_Controller {

	/* Displays all notes in a list */
	public function index()
	{
	
		$data['notes'] = $this->db->get('notes');
	
		$this->load->view('layout/header');
		$this->load->view('notes/main', $data);
		$this->load->view('layout/footer');
	}
	
	/* Provides function for adding notes to the system. */
	function add() {
	
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
			$data = array(
			   'cat' => $this->input->post('category'),
			   'title' => $this->input->post('title'),
			   'note' => $this->input->post('content')
			);

			$this->db->insert('notes', $data); 
			
			redirect('notes');
		}
	}
	
	/* View Notes */
	function view($id) {
		// Get Note
		$this->db->where('id', $id); 
		$data['note'] = $this->db->get('notes');
		
		// Display
		$this->load->view('layout/header');
		$this->load->view('notes/view',$data);
		$this->load->view('layout/footer');
	}
	
	/* Edit Notes */
	function edit($id) {
	
		$data['id'] = $id;
		$this->db->where('id', $id); 
		$data['note'] = $this->db->get('notes');
			
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
			$data = array(
			   'cat' => $this->input->post('category'),
			   'title' => $this->input->post('title'),
			   'note' => $this->input->post('content')
			);

			$this->db->where('id', $this->input->post('id'));
			$this->db->update('notes', $data); 

			redirect('notes');
		}
	}
	
	/* Delete Note */
	function delete($id) {
		$this->db->delete('notes', array('id' => $id)); 
		redirect('notes');
	}
}