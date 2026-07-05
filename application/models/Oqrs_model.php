<?php

class Oqrs_model extends CI_Model {
	private function normalize_location_ids($location_list) {
		if (empty($location_list)) {
			return array();
		}

		$ids = array();
		$parts = explode(',', $location_list);
		foreach ($parts as $part) {
			$trimmed = trim($part, " \t\n\r\0\x0B'\"");
			if (is_numeric($trimmed)) {
				$ids[] = (int) $trimmed;
			}
		}

		return array_values(array_unique($ids));
	}

    function get_oqrs_stations() {
        $this->db->where('oqrs', "1");
		return $this->db->get('station_profile');
	}

    function get_station_info($station_id) {
		$station_id = (int) $station_id;

		$this->db->select('count(*) as count, min(col_time_on) as mindate, max(col_time_on) as maxdate', false);
		$this->db->where('station_id', $station_id);
		$query = $this->db->get($this->config->item('table_name'));

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

	
    function get_qsos_grouped($callsign){

		// Populating array with worked band/mode combinations
		$worked = $this->getQueryData($station_id, $callsign);

		$result['qsocount'] = count($worked);
		$result['qsoarray'] = $resultArray;

		return $result;
	}

	/*
	 * Builds query depending on what we are searching for
	 */
	function getQueryData($station_id, $callsign) {
        // Properly sanitize and validate inputs
        $station_id = (int) $station_id; // Cast to integer to prevent SQL injection
        $callsign = $this->db->escape_str($callsign); // Properly escape the callsign
        
        // Use parameterized query to prevent SQL injection
        $sql = 'select lower(col_mode) col_mode, coalesce(col_submode, "") col_submode, col_band from ' . $this->config->item('table_name') . ' where station_id = ? and col_call = ? and col_prop_mode != "SAT"';
        $sql .= ' union all select lower(col_mode) col_mode, coalesce(col_submode, "") col_submode, "SAT" col_band from ' . $this->config->item('table_name') . ' where station_id = ? and col_call = ? and col_prop_mode = "SAT"';

        $query = $this->db->query($sql, [$station_id, $callsign, $station_id, $callsign]);

        return $query->result();
	}

	/*
	 * Builds query depending on what we are searching for
	 */
	function getQueryDataGrouped($callsign) {
        // Properly escape the callsign to prevent SQL injection
        $callsign = $this->db->escape_str($callsign);
        
        // Use parameterized query to prevent SQL injection
        $sql = 'select lower(col_mode) col_mode, coalesce(col_submode, "") col_submode, col_band, station_callsign, station_profile_name, l.station_id from ' . $this->config->item('table_name') . ' as l join station_profile on l.station_id = station_profile.station_id where station_profile.oqrs = "1" and l.col_call = ? and l.col_prop_mode != "SAT"';

		$sql .= ' union all select lower(col_mode) col_mode, coalesce(col_submode, "") col_submode, "SAT" col_band, station_callsign, station_profile_name, l.station_id from ' . 
			$this->config->item('table_name') . ' l' . 
			' join station_profile on l.station_id = station_profile.station_id where station_profile.oqrs = "1" and col_call = ? and col_prop_mode = "SAT"';

        $query = $this->db->query($sql, [$callsign, $callsign]);

		if ($query) {
			return $query->result();
		}

		return;
	}

	/*
	 * Get's the worked modes from the log
	 */
	function get_worked_modes($station_id)
	{
		$station_id = (int) $station_id;
		// get all worked modes from database
		$this->db->distinct();
		$this->db->select('LOWER(`COL_MODE`) as `COL_MODE`', false);
		$this->db->from($this->config->item('table_name'));
		$this->db->where('station_id', $station_id);
		$this->db->order_by('COL_MODE', 'ASC');
		$data = $this->db->get();
		$results = array();
		foreach ($data->result() as $row) {
			array_push($results, $row->COL_MODE);
		}

		$this->db->distinct();
		$this->db->select('LOWER(`COL_SUBMODE`) as `COL_SUBMODE`', false);
		$this->db->from($this->config->item('table_name'));
		$this->db->where('station_id', $station_id);
		$this->db->where("coalesce(COL_SUBMODE, '') <> ''", NULL, false);
		$this->db->order_by('COL_SUBMODE', 'ASC');
		$data = $this->db->get();
		foreach ($data->result() as $row) {
			if (!in_array($row, $results)) {
				array_push($results, $row->COL_SUBMODE);
			}
		}

		return $results;
	}

	function getOqrsRequests($location_list) {
		$location_ids = $this->normalize_location_ids($location_list);
		if (empty($location_ids)) {
			return array();
		}

		$this->db->select('oqrs.id, oqrs.requestcallsign, oqrs.date, oqrs.time, oqrs.band, oqrs.mode, oqrs.status, oqrs.note, oqrs.email, oqrs.qslroute, station_profile.station_callsign, station_profile.station_profile_name');
		$this->db->from('oqrs');
		$this->db->join('station_profile', 'oqrs.station_id = station_profile.station_id');
		$this->db->where_in('oqrs.station_id', $location_ids);
		$query = $this->db->get();

        return $query->result();
	}

	function save_oqrs_request($postdata) {
		$station_ids = array();
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
			}
			$data['qsoid'] = $qsoid;
	
			$this->db->insert('oqrs', $data);
			if(!in_array(xss_clean($postdata['station_id']), $station_ids)){
				array_push($station_ids, xss_clean($postdata['station_id']));
			}
		}

