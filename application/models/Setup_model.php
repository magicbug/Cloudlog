<?php

class Setup_model extends CI_Model {

	function getCountryCount() {
		$sql = 'select count(*) as count from dxcc_entities';
		$query = $this->db->query($sql);

		return $query->row()->count;
	}

	function getLogbookCount() {
		$userid = xss_clean($this->session->userdata('user_id'));
		$sql = 'select count(*) as count from station_logbooks where user_id =' . $userid;
		$query = $this->db->query($sql);

		return $query->row()->count;
	}

	function getLocationCount() {
		$userid = xss_clean($this->session->userdata('user_id'));
		$sql = 'select count(*) as count from station_profile where user_id =' . $userid;
		$query = $this->db->query($sql);

		return $query->row()->count;
	}

	// Consolidated method to get all setup counts in one query
	function getAllSetupCounts() {
		$userid = xss_clean($this->session->userdata('user_id'));
		
		$sql = "SELECT 
			(SELECT COUNT(*) FROM dxcc_entities) as country_count,
			(SELECT COUNT(*) FROM station_logbooks WHERE user_id = {$userid}) as logbook_count,
			(SELECT COUNT(*) FROM station_profile WHERE user_id = {$userid}) as location_count";
		
		$query = $this->db->query($sql);
		$row = $query->row();
		
		return array(
			'country_count' => $row->country_count,
			'logbook_count' => $row->logbook_count,
			'location_count' => $row->location_count
		);
	}
}

?>
