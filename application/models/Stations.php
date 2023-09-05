<?php

class Stations extends CI_Model {

    function all_with_count() {

		$this->db->select('station_profile.*, dxcc_entities.name as station_country, dxcc_entities.end as dxcc_end, count('.$this->config->item('table_name').'.station_id) as qso_total');
        $this->db->from('station_profile');
        $this->db->join($this->config->item('table_name'),'station_profile.station_id = '.$this->config->item('table_name').'.station_id','left');
        $this->db->join('dxcc_entities','station_profile.station_dxcc = dxcc_entities.adif','left outer');
       	$this->db->group_by('station_profile.station_id');
		$this->db->where('station_profile.user_id', $this->session->userdata('user_id'));
		$this->db->or_where('station_profile.user_id =', NULL);
        return $this->db->get();
	}

	// Returns ALL station profiles regardless of user logged in
	// This is also used by LoTW sync so must not be changed.
	function all() {
		$this->db->select('station_profile.*, dxcc_entities.name as station_country');
		$this->db->from('station_profile');
		$this->db->join('dxcc_entities','station_profile.station_dxcc = dxcc_entities.adif','left outer');
		return $this->db->get();
	}

	function all_of_user($userid = null) {
		if ($userid == null) { 
			$userid=$this->session->userdata('user_id'); // Fallback to session-uid, if userid is omitted
		}
		$this->db->select('station_profile.*, dxcc_entities.name as station_country, dxcc_entities.end as dxcc_end');
		$this->db->where('user_id', $userid);
		$this->db->join('dxcc_entities','station_profile.station_dxcc = dxcc_entities.adif','left outer');
		return $this->db->get('station_profile');
	}

	function profile($id) {
		// Clean ID
		$clean_id = $this->security->xss_clean($id);
		$this->db->where('station_id', $clean_id);
		return $this->db->get('station_profile');
	}

	function profile_clean($id) {
		// Clean ID
		$clean_id = $this->security->xss_clean($id);


		$this->db->where('station_id', $clean_id);
		$query = $this->db->get('station_profile');

		$row = $query->row();

		return $row;
	}

	/*
	*	Function: add
	*	Adds post material into the station profile table.
	*/
	function add() {
		// check if user has no active station profile yet
		$station_active = null;
		if ($this->find_active() === '0') {
			$station_active = 1;
		}

		// Check if the state is Canada and get the correct state
		if ($this->input->post('dxcc') == 1 && $this->input->post('station_ca_state') !="") {
			$state = $this->input->post('station_ca_state');
		} else {
			$state = $this->input->post('station_state');
		}

		// Create data array with field values
		$data = array(
			'user_id' => $this->session->userdata('user_id'),
			'station_active' => $station_active,
			'station_profile_name' => xss_clean($this->input->post('station_profile_name', true)),
			'station_gridsquare' =>  xss_clean(strtoupper($this->input->post('gridsquare', true))),
			'station_city' =>  xss_clean($this->input->post('city', true)),
			'station_iota' =>  xss_clean(strtoupper($this->input->post('iota', true))),
			'station_sota' =>  xss_clean(strtoupper($this->input->post('sota', true))),
			'station_wwff' =>  xss_clean(strtoupper($this->input->post('wwff', true))),
			'station_pota' =>  xss_clean(strtoupper($this->input->post('pota', true))),
			'station_sig' =>  xss_clean(strtoupper($this->input->post('sig', true))),
			'station_sig_info' =>  xss_clean(strtoupper($this->input->post('sig_info', true))),
			'station_callsign' =>  xss_clean($this->input->post('station_callsign', true)),
			'station_power' => is_numeric(xss_clean($this->input->post('station_power', true))) ? xss_clean($this->input->post('station_power', true)) : NULL,
			'station_dxcc' =>  xss_clean($this->input->post('dxcc', true)),
			'station_cnty' =>  xss_clean($this->input->post('station_cnty', true)),
			'station_cq' =>  xss_clean($this->input->post('station_cq', true)),
			'station_itu' =>  xss_clean($this->input->post('station_itu', true)),
			'state' =>  $state,
			'eqslqthnickname' => xss_clean($this->input->post('eqslnickname', true)),
			'hrdlog_code' => xss_clean($this->input->post('hrdlog_code', true)),
			'hrdlogrealtime' => xss_clean($this->input->post('hrdlogrealtime', true)),
			'qrzapikey' => xss_clean($this->input->post('qrzapikey', true)),
			'qrzrealtime' => xss_clean($this->input->post('qrzrealtime', true)),
			'oqrs' => xss_clean($this->input->post('oqrs', true)),
			'oqrs_email' => xss_clean($this->input->post('oqrsemail', true)),
			'oqrs_text' => xss_clean($this->input->post('oqrstext', true)),
			'webadifapikey' => xss_clean($this->input->post('webadifapikey', true)),
			'webadifapiurl' => 'https://qo100dx.club/api',
			'webadifrealtime' => xss_clean($this->input->post('webadifrealtime', true)),
		);

		// Insert Records
		$this->db->insert('station_profile', $data);
	}

