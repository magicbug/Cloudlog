<?php

class Sota_model extends CI_Model {

	private $table;

	private function tableName() {
		if (!$this->table) {
			$this->table = $this->config->item('table_name');
		}
		return $this->table;
	}

	// Legacy helper used by older view; retained for compatibility
	public function get_all() {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		if (!$logbooks_locations_array) {
			return null;
		}

		$this->load->model('bands');
		$bandslots = $this->bands->get_worked_bands('sota');

		if(!$bandslots) return null;

		$this->db->from($this->tableName());
		$this->db->where_in('station_id', $logbooks_locations_array);
		$this->db->where_in('col_band', $bandslots);
		$this->db->where('COL_SOTA_REF !=', '');
		$this->db->order_by('COL_SOTA_REF', 'ASC');

		return $this->db->get();
	}

	public function fetch_qsos($filters) {
		if (!$this->apply_base_filters($filters)) {
			return [];
		}

		$this->db->order_by('COL_TIME_ON', 'DESC');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_first_last($filters) {
		if (!$this->apply_base_filters($filters)) {
			return ['first' => null, 'last' => null];
		}

		$this->db->order_by('COL_TIME_ON', 'ASC');
		$this->db->limit(1);
		$first = $this->db->get()->row();

		if (!$this->apply_base_filters($filters)) {
			return ['first' => $first, 'last' => null];
		}

		$this->db->order_by('COL_TIME_ON', 'DESC');
		$this->db->limit(1);
		$last = $this->db->get()->row();

		return ['first' => $first, 'last' => $last];
	}

	public function get_uniques($filters, $by = null) {
		if (!$this->apply_base_filters($filters)) {
			return $by ? [] : 0;
		}

		if ($by === 'band') {
			$this->db->select('COL_BAND as k, COUNT(DISTINCT COL_SOTA_REF) as v');
			$this->db->group_by('COL_BAND');
		} elseif ($by === 'mode') {
			$this->db->select('(CASE WHEN COL_SUBMODE IS NOT NULL AND COL_SUBMODE<>"" THEN COL_SUBMODE ELSE COL_MODE END) as k, COUNT(DISTINCT COL_SOTA_REF) as v', false);
			$this->db->group_by('k');
		} else {
			$this->db->select('COUNT(DISTINCT COL_SOTA_REF) as v');
		}

		$query = $this->db->get();
		if (!$by) {
			$row = $query->row();
			return $row ? (int)$row->v : 0;
		}

		$out = [];
		foreach ($query->result() as $r) {
			$key = $r->k;
			// Normalize mode grouping: LSB, USB -> SSB
			if ($by === 'mode' && in_array(strtoupper($key), ['LSB', 'USB'])) {
				$key = 'SSB';
			}
			if (!isset($out[$key])) {
				$out[$key] = 0;
			}
			$out[$key] += (int)$r->v;
		}
		return $out;
	}

	public function get_confirmations($filters) {
		$filtersConfirmed = $filters;
		$filtersConfirmed['confirmed'] = true;
		return $this->get_uniques($filtersConfirmed);
	}

	public function get_unique_refs($filters = []) {
		if (!$this->apply_base_filters($filters)) {
			return [];
		}

		$this->db->select('DISTINCT COL_SOTA_REF as ref', false);
		$query = $this->db->get();
		$out = [];
		foreach ($query->result() as $row) {
			if (!empty($row->ref)) {
				$out[] = $row->ref;
			}
		}
		return $out;
	}

	public function get_summits_meta($refs) {
		$refs = array_values(array_unique(array_filter(array_map([$this, 'normalize_ref'], $refs))));
		if (empty($refs)) {
			return [];
		}

		return $this->load_summits_subset($refs);
	}

	private function apply_base_filters($filters) {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		if (!$logbooks_locations_array) {
			return false;
		}

		$this->db->from($this->tableName());
		$this->db->where_in('station_id', $logbooks_locations_array);
		$this->db->where('COL_SOTA_REF !=', '');

		$this->apply_filters($filters);

		return true;
	}

	private function apply_filters($filters) {
		if (!empty($filters['from'])) {
			$this->db->where('COL_TIME_ON >=', $filters['from'] . ' 00:00:00');
		}
		if (!empty($filters['to'])) {
			$this->db->where('COL_TIME_ON <=', $filters['to'] . ' 23:59:59');
		}

		if (!empty($filters['band']) && $filters['band'] !== 'All') {
			if ($filters['band'] === 'SAT') {
				$this->db->where('COL_PROP_MODE', 'SAT');
			} else {
				$this->db->where('(COL_PROP_MODE IS NULL OR COL_PROP_MODE <> "SAT")', null, false);
				$this->db->where('COL_BAND', $filters['band']);
			}
		}

		if (!empty($filters['mode']) && $filters['mode'] !== 'All') {
			$this->db->group_start();
			$this->db->where('COL_MODE', $filters['mode']);
			$this->db->or_where('COL_SUBMODE', $filters['mode']);
			$this->db->group_end();
		}

		if (!empty($filters['confirmed'])) {
			$this->db->group_start();
			$this->db->where('col_qsl_rcvd', 'Y');
			$this->db->or_where('col_lotw_qsl_rcvd', 'Y');
			$this->db->group_end();
		}
	}

	private function load_summits_subset($wantedRefs) {
		$path = FCPATH . 'assets/json/sota_summits.csv';
		if (!file_exists($path)) {
			return [];
		}

		$wantedSet = array_fill_keys(array_map([$this, 'normalize_ref'], $wantedRefs), true);
		$found = [];
		$fh = fopen($path, 'r');
		if ($fh === false) {
			return [];
		}

		$header = null;
		$idx = null;
		while (($cols = fgetcsv($fh)) !== false) {
			if ($header === null) {
				if (!isset($cols[0])) {
					continue;
				}
				if (stripos($cols[0], 'sota summits') !== false) {
					continue;
				}
				if (stripos($cols[0], 'summitcode') === 0) {
					$header = $cols;
					$idx = $this->map_header_indices($header);
				}
				continue;
			}

			$refRaw = isset($cols[$idx['ref']]) ? $cols[$idx['ref']] : '';
			$ref = $this->normalize_ref($refRaw);
			if ($ref === '' || !isset($wantedSet[$ref])) {
				continue;
			}

			$found[$ref] = [
				'name' => $this->safe_col($cols, $idx['name']),
				'association' => $this->safe_col($cols, $idx['association']),
				'region' => $this->safe_col($cols, $idx['region']),
				'lat' => $this->safe_float($cols, $idx['lat']),
				'lon' => $this->safe_float($cols, $idx['lon']),
				'alt_m' => $this->safe_float($cols, $idx['alt_m']),
				'points' => $this->safe_int($cols, $idx['points']),
			];

			if (count($found) === count($wantedSet)) {
				break;
			}
		}

		fclose($fh);
		return $found;
	}

	private function normalize_ref($ref) {
		return strtoupper(trim((string)$ref));
	}

	private function map_header_indices($header) {
		$find = function($candidates) use ($header) {
			foreach ($header as $i => $h) {
				$hn = strtolower(trim($h));
				foreach ($candidates as $cand) {
					if ($hn === $cand) return $i;
				}
			}
			foreach ($header as $i => $h) {
				$hn = strtolower(trim($h));
				foreach ($candidates as $cand) {
					if (strpos($hn, $cand) !== false) return $i;
				}
			}
			return null;
		};

		$refIdx = $find(['summitcode', 'summit code']);
		if ($refIdx === null) $refIdx = 0;

		return [
			'ref' => $refIdx,
			'name' => $find(['summitname', 'summit name']),
			'association' => $find(['associationname', 'association name']),
			'region' => $find(['regionname', 'region name']),
			'lat' => $find(['latitude', 'lat']),
			'lon' => $find(['longitude', 'lon', 'lng']),
			'alt_m' => $find(['altm', 'alt m', 'altitude', 'metres', 'meters']),
			'points' => $find(['points']),
		];
	}

	private function safe_col($cols, $idx) {
		if ($idx === null || !isset($cols[$idx])) {
			return null;
		}
		return $cols[$idx];
	}

	private function safe_float($cols, $idx) {
		if ($idx === null || !isset($cols[$idx]) || $cols[$idx] === '') {
			return null;
		}
		return floatval($cols[$idx]);
	}

	private function safe_int($cols, $idx) {
		if ($idx === null || !isset($cols[$idx]) || $cols[$idx] === '') {
			return null;
		}
		return intval($cols[$idx]);
	}
}

?>
