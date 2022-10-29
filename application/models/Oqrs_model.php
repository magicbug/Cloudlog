<?php

class Oqrs_model extends CI_Model {
    function get_oqrs_stations() {
        $this->db->where('oqrs', "1");
		return $this->db->get('station_profile');
	}

    function get_station_info($station_id) {
        $station_id = $this->security->xss_clean($station_id);

        $sql = 'select 
        count(*) as count,
        min(col_time_on) as mindate,
        max(col_time_on) as maxdate
        from ' . $this->config->item('table_name') . ' where station_id = ' . $station_id;

        $query = $this->db->query($sql);

        return $query->row();
    }

    function get_qsos($station_id, $callsign, $bands){
		$modes = $this->get_worked_modes($station_id);

		// Creating an empty array with all the bands and modes from the database
		foreach ($modes as $mode) {
			foreach ($bands as $band) {
				$resultArray[$mode][$band] = '-';
			}
		}

		// Populating array with worked band/mode combinations
		$worked = $this->getQueryData($station_id, $callsign);
		foreach ($worked as $w) {
			$resultArray[$w->col_mode][$w->col_band] = '<i class="fa fa-check" aria-hidden="true"></i>';
		}

		$result['qsocount'] = count($worked);
		$result['qsoarray'] = $resultArray;

		return $result;
	}

	/*
	 * Builds query depending on what we are searching for
	 */
	function getQueryData($station_id, $callsign) {
        $station_id = $this->security->xss_clean($station_id);
        $callsign = $this->security->xss_clean($callsign);
        $sql = 'select lower(col_mode) col_mode, coalesce(col_submode, "") col_submode, col_band from ' . $this->config->item('table_name') . ' where station_id = ' . $station_id . ' and col_call ="' . $callsign . '"';

        $query = $this->db->query($sql);

        return $query->result();
	}

	/*
	 * Get's the worked modes from the log
	 */
	function get_worked_modes($station_id)
	{
		// get all worked modes from database
		$data = $this->db->query(
			"SELECT distinct LOWER(`COL_MODE`) as `COL_MODE` FROM `" . $this->config->item('table_name') . "` WHERE station_id in (" . $station_id . ") order by COL_MODE ASC"
		);
		$results = array();
		foreach ($data->result() as $row) {
			array_push($results, $row->COL_MODE);
		}

		$data = $this->db->query(
			"SELECT distinct LOWER(`COL_SUBMODE`) as `COL_SUBMODE` FROM `" . $this->config->item('table_name') . "` WHERE station_id in (" . $station_id . ") and coalesce(COL_SUBMODE, '') <> '' order by COL_SUBMODE ASC"
		);
		foreach ($data->result() as $row) {
			if (!in_array($row, $results)) {
				array_push($results, $row->COL_SUBMODE);
			}
		}

		return $results;
	}

	function getOqrsRequests($location_list) {
        $sql = 'select * from oqrs join station_profile on oqrs.station_id = station_profile.station_id where oqrs.station_id in (' . $location_list . ') and oqrs.status = 0';

        $query = $this->db->query($sql);

        return $query->result();
	}

	function save_oqrs_request($postdata) {
		$qsos = $postdata['qsos'];
		foreach($qsos as $qso) {
			$data = array(
				'date' 				=> xss_clean($qso[1]),
				'time'	 			=> xss_clean($qso[2]),
				'band' 				=> xss_clean($qso[3]),
				'mode' 				=> xss_clean($qso[4]),
				'requestcallsign' 	=> xss_clean($postdata['callsign']),
				'station_id' 		=> xss_clean($postdata['station_id']),
				'matchtime' 		=> '',
				'matchdate' 		=> '',
				'note' 				=> xss_clean($postdata['message']),
				'email' 			=> xss_clean($postdata['email']),
				'type' 				=> '1',
				'qslroute' 			=> '',
				'status' 			=> '',
			);
	
			$this->db->insert('oqrs', $data);
		}
	}

	function delete_oqrs_line($id) {
        $sql = 'delete from oqrs where id =' . xss_clean($id);

        $query = $this->db->query($sql);

        return true;
	}

	function save_not_in_log($postdata) {
		$qsos = $postdata['qsos'];
		foreach($qsos as $qso) {
			$data = array(
				'date' 				=> xss_clean($qso[1]),
				'time'	 			=> xss_clean($qso[2]),
				'band' 				=> xss_clean($qso[3]),
				'mode' 				=> xss_clean($qso[4]),
				'requestcallsign' 	=> xss_clean($postdata['callsign']),
				'station_id' 		=> xss_clean($postdata['station_id']),
				'matchtime' 		=> '',
				'matchdate' 		=> '',
				'note' 				=> xss_clean($postdata['message']),
				'email' 			=> xss_clean($postdata['email']),
				'type' 				=> '2',
				'qslroute' 			=> '',
				'status' 			=> '',
			);
	
			$this->db->insert('oqrs', $data);
		}
	}
}