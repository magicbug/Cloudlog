<?php

class Wpx extends CI_Model {

	private function logbook_locations() {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		return $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));
	}

	private function apply_filters($filters) {
		if (isset($filters['mode']) && $filters['mode'] !== 'All' && $filters['mode'] !== '') {
			$this->db->group_start();
			$this->db->where('COL_MODE', $filters['mode']);
			$this->db->or_where('COL_SUBMODE', $filters['mode']);
			$this->db->group_end();
		}

		if (isset($filters['band']) && $filters['band'] !== 'All' && $filters['band'] !== '') {
			$this->db->where('COL_BAND', $filters['band']);
		}

		// WPX is HF through 6 m only; satellite contacts do not count.
		$this->db->where_in('COL_BAND', array('160m', '80m', '60m', '40m', '30m', '20m', '17m', '15m', '12m', '10m', '6m'));
		$this->db->where('(COL_PROP_MODE IS NULL OR COL_PROP_MODE != "SAT")', NULL, FALSE);
	}

	/*
		Build synthetic QSO rows for Wpx_award::evaluate().
		Rows with a stored COL_PFX are aggregated in SQL.
		Calls with an empty COL_PFX are fetched and prefixed in PHP.
	*/
	function get_qsos($filters) {
		$locations = $this->logbook_locations();
		if (!$locations) {
			return array();
		}

		$table = $this->config->item('table_name');
		$any_ticked = !empty($filters['qsl']) || !empty($filters['lotw']) || !empty($filters['eqsl']);

		if ($any_ticked) {
			$parts = array();
			if (!empty($filters['qsl'])) {
				$parts[] = "COL_QSL_RCVD = 'Y'";
			}
			if (!empty($filters['lotw'])) {
				$parts[] = "COL_LOTW_QSL_RCVD = 'Y'";
			}
			if (!empty($filters['eqsl'])) {
				$parts[] = "COL_EQSL_QSL_RCVD = 'Y'";
			}
			$confirmed_expr = '(' . implode(' OR ', $parts) . ')';
		} else {
			$confirmed_expr = '1';
		}

		$qsos = array();

		// Aggregated rows that already carry a WPX prefix.
		$this->db->select("UPPER(TRIM(COL_PFX)) AS pfx, COL_BAND AS band, COL_MODE AS mode, COL_SUBMODE AS submode, UPPER(TRIM(COL_CONT)) AS cont, MAX($confirmed_expr) AS confirmed, COUNT(*) AS qsos", FALSE);
		$this->db->from($table);
		$this->db->where_in('station_id', $locations);
		$this->db->where('COL_PFX IS NOT NULL', NULL, FALSE);
		$this->db->where("TRIM(COL_PFX) !=", '');
		$this->apply_filters($filters);
		$this->db->group_by('pfx, band, mode, submode, cont');
		$query = $this->db->get();
		foreach ($query->result() as $row) {
			$qsos[] = array(
				'call' => '',
				'pfx' => $row->pfx,
				'band' => $row->band,
				'mode' => $row->mode,
				'submode' => $row->submode,
				'cont' => $row->cont,
				'confirmed' => (bool) $row->confirmed,
				'count' => (int) $row->qsos,
			);
		}

		// Calls without a stored prefix — compute individually, then collapse.
		$this->db->select('COL_CALL, COL_BAND, COL_MODE, COL_SUBMODE, COL_CONT, COL_QSL_RCVD, COL_LOTW_QSL_RCVD, COL_EQSL_QSL_RCVD');
		$this->db->from($table);
		$this->db->where_in('station_id', $locations);
		$this->db->group_start();
		$this->db->where('COL_PFX IS NULL', NULL, FALSE);
		$this->db->or_where("TRIM(COL_PFX) =", '');
		$this->db->group_end();
		$this->apply_filters($filters);
		$query = $this->db->get();

		$CI =& get_instance();
		$CI->load->library('wpx_award');
		$computed = array();
		foreach ($query->result() as $row) {
			$confirmed = !$any_ticked
				|| (!empty($filters['qsl']) && $row->COL_QSL_RCVD == 'Y')
				|| (!empty($filters['lotw']) && $row->COL_LOTW_QSL_RCVD == 'Y')
				|| (!empty($filters['eqsl']) && $row->COL_EQSL_QSL_RCVD == 'Y');
			$pfx = $CI->wpx_award->prefix_from_call($row->COL_CALL);
			if ($pfx === '') {
				continue;
			}
			$key = $pfx . '|' . $row->COL_BAND . '|' . $row->COL_MODE . '|' . $row->COL_SUBMODE . '|' . strtoupper(trim((string) $row->COL_CONT));
			if (!isset($computed[$key])) {
				$computed[$key] = array(
					'call' => $row->COL_CALL,
					'pfx' => $pfx,
					'band' => $row->COL_BAND,
					'mode' => $row->COL_MODE,
					'submode' => $row->COL_SUBMODE,
					'cont' => $row->COL_CONT,
					'confirmed' => false,
					'count' => 0,
				);
			}
			$computed[$key]['count']++;
			if ($confirmed) {
				$computed[$key]['confirmed'] = true;
			}
		}
		foreach ($computed as $row) {
			$qsos[] = $row;
		}

		return $qsos;
	}

	/*
		Aggregate worked/confirmed WPX prefixes per DXCC entity for the interactive world map.
	*/
	function get_map_entities($filters) {
		$locations = $this->logbook_locations();
		if (!$locations) {
			return array();
		}

		$table = $this->config->item('table_name');
		$any_ticked = !empty($filters['qsl']) || !empty($filters['lotw']) || !empty($filters['eqsl']);

		if ($any_ticked) {
			$parts = array();
			if (!empty($filters['qsl'])) {
				$parts[] = "t.COL_QSL_RCVD = 'Y'";
			}
			if (!empty($filters['lotw'])) {
				$parts[] = "t.COL_LOTW_QSL_RCVD = 'Y'";
			}
			if (!empty($filters['eqsl'])) {
				$parts[] = "t.COL_EQSL_QSL_RCVD = 'Y'";
			}
			$confirmed_expr = '(' . implode(' OR ', $parts) . ')';
		} else {
			$confirmed_expr = '1';
		}

		$this->db->select("t.COL_DXCC AS adif, UPPER(TRIM(t.COL_PFX)) AS pfx, MAX($confirmed_expr) AS confirmed, d.name AS name, d.cont AS cont, d.lat AS lat, d.`long` AS lng", FALSE);
		$this->db->from($table . ' t');
		$this->db->join('dxcc_entities d', 't.COL_DXCC = d.adif', 'inner');
		$this->db->where_in('t.station_id', $locations);
		$this->db->where('t.COL_DXCC >', 0);
		$this->db->where('t.COL_PFX IS NOT NULL', NULL, FALSE);
		$this->db->where("TRIM(t.COL_PFX) !=", '');
		$this->db->where('d.lat IS NOT NULL', NULL, FALSE);
		$this->db->where('d.`long` IS NOT NULL', NULL, FALSE);

		if (isset($filters['mode']) && $filters['mode'] !== 'All' && $filters['mode'] !== '') {
			$this->db->group_start();
			$this->db->where('t.COL_MODE', $filters['mode']);
			$this->db->or_where('t.COL_SUBMODE', $filters['mode']);
			$this->db->group_end();
		}
		if (isset($filters['band']) && $filters['band'] !== 'All' && $filters['band'] !== '') {
			$this->db->where('t.COL_BAND', $filters['band']);
		}
		$this->db->where_in('t.COL_BAND', array('160m', '80m', '60m', '40m', '30m', '20m', '17m', '15m', '12m', '10m', '6m'));
		$this->db->where('(t.COL_PROP_MODE IS NULL OR t.COL_PROP_MODE != "SAT")', NULL, FALSE);

		$this->db->group_by('t.COL_DXCC, pfx, d.name, d.cont, d.lat, d.`long`');
		$query = $this->db->get();

		$entities = array();
		foreach ($query->result() as $row) {
			$adif = (int) $row->adif;
			if (!isset($entities[$adif])) {
				$entities[$adif] = array(
					'adif' => $adif,
					'name' => ucwords(strtolower((string) $row->name)),
					'cont' => strtoupper(trim((string) $row->cont)),
					'lat' => (float) $row->lat,
					'lng' => (float) $row->lng,
					'worked' => 0,
					'confirmed' => 0,
					'sample' => array(),
				);
			}
			$entities[$adif]['worked']++;
			if (!empty($row->confirmed)) {
				$entities[$adif]['confirmed']++;
			}
			if (count($entities[$adif]['sample']) < 8) {
				$entities[$adif]['sample'][] = $row->pfx;
			}
		}

		return array_values($entities);
	}
}
