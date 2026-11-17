<?php

class Rsgb extends CI_Model {

	private function logbook_locations() {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		return $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));
	}

	/*
		Station callsigns in the active logbook: the station location callsign,
		or the QSO's own station callsign when the location has none.
	*/
	function station_callsigns() {
		$locations = $this->logbook_locations();
		if (!$locations) {
			return array();
		}
		$table = $this->config->item('table_name');
		$this->db->select("DISTINCT COALESCE(NULLIF(station_profile.station_callsign, ''), " . $table . ".COL_STATION_CALLSIGN) AS callsign", FALSE);
		$this->db->from($table);
		$this->db->join('station_profile', 'station_profile.station_id = ' . $table . '.station_id', 'left');
		$this->db->where_in($table . '.station_id', $locations);
		$calls = array();
		foreach ($this->db->get()->result() as $row) {
			if ($row->callsign !== null && $row->callsign !== '') {
				$calls[] = strtoupper($row->callsign);
			}
		}
		return array_values(array_unique($calls));
	}

	/*
		QSOs in the active logbook for one award group, oldest first.
		A QSO is confirmed when it carries a QSL type that is ticked. With nothing ticked, every QSO counts.
	*/
	function get_qsos($group, $filters) {
		$locations = $this->logbook_locations();
		if (!$locations) {
			return array();
		}
		$table = $this->config->item('table_name');
		$this->db->select($table . '.COL_PRIMARY_KEY, COL_CALL, COL_TIME_ON, COL_BAND, COL_FREQ, COL_MODE, COL_SUBMODE, COL_PROP_MODE, COL_DXCC, COL_COUNTRY, COALESCE(NULLIF(COL_CONT, \'\'), dxcc_entities.cont) AS COL_CONT, COL_ITUZ, COL_GRIDSQUARE, COL_VUCC_GRIDS, COL_RST_SENT, COL_RST_RCVD, COL_STATION_CALLSIGN, COL_QSL_RCVD, COL_LOTW_QSL_RCVD, COL_EQSL_QSL_RCVD, COL_CLUBLOG_QSO_DOWNLOAD_STATUS, station_profile.station_callsign AS location_callsign', FALSE);
		$this->db->from($table);
		$this->db->join('station_profile', 'station_profile.station_id = ' . $table . '.station_id', 'left');
		$this->db->join('dxcc_entities', 'dxcc_entities.adif = ' . $table . '.COL_DXCC', 'left');
		$this->db->where_in($table . '.station_id', $locations);
		if (!empty($group['bands'])) {
			$this->db->where_in('COL_BAND', $group['bands']);
		}
		if (!empty($group['since'])) {
			$this->db->where('COL_TIME_ON >=', $group['since'] . ' 00:00:00');
		}
		if (!empty($group['no_sat'])) {
			$this->db->where('(COL_PROP_MODE IS NULL OR COL_PROP_MODE != "SAT")', NULL, FALSE);
		}
		$this->db->order_by('COL_TIME_ON', 'ASC');
		$query = $this->db->get();

		$any_ticked = !empty($filters['qsl']) || !empty($filters['lotw']) || !empty($filters['eqsl']) || !empty($filters['clublog']);
		$qsos = array();
		foreach ($query->result() as $row) {
			$confirmed = !$any_ticked
				|| (!empty($filters['qsl']) && $row->COL_QSL_RCVD == 'Y')
				|| (!empty($filters['lotw']) && $row->COL_LOTW_QSL_RCVD == 'Y')
				|| (!empty($filters['eqsl']) && $row->COL_EQSL_QSL_RCVD == 'Y')
				|| (!empty($filters['clublog']) && $row->COL_CLUBLOG_QSO_DOWNLOAD_STATUS == 'Y');
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
				'cont' => $row->COL_CONT,
				'ituz' => $row->COL_ITUZ,
				'grid' => $row->COL_GRIDSQUARE,
				'vucc' => $row->COL_VUCC_GRIDS,
				'rst_sent' => $row->COL_RST_SENT,
				'rst_rcvd' => $row->COL_RST_RCVD,
				'station_call' => $row->location_callsign ? $row->location_callsign : $row->COL_STATION_CALLSIGN,
				'qsl' => $row->COL_QSL_RCVD == 'Y',
				'lotw' => $row->COL_LOTW_QSL_RCVD == 'Y',
				'eqsl' => $row->COL_EQSL_QSL_RCVD == 'Y',
				'clublog' => $row->COL_CLUBLOG_QSO_DOWNLOAD_STATUS == 'Y',
				'confirmed' => $confirmed,
			);
		}
		return $qsos;
	}
}
