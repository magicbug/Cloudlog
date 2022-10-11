<?php

class Note extends CI_Model {

	function list_all() {
		$this->db->where('user_id', $this->session->userdata('user_id'));
		return $this->db->get('notes');
	}

	function add() {
		$data = array(
			'cat' => xss_clean($this->input->post('category')),
			'title' => xss_clean($this->input->post('title')),
			'note' => xss_clean($this->input->post('content')),
			'user_id' => $this->session->userdata('user_id')
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
		$this->db->where('user_id', $this->session->userdata('user_id'));
		$this->db->update('notes', $data);
	}

	function delete($id) {
		$this->db->delete('notes', array('id' => xss_clean($id), 'user_id' =>$this->session->userdata('user_id')));
	}

	function view($id) {
		// Get Note
		$this->db->where('id', xss_clean($id));
		$this->db->where('user_id', $this->session->userdata('user_id'));
		return $this->db->get('notes');
	}

	function ClaimAllNotes($id = NULL) {
		// if $id is empty then use session user_id
		if (empty($id)) {
			// Get the first USER ID from user table in the database
			$id = $this->db->get("users")->row()->user_id;
		}

		$data = array(
				'user_id' => $id,
		);
			
		$this->db->update('notes', $data);
	}

	function CountAllNotes() {
		// count all notes
		$this->db->where('user_id =', NULL);
		$query = $this->db->get('notes');
		return $query->num_rows();
	}

}

?>
