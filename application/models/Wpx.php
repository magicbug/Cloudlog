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
		Includes both rows with stored COL_PFX and rows where COL_PFX is empty (derived from COL_CALL).
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

		$apply_map_filters = function ($db) use ($filters) {
			if (isset($filters['mode']) && $filters['mode'] !== 'All' && $filters['mode'] !== '') {
				$db->group_start();
				$db->where('t.COL_MODE', $filters['mode']);
				$db->or_where('t.COL_SUBMODE', $filters['mode']);
				$db->group_end();
			}
			if (isset($filters['band']) && $filters['band'] !== 'All' && $filters['band'] !== '') {
				$db->where('t.COL_BAND', $filters['band']);
			}
			$db->where_in('t.COL_BAND', array('160m', '80m', '60m', '40m', '30m', '20m', '17m', '15m', '12m', '10m', '6m'));
			$db->where('(t.COL_PROP_MODE IS NULL OR t.COL_PROP_MODE != "SAT")', NULL, FALSE);
		};

		$entities = array();
		$add_pfx = function ($adif, $name, $cont, $lat, $lng, $pfx, $confirmed) use (&$entities) {
			if ($pfx === '') return;
			if (!isset($entities[$adif])) {
				$entities[$adif] = array(
					'adif' => $adif,
					'name' => ucwords(strtolower((string) $name)),
					'cont' => strtoupper(trim((string) $cont)),
					'lat' => (float) $lat,
					'lng' => (float) $lng,
					'prefixes' => array(),
				);
			}
			if (!isset($entities[$adif]['prefixes'][$pfx])) {
				$entities[$adif]['prefixes'][$pfx] = false;
			}
			if ($confirmed) {
				$entities[$adif]['prefixes'][$pfx] = true;
			}
		};

		// 1. Rows with stored COL_PFX
		$this->db->select("t.COL_DXCC AS adif, UPPER(TRIM(t.COL_PFX)) AS pfx, MAX($confirmed_expr) AS confirmed, d.name AS name, d.cont AS cont, d.lat AS lat, d.`long` AS lng", FALSE);
		$this->db->from($table . ' t');
		$this->db->join('dxcc_entities d', 't.COL_DXCC = d.adif', 'inner');
		$this->db->where_in('t.station_id', $locations);
		$this->db->where('t.COL_DXCC >', 0);
		$this->db->where('t.COL_PFX IS NOT NULL', NULL, FALSE);
		$this->db->where("TRIM(t.COL_PFX) !=", '');
		$this->db->where('d.lat IS NOT NULL', NULL, FALSE);
		$this->db->where('d.`long` IS NOT NULL', NULL, FALSE);
		$apply_map_filters($this->db);
		$this->db->group_by('t.COL_DXCC, pfx, d.name, d.cont, d.lat, d.`long`');
		$query = $this->db->get();

		foreach ($query->result() as $row) {
			$add_pfx((int) $row->adif, $row->name, $row->cont, $row->lat, $row->lng, $row->pfx, !empty($row->confirmed));
		}

		// 2. Rows with empty/NULL COL_PFX — derive prefix from COL_CALL
		$this->db->select("t.COL_DXCC AS adif, t.COL_CALL AS callsign, MAX($confirmed_expr) AS confirmed, d.name AS name, d.cont AS cont, d.lat AS lat, d.`long` AS lng", FALSE);
		$this->db->from($table . ' t');
		$this->db->join('dxcc_entities d', 't.COL_DXCC = d.adif', 'inner');
		$this->db->where_in('t.station_id', $locations);
		$this->db->where('t.COL_DXCC >', 0);
		$this->db->group_start();
		$this->db->where('t.COL_PFX IS NULL', NULL, FALSE);
		$this->db->or_where("TRIM(t.COL_PFX) =", '');
		$this->db->group_end();
		$this->db->where('d.lat IS NOT NULL', NULL, FALSE);
		$this->db->where('d.`long` IS NOT NULL', NULL, FALSE);
		$apply_map_filters($this->db);
		$this->db->group_by('t.COL_DXCC, t.COL_CALL, d.name, d.cont, d.lat, d.`long`');
		$query2 = $this->db->get();

		$CI =& get_instance();
		$CI->load->library('wpx_award');
		foreach ($query2->result() as $row) {
			$pfx = $CI->wpx_award->prefix_from_call($row->callsign);
			$add_pfx((int) $row->adif, $row->name, $row->cont, $row->lat, $row->lng, $pfx, !empty($row->confirmed));
		}

		// 3. Include current DXCC entities with 0 worked prefixes (for optional "Show Unworked" map toggle)
		$this->db->select('adif, name, cont, lat, `long` AS lng', FALSE);
		$this->db->from('dxcc_entities');
		$this->db->where('adif >', 0);
		$this->db->where('end IS NULL', NULL, FALSE);
		$this->db->where('lat IS NOT NULL', NULL, FALSE);
		$this->db->where('`long` IS NOT NULL', NULL, FALSE);
		$all_dxcc = $this->db->get();
		foreach ($all_dxcc->result() as $d) {
			$adif = (int) $d->adif;
			if (!isset($entities[$adif])) {
				$entities[$adif] = array(
					'adif' => $adif,
					'name' => ucwords(strtolower((string) $d->name)),
					'cont' => strtoupper(trim((string) $d->cont)),
					'lat' => (float) $d->lat,
					'lng' => (float) $d->lng,
					'prefixes' => array(),
				);
			}
		}

		$result = array();
		foreach ($entities as $ent) {
			$worked = count($ent['prefixes']);
			$confirmed = 0;
			foreach ($ent['prefixes'] as $is_c) {
				if ($is_c) $confirmed++;
			}
			$sample = array_slice(array_keys($ent['prefixes']), 0, 8);
			$result[] = array(
				'adif' => $ent['adif'],
				'name' => $ent['name'],
				'cont' => $ent['cont'],
				'lat' => $ent['lat'],
				'lng' => $ent['lng'],
				'worked' => $worked,
				'confirmed' => $confirmed,
				'sample' => $sample,
			);
		}

		return $result;
	}
}
