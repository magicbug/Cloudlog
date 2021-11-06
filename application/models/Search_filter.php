<?php

class Search_filter extends CI_Model {

	function get_table_columns() {
		$query = $this->db->query('DESCRIBE '.$this->config->item('table_name'));

		return $query;
	}

	function get_stored_queries() {
		$this->db->where('userid', $this->session->userdata('user_id'));
		return $this->db->get('queries')->result();
	}

	function delete_query($id) {
		$this->db->delete('queries', array('id' => xss_clean($id), 'userid' =>$this->session->userdata('user_id')));
	}

}

?>
