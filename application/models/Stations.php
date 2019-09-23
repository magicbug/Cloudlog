<?php

class Stations extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    function all() {
		return $this->db->get('station_profile');
	}

	function profile($id) {
		$this->db->where('station_id', $id);
		return $this->db->get('station_profile');
	}


	function add() {
		$data = array(
			'station_profile_name' => $this->input->post('station_profile_name'),
			'station_gridsquare' => strtoupper($this->input->post('gridsquare')),
			'station_city' => $this->input->post('city'),
			'station_iota' => strtoupper($this->input->post('iota')),
			'station_sota' => strtoupper($this->input->post('sota')),
			'station_callsign' => $this->input->post('station_callsign'),
			'station_dxcc' => $this->input->post('dxcc'),
			'station_country' => $this->input->post('station_country'),
			'station_cnty' => $this->input->post('station_cnty'),
			'station_cq' => $this->input->post('station_cq'),
			'station_itu' => $this->input->post('station_itu'),
		);

		$this->db->insert('station_profile', $data); 
	}

	function edit() {
		$data = array(
			'station_profile_name' => $this->input->post('station_profile_name'),
			'station_gridsquare' => $this->input->post('gridsquare'),
			'station_city' => $this->input->post('city'),
			'station_iota' => $this->input->post('iota'),
			'station_sota' => $this->input->post('sota'),
			'station_callsign' => $this->input->post('station_callsign'),
			'station_dxcc' => $this->input->post('dxcc'),
			'station_country' => $this->input->post('station_country'),
			'station_cnty' => $this->input->post('station_cnty'),
			'station_cq' => $this->input->post('station_cq'),
			'station_itu' => $this->input->post('station_itu'),
		);

		$this->db->where('station_id', $this->input->post('station_id'));
		$this->db->update('station_profile', $data); 
	}

	function delete($id) {
		$this->db->delete('station_profile', array('station_id' => $id)); 
	}

	function set_active($current, $new) {
        // Deselect current default
		$current_default = array(
				'station_active' => null,
		);
		$this->db->where('station_id', $current);
		$this->db->update('station_profile', $current_default);
		
		// Deselect current default	
		$newdefault = array(
			'station_active' => 1,
		);
		$this->db->where('station_id', $new);
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
    	$this->db->where('station_id', $id);
		$query = $this->db->get('station_profile');

		$row = $query->row();

		//print_r($row);

		$data = array(
		        'station_id' => $id,
		);

		$this->db->where('COL_STATION_CALLSIGN', $row->station_callsign);
		
		if($row->station_iota != "") {
			$this->db->where('COL_MY_IOTA', $row->station_country);
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

		echo $str;

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