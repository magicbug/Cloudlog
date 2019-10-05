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
			'station_profile_name' => $this->input->post('station_profile_name', true),
			'station_gridsquare' => strtoupper($this->input->post('gridsquare', true)),
			'station_city' => $this->input->post('city', true),
			'station_iota' => strtoupper($this->input->post('iota', true)),
			'station_sota' => strtoupper($this->input->post('sota', true)),
			'station_callsign' => $this->input->post('station_callsign', true),
			'station_dxcc' => $this->input->post('dxcc', true),
			'station_country' => $this->input->post('station_country', true),
			'station_cnty' => $this->input->post('station_cnty', true),
			'station_cq' => $this->input->post('station_cq', true),
			'station_itu' => $this->input->post('station_itu', true),
		);

		$this->db->insert('station_profile', $data); 
	}

	function edit() {
		$data = array(
			'station_profile_name' => $this->input->post('station_profile_name', true),
			'station_gridsquare' => $this->input->post('gridsquare', true),
			'station_city' => $this->input->post('city', true),
			'station_iota' => $this->input->post('iota', true),
			'station_sota' => $this->input->post('sota', true),
			'station_callsign' => $this->input->post('station_callsign', true),
			'station_dxcc' => $this->input->post('dxcc', true),
			'station_country' => $this->input->post('station_country', true),
			'station_cnty' => $this->input->post('station_cnty', true),
			'station_cq' => $this->input->post('station_cq', true),
			'station_itu' => $this->input->post('station_itu', true),
			'eqslqthnickname' => $this->input->post('eqslnickname', true),
		);

		$this->db->where('station_id', $this->input->post('station_id', true));
		$this->db->update('station_profile', $data); 
	}

	function delete($id) {
		// Clean ID
		$clean_id = $this->security->xss_clean($id);

		$this->db->delete('station_profile', array('station_id' => $clean_id)); 
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

}

?>