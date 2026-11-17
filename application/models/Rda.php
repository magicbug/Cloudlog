<?php

class Rda extends CI_Model {

	const CNTY_PATTERN = '^[A-Za-z]{2}[ -]?[0-9]{1,3}$';

	/*
		QSOs in the active logbook whose county is an RDA code, oldest first.
		Confirmed follows the QSL, LoTW, and eQSL ticks on the page.
	*/
	function get_qsos($filters) {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		if (!$logbooks_locations_array) {
			return array();
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
		$confirmed_sql = $confirmed ? '(' . implode(' OR ', $confirmed) . ')' : '0';

		$this->db->select('COL_CALL, COL_TIME_ON, COL_BAND, COL_MODE, COL_SUBMODE, COL_PROP_MODE, COL_CNTY, ' . $confirmed_sql . ' AS confirmed', FALSE);
		$this->db->where_in('station_id', $logbooks_locations_array);
		$this->db->where("COL_CNTY REGEXP '" . self::CNTY_PATTERN . "'", NULL, FALSE);

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

		$this->db->order_by('COL_TIME_ON', 'ASC');
		$query = $this->db->get($this->config->item('table_name'));

		$qsos = array();
		foreach ($query->result() as $row) {
			$qsos[] = array(
				'call' => $row->COL_CALL,
				'time' => $row->COL_TIME_ON,
				'band' => $row->COL_PROP_MODE == 'SAT' ? 'SAT' : $row->COL_BAND,
				'mode' => $row->COL_SUBMODE ? $row->COL_SUBMODE : $row->COL_MODE,
				'cnty' => $row->COL_CNTY,
				'confirmed' => (bool) $row->confirmed,
			);
		}
		return $qsos;
	}
}
