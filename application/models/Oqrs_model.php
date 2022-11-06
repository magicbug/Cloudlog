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
        $sql = 'select * from oqrs join station_profile on oqrs.station_id = station_profile.station_id where oqrs.station_id in (' . $location_list . ') and oqrs.status < 2';

        $query = $this->db->query($sql);

        return $query->result();
	}

	function save_oqrs_request($postdata) {
		$qsos = $postdata['qsos'];
		foreach($qsos as $qso) {
			$data = array(
				'date' 				=> xss_clean($qso[0]),
				'time'	 			=> xss_clean($qso[1]),
				'band' 				=> xss_clean($qso[2]),
				'mode' 				=> xss_clean($qso[3]),
				'requestcallsign' 	=> xss_clean($postdata['callsign']),
				'station_id' 		=> xss_clean($postdata['station_id']),
				'note' 				=> xss_clean($postdata['message']),
				'email' 			=> xss_clean($postdata['email']),
				'qslroute' 			=> xss_clean($postdata['qslroute']),
				'status' 			=> '0',
			);

			$qsoid = $this->check_oqrs($data);

			if ($qsoid > 0) {
				$data['status'] = '2';
				$data['qsoid'] = $qsoid;
			}
	
			$this->db->insert('oqrs', $data);
		}
	}

	function delete_oqrs_line($id) {
        $sql = 'delete from oqrs where id =' . xss_clean($id);

        $query = $this->db->query($sql);

        return true;
	}


	// Status:
	// 0 = open request
	// 1 = not in log request
	// 2 = request done, means we found a match in the log
	function save_not_in_log($postdata) {
		$qsos = $postdata['qsos'];
		foreach($qsos as $qso) {
			$data = array(
				'date' 				=> xss_clean($qso[0]),
				'time'	 			=> xss_clean($qso[1]),
				'band' 				=> xss_clean($qso[2]),
				'mode' 				=> xss_clean($qso[3]),
				'requestcallsign' 	=> xss_clean($postdata['callsign']),
				'station_id' 		=> xss_clean($postdata['station_id']),
				'note' 				=> xss_clean($postdata['message']),
				'email' 			=> xss_clean($postdata['email']),
				'qslroute' 			=> '',
				'status' 			=> '1',
			);

			$this->db->insert('oqrs', $data);
		}
	}

	function check_oqrs($qsodata) {
		$sql = 'select * from ' . $this->config->item('table_name') . 
		' where col_band = \'' . $qsodata['band'] . '\'
		 and col_call = \'' . $qsodata['requestcallsign'] . '\'
		 and date(col_time_on) = \'' . $qsodata['date'] . '\'
		 and (col_mode = \'' . $qsodata['mode'] . '\'
		 or col_submode = \'' . $qsodata['mode'] . '\')
		 and timediff(time(col_time_on), \'' . $qsodata['time'] . '\') <= 3000
		 and station_id = ' . $qsodata['station_id'];
		
		$query = $this->db->query($sql);

		if ($result = $query->result()) {
			$id = 0;
			foreach ($result as $qso) {
				$this->paperqsl_requested($qso->COL_PRIMARY_KEY, $qsodata['qslroute']);
				$id = $qso->COL_PRIMARY_KEY;
			}
			return $id;
		}

		return 0;
	}

	// Set Paper to requested
	function paperqsl_requested($qso_id, $method) {

	$data = array(
			'COL_QSLSDATE' => date('Y-m-d H:i:s'),
			'COL_QSL_SENT' => 'R',
			'COL_QSL_SENT_VIA ' => $method
	);

	$this->db->where('COL_PRIMARY_KEY', $qso_id);

	$this->db->update($this->config->item('table_name'), $data);
	}

	function search_log($callsign) {
		$this->db->join('station_profile', 'station_profile.station_id = '.$this->config->item('table_name').'.station_id');
		// always filter user. this ensures that no inaccesible QSOs will be returned
		$this->db->where('station_profile.user_id', $this->session->userdata('user_id'));
		$this->db->where('COL_CALL like "%'.$callsign.'%"');
		$this->db->order_by("COL_TIME_ON", "ASC");
		$query = $this->db->get($this->config->item('table_name'));

		return $query;
	}

	function search_log_time_date($time, $date, $band, $mode) {
		$sql = 'select * from ' . $this->config->item('table_name') . ' thcv
		 join station_profile on thcv.station_id = station_profile.station_id where col_band = \'' . $band . '\'
		 and date(col_time_on) = \'' . $date . '\'
		 and (col_mode = \'' . $mode . '\'
		 or col_submode = \'' . $mode . '\')
		 and timediff(time(col_time_on), \'' . $time . '\') <= 3000
		 and station_profile.user_id = '. $this->session->userdata('user_id');

		return $this->db->query($sql);;
	}

	function mark_oqrs_line_as_done($id) {
		$data = array(
			'status' => '2',
	   );
   
	   $this->db->where('id', $id);
   
	   $this->db->update('oqrs', $data);
	}

	function getQslInfo($station_id) {
		$sql = 'select oqrs_text from station_profile where station_id = ' . $station_id;

		$query = $this->db->query($sql);

		if ($query->num_rows() > 0)
		{
			$row = $query->row(); 
			return $row->oqrs_text;
		}

		return '';
	}

	function getOqrsEmailSetting($station_id) {
		$sql = 'select oqrs_email from station_profile where station_id = ' . $station_id;

		$query = $this->db->query($sql);

		if ($query->num_rows() > 0)
		{
			$row = $query->row(); 
			return $row->oqrs_email;
		}

		return '';
	}
}