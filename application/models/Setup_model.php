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
}

?>