		return $station_ids;
	}

	function save_oqrs_request_grouped($postdata) {
		$station_ids = array();
		$qsos = $postdata['qsos'];
		foreach($qsos as $qso) {
			$data = array(
				'date' 				=> xss_clean($qso[0]),
				'time'	 			=> xss_clean($qso[1]),
				'band' 				=> xss_clean($qso[2]),
				'mode' 				=> xss_clean($qso[3]),
				'requestcallsign' 	=> xss_clean($postdata['callsign']),
				'station_id' 		=> xss_clean($qso[4]),
				'note' 				=> xss_clean($postdata['message']),
				'email' 			=> xss_clean($postdata['email']),
				'qslroute' 			=> xss_clean($postdata['qslroute']),
				'status' 			=> '0',
			);

			$qsoid = $this->check_oqrs($data);

			if ($qsoid > 0) {
				$data['status'] = '2';
			}
			$data['qsoid'] = $qsoid;
	
			$this->db->insert('oqrs', $data);
			
			if(!in_array(xss_clean($qso[4]), $station_ids)){
				array_push($station_ids, xss_clean($qso[4]));
			}
		}
		return $station_ids;
	}

	function delete_oqrs_line($id) {
		$this->db->where('id', (int) $id);
		$this->db->delete('oqrs');

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
				'qsoid' 			=> '0',
			);

			$this->db->insert('oqrs', $data);
		}
	}

	function check_oqrs($qsodata) {
		$this->db->select('COL_PRIMARY_KEY');
		$this->db->from($this->config->item('table_name'));
		$this->db->group_start();
		$this->db->where('col_band', $qsodata['band']);
		$this->db->or_where('col_prop_mode', $qsodata['band']);
		$this->db->group_end();
		$this->db->where('col_call', $qsodata['requestcallsign']);
		$this->db->where('date(col_time_on)', $qsodata['date']);
		$this->db->group_start();
		$this->db->where('col_mode', $qsodata['mode']);
		$this->db->or_where('col_submode', $qsodata['mode']);
		$this->db->group_end();
		$this->db->where('timediff(time(col_time_on), ' . $this->db->escape($qsodata['time']) . ') <= 3000', NULL, false);
		$this->db->where('station_id', (int) $qsodata['station_id']);
		$query = $this->db->get();

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
		$this->db->like('COL_CALL', $callsign);
		$this->db->order_by("COL_TIME_ON", "ASC");
		$query = $this->db->get($this->config->item('table_name'));

		return $query;
	}

	function search_log_time_date($time, $date, $band, $mode) {
		$this->db->select('thcv.COL_PRIMARY_KEY, thcv.COL_CALL, thcv.COL_BAND, thcv.COL_MODE, thcv.COL_TIME_ON, station_profile.station_callsign');
		$this->db->from($this->config->item('table_name') . ' thcv');
		$this->db->join('station_profile', 'thcv.station_id = station_profile.station_id');
		$this->db->group_start();
		$this->db->where('thcv.col_band', $band);
		$this->db->or_where('thcv.col_prop_mode', $band);
		$this->db->group_end();
		$this->db->where('date(thcv.col_time_on)', $date);
		$this->db->group_start();
		$this->db->where('thcv.col_mode', $mode);
		$this->db->or_where('thcv.col_submode', $mode);
		$this->db->group_end();
		$this->db->where('timediff(time(thcv.col_time_on), ' . $this->db->escape($time) . ') <= 3000', NULL, false);
		$this->db->where('station_profile.user_id', (int) $this->session->userdata('user_id'));

		return $this->db->get();
	}

	function mark_oqrs_line_as_done($id) {
		$data = array(
			'status' => '2',
	   );
   
	   $this->db->where('id', $id);
   
	   $this->db->update('oqrs', $data);
	}

	function getQslInfo($station_id) {
		$this->db->select('oqrs_text');
		$this->db->where('station_id', (int) $station_id);
		$query = $this->db->get('station_profile');

		if ($query->num_rows() > 0)
		{
			$row = $query->row(); 
			return $row->oqrs_text;
		}

		return '';
	}

	function getOqrsEmailSetting($station_id) {
		$this->db->select('oqrs_email');
		$this->db->where('station_id', (int) $station_id);
		$query = $this->db->get('station_profile');

		if ($query->num_rows() > 0)
		{
			$row = $query->row(); 
			return $row->oqrs_email;
		}

		return '';
	}

	/*
   * @param array $searchCriteria
   * @return array
   */
  public function searchOqrs($searchCriteria) : array {
		$conditions = [];
		$binding = [$searchCriteria['user_id']];

		if ($searchCriteria['de'] !== '') {
			$conditions[] = "station_profile.station_id = ?";
			$binding[] = trim($searchCriteria['de']);
		}
		if ($searchCriteria['dx'] !== '') {
			$conditions[] = "oqrs.requestcallsign LIKE ?";
			$binding[] = '%' . trim($searchCriteria['dx']) . '%';
		}
		if ($searchCriteria['status'] !== '') {
			$conditions[] = "oqrs.status = ?";
			$binding[] = $searchCriteria['status'];
		}

		$where = trim(implode(" AND ", $conditions));
		if ($where != "") {
			$where = "AND $where";
		}

		$limit = $searchCriteria['oqrsResults'];
		// Ensure limit has a valid value, default to 50 if empty or invalid
		if (empty($limit) || (!is_numeric($limit) && $limit !== 'All') || (is_numeric($limit) && $limit <= 0)) {
			$limit = 50;
		}

		$limitClause = ($limit === 'All') ? '' : "LIMIT $limit";
		
		$sql = "
			SELECT *, DATE_FORMAT(requesttime, \"%Y-%m-%d %H:%i\") as requesttime, DATE_FORMAT(time, \"%H:%i\") as time
			FROM oqrs
			INNER JOIN station_profile ON oqrs.station_id=station_profile.station_id
			WHERE station_profile.user_id =  ?
			$where
			ORDER BY oqrs.id
			$limitClause
		";

		$data = $this->db->query($sql, $binding);

		return $data->result('array');
	}

	public function oqrs_requests($location_list) {
		$location_ids = $this->normalize_location_ids($location_list);
		if (!empty($location_ids)) {
			$this->db->select('COUNT(*) AS number', false);
			$this->db->from('oqrs');
			$this->db->join('station_profile', 'oqrs.station_id = station_profile.station_id');
			$this->db->where_in('oqrs.station_id', $location_ids);
			$this->db->where('status <', 2);
			$query = $this->db->get();
			$row = $query->row();
			return $row->number;
		} else {
			return 0;
		}
	}

	function getOqrsStationsFromSlug($logbook_id) {
		$this->db->select('station_callsign');
		$this->db->from('station_logbooks_relationship');
		$this->db->join('station_profile', 'station_logbooks_relationship.station_location_id = station_profile.station_id');
		$this->db->where('station_profile.oqrs', 1);
		$this->db->where('station_logbook_id', (int) $logbook_id);
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
}
