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

}

?>