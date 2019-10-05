<?php

class Note extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

	function list_all() {
		return $this->db->get('notes');
	}

	function add() {
		$data = array(
			'cat' => xss_clean($this->input->post('category')),
			'title' => xss_clean($this->input->post('title')),
			'note' => xss_clean($this->input->post('content'))
		);

		$this->db->insert('notes', $data); 
	}

	function edit() {
		$data = array(
			'cat' => xss_clean($this->input->post('category')),
			'title' => xss_clean($this->input->post('title')),
			'note' => xss_clean($this->input->post('content'))
		);

		$this->db->where('id', xss_clean($this->input->post('id')));
		$this->db->update('notes', $data); 
	}

	function delete($id) {
		$this->db->delete('notes', array('id' => xss_clean($id))); 
	}

	function view($id) {
		// Get Note
		$this->db->where('id', xss_clean($id)); 
		return $this->db->get('notes');
	}

}

?>