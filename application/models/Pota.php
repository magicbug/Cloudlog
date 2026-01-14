<?php

class Pota extends CI_Model {

	// Existing method used by current simple table
	function get_all() {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		if (!$logbooks_locations_array) {
			return null;
		}

		$this->load->model('bands');

		$bandslots = $this->bands->get_worked_bands('pota');

		if(!$bandslots) return null;

		$this->db->where_in("station_id", $logbooks_locations_array);
		$this->db->where_in("col_band", $bandslots);
		$this->db->order_by("COL_POTA_REF", "ASC");
		$this->db->where('COL_POTA_REF !=', '');

		return $this->db->get($this->config->item('table_name'));
	}

	// New: generic filtered fetch for HTMX table
	function fetch_qsos($filters) {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		if (!$logbooks_locations_array) {
			return [];
		}

		$this->db->from($this->config->item('table_name'));
		$this->db->where_in('station_id', $logbooks_locations_array);
		$this->db->where('COL_POTA_REF !=', '');

		$this->apply_filters($filters);

		$this->db->order_by('COL_TIME_ON', 'DESC');
		$query = $this->db->get();
		return $query->result();
	}

	// New: first and last QSO timestamps/refs within filters
	function get_first_last($filters) {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));
		if (!$logbooks_locations_array) {
			return ['first'=>null,'last'=>null];
		}

		// first
		$this->db->from($this->config->item('table_name'));
		$this->db->where_in('station_id', $logbooks_locations_array);
		$this->db->where('COL_POTA_REF !=', '');
		$this->apply_filters($filters);
		$this->db->order_by('COL_TIME_ON', 'ASC');
		$this->db->limit(1);
		$first = $this->db->get()->row();

		// last
		$this->db->from($this->config->item('table_name'));
		$this->db->where_in('station_id', $logbooks_locations_array);
		$this->db->where('COL_POTA_REF !=', '');
		$this->apply_filters($filters);
		$this->db->order_by('COL_TIME_ON', 'DESC');
		$this->db->limit(1);
		$last = $this->db->get()->row();

		return ['first'=>$first, 'last'=>$last];
	}

	// New: unique parks and optionally by band/mode
	function get_uniques($filters, $by = null) {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));
		if (!$logbooks_locations_array) {
			return [];
		}

		$this->db->from($this->config->item('table_name'));
		$this->db->where_in('station_id', $logbooks_locations_array);
		$this->db->where('COL_POTA_REF !=', '');
		$this->apply_filters($filters);

		if ($by === 'band') {
			$this->db->select('COL_BAND as k, COUNT(DISTINCT COL_POTA_REF) as v');
			$this->db->group_by('COL_BAND');
		} elseif ($by === 'mode') {
			$this->db->select('(CASE WHEN COL_SUBMODE IS NOT NULL AND COL_SUBMODE<>"" THEN COL_SUBMODE ELSE COL_MODE END) as k, COUNT(DISTINCT COL_POTA_REF) as v', false);
			$this->db->group_by('k');
		} else {
			$this->db->select('COUNT(DISTINCT COL_POTA_REF) as v');
		}

		$query = $this->db->get();
		if (!$by) {
			$row = $query->row();
			return $row ? (int)$row->v : 0;
		}
		$out = [];
		foreach ($query->result() as $r) {
			$out[$r->k] = (int)$r->v;
		}
		return $out;
	}

	// New: confirmation counts
	function get_confirmations($filters) {
		$filtersConfirmed = $filters;
		$filtersConfirmed['confirmed'] = true;
		return $this->get_uniques($filtersConfirmed);
	}

	// New: read assets/json/pota_parks.csv and map refs to metadata
	function get_parks_meta($refs) {
		$refs = array_values(array_unique(array_filter($refs)));
		if (empty($refs)) return [];

		// Read only the needed refs from CSV to keep memory low
		$out = $this->load_parks_subset($refs);

		// If still missing coords, we skip map markers quietly
		return $out;
	}

	private function load_parks_subset($wantedRefs) {
		$path = FCPATH . 'assets/json/pota_parks.csv';
		if (!file_exists($path)) return [];

		$wantedSet = array_fill_keys($wantedRefs, true);
		$found = [];

		$fh = fopen($path, 'r');
		if ($fh === false) return [];

		$header = fgetcsv($fh);
		if ($header === false) { fclose($fh); return []; }
		$idx = $this->map_header_indices($header);

		while (($cols = fgetcsv($fh)) !== false) {
			if (!isset($cols[$idx['ref']])) continue;
			$ref = trim($cols[$idx['ref']]);
			if ($ref === '' || !isset($wantedSet[$ref])) continue;

			$found[$ref] = [
				'name' => $idx['name'] !== null && isset($cols[$idx['name']]) ? $cols[$idx['name']] : null,
				'entity' => $idx['entity'] !== null && isset($cols[$idx['entity']]) ? $cols[$idx['entity']] : null,
				'country' => $idx['country'] !== null && isset($cols[$idx['country']]) ? $cols[$idx['country']] : null,
				'lat' => $idx['lat'] !== null && isset($cols[$idx['lat']]) ? floatval($cols[$idx['lat']]) : null,
				'lon' => $idx['lon'] !== null && isset($cols[$idx['lon']]) ? floatval($cols[$idx['lon']]) : null,
			];

			// Early exit if we got all requested refs
			if (count($found) === count($wantedSet)) {
				break;
			}
		}
		fclose($fh);
		return $found;
	}

	private function map_header_indices($header) {
		$find = function($candidates) use ($header) {
			foreach ($header as $i => $h) {
				$hn = strtolower(trim($h));
				foreach ($candidates as $cand) {
					if ($hn === $cand) return $i;
					if (strpos($hn, $cand) !== false) return $i;
				}
			}
			return null;
		};
		// Try to detect columns by common names
		$refIdx = $find(['ref','reference','park id','park','entity id']);
		if ($refIdx === null) $refIdx = 0; // fallback to first column
		return [
			'ref' => $refIdx,
			'name' => $find(['name','park name','designation']),
			'entity' => $find(['entity','state','province','region']),
			'country' => $find(['country','country code']),
			'lat' => $find(['lat','latitude']),
			'lon' => $find(['lon','longitude','lng']),
		];
	}

	private function apply_filters($filters) {
		// Date range
		if (!empty($filters['from'])) {
			$this->db->where('COL_TIME_ON >=', $filters['from'] . ' 00:00:00');
		}
		if (!empty($filters['to'])) {
			$this->db->where('COL_TIME_ON <=', $filters['to'] . ' 23:59:59');
		}

		// Band / SAT
		if (!empty($filters['band']) && $filters['band'] !== 'All') {
			if ($filters['band'] === 'SAT') {
				$this->db->where('COL_PROP_MODE', 'SAT');
			} else {
				$this->db->where('(COL_PROP_MODE IS NULL OR COL_PROP_MODE <> "SAT")', null, false);
				$this->db->where('COL_BAND', $filters['band']);
			}
		}

		// Mode
		if (!empty($filters['mode']) && $filters['mode'] !== 'All') {
			$this->db->group_start();
			$this->db->where('COL_MODE', $filters['mode']);
			$this->db->or_where('COL_SUBMODE', $filters['mode']);
			$this->db->group_end();
		}

		// Confirmed only
		if (!empty($filters['confirmed'])) {
			$this->db->group_start();
			$this->db->where('col_qsl_rcvd', 'Y');
			$this->db->or_where('col_lotw_qsl_rcvd', 'Y');
			$this->db->group_end();
		}
	}
}

?>
