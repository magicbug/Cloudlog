<?php

class Search extends CI_Model {

	function callsign_iota($reference) {
		$this->db->where('COL_IOTA', $reference);
		$query = $this->db->get($this->config->item('table_name')); 
		
		return $query;
	}

	function get_table_columns() {
		$query = $this->db->query('DESCRIBE '.$this->config->item('table_name'));

		return $query;
	}

}

?>