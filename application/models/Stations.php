<?php

class Stations extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    function all_with_count() {

		$this->db->select('station_profile.*, count('.$this->config->item('table_name').'.station_id) as qso_total');
        $this->db->from('station_profile');
        $this->db->join($this->config->item('table_name'),'station_profile.station_id = '.$this->config->item('table_name').'.station_id','left');
       	$this->db->group_by('station_profile.station_id');
        return $this->db->get();
	}

	function all() {
		return $this->db->get('station_profile');
	}

	function profile($id) {
		// Clean ID
		$clean_id = $this->security->xss_clean($id);


		$this->db->where('station_id', $clean_id);
		return $this->db->get('station_profile');
	}


	function add() {
		$data = array(
			'station_profile_name' => xss_clean($this->input->post('station_profile_name', true)),
			'station_gridsquare' =>  xss_clean(strtoupper($this->input->post('gridsquare', true))),
			'station_city' =>  xss_clean($this->input->post('city', true)),
			'station_iota' =>  xss_clean(strtoupper($this->input->post('iota', true))),
			'station_sota' =>  xss_clean(strtoupper($this->input->post('sota', true))),
			'station_callsign' =>  xss_clean($this->input->post('station_callsign', true)),
			'station_dxcc' =>  xss_clean($this->input->post('dxcc', true)),
			'station_country' =>  xss_clean($this->input->post('station_country', true)),
			'station_cnty' =>  xss_clean($this->input->post('station_cnty', true)),
			'station_cq' =>  xss_clean($this->input->post('station_cq', true)),
			'station_itu' =>  xss_clean($this->input->post('station_itu', true)),
			'state' =>  xss_clean($this->input->post('station_state', true)),
            'eqslqthnickname' => xss_clean($this->input->post('eqslnickname', true)),
            'qrzapikey' => xss_clean($this->input->post('qrzapikey', true)),
		);

		$this->db->insert('station_profile', $data); 
	}

	function edit() {
		$data = array(
			'station_profile_name' => xss_clean($this->input->post('station_profile_name', true)),
			'station_gridsquare' => xss_clean($this->input->post('gridsquare', true)),
			'station_city' => xss_clean($this->input->post('city', true)),
			'station_iota' => xss_clean($this->input->post('iota', true)),
			'station_sota' => xss_clean($this->input->post('sota', true)),
			'station_callsign' => xss_clean($this->input->post('station_callsign', true)),
			'station_dxcc' => xss_clean($this->input->post('dxcc', true)),
			'station_country' => xss_clean($this->input->post('station_country', true)),
			'station_cnty' => xss_clean($this->input->post('station_cnty', true)),
			'station_cq' => xss_clean($this->input->post('station_cq', true)),
			'station_itu' => xss_clean($this->input->post('station_itu', true)),
			'state' => xss_clean($this->input->post('station_state', true)),
			'eqslqthnickname' => xss_clean($this->input->post('eqslnickname', true)),
            'qrzapikey' => xss_clean($this->input->post('qrzapikey', true)),
		);

		$this->db->where('station_id', xss_clean($this->input->post('station_id', true)));
		$this->db->update('station_profile', $data); 
	}

	function delete($id) {
		// Clean ID
		$clean_id = $this->security->xss_clean($id);

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

	function set_active($current, $new) {

		// Clean inputs

		$clean_current = $this->security->xss_clean($current);
		$clean_new = $this->security->xss_clean($new);

        // Deselect current default
		$current_default = array(
				'station_active' => null,
		);
		$this->db->where('station_id', $clean_current);
		$this->db->update('station_profile', $current_default);
		
		// Deselect current default	
		$newdefault = array(
			'station_active' => 1,
		);
		$this->db->where('station_id', $clean_new);
		$this->db->update('station_profile', $newdefault);
    }

    public function find_active() {
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

    public function reassign($id) {
		// Clean ID
		$clean_id = $this->security->xss_clean($id);

    	$this->db->where('station_id', $clean_id);
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
                where coalesce(station_profile.qrzapikey, '') <> ''";
        $query = $this->db->query($sql);

        return $query;
    }

}

?>