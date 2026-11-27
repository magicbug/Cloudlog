<?php

class Wae extends CI_Model {

	private function logbook_locations() {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		return $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));
	}

	/*
		QSOs that may count for WAE from the active logbook.
		Selection is by resolved country in Wae_award, so the query is broad:
		known WAE DXCC entities plus callsign specials (4U1V, TA1, Sicily, Shetland, Bear Island).
	*/
	function get_qsos($filters) {
		$locations = $this->logbook_locations();
		if (!$locations) {
			return array();
		}

		$CI =& get_instance();
		$CI->load->library('wae_award');

		$dxcc_ids = array();
		foreach ($CI->wae_award->countries as $info) {
			if ($info['dxcc'] !== null) {
				$dxcc_ids[] = (int) $info['dxcc'];
			}
		}
		foreach ($CI->wae_award->deleted as $info) {
			if ($info['dxcc'] !== null) {
				$dxcc_ids[] = (int) $info['dxcc'];
			}
		}
		// Turkey (European call area 1 only) and Austria (for 4U1V logged as OE).
		$dxcc_ids[] = 390;
		$dxcc_ids[] = 206;
		$dxcc_ids = array_values(array_unique($dxcc_ids));

		$table = $this->config->item('table_name');
		$this->db->select('COL_CALL, COL_DXCC, COL_TIME_ON, COL_BAND, COL_MODE, COL_SUBMODE, COL_PROP_MODE, COL_QSL_RCVD, COL_LOTW_QSL_RCVD, COL_EQSL_QSL_RCVD');
		$this->db->from($table);
		$this->db->where_in('station_id', $locations);
		$this->db->group_start();
		$this->db->where_in('COL_DXCC', $dxcc_ids);
		$this->db->or_like('COL_CALL', '4U1V', 'after');
		$this->db->or_like('COL_CALL', '4U1ITU', 'after');
		$this->db->or_like('COL_CALL', 'TA1', 'after');
		$this->db->or_like('COL_CALL', 'TB1', 'after');
		$this->db->or_like('COL_CALL', 'TC1', 'after');
		$this->db->or_like('COL_CALL', 'YM1', 'after');
		$this->db->or_like('COL_CALL', 'IT9', 'after');
		$this->db->or_like('COL_CALL', '/S', 'before');
		$this->db->or_like('COL_CALL', '/B', 'before');
		$this->db->or_like('COL_CALL', 'GS', 'after');
		$this->db->group_end();

		if (isset($filters['band']) && $filters['band'] !== 'All' && $filters['band'] !== '') {
			if ($filters['band'] != 'SAT') {
				$this->db->where('COL_BAND', $filters['band']);
				$this->db->where('(COL_PROP_MODE IS NULL OR COL_PROP_MODE != "SAT")', NULL, FALSE);
			} else {
				$this->db->where('COL_PROP_MODE', 'SAT');
			}
		}

		if (isset($filters['mode']) && $filters['mode'] !== 'All' && $filters['mode'] !== ''
			&& !in_array($filters['mode'], array('Mixed', 'Phone', 'Digital', 'CW', 'SSB', 'RTTY', 'FT8'), true)) {
			$this->db->group_start();
			$this->db->where('COL_MODE', $filters['mode']);
			$this->db->or_where('COL_SUBMODE', $filters['mode']);
			$this->db->group_end();
		}

		$this->db->order_by('COL_TIME_ON', 'ASC');
		$query = $this->db->get();

		$any_ticked = !empty($filters['qsl']) || !empty($filters['lotw']) || !empty($filters['eqsl']);
		$qsos = array();
		foreach ($query->result() as $row) {
			$confirmed = !$any_ticked
				|| (!empty($filters['qsl']) && $row->COL_QSL_RCVD == 'Y')
				|| (!empty($filters['lotw']) && $row->COL_LOTW_QSL_RCVD == 'Y')
				|| (!empty($filters['eqsl']) && $row->COL_EQSL_QSL_RCVD == 'Y');
			$qsos[] = array(
				'call' => $row->COL_CALL,
				'dxcc' => (int) $row->COL_DXCC,
				'time' => $row->COL_TIME_ON,
				'band' => $row->COL_PROP_MODE == 'SAT' ? 'SAT' : $row->COL_BAND,
				'mode' => $row->COL_MODE,
				'submode' => $row->COL_SUBMODE,
				'confirmed' => $confirmed,
			);
		}
		return $qsos;
	}
}