	function edit() {

		// Check if the state is Canada and get the correct state
		if ($this->input->post('dxcc') == 1 && $this->input->post('station_ca_state') !="") {
			$state = $this->input->post('station_ca_state');
		} else {
			$state = $this->input->post('station_state');
		}

		$data = array(
			'station_profile_name' => xss_clean($this->input->post('station_profile_name', true)),
			'station_gridsquare' => xss_clean($this->input->post('gridsquare', true)),
			'station_city' => xss_clean($this->input->post('city', true)),
			'station_iota' => xss_clean($this->input->post('iota', true)),
			'station_sota' => xss_clean($this->input->post('sota', true)),
			'station_wwff' => xss_clean($this->input->post('wwff', true)),
			'station_pota' => xss_clean($this->input->post('pota', true)),
			'station_sig' => xss_clean($this->input->post('sig', true)),
			'station_sig_info' => xss_clean($this->input->post('sig_info', true)),
			'station_callsign' => xss_clean($this->input->post('station_callsign', true)),
			'station_power' => is_numeric(xss_clean($this->input->post('station_power', true))) ? xss_clean($this->input->post('station_power', true)) : NULL,
			'station_dxcc' => xss_clean($this->input->post('dxcc', true)),
			'station_cnty' => xss_clean($this->input->post('station_cnty', true)),
			'station_cq' => xss_clean($this->input->post('station_cq', true)),
			'station_itu' => xss_clean($this->input->post('station_itu', true)),
			'state' => $state,
			'eqslqthnickname' => xss_clean($this->input->post('eqslnickname', true)),
			'hrdlog_code' => xss_clean($this->input->post('hrdlog_code', true)),
			'hrdlogrealtime' => xss_clean($this->input->post('hrdlogrealtime', true)),
			'qrzapikey' => xss_clean($this->input->post('qrzapikey', true)),
			'qrzrealtime' => xss_clean($this->input->post('qrzrealtime', true)),
			'oqrs' => xss_clean($this->input->post('oqrs', true)),
			'oqrs_email' => xss_clean($this->input->post('oqrsemail', true)),
			'oqrs_text' => xss_clean($this->input->post('oqrstext', true)),
			'webadifapikey' => xss_clean($this->input->post('webadifapikey', true)),
			'webadifapiurl' => 'https://qo100dx.club/api',
			'webadifrealtime' => xss_clean($this->input->post('webadifrealtime', true)),
		);

		$this->db->where('user_id', $this->session->userdata('user_id'));
		$this->db->where('station_id', xss_clean($this->input->post('station_id', true)));
		$this->db->update('station_profile', $data);
	}

	function delete($id) {
		// Clean ID
		$clean_id = $this->security->xss_clean($id);

		// do not delete active station
		if ($clean_id === $this->find_active()) {
			return;
		}

		// Delete QSOs
		$this->db->where('station_id', $id);
		$this->db->delete($this->config->item('table_name'));

		// Delete Station Profile
		$this->db->delete('station_profile', array('station_id' => $clean_id));
	}

	function deletelog($id) {
        // Delete QSOs
        $this->db->where('station_id', $id);
        $this->db->delete($this->config->item('table_name'));
    }

	function claim_user($id) {
		$data = array(
				'user_id' => $this->session->userdata('user_id'),
		);

		$this->db->where('station_id', $id);
		$this->db->update('station_profile', $data);
	}

	function ClaimAllStationLocations($id = NULL) {
		// if $id is empty then use session user_id
		if (empty($id)) {
			// Get the first USER ID from user table in the database
			$id = $this->db->get("users")->row()->user_id;
		}

		$data = array(
				'user_id' => $id,
		);

		$this->db->update('station_profile', $data);
	}

	function CountAllStationLocations() {
		$this->db->where('user_id =', NULL);
		$query = $this->db->get('station_profile');
		return $query->num_rows();
	}

