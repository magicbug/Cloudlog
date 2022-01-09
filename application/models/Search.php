<?php

class Search extends CI_Model {

	function callsign_iota($reference) {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		$this->db->where('COL_IOTA', $reference);
		$this->db->where_in('station_id', $logbooks_locations_array);
		$query = $this->db->get($this->config->item('table_name')); 
		
		return $query;
	}

	function get_table_columns() {
		$query = $this->db->query('DESCRIBE '.$this->config->item('table_name'));

		return $query;
	}

}

?>