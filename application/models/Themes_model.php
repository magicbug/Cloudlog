<?php
class Themes_model extends CI_Model {

	// FUNCTION: array getThemes()
	// Returns a list of themes
	function getThemes() {
		$result = $this->db->query('SELECT * FROM themes order by name');

		return $result->result();
	}

	function delete($id) {
		// Clean ID
		$clean_id = $this->security->xss_clean($id);

		// Delete Theme
		$this->db->delete('themes', array('id' => $clean_id));
	}

	function add() {
		$data = array(
			'name' => xss_clean($this->input->post('name', true)),
			'foldername' => xss_clean($this->input->post('foldername', true)),
		);

		$this->db->insert('themes', $data);
	}


	function theme($id) {
		// Clean ID
		$clean_id = $this->security->xss_clean($id);

		$sql = "SELECT * FROM themes where id =" . $clean_id;

		$data = $this->db->query($sql);

		return ($data->row());
	}

	function edit($id) {
		$data = array(
			'name' => xss_clean($this->input->post('name', true)),
			'foldername' => xss_clean($this->input->post('foldername', true)),
		);

		$this->db->where('id', $id);
		$this->db->update('themes', $data);
	}
}