	function set_active($current, $new) {
		// Clean inputs
		$clean_current = $this->security->xss_clean($current);
		$clean_new = $this->security->xss_clean($new);

		// be sure that stations belong to user
		if ($clean_current != 0) {
			if (!$this->check_station_is_accessible($clean_current)) {
				return;
			}
		}
		if (!$this->check_station_is_accessible($clean_new)) {
			return;
		}

		// Deselect current default
		$current_default = array(
			'station_active' => null,
		);
		$this->db->where('user_id', $this->session->userdata('user_id'));
		$this->db->update('station_profile', $current_default);

		// Deselect current default
		$newdefault = array(
			'station_active' => 1,
		);
		$this->db->where('user_id', $this->session->userdata('user_id'));
		$this->db->where('station_id', $clean_new);
		$this->db->update('station_profile', $newdefault);
	}

	public function find_active() {
		$this->db->where('user_id', $this->session->userdata('user_id'));
		$this->db->where('station_active', 1);
		$query = $this->db->get('station_profile');

		if($query->num_rows() >= 1) {
			foreach ($query->result() as $row)
			{
				return $row->station_id;
			}
		} else {
			return "0";
		}
	}

	public function find_gridsquare() {
		$this->db->where('user_id', $this->session->userdata('user_id'));
		$this->db->where('station_active', 1);
		$query = $this->db->get('station_profile');

		if($query->num_rows() >= 1) {
			foreach ($query->result() as $row)
			{
				return $row->station_gridsquare;
			}
		} else {
			return "0";
		}
	}

	public function find_name() {
		$this->db->where('user_id', $this->session->userdata('user_id'));
		$this->db->where('station_active', 1);
		$query = $this->db->get('station_profile');

		if($query->num_rows() >= 1) {
			foreach ($query->result() as $row)
			{
				return $row->station_profile_name;
			}
		} else {
			return "0";
		}
	}

    public function reassign($id) {
		// Clean ID
		$clean_id = $this->security->xss_clean($id);

		$this->db->select('station_profile.*, dxcc_entities.name as station_country');
		$this->db->where('station_id', $clean_id);
		$this->db->join('dxcc_entities', 'station_profile.station_dxcc = dxcc_entities.adif', 'left outer');
		$query = $this->db->get('station_profile');

		$row = $query->row();

		//print_r($row);

		$data = array(
		        'station_id' => $id,
		);

		$this->db->where('COL_STATION_CALLSIGN', $row->station_callsign);

		if($row->station_iota != "") {
			$this->db->where('COL_MY_IOTA', $row->station_iota);
		}

		if($row->station_sota != "") {
			$this->db->where('COL_MY_SOTA_REF', $row->station_sota);
		}

		if($row->station_wwff != "") {
			$this->db->where('COL_MY_WWFF_REF', $row->station_wwff);
		}

		if($row->station_pota != "") {
			$this->db->where('COL_MY_POTA_REF', $row->station_pota);
		}

		if($row->station_sig != "") {
			$this->db->where('COL_MY_SIG', $row->station_sig);
		}

		if($row->station_sig_info != "") {
			$this->db->where('COL_MY_SIG_INFO', $row->station_sig_info);
		}

		$this->db->where('COL_MY_COUNTRY', $row->station_country);

		if( strpos($row->station_gridsquare, ',') !== false ) {
		     $this->db->where('COL_MY_VUCC_GRIDS', $row->station_gridsquare);
		} else {
			$this->db->where('COL_MY_GRIDSQUARE', $row->station_gridsquare);
		}

		$this->db->update($this->config->item('table_name'), $data);

		$str = $this->db->last_query();

    }

    function profile_exists() {
	    $query = $this->db->get('station_profile');
		if($query->num_rows() >= 1) {
	    	return 1;
	    } else {
	    	return 0;
	    }
    }

    function stations_with_hrdlog_code() {
       $sql = "select station_profile.station_id, station_profile.station_profile_name, station_profile.station_callsign, modc.modcount, notc.notcount, totc.totcount
                from station_profile
                left outer join (
                            select count(*) modcount, station_id
                    from ". $this->config->item('table_name') .
                    " where COL_HRDLOG_QSO_UPLOAD_STATUS = 'M'
                    group by station_id
                ) as modc on station_profile.station_id = modc.station_id
                left outer join (
                            select count(*) notcount, station_id
                    from " . $this->config->item('table_name') .
                    " where (coalesce(COL_HRDLOG_QSO_UPLOAD_STATUS, '') = ''
                    or COL_HRDLOG_QSO_UPLOAD_STATUS = 'N')
                    group by station_id
                ) as notc on station_profile.station_id = notc.station_id
                left outer join (
                    select count(*) totcount, station_id
                    from " . $this->config->item('table_name') .
                    " where COL_HRDLOG_QSO_UPLOAD_STATUS = 'Y'
                    group by station_id
                ) as totc on station_profile.station_id = totc.station_id
                where coalesce(station_profile.hrdlog_code, '') <> ''
				 and station_profile.user_id = " . $this->session->userdata('user_id');
        $query = $this->db->query($sql);

