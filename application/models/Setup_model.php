<?php

class Setup_model extends CI_Model {

	function getCountryCount() {
		$sql = 'select count(*) as count from dxcc_entities';
		$query = $this->db->query($sql);

		return $query->row()->count;
	}

	function getLogbookCount() {
		$userid = (int) $this->session->userdata('user_id');
		// Count logbooks the user owns OR has been granted permissions to
		$this->db->select('COUNT(DISTINCT sl.logbook_id) AS count', false);
		$this->db->from('station_logbooks sl');
		$this->db->join('station_logbooks_permissions slp', 'slp.logbook_id = sl.logbook_id AND slp.user_id = '.$this->db->escape($userid), 'left', false);
		$this->db->group_start();
		$this->db->where('sl.user_id', $userid);
		$this->db->or_where('slp.user_id', $userid);
		$this->db->group_end();
		$query = $this->db->get();

		return $query->row()->count;
	}

	function getLocationCount() {
		$userid = (int) $this->session->userdata('user_id');
		$this->db->select('count(*) as count', false);
		$this->db->where('user_id', $userid);
		$query = $this->db->get('station_profile');

		return $query->row()->count;
	}

	// Consolidated method to get all setup counts in one query
	function getAllSetupCounts() {
		$userid = (int) $this->session->userdata('user_id');
		$userid_escaped = $this->db->escape($userid);
		
		$sql = "SELECT 
			(SELECT COUNT(*) FROM dxcc_entities) as country_count,
			(SELECT COUNT(DISTINCT sl.logbook_id) FROM station_logbooks sl
				LEFT JOIN station_logbooks_permissions slp
					ON slp.logbook_id = sl.logbook_id AND slp.user_id = {$userid_escaped}
				WHERE sl.user_id = {$userid_escaped} OR slp.user_id = {$userid_escaped}) as logbook_count,
			(SELECT COUNT(*) FROM station_profile WHERE user_id = {$userid_escaped}) as location_count";
		
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
