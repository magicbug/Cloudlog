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
			'cat' => $this->input->post('category'),
			'title' => $this->input->post('title'),
			'note' => $this->input->post('content')
		);

		$this->db->insert('notes', $data); 
	}

	function edit() {
		$data = array(
			'cat' => $this->input->post('category'),
			'title' => $this->input->post('title'),
			'note' => $this->input->post('content')
		);

		$this->db->where('id', $this->input->post('id'));
		$this->db->update('notes', $data); 
	}

	function delete($id) {
		$this->db->delete('notes', array('id' => $id)); 
	}

	function view($id) {
		// Get Note
		$this->db->where('id', $id); 
		return $this->db->get('notes');
	}

}

?>