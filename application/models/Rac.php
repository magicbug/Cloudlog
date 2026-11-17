<?php

class Rac extends CI_Model {

	private function logbook_locations() {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		return $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));
	}

	/*
		Canadian QSOs in the active logbook since the award start date, oldest first.
		Official RAC stations are included even when the QSO has no DXCC.
		A QSO is confirmed when it carries a QSL type that is ticked. With nothing ticked, every QSO counts.
	*/
	function get_qsos($group, $filters) {
		$locations = $this->logbook_locations();
		if (!$locations) {
			return array();
		}
		$table = $this->config->item('table_name');
		$this->db->select($table . '.COL_PRIMARY_KEY, COL_CALL, COL_TIME_ON, COL_BAND, COL_FREQ, COL_MODE, COL_SUBMODE, COL_PROP_MODE, COL_DXCC, COL_COUNTRY, COL_STATE, COL_RST_SENT, COL_RST_RCVD, COL_STATION_CALLSIGN, COL_QSL_RCVD, COL_LOTW_QSL_RCVD, COL_EQSL_QSL_RCVD, station_profile.station_callsign AS location_callsign', FALSE);
		$this->db->from($table);
		$this->db->join('station_profile', 'station_profile.station_id = ' . $table . '.station_id', 'left');
		$this->db->where_in($table . '.station_id', $locations);
		$this->db->where("(COL_DXCC = 1 OR COL_CALL LIKE '%RAC%' OR COL_CALL LIKE '%RHQ%')", NULL, FALSE);
		if (!empty($group['since'])) {
			$this->db->where('COL_TIME_ON >=', $group['since'] . ' 00:00:00');
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
				'id' => $row->COL_PRIMARY_KEY,
				'call' => $row->COL_CALL,
				'time' => $row->COL_TIME_ON,
				'band' => $row->COL_BAND,
				'freq' => $row->COL_FREQ,
				'mode' => $row->COL_MODE,
				'submode' => $row->COL_SUBMODE,
				'prop' => $row->COL_PROP_MODE,
				'dxcc' => $row->COL_DXCC,
				'country' => $row->COL_COUNTRY,
				'state' => $row->COL_STATE,
				'rst_sent' => $row->COL_RST_SENT,
				'rst_rcvd' => $row->COL_RST_RCVD,
				'station_call' => $row->location_callsign ? $row->location_callsign : $row->COL_STATION_CALLSIGN,
				'qsl' => $row->COL_QSL_RCVD == 'Y',
				'lotw' => $row->COL_LOTW_QSL_RCVD == 'Y',
				'eqsl' => $row->COL_EQSL_QSL_RCVD == 'Y',
				'confirmed' => $confirmed,
			);
		}
		return $qsos;
	}
}
