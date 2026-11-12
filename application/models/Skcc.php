<?php

class Skcc extends CI_Model {

	/*
		QSOs in the active logbook that carry an SKCC number, oldest first.
		The number is in COL_SKCC, or in SIG Info when SIG is SKCC.
	*/
	function get_qsos($filters) {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		if (!$logbooks_locations_array) {
			return array();
		}

		$this->db->select('COL_CALL, COL_NAME, COL_TIME_ON, COL_BAND, COL_MODE, COL_SUBMODE, COL_PROP_MODE, COL_SKCC, COL_SIG, COL_SIG_INFO');
		$this->db->where_in('station_id', $logbooks_locations_array);
		$this->db->group_start();
		$this->db->group_start();
		$this->db->where('COL_SKCC IS NOT NULL', NULL, FALSE);
		$this->db->where('COL_SKCC !=', '');
		$this->db->group_end();
		$this->db->or_where('UPPER(COL_SIG) =', 'SKCC');
		$this->db->group_end();

		if (isset($filters['mode']) && $filters['mode'] !== 'All' && $filters['mode'] !== '') {
			$this->db->group_start();
			$this->db->where('COL_MODE', $filters['mode']);
			$this->db->or_where('COL_SUBMODE', $filters['mode']);
			$this->db->group_end();
		}

		if (isset($filters['band']) && $filters['band'] !== 'All' && $filters['band'] !== '') {
			if ($filters['band'] != 'SAT') {
				$this->db->where('COL_BAND', $filters['band']);
				$this->db->where('(COL_PROP_MODE IS NULL OR COL_PROP_MODE != "SAT")', NULL, FALSE);
			} else {
				$this->db->where('COL_PROP_MODE', 'SAT');
			}
		}

		$confirmed = array();
		if (!empty($filters['qsl'])) {
			$confirmed[] = "COL_QSL_RCVD = 'Y'";
		}
		if (!empty($filters['lotw'])) {
			$confirmed[] = "COL_LOTW_QSL_RCVD = 'Y'";
		}
		if (!empty($filters['eqsl'])) {
			$confirmed[] = "COL_EQSL_QSL_RCVD = 'Y'";
		}
		if ($confirmed) {
			$this->db->where('(' . implode(' OR ', $confirmed) . ')', NULL, FALSE);
		}

		$this->db->order_by('COL_TIME_ON', 'ASC');
		$query = $this->db->get($this->config->item('table_name'));

		$qsos = array();
		foreach ($query->result() as $row) {
			$qsos[] = array(
				'call' => $row->COL_CALL,
				'name' => $row->COL_NAME,
				'time' => $row->COL_TIME_ON,
				'band' => $row->COL_PROP_MODE == 'SAT' ? 'SAT' : $row->COL_BAND,
				'mode' => $row->COL_SUBMODE ? $row->COL_SUBMODE : $row->COL_MODE,
				'skcc' => $row->COL_SKCC,
				'sig' => $row->COL_SIG,
				'sig_info' => $row->COL_SIG_INFO,
			);
		}
		return $qsos;
	}
}
