<?php

	class Stats extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}
	
	function result() {
		$this->db->select('COL_CALL, COL_BAND, COL_TIME_ON, COL_RST_RCVD, COL_RST_SENT, COL_MODE, COL_NAME, COL_COUNTRY, COL_PRIMARY_KEY, COL_SAT_NAME');
	
		$this->db->where('COL_TIME_ON >=', $this->input->post('start_date')); 
		$this->db->where('COL_TIME_OFF <=', $this->input->post('end_date')); 
		
		if($this->input->post('band_6m') == "6m") {
			$this->db->where('COL_BAND', $this->input->post('band_6m')); 
		}
		
		if($this->input->post('band_2m') == "2m") {
			$this->db->where('COL_BAND', $this->input->post('band_2m')); 
		}
		
		if($this->input->post('band_70cm') == "70cm") {
			$this->db->where('COL_BAND', $this->input->post('band_70cm')); 
		}
		
		if($this->input->post('band_23cm') == "23cm") {
			$this->db->where('COL_BAND', $this->input->post('band_23cm')); 
		}
		
		if($this->input->post('band_3cm') == "3cm") {
			$this->db->where('COL_BAND', $this->input->post('band_3cm')); 
		}
				
		// Select Voice QSOs
		if($this->input->post('mode_data') == "data") {
			if($this->input->post('mode_ssb') != "ssb") {
				$this->db->where('COL_MODE !=', 'SSB');
				$this->db->where('COL_MODE !=', 'LSB');
				$this->db->where('COL_MODE !=', 'USB');
			}
			if($this->input->post('mode_cw') != "cw") {
				$this->db->where('COL_MODE !=', 'CW');
			}
			if($this->input->post('mode_fm') != "fm") {
				$this->db->where('COL_MODE !=', 'FM');
			}
			if($this->input->post('mode_am') != "am") {
				$this->db->where('COL_MODE !=', 'AM');
			}
		}
		
		// Select Voice QSOs
		if($this->input->post('mode_ssb') == "ssb") {
			$this->db->where('COL_MODE', $this->input->post('mode_ssb')); 
			$this->db->or_where('COL_MODE', 'USB');
			$this->db->or_where('COL_MODE', 'LSB');  
		}
		
		// Select CW QSOs
		if($this->input->post('mode_cw') == "cw") {
			$this->db->where('COL_MODE', $this->input->post('mode_ssb')); 
		}
		
		// Select FM QSOs
		if($this->input->post('mode_fm') == "fm") {
			$this->db->where('COL_MODE', $this->input->post('mode_ssb')); 
		}
		
		// Select AM QSOs
		if($this->input->post('mode_am') == "am") {
			$this->db->where('COL_MODE', $this->input->post('mode_am')); 
		}
		
		return $this->db->get($this->config->item('table_name'));
	}
}

?>