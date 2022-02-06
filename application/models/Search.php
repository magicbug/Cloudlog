<?php

class Search extends CI_Model {

	function callsign_iota($reference) {
		$this->db->where('COL_IOTA', $reference);
		$this->db->join('station_profile', 'station_profile.station_id = '.$this->config->item('table_name').'.station_id');
		$this->db->where('station_profile.user_id', $this->session->userdata('user_id'));
		$query = $this->db->get($this->config->item('table_name')); 
		
		return $query;
	}

	function get_table_columns() {
		$query = $this->db->query('DESCRIBE '.$this->config->item('table_name'));

		return $query;
	}

}

?>