        return $query;
    }

    function stations_with_qrz_api_key() {
       $sql = "select station_profile.station_id, station_profile.station_profile_name, station_profile.station_callsign, modc.modcount, notc.notcount, totc.totcount
                from station_profile
                left outer join (
                            select count(*) modcount, station_id
                    from ". $this->config->item('table_name') .
                    " where COL_QRZCOM_QSO_UPLOAD_STATUS = 'M'
                    group by station_id
                ) as modc on station_profile.station_id = modc.station_id
                left outer join (
                            select count(*) notcount, station_id
                    from " . $this->config->item('table_name') .
                    " where (coalesce(COL_QRZCOM_QSO_UPLOAD_STATUS, '') = ''
                    or COL_QRZCOM_QSO_UPLOAD_STATUS = 'N')
                    group by station_id
                ) as notc on station_profile.station_id = notc.station_id
                left outer join (
                    select count(*) totcount, station_id
                    from " . $this->config->item('table_name') .
                    " where COL_QRZCOM_QSO_UPLOAD_STATUS = 'Y'
                    group by station_id
                ) as totc on station_profile.station_id = totc.station_id
                where coalesce(station_profile.qrzapikey, '') <> ''
				 and station_profile.user_id = " . $this->session->userdata('user_id');
        $query = $this->db->query($sql);

        return $query;
    }

	function stations_with_webadif_api_key() {
		$sql="
			SELECT station_profile.station_id, station_profile.station_profile_name, station_profile.station_callsign, notc.c notcount, totc.c totcount
			FROM station_profile
			LEFT OUTER JOIN (
				SELECT qsos.station_id, COUNT(qsos.COL_PRIMARY_KEY) c
				FROM %s qsos
				LEFT JOIN webadif ON qsos.COL_PRIMARY_KEY = webadif.qso_id
				WHERE webadif.qso_id IS NULL AND qsos.COL_SAT_NAME = 'QO-100'
				GROUP BY qsos.station_id
			) notc ON station_profile.station_id = notc.station_id
			LEFT JOIN (
				SELECT qsos.station_id, COUNT(qsos.COL_PRIMARY_KEY) c
				FROM %s qsos
				WHERE qsos.COL_SAT_NAME = 'QO-100'
				GROUP BY qsos.station_id
			) totc ON station_profile.station_id = totc.station_id
			WHERE COALESCE(station_profile.webadifapikey, '') <> ''
			AND COALESCE(station_profile.webadifapiurl, '') <> ''
			AND station_profile.user_id = %d
		";
		$sql=sprintf(
			$sql,
			$this->config->item('table_name'),
			$this->config->item('table_name'),
			$this->session->userdata('user_id')
		);
		return $this->db->query($sql);
	}

    /*
	*	Function: are_eqsl_nicks_defined
	*	Description: Returns number of station profiles with eqslnicknames
    */
    function are_eqsl_nicks_defined() {
    	$this->db->select('eqslqthnickname');
    	$this->db->where('eqslqthnickname IS NOT NULL');
    	$this->db->where('eqslqthnickname !=', '');
		$this->db->from('station_profile');
		$query = $this->db->get();

		return $query->num_rows();
    }

	public function check_station_is_accessible($id) {
		// check if station belongs to user
		$this->db->select('station_id');
		$this->db->where('user_id', $this->session->userdata('user_id'));
		$this->db->where('station_id', $id);
		$query = $this->db->get('station_profile');
		if ($query->num_rows() == 1) {
			return true;
		}
		return false;
	}

	public function get_station_power($id) {
		$this->db->select('station_power');
		$this->db->where('user_id', $this->session->userdata('user_id'));
		$this->db->where('station_id', $id);
		$query = $this->db->get('station_profile');
		if($query->num_rows() >= 1) {
			foreach ($query->result() as $row)
			{
				return $row->station_power;
			}
		} else {
			return null;
		}
	}

	public function check_station_against_user($stationid, $userid) {
		$this->db->select('station_id');
		$this->db->where('user_id', $userid);
		$this->db->where('station_id', $stationid);
		$query = $this->db->get('station_profile');
		if ($query->num_rows() == 1) {
			return true;
		}
		return false;
	}

	public function check_station_against_callsign($stationid, $callsign) {
		$this->db->select('station_id');
		$this->db->where('station_callsign', $callsign);
		$this->db->where('station_id', $stationid);
		$query = $this->db->get('station_profile');
		if ($query->num_rows() == 1) {
			return true;
		}
		return false;
	}
}

?>
