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
		$filters = array(
			'search' => $this->input->get('q', TRUE),
			'category' => $this->input->get('category', TRUE),
			'date_from' => $this->input->get('date_from', TRUE),
			'date_to' => $this->input->get('date_to', TRUE)
		);
		$data['filters'] = $filters;
		$data['categories'] = $this->note->list_categories();
		$data['notes'] = $this->note->list_all(null, $filters);
		
		// Check if there are any Station Diary entries
		$diary_filter = array('category' => 'Station Diary');
		$diary_entries = $this->note->list_all(null, $diary_filter);
		$data['has_diary_entries'] = $diary_entries->num_rows() > 0;
		
		$data['page_title'] = "Notes";
		$this->load->view('interface_assets/header', $data);
		$this->load->view('notes/main');
		$this->load->view('interface_assets/footer');
	}
	
	/* Provides function for adding notes to the system. */
	function add() {
	
		$this->load->model('note');
		$data['categories'] = $this->note->list_categories();
	
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
	
	/* Quick add note via HTMX (for Station Diary modal) */
	function quick_add() {
		$this->load->model('note');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('title', 'Note Title', 'required');
		$this->form_validation->set_rules('content', 'Content', 'required');
		$this->form_validation->set_rules('category', 'Category', 'required');

		if ($this->form_validation->run() == FALSE) {
			echo '<div class="alert alert-danger">' . validation_errors() . '</div>';
		} else {
			$this->note->add();
			echo '<div class="alert alert-success">Note saved successfully! <a href="' . site_url('notes') . '">View all notes</a></div>';
			// Reset form via JavaScript
			echo '<script>setTimeout(function(){ document.getElementById("stationDiaryForm").reset(); }, 1500);</script>';
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
		$data['categories'] = $this->note->list_categories();
			
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
	function delete() {
		// Enforce POST for destructive action
		if (strtolower($this->input->method()) !== 'post') {
			$this->session->set_flashdata('notice', $this->lang->line('general_word_warning') . ': invalid request method.');
			redirect('notes');
			return;
		}

		$id = $this->input->post('id', TRUE);
		if (empty($id)) {
			$this->session->set_flashdata('notice', $this->lang->line('general_word_warning') . ': missing note id.');
			redirect('notes');
			return;
		}

		$this->load->model('note');
		$this->note->delete($id);
		$this->session->set_flashdata('notice', $this->lang->line('admin_delete') ?: 'Deleted');
		redirect('notes');
	}

	/* Print Station Diary */
	public function station_diary() {
		$this->load->model('note');
		
		$filters = array(
			'category' => 'Station Diary'
		);
		
		$data['diary_entries'] = $this->note->list_all(null, $filters);
		$data['page_title'] = "Station Diary";
		
		// Load without header/footer for print formatting
		$this->load->view('notes/station_diary_print', $data);
	}

	/* Delete/Merge Category */
	function delete_category() {
		if (strtolower($this->input->method()) !== 'post') {
			$this->session->set_flashdata('notice', $this->lang->line('general_word_warning') . ': invalid request method.');
			redirect('notes');
			return;
		}

		$source = trim($this->input->post('source_category', TRUE));
		$target = trim($this->input->post('target_category', TRUE));

		if ($source === '') {
			$this->session->set_flashdata('notice', $this->lang->line('general_word_warning') . ': missing source category.');
			redirect('notes');
			return;
		}

		if ($target === '') {
			$target = 'General';
		}

		if ($source === $target) {
			$this->session->set_flashdata('notice', $this->lang->line('general_word_warning') . ': choose a different target category.');
			redirect('notes');
			return;
		}

		$this->load->model('note');
		$affected = $this->note->replace_category($source, $target);
		$this->session->set_flashdata('notice', sprintf('%s → %s (%d)', $source, $target, $affected));
		redirect('notes');
	}
}