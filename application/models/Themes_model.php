<?php
class Themes_model extends CI_Model {

	// FUNCTION: array getThemes()
	// Returns the supported Light and Dark theme options
	function getThemes() {
		return array(
			(object) array('id' => 1, 'name' => 'Light', 'foldername' => 'default'),
			(object) array('id' => 2, 'name' => 'Dark', 'foldername' => 'darkly'),
		);
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
		$clean_id = (int) $id;

		$this->db->where('id', $clean_id);
		$data = $this->db->get('themes');

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
