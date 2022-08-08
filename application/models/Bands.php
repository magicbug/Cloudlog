<?php

class Bands extends CI_Model {

	public $bandslots = array(
		"160m"=>0,
		"80m"=>0,
		"60m"=>0,
		"40m"=>0,
		"30m"=>0,
		"20m"=>0,
		"17m"=>0,
		"15m"=>0,
		"12m"=>0,
		"10m"=>0,
		"6m"=>0,
		"4m"=>0,
		"2m"=>0,
		"1.25m"=>0,
		"70cm"=>0,
		"33cm"=>0,
		"23cm"=>0,
		"13cm"=>0,
		"9cm"=>0,
		"6cm"=>0,
		"3cm"=>0,
		"1.25cm"=>0,
		"SAT"=>0,
	);

	function get_worked_bands() {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		if (!$logbooks_locations_array) {
			return array();
		}

		$location_list = "'".implode("','",$logbooks_locations_array)."'";

		// get all worked slots from database
		$data = $this->db->query(
			"SELECT distinct LOWER(`COL_BAND`) as `COL_BAND` FROM `".$this->config->item('table_name')."` WHERE station_id in (" . $location_list . ") AND COL_PROP_MODE != \"SAT\""
		);
		$worked_slots = array();
		foreach($data->result() as $row){
			array_push($worked_slots, $row->COL_BAND);
		}

		$SAT_data = $this->db->query(
			"SELECT distinct LOWER(`COL_PROP_MODE`) as `COL_PROP_MODE` FROM `".$this->config->item('table_name')."` WHERE station_id in (" . $location_list . ") AND COL_PROP_MODE = \"SAT\""
		);

		foreach($SAT_data->result() as $row){
			array_push($worked_slots, strtoupper($row->COL_PROP_MODE));
		}


		// bring worked-slots in order of defined $bandslots
		$results = array();
		foreach(array_keys($this->bandslots) as $slot) {
			if(in_array($slot, $worked_slots)) {
				array_push($results, $slot);
			}
		}

		return $results;
	}

	function get_worked_bands_for_user($userId) {

		// get all worked slots from database
		$sql = "
				SELECT distinct LOWER(`COL_BAND`) as `COL_BAND`
				FROM `" . $this->config->item('table_name') . "` qsos
				INNER JOIN station_profile ON qsos.station_id=station_profile.station_id
				WHERE station_profile.user_id =  ?
			";

		$data = $this->db->query($sql, $userId);
		$results = [];
		foreach ($data->result('array') as $row) {
			$results[]=$row['COL_BAND'];
		}
		return $results;
	}

	function get_worked_bands_distances() {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		if (!$logbooks_locations_array) {
			return array();
		}
		$location_list = "'".implode("','",$logbooks_locations_array)."'";

        // get all worked slots from database
        $sql = "SELECT distinct LOWER(COL_BAND) as COL_BAND FROM ".$this->config->item('table_name')." WHERE station_id in (" . $location_list . ")";

        $data = $this->db->query($sql);
        $worked_slots = array();
        foreach($data->result() as $row){
            array_push($worked_slots, $row->COL_BAND);
        }

        // bring worked-slots in order of defined $bandslots
        $results = array();
        foreach(array_keys($this->bandslots) as $slot) {
            if(in_array($slot, $worked_slots)) {
                array_push($results, $slot);
            }
        }
        return $results;
    }

	function get_worked_sats() {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		if (!$logbooks_locations_array) {
			return array();
		}

		$location_list = "'".implode("','",$logbooks_locations_array)."'";

        // get all worked sats from database
        $sql = "SELECT distinct col_sat_name FROM ".$this->config->item('table_name')." WHERE station_id in (" . $location_list . ") and coalesce(col_sat_name, '') <> '' ORDER BY col_sat_name";

        $data = $this->db->query($sql);

        $worked_sats = array();
        foreach($data->result() as $row){
            array_push($worked_sats, $row->col_sat_name);
        }

        return $worked_sats;
    }

	function get_worked_bands_dok() {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		if (!$logbooks_locations_array) {
			return array();
		}

		$location_list = "'".implode("','",$logbooks_locations_array)."'";

		// get all worked slots from database
		$data = $this->db->query(
			"SELECT distinct LOWER(`COL_BAND`) as `COL_BAND` FROM `".$this->config->item('table_name')."` WHERE station_id in (" . $location_list . ") AND COL_DARC_DOK IS NOT NULL AND COL_DARC_DOK != ''  AND COL_DXCC = 230 "
		);
		$worked_slots = array();
		foreach($data->result() as $row){
			array_push($worked_slots, $row->COL_BAND);
		}


		// bring worked-slots in order of defined $bandslots
		$results = array();
		foreach(array_keys($this->bandslots) as $slot) {
			if(in_array($slot, $worked_slots)) {
				array_push($results, $slot);
			}
		}
		return $results;
	}

}

?>
