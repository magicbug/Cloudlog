<?php

class Contests extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

	function information($id) {
		$this->db->select('*');
		$this->db->from('contests');
		//$this->db->where('id', $id); 
		$this->db->join('contest_template', 'contest_template.id = contests.template');
		$query = $this->db->get();
		return $query->row(); 
	}

	function create_template() {
		$data = array(
			'name' => $this->input->post('contest_name'),
			'band_160' => $this->input->post('160m'),
			'band_80' => $this->input->post('80m'),
			'band_40' => $this->input->post('40m'),
			'band_20' => $this->input->post('20m'),
			'band_15' => $this->input->post('15m'),
			'band_10' => $this->input->post('10m'),
			'band_6m' => $this->input->post('6m'),
			'band_4m' => $this->input->post('4m'),
			'band_2m' => $this->input->post('2m'),
			'band_70cm' => $this->input->post('70cm'),
			'band_23cm' => $this->input->post('23cm'),
			'mode_ssb' => $this->input->post('SSB'),
			'mode_cw' => $this->input->post('CW'),
			'serial' => $this->input->post('serial_num'),
			'point_per_km' => $this->input->post('points_per_km'),
			'qra' => $this->input->post('qra'),
			'other_exch' => $this->input->post('other_exch'),
			'scoring' => $this->input->post('scoring'),
		);

		$this->db->insert('contest_template', $data); 
	}
	
	
	function create_contest() {
		$start = $this->input->post('start_date')." ".$this->input->post('start_time');
		$end = $this->input->post('end_date')." ".$this->input->post('end_time');
		$data = array(
			'name' => $this->input->post('contest_name'),
			'start' => $start,
			'end' => $end,
			'template' => $this->input->post('template'),
		);

		$this->db->insert('contests', $data); 
	}

	function list_templates() {
		return $this->db->get('contest_template');
	}
	
	function list_contests() {
		return $this->db->get('contests');
	}

}

?>