<?php

class Setup_model extends CI_Model {

	function getCountryCount() {
		$sql = 'select count(*) from dxcc_entities';
        $query = $this->db->query($sql);

        $result = $query->result();

		return count($result);
	}

	function getLogbookCount() {
		$userid = xss_clean($this->session->userdata('user_id'));
		$sql = 'select count(*) from station_logbooks where user_id =' . $userid;
        $query = $this->db->query($sql);

        $result = $query->result();

		return count($result);
	}

	function getLocationCount() {
		$userid = xss_clean($this->session->userdata('user_id'));
		$sql = 'select count(*) from station_profile where user_id =' . $userid;
        $query = $this->db->query($sql);

        $result = $query->result();

		return count($result);
	}
}

?>
