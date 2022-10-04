<?php

	class Stats extends CI_Model {

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

	function unique_callsigns() {
		$qsoView = array();

		$bands = $this->get_bands();
		$modes = $this->get_modes();

		$bandunique = $this->getUniqueCallsignsBands();
		$modeunique = $this->getUniqueCallsignsModes();
		
		// Generating the band/mode table
		foreach ($bands as $band) {
			$bandtotal[$band] = 0;
			foreach ($modes as $mode) {
				$qsoView [$mode][$band] = '-';
			}
		}

		foreach ($bandunique as $band) {
			$bandcalls[$band->band] = $band->calls;
		}

		foreach ($modeunique as $mode) {
			//if ($mode->col_submode == null) {
			if ($mode->col_submode == null || $mode->col_submode == "") {
				$modecalls[$mode->col_mode] = $mode->calls;
			} else {
				$modecalls[$mode->col_submode] = $mode->calls;
			}
		}

		// Populating array with worked
		$workedQso = $this->getUniqueCallsigns();

		foreach ($workedQso as $line) {
			//if ($line->col_submode == null) {
			if ($line->col_submode == null || $line->col_submode == "") {
				$qsoView [$line->col_mode]  [$line->band] = $line->calls;
			} else {
				$qsoView [$line->col_submode]  [$line->band] = $line->calls;
			}
		}

		$result['qsoView'] = $qsoView;
		$result['bandunique'] = $bandcalls;
		$result['modeunique'] = $modecalls;
		$result['total'] = $this->getUniqueCallsignsTotal();

		return $result;
	}

	function getUniqueCallsigns() {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));
	
		if (!$logbooks_locations_array) {
		  return null;
		}

		$bands = array();
	
		$this->db->select('count(distinct col_call) as calls, lower(col_band) as band, col_mode, coalesce(col_submode, "") col_submode', FALSE);
		$this->db->where_in('station_id', $logbooks_locations_array);
		$this->db->group_by('lower(col_band), col_mode, coalesce(col_submode, "")');
	
		$query = $this->db->get($this->config->item('table_name'));

		return $query->result();
	}

	function getUniqueCallsignsModes() {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));
	
		if (!$logbooks_locations_array) {
		  return null;
		}

		$bands = array();
	
		$this->db->select('count(distinct col_call) as calls, col_mode, coalesce(col_submode, "") col_submode', FALSE);
		$this->db->where_in('station_id', $logbooks_locations_array);
		$this->db->group_by('col_mode, coalesce(col_submode, "")');
	
		$query = $this->db->get($this->config->item('table_name'));

		return $query->result();
	}

	function getUniqueCallsignsBands() {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));
	
		if (!$logbooks_locations_array) {
		  return null;
		}

		$bands = array();
	
		$this->db->select('count(distinct col_call) as calls, col_band as band', FALSE);
		$this->db->where_in('station_id', $logbooks_locations_array);
		$this->db->group_by('col_band');
	
		$query = $this->db->get($this->config->item('table_name'));

		return $query->result();
	}

	function getUniqueCallsignsTotal() {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));
	
		if (!$logbooks_locations_array) {
		  return null;
		}

		$bands = array();
	
		$this->db->select('count(distinct col_call) as calls', FALSE);
		$this->db->where_in('station_id', $logbooks_locations_array);
	
		$query = $this->db->get($this->config->item('table_name'));

		return $query->row();
	}

	function total_qsos() {
		$qsoView = array();

		$bands = $this->get_bands();
		$modes = $this->get_modes();

		$bandtotal = array();
		$modetotal = array();
		// Generating the band/mode table
		foreach ($bands as $band) {
			$bandtotal[$band] = 0;
			foreach ($modes as $mode) {
				$qsoView [$mode][$band] = '-';
				$modetotal[$mode] = 0;
			}
		}

		// Populating array with worked
		$workedQso = $this->modeBandQso();
		foreach ($workedQso as $line) {
			if ($line->col_submode == null || $line->col_submode == "") {
				$qsoView [$line->col_mode]  [$line->band] = $line->count;
				$modetotal[$line->col_mode] += $line->count;
			} else {
				$qsoView [$line->col_submode]  [$line->band] = $line->count;
				$modetotal[$line->col_submode] += $line->count;
			}
			$bandtotal[$line->band] += $line->count;
		}

		$result['qsoView'] = $qsoView;
		$result['bandtotal'] = $bandtotal;
		$result['modetotal'] = $modetotal;

		return $result;
	}

	function modeBandQso() {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));
	
		if (!$logbooks_locations_array) {
		  return null;
		}

		$bands = array();
	
		$this->db->select('count(*) as count, lower(col_band) as band, col_mode, coalesce(col_submode, "") col_submode', FALSE);
		$this->db->where_in('station_id', $logbooks_locations_array);
		$this->db->group_by('lower(col_band), col_mode, coalesce(col_submode, "")');
	
		$query = $this->db->get($this->config->item('table_name'));

		return $query->result();
	}

	function get_bands() {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));
	
		if (!$logbooks_locations_array) {
		  return null;
		}

		$bands = array();
	
		$this->db->select('distinct col_band+0 as bandsort, lower(col_band) as band', FALSE);
		$this->db->where_in('station_id', $logbooks_locations_array);
		$this->db->order_by('bandsort', 'desc');
	
		$query = $this->db->get($this->config->item('table_name'));

		foreach($query->result() as $band){
			array_push($bands, $band->band);
		}
	
		return $bands;
	}

	function get_modes() {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));
	
		if (!$logbooks_locations_array) {
		  return null;
		}

		$modes = array();
	
		$this->db->select('distinct col_mode, coalesce(col_submode, "") col_submode', FALSE);
		$this->db->where_in('station_id', $logbooks_locations_array);
		$this->db->order_by('col_mode, col_submode', 'ASC');

		$query = $this->db->get($this->config->item('table_name'));
	
		foreach($query->result() as $mode){
			if ($mode->col_submode == null || $mode->col_submode == "") {
				array_push($modes, $mode->col_mode);
			} else {
				array_push($modes, $mode->col_submode);
			}
		}

		return $modes;
	}
}

?>