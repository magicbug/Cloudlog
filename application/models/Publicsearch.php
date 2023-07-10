<?php

class Publicsearch extends CI_Model {

	function search($slug, $callsign) {
		$userid = $this->get_userid_for_slug($slug);
		$this->db->where('COL_CALL', $callsign);
		$this->db->join('station_profile', 'station_profile.station_id = '.$this->config->item('table_name').'.station_id');
		$this->db->join('lotw_users', 'lotw_users.callsign = '.$this->config->item('table_name').'.col_call', 'left outer');
		$this->db->where('station_profile.user_id', $userid);
		$this->db->order_by('COL_TIME_ON', 'DESC');
		$query = $this->db->get($this->config->item('table_name')); 
		
		return $query;
	}

	function get_userid_for_slug($slug) {
		$this->db->select('user_id');
		$this->db->where('public_slug', $slug);
		$query = $this->db->get('station_logbooks'); 

		return $query->result_array()[0]['user_id'];
	}

}

?>
