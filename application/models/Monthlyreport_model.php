<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Monthlyreport_model extends CI_Model {

	/**
	 * Generate comprehensive monthly report data
	 * 
	 * @param array $logbooks_locations_array Station location IDs
	 * @param string $year Year (YYYY)
	 * @param string $month Month (1-12)
	 * @return array Report data
	 */
	public function generate_report($logbooks_locations_array, $year, $month) {
		// Calculate date range for the month
		$start_date = sprintf("%04d-%02d-01 00:00:00", $year, $month);
		$days_in_month = date('t', strtotime($start_date));
		$end_date = sprintf("%04d-%02d-%02d 23:59:59", $year, $month, $days_in_month);
		
		// Calculate previous month end date for "new" calculations
		$prev_month_end = date('Y-m-d 23:59:59', strtotime($start_date . ' -1 day'));
		
		// PERFORMANCE OPTIMIZATION: Pre-load all historical data for "new" checks
		// This reduces hundreds of queries to just a few
		$this->build_historical_caches($logbooks_locations_array, $start_date);
		
		$report = array(
			'total_qsos' => $this->get_total_qsos($logbooks_locations_array, $start_date, $end_date),
			'unique_callsigns' => $this->get_unique_callsigns($logbooks_locations_array, $start_date, $end_date),
			'callsign_list' => $this->get_callsign_list($logbooks_locations_array, $start_date, $end_date),
			'new_dxcc' => $this->get_new_dxcc($logbooks_locations_array, $start_date, $end_date, $prev_month_end),
			'new_dxcc_by_band' => $this->get_new_dxcc_by_band($logbooks_locations_array, $start_date, $end_date, $prev_month_end),
			'new_dxcc_satellite' => $this->get_new_dxcc_by_prop($logbooks_locations_array, $start_date, $end_date, $prev_month_end, 'SAT'),
			'new_dxcc_eme' => $this->get_new_dxcc_by_prop($logbooks_locations_array, $start_date, $end_date, $prev_month_end, 'EME'),
			'new_grids' => $this->get_new_gridsquares($logbooks_locations_array, $start_date, $end_date, $prev_month_end),
			'new_grids_satellite' => $this->get_new_gridsquares_by_prop($logbooks_locations_array, $start_date, $end_date, $prev_month_end, 'SAT'),
			'new_grids_eme' => $this->get_new_gridsquares_by_prop($logbooks_locations_array, $start_date, $end_date, $prev_month_end, 'EME'),
			'new_grids_hf' => $this->get_new_gridsquares_hf($logbooks_locations_array, $start_date, $end_date, $prev_month_end),
			'countries_worked' => $this->get_countries_worked($logbooks_locations_array, $start_date, $end_date),
			'modes' => $this->get_modes_breakdown($logbooks_locations_array, $start_date, $end_date),
			'bands' => $this->get_bands_breakdown($logbooks_locations_array, $start_date, $end_date),
			'continents' => $this->get_continents_breakdown($logbooks_locations_array, $start_date, $end_date),
			'satellite_qsos' => $this->get_satellite_qsos($logbooks_locations_array, $start_date, $end_date),
			'satellite_breakdown' => $this->get_satellite_breakdown($logbooks_locations_array, $start_date, $end_date),
			'eme_qsos' => $this->get_eme_qsos($logbooks_locations_array, $start_date, $end_date),
			'top_band' => '',
			'top_mode' => ''
		);
		
		// Calculate top band and mode
		if (!empty($report['bands'])) {
			arsort($report['bands']);
			$report['top_band'] = array_key_first($report['bands']);
		}
		
		if (!empty($report['modes'])) {
			arsort($report['modes']);
			$report['top_mode'] = array_key_first($report['modes']);
		}
		
		return $report;
	}

	// Cache variables for historical data lookups
	private $historical_grids = array();
	private $historical_grids_by_prop = array();
	private $historical_grids_hf = array();

	/**
	 * Build historical caches for fast "new" checks
	 * This prevents hundreds of individual queries
	 */
	private function build_historical_caches($locations, $start_date) {
		// Get ALL historical gridsquares (before this month)
		$this->db->select('COL_GRIDSQUARE, COL_VUCC_GRIDS, COL_PROP_MODE');
		$this->db->from($this->config->item('table_name'));
		$this->db->where_in('station_id', $locations);
		$this->db->where('COL_TIME_ON <', $start_date);
		$this->db->where("(COL_GRIDSQUARE IS NOT NULL AND COL_GRIDSQUARE != '') OR (COL_VUCC_GRIDS IS NOT NULL AND COL_VUCC_GRIDS != '')");
		
		$query = $this->db->get();
		
		foreach ($query->result() as $row) {
			$prop_mode = !empty($row->COL_PROP_MODE) ? $row->COL_PROP_MODE : '';
			
			// Process main gridsquare
			if (!empty($row->COL_GRIDSQUARE) && strlen($row->COL_GRIDSQUARE) >= 4) {
				$grid_4char = strtoupper(substr($row->COL_GRIDSQUARE, 0, 4));
				$this->historical_grids[$grid_4char] = true;
				
				// Track by prop mode
				if ($prop_mode === 'SAT' || $prop_mode === 'EME') {
					$this->historical_grids_by_prop[$prop_mode][$grid_4char] = true;
				} else {
					$this->historical_grids_hf[$grid_4char] = true;
				}
			}
			
			// Process VUCC grids
			if (!empty($row->COL_VUCC_GRIDS)) {
				$vucc_grids = explode(',', $row->COL_VUCC_GRIDS);
				foreach ($vucc_grids as $grid) {
					$grid = trim($grid);
					if (strlen($grid) >= 4) {
						$grid_4char = strtoupper(substr($grid, 0, 4));
						$this->historical_grids[$grid_4char] = true;
						
						// Track by prop mode
						if ($prop_mode === 'SAT' || $prop_mode === 'EME') {
							$this->historical_grids_by_prop[$prop_mode][$grid_4char] = true;
						} else {
							$this->historical_grids_hf[$grid_4char] = true;
						}
					}
				}
			}
		}
	}

	/**
	 * Sort bands in amateur radio order (160m, 80m, 40m, 20m, etc.)
	 */
	private function sort_bands_array($bands_array) {
		// Define standard band order
		$band_order = array(
			'2190m' => 1, '630m' => 2, '560m' => 3, '160m' => 4, '80m' => 5, '60m' => 6,
			'40m' => 7, '30m' => 8, '20m' => 9, '17m' => 10, '15m' => 11, '12m' => 12,
			'10m' => 13, '8m' => 14, '6m' => 15, '5m' => 16, '4m' => 17, '2m' => 18,
			'1.25m' => 19, '70cm' => 20, '33cm' => 21, '23cm' => 22, '13cm' => 23,
			'9cm' => 24, '6cm' => 25, '3cm' => 26, '1.25cm' => 27, '6mm' => 28, '4mm' => 29,
			'2.5mm' => 30, '2mm' => 31, '1mm' => 32
		);
		
		uksort($bands_array, function($a, $b) use ($band_order) {
			$pos_a = isset($band_order[$a]) ? $band_order[$a] : 999;
			$pos_b = isset($band_order[$b]) ? $band_order[$b] : 999;
			
			if ($pos_a == $pos_b) {
				return strcmp($a, $b);
			}
			return $pos_a - $pos_b;
		});
		
		return $bands_array;
	}

	/**
	 * Get total QSO count for the period
	 */
	private function get_total_qsos($locations, $start_date, $end_date) {
		$this->db->select('COUNT(*) as count');
		$this->db->from($this->config->item('table_name'));
		$this->db->where_in('station_id', $locations);
		$this->db->where('COL_TIME_ON >=', $start_date);
		$this->db->where('COL_TIME_ON <=', $end_date);
		
		$query = $this->db->get();
		$row = $query->row();
		return $row ? (int)$row->count : 0;
	}

	/**
	 * Get count of unique callsigns worked
	 */
	private function get_unique_callsigns($locations, $start_date, $end_date) {
		$this->db->select('COUNT(DISTINCT COL_CALL) as count', FALSE);
		$this->db->from($this->config->item('table_name'));
		$this->db->where_in('station_id', $locations);
		$this->db->where('COL_TIME_ON >=', $start_date);
		$this->db->where('COL_TIME_ON <=', $end_date);
		
		$query = $this->db->get();
		$row = $query->row();
		return $row ? (int)$row->count : 0;
	}

	/**
	 * Get list of unique callsigns worked with QSO counts
	 */
	private function get_callsign_list($locations, $start_date, $end_date) {
		$this->db->select('COL_CALL, COUNT(*) as qso_count');
		$this->db->from($this->config->item('table_name'));
		$this->db->where_in('station_id', $locations);
		$this->db->where('COL_TIME_ON >=', $start_date);
		$this->db->where('COL_TIME_ON <=', $end_date);
		$this->db->group_by('COL_CALL');
		$this->db->order_by('qso_count', 'DESC');
		
		$query = $this->db->get();
		
		$callsigns = array();
		foreach ($query->result() as $row) {
			$callsigns[] = array(
				'callsign' => $row->COL_CALL,
				'qso_count' => (int)$row->qso_count
			);
		}
		
		return $callsigns;
	}

	/**
	 * Get new DXCC entities worked this month (first time ever)
	 */
	private function get_new_dxcc($locations, $start_date, $end_date, $prev_month_end) {
		$new_dxcc = array();
		
		// Get all unique DXCC entities worked this month (group by DXCC)
		$this->db->select('COL_DXCC, MAX(COL_COUNTRY) as COL_COUNTRY', FALSE);
		$this->db->from($this->config->item('table_name'));
		$this->db->where_in('station_id', $locations);
		$this->db->where('COL_TIME_ON >=', $start_date);
		$this->db->where('COL_TIME_ON <=', $end_date);
		$this->db->where('COL_DXCC IS NOT NULL');
		$this->db->where('COL_DXCC !=', '');
		$this->db->group_by('COL_DXCC');
		
		$query = $this->db->get();
		
		foreach ($query->result() as $row) {
			// Check if this DXCC was worked before this month
			$this->db->select('COUNT(*) as count');
			$this->db->from($this->config->item('table_name'));
			$this->db->where_in('station_id', $locations);
			$this->db->where('COL_DXCC', $row->COL_DXCC);
			$this->db->where('COL_TIME_ON <', $start_date);
			
			$prev_query = $this->db->get();
			$prev_row = $prev_query->row();
			
			// If not worked before, it's new this month
			if ($prev_row->count == 0) {
				$new_dxcc[] = array(
					'dxcc_id' => $row->COL_DXCC,
					'name' => $row->COL_COUNTRY
				);
			}
		}
		
		return $new_dxcc;
	}

	/**
	 * Get new DXCC entities by band
	 */
	private function get_new_dxcc_by_band($locations, $start_date, $end_date, $prev_month_end) {
		$new_dxcc_by_band = array();
		
		// Get all bands worked this month, plus propagation mode to separate SAT/EME
		$this->db->distinct();
		$this->db->select('COL_BAND, COL_PROP_MODE');
		$this->db->from($this->config->item('table_name'));
		$this->db->where_in('station_id', $locations);
		$this->db->where('COL_TIME_ON >=', $start_date);
		$this->db->where('COL_TIME_ON <=', $end_date);
		$this->db->where('COL_BAND IS NOT NULL');
		$this->db->where('COL_BAND !=', '');
		$this->db->where('COL_DXCC IS NOT NULL');
		$this->db->where('COL_DXCC !=', '');
		$bands_query = $this->db->get();
		
		foreach ($bands_query->result() as $band_row) {
			$band = $band_row->COL_BAND;
			$prop_mode = $band_row->COL_PROP_MODE;
			
			// Group SAT and EME separately, not by frequency band
			if ($prop_mode == 'SAT') {
				$band_key = 'Satellite';
			} elseif ($prop_mode == 'EME') {
				$band_key = 'EME';
			} else {
				$band_key = $band;
			}
			
			if (!isset($new_dxcc_by_band[$band_key])) {
				$new_dxcc_by_band[$band_key] = array();
			}
			
			// Get all unique DXCC entities worked on this band/prop this month (group by DXCC, get earliest callsign)
			$this->db->select('COL_DXCC, MAX(COL_COUNTRY) as COL_COUNTRY, MIN(COL_TIME_ON) as FIRST_QSO', FALSE);
			$this->db->from($this->config->item('table_name'));
			$this->db->where_in('station_id', $locations);
			$this->db->where('COL_TIME_ON >=', $start_date);
			$this->db->where('COL_TIME_ON <=', $end_date);
			$this->db->where('COL_BAND', $band);
			$this->db->where('COL_DXCC IS NOT NULL');
			$this->db->where('COL_DXCC !=', '');
			
			// Filter by propagation mode
			if ($prop_mode) {
				$this->db->where('COL_PROP_MODE', $prop_mode);
			} else {
				// For terrestrial, exclude SAT and EME
				$this->db->where('(COL_PROP_MODE IS NULL OR (COL_PROP_MODE != "SAT" AND COL_PROP_MODE != "EME"))', NULL, FALSE);
			}
			
			$this->db->group_by('COL_DXCC');
			
			$query = $this->db->get();
			
			foreach ($query->result() as $row) {
				// Check if this DXCC was worked before this month on this band/prop
				$this->db->select('COUNT(*) as count');
				$this->db->from($this->config->item('table_name'));
				$this->db->where_in('station_id', $locations);
				$this->db->where('COL_DXCC', $row->COL_DXCC);
				$this->db->where('COL_BAND', $band);
				if ($prop_mode) {
					$this->db->where('COL_PROP_MODE', $prop_mode);
				} else {
					$this->db->where('(COL_PROP_MODE IS NULL OR (COL_PROP_MODE != "SAT" AND COL_PROP_MODE != "EME"))', NULL, FALSE);
				}
				$this->db->where('COL_TIME_ON <', $start_date);
				
				$prev_query = $this->db->get();
				$prev_row = $prev_query->row();
				
				// If not worked before on this band/prop, it's new
				if ($prev_row->count == 0) {
					// Get the callsign and mode from the first QSO with this DXCC on this band/prop
					$this->db->select('COL_CALL, COL_MODE, COL_SUBMODE');
					$this->db->from($this->config->item('table_name'));
					$this->db->where_in('station_id', $locations);
					$this->db->where('COL_DXCC', $row->COL_DXCC);
					$this->db->where('COL_BAND', $band);
					if ($prop_mode) {
						$this->db->where('COL_PROP_MODE', $prop_mode);
					} else {
						$this->db->where('(COL_PROP_MODE IS NULL OR (COL_PROP_MODE != "SAT" AND COL_PROP_MODE != "EME"))', NULL, FALSE);
					}
					$this->db->where('COL_TIME_ON', $row->FIRST_QSO);
					$this->db->limit(1);
					
					$call_query = $this->db->get();
					$call_row = $call_query->row();
					
					$mode = '';
					if ($call_row) {
						$mode = !empty($call_row->COL_SUBMODE) ? $call_row->COL_SUBMODE : $call_row->COL_MODE;
					}
					
					// Avoid duplicates in the same band_key
					$already_added = false;
					foreach ($new_dxcc_by_band[$band_key] as $existing) {
						if ($existing['dxcc_id'] == $row->COL_DXCC) {
							$already_added = true;
							break;
						}
					}
					
					if (!$already_added) {
						$new_dxcc_by_band[$band_key][] = array(
							'dxcc_id' => $row->COL_DXCC,
							'name' => $row->COL_COUNTRY,
							'callsign' => $call_row ? $call_row->COL_CALL : '',
							'mode' => $mode
						);
					}
				}
			}
		}
		
		// Remove bands with no new DXCC
		$new_dxcc_by_band = array_filter($new_dxcc_by_band, function($arr) {
			return !empty($arr);
		});
		
		// Sort bands in proper amateur radio order
		$new_dxcc_by_band = $this->sort_bands_array($new_dxcc_by_band);
		
		return $new_dxcc_by_band;
	}

	/**
	 * Get new DXCC entities by propagation mode (SAT or EME)
	 */
	private function get_new_dxcc_by_prop($locations, $start_date, $end_date, $prev_month_end, $prop_mode) {
		$new_dxcc = array();
		
		// Get all unique DXCC entities worked this month via this prop mode (group by DXCC, get earliest time)
		$this->db->select('COL_DXCC, MAX(COL_COUNTRY) as COL_COUNTRY, MIN(COL_TIME_ON) as FIRST_QSO', FALSE);
		$this->db->from($this->config->item('table_name'));
		$this->db->where_in('station_id', $locations);
		$this->db->where('COL_TIME_ON >=', $start_date);
		$this->db->where('COL_TIME_ON <=', $end_date);
		$this->db->where('COL_PROP_MODE', $prop_mode);
		$this->db->where('COL_DXCC IS NOT NULL');
		$this->db->where('COL_DXCC !=', '');
		$this->db->group_by('COL_DXCC');
		
		$query = $this->db->get();
		
		foreach ($query->result() as $row) {
			// Check if this DXCC was worked before this month via this prop mode
			$this->db->select('COUNT(*) as count');
			$this->db->from($this->config->item('table_name'));
			$this->db->where_in('station_id', $locations);
			$this->db->where('COL_DXCC', $row->COL_DXCC);
			$this->db->where('COL_PROP_MODE', $prop_mode);
			$this->db->where('COL_TIME_ON <', $start_date);
			
			$prev_query = $this->db->get();
			$prev_row = $prev_query->row();
			
			// If not worked before via this prop mode, it's new
			if ($prev_row->count == 0) {
				// Get the callsign and mode from the first QSO
				$this->db->select('COL_CALL, COL_MODE, COL_SUBMODE');
				$this->db->from($this->config->item('table_name'));
				$this->db->where_in('station_id', $locations);
				$this->db->where('COL_DXCC', $row->COL_DXCC);
				$this->db->where('COL_PROP_MODE', $prop_mode);
				$this->db->where('COL_TIME_ON', $row->FIRST_QSO);
				$this->db->limit(1);
				
				$call_query = $this->db->get();
				$call_row = $call_query->row();
				
				$mode = '';
				if ($call_row) {
					$mode = !empty($call_row->COL_SUBMODE) ? $call_row->COL_SUBMODE : $call_row->COL_MODE;
				}
				
				$new_dxcc[] = array(
					'dxcc_id' => $row->COL_DXCC,
					'name' => $row->COL_COUNTRY,
					'callsign' => $call_row ? $call_row->COL_CALL : '',
					'mode' => $mode
				);
			}
		}
		
		return $new_dxcc;
	}

	/**
	 * Get new gridsquares worked this month (first time ever) - All modes
	 */
	private function get_new_gridsquares($locations, $start_date, $end_date, $prev_month_end) {
		$new_grids = array();
		
		// Get all gridsquares worked this month (including VUCC grids)
		$this->db->select('COL_CALL, COL_GRIDSQUARE, COL_VUCC_GRIDS, COL_TIME_ON, COL_MODE, COL_SUBMODE');
		$this->db->from($this->config->item('table_name'));
		$this->db->where_in('station_id', $locations);
		$this->db->where('COL_TIME_ON >=', $start_date);
		$this->db->where('COL_TIME_ON <=', $end_date);
		$this->db->order_by('COL_TIME_ON', 'ASC');
		
		$query = $this->db->get();
		$grid_data = array();
		
		foreach ($query->result() as $row) {
			$mode = !empty($row->COL_SUBMODE) ? $row->COL_SUBMODE : $row->COL_MODE;
			
			// Process main gridsquare (first 4 characters only)
			if (!empty($row->COL_GRIDSQUARE) && strlen($row->COL_GRIDSQUARE) >= 4) {
				$grid_4char = strtoupper(substr($row->COL_GRIDSQUARE, 0, 4));
				if (!isset($grid_data[$grid_4char])) {
					$grid_data[$grid_4char] = array(
						'callsign' => $row->COL_CALL,
						'mode' => $mode
					);
				}
			}
			
			// Process VUCC grids (comma-separated, first 4 characters only)
			if (!empty($row->COL_VUCC_GRIDS)) {
				$vucc_grids = explode(',', $row->COL_VUCC_GRIDS);
				foreach ($vucc_grids as $grid) {
					$grid = trim($grid);
					if (strlen($grid) >= 4) {
						$grid_4char = strtoupper(substr($grid, 0, 4));
						if (!isset($grid_data[$grid_4char])) {
							$grid_data[$grid_4char] = array(
								'callsign' => $row->COL_CALL,
								'mode' => $mode
							);
						}
					}
				}
			}
		}
		
		// Check each grid to see if it's new
		foreach ($grid_data as $grid => $data) {
			if ($this->is_grid_new($locations, $grid, $start_date)) {
				$new_grids[] = array(
					'grid' => $grid,
					'callsign' => $data['callsign'],
					'mode' => $data['mode']
				);
			}
		}
		
		// Sort alphabetically
		usort($new_grids, function($a, $b) {
			return strcmp($a['grid'], $b['grid']);
		});
		
		return $new_grids;
	}

	/**
	 * Get new gridsquares for specific propagation mode
	 */
	private function get_new_gridsquares_by_prop($locations, $start_date, $end_date, $prev_month_end, $prop_mode) {
		$new_grids = array();
		
		// Get all gridsquares worked this month with this prop mode
		$this->db->select('COL_CALL, COL_GRIDSQUARE, COL_VUCC_GRIDS, COL_TIME_ON, COL_SAT_NAME, COL_MODE, COL_SUBMODE');
		$this->db->from($this->config->item('table_name'));
		$this->db->where_in('station_id', $locations);
		$this->db->where('COL_TIME_ON >=', $start_date);
		$this->db->where('COL_TIME_ON <=', $end_date);
		$this->db->where('COL_PROP_MODE', $prop_mode);
		$this->db->order_by('COL_TIME_ON', 'ASC');
		
		$query = $this->db->get();
		$grid_data = array();
		
		foreach ($query->result() as $row) {
			$mode = !empty($row->COL_SUBMODE) ? $row->COL_SUBMODE : $row->COL_MODE;
			
			// Process main gridsquare (first 4 characters only)
			if (!empty($row->COL_GRIDSQUARE) && strlen($row->COL_GRIDSQUARE) >= 4) {
				$grid_4char = strtoupper(substr($row->COL_GRIDSQUARE, 0, 4));
				if (!isset($grid_data[$grid_4char])) {
					$grid_data[$grid_4char] = array(
						'callsign' => $row->COL_CALL,
						'satellite' => !empty($row->COL_SAT_NAME) ? $row->COL_SAT_NAME : '',
						'mode' => $mode
					);
				}
			}
			
			// Process VUCC grids (first 4 characters only)
			if (!empty($row->COL_VUCC_GRIDS)) {
				$vucc_grids = explode(',', $row->COL_VUCC_GRIDS);
				foreach ($vucc_grids as $grid) {
					$grid = trim($grid);
					if (strlen($grid) >= 4) {
						$grid_4char = strtoupper(substr($grid, 0, 4));
						if (!isset($grid_data[$grid_4char])) {
							$grid_data[$grid_4char] = array(
								'callsign' => $row->COL_CALL,
								'satellite' => !empty($row->COL_SAT_NAME) ? $row->COL_SAT_NAME : '',
								'mode' => $mode
							);
						}
					}
				}
			}
		}
		
		// Check each grid to see if it's new for this prop mode
		foreach ($grid_data as $grid => $data) {
			if ($this->is_grid_new_for_prop($locations, $grid, $start_date, $prop_mode)) {
				$new_grids[] = array(
					'grid' => $grid,
					'callsign' => $data['callsign'],
					'satellite' => $data['satellite'],
					'mode' => $data['mode']
				);
			}
		}
		
		// Sort alphabetically
		usort($new_grids, function($a, $b) {
			return strcmp($a['grid'], $b['grid']);
		});
		
		return $new_grids;
	}

	/**
	 * Get new HF gridsquares (non-SAT, non-EME)
	 */
	private function get_new_gridsquares_hf($locations, $start_date, $end_date, $prev_month_end) {
		$new_grids = array();
		
		// Get all gridsquares worked this month via HF
		$this->db->select('COL_CALL, COL_GRIDSQUARE, COL_VUCC_GRIDS, COL_TIME_ON, COL_MODE, COL_SUBMODE');
		$this->db->from($this->config->item('table_name'));
		$this->db->where_in('station_id', $locations);
		$this->db->where('COL_TIME_ON >=', $start_date);
		$this->db->where('COL_TIME_ON <=', $end_date);
		$this->db->where("(COL_PROP_MODE IS NULL OR COL_PROP_MODE = '' OR COL_PROP_MODE NOT IN ('SAT','EME'))");
		$this->db->order_by('COL_TIME_ON', 'ASC');
		
		$query = $this->db->get();
		$grid_data = array();
		
		foreach ($query->result() as $row) {
			$mode = !empty($row->COL_SUBMODE) ? $row->COL_SUBMODE : $row->COL_MODE;
			
			// Process main gridsquare (first 4 characters only)
			if (!empty($row->COL_GRIDSQUARE) && strlen($row->COL_GRIDSQUARE) >= 4) {
				$grid_4char = strtoupper(substr($row->COL_GRIDSQUARE, 0, 4));
				if (!isset($grid_data[$grid_4char])) {
					$grid_data[$grid_4char] = array(
						'callsign' => $row->COL_CALL,
						'mode' => $mode
					);
				}
			}
			
			// Process VUCC grids (first 4 characters only)
			if (!empty($row->COL_VUCC_GRIDS)) {
				$vucc_grids = explode(',', $row->COL_VUCC_GRIDS);
				foreach ($vucc_grids as $grid) {
					$grid = trim($grid);
					if (strlen($grid) >= 4) {
						$grid_4char = strtoupper(substr($grid, 0, 4));
						if (!isset($grid_data[$grid_4char])) {
							$grid_data[$grid_4char] = array(
								'callsign' => $row->COL_CALL,
								'mode' => $mode
							);
						}
					}
				}
			}
		}
		
		// Check each grid to see if it's new for HF
		foreach ($grid_data as $grid => $data) {
			if ($this->is_grid_new_for_hf($locations, $grid, $start_date)) {
				$new_grids[] = array(
					'grid' => $grid,
					'callsign' => $data['callsign'],
					'mode' => $data['mode']
				);
			}
		}
		
		// Sort alphabetically
		usort($new_grids, function($a, $b) {
			return strcmp($a['grid'], $b['grid']);
		});
		
		return $new_grids;
	}

	/**
	 * Check if a grid is new (never worked before)
	 */
	private function is_grid_new($locations, $grid, $start_date) {
		// Use pre-built cache for instant lookup
		return !isset($this->historical_grids[strtoupper($grid)]);
	}

	/**
	 * Check if a grid is new for a specific propagation mode
	 */
	private function is_grid_new_for_prop($locations, $grid, $start_date, $prop_mode) {
		// Use pre-built cache for instant lookup
		return !isset($this->historical_grids_by_prop[$prop_mode][strtoupper($grid)]);
	}

	/**
	 * Check if a grid is new for HF (non-SAT, non-EME)
	 */
	private function is_grid_new_for_hf($locations, $grid, $start_date) {
		// Use pre-built cache for instant lookup
		return !isset($this->historical_grids_hf[strtoupper($grid)]);
	}

	/**
	 * Get all countries worked this month
	 */
	private function get_countries_worked($locations, $start_date, $end_date) {
		$countries = array();
		
		$this->db->select('COL_DXCC, MAX(COL_COUNTRY) as COL_COUNTRY', FALSE);
		$this->db->from($this->config->item('table_name'));
		$this->db->where_in('station_id', $locations);
		$this->db->where('COL_TIME_ON >=', $start_date);
		$this->db->where('COL_TIME_ON <=', $end_date);
		$this->db->where('COL_DXCC IS NOT NULL');
		$this->db->where('COL_DXCC !=', '');
		$this->db->group_by('COL_DXCC');
		
		$query = $this->db->get();
		
		foreach ($query->result() as $row) {
			$countries[] = array(
				'dxcc_id' => $row->COL_DXCC,
				'name' => $row->COL_COUNTRY
			);
		}
		
		return $countries;
	}

	/**
	 * Get modes breakdown with QSO counts
	 */
	private function get_modes_breakdown($locations, $start_date, $end_date) {
		$modes = array();
		
		// Use COALESCE to prefer submode over mode
		$this->db->select('COALESCE(NULLIF(COL_SUBMODE, ""), COL_MODE) as mode_used, COUNT(*) as count', FALSE);
		$this->db->from($this->config->item('table_name'));
		$this->db->where_in('station_id', $locations);
		$this->db->where('COL_TIME_ON >=', $start_date);
		$this->db->where('COL_TIME_ON <=', $end_date);
		$this->db->group_by('mode_used');
		$this->db->order_by('count', 'DESC');
		
		$query = $this->db->get();
		
		foreach ($query->result() as $row) {
			$mode = $row->mode_used ? $row->mode_used : 'Unknown';
			$modes[$mode] = (int)$row->count;
		}
		
		return $modes;
	}

	/**
	 * Get bands breakdown with QSO counts
	 */
	private function get_bands_breakdown($locations, $start_date, $end_date) {
		$bands = array();
		
		$this->db->select('COL_BAND, COUNT(*) as count');
		$this->db->from($this->config->item('table_name'));
		$this->db->where_in('station_id', $locations);
		$this->db->where('COL_TIME_ON >=', $start_date);
		$this->db->where('COL_TIME_ON <=', $end_date);
		$this->db->group_by('COL_BAND');
		
		$query = $this->db->get();
		
		foreach ($query->result() as $row) {
			$band = $row->COL_BAND ? $row->COL_BAND : 'Unknown';
			$bands[$band] = (int)$row->count;
		}
		
		// Sort bands in proper amateur radio order
		$bands = $this->sort_bands_array($bands);
		
		return $bands;
	}

	/**
	 * Get continents breakdown with QSO counts
	 */
	private function get_continents_breakdown($locations, $start_date, $end_date) {
		$continents = array();
		
		$this->db->select('COL_CONT, COUNT(*) as count');
		$this->db->from($this->config->item('table_name'));
		$this->db->where_in('station_id', $locations);
		$this->db->where('COL_TIME_ON >=', $start_date);
		$this->db->where('COL_TIME_ON <=', $end_date);
		$this->db->where('COL_CONT IS NOT NULL');
		$this->db->where('COL_CONT !=', '');
		$this->db->group_by('COL_CONT');
		$this->db->order_by('count', 'DESC');
		
		$query = $this->db->get();
		
		foreach ($query->result() as $row) {
			$continents[$row->COL_CONT] = (int)$row->count;
		}
		
		return $continents;
	}

	/**
	 * Get satellite QSO count (via SAT propagation mode)
	 */
	private function get_satellite_qsos($locations, $start_date, $end_date) {
		$this->db->select('COUNT(*) as count');
		$this->db->from($this->config->item('table_name'));
		$this->db->where_in('station_id', $locations);
		$this->db->where('COL_TIME_ON >=', $start_date);
		$this->db->where('COL_TIME_ON <=', $end_date);
		$this->db->where('COL_PROP_MODE', 'SAT');
		
		$query = $this->db->get();
		$row = $query->row();
		return $row ? (int)$row->count : 0;
	}

	/**
	 * Get breakdown of QSOs per satellite
	 */
	private function get_satellite_breakdown($locations, $start_date, $end_date) {
		$satellites = array();
		
		$this->db->select('COL_SAT_NAME, COUNT(*) as count');
		$this->db->from($this->config->item('table_name'));
		$this->db->where_in('station_id', $locations);
		$this->db->where('COL_TIME_ON >=', $start_date);
		$this->db->where('COL_TIME_ON <=', $end_date);
		$this->db->where('COL_PROP_MODE', 'SAT');
		$this->db->where('COL_SAT_NAME IS NOT NULL');
		$this->db->where('COL_SAT_NAME !=', '');
		$this->db->group_by('COL_SAT_NAME');
		$this->db->order_by('count', 'DESC');
		
		$query = $this->db->get();
		
		foreach ($query->result() as $row) {
			$satellites[$row->COL_SAT_NAME] = (int)$row->count;
		}
		
		return $satellites;
	}

	/**
	 * Get EME (moonbounce) QSO count
	 */
	private function get_eme_qsos($locations, $start_date, $end_date) {
		$this->db->select('COUNT(*) as count');
		$this->db->from($this->config->item('table_name'));
		$this->db->where_in('station_id', $locations);
		$this->db->where('COL_TIME_ON >=', $start_date);
		$this->db->where('COL_TIME_ON <=', $end_date);
		$this->db->where('COL_PROP_MODE', 'EME');
		
		$query = $this->db->get();
		$row = $query->row();
		return $row ? (int)$row->count : 0;
	}

	/**
	 * Extract prefix from callsign
	 */
	private function extract_prefix($callsign) {
		// Simple prefix extraction - take everything before first digit
		if (preg_match('/^([A-Z0-9]+?)\d/', $callsign, $matches)) {
			return $matches[1];
		}
		return $callsign;
	}

	/**
	 * Get list of months with QSO activity
	 * Useful for populating month/year selectors
	 */
	public function get_active_months($locations) {
		$this->db->select('YEAR(COL_TIME_ON) as year, MONTH(COL_TIME_ON) as month, COUNT(*) as qso_count');
		$this->db->from($this->config->item('table_name'));
		$this->db->where_in('station_id', $locations);
		$this->db->group_by('YEAR(COL_TIME_ON), MONTH(COL_TIME_ON)');
		$this->db->order_by('year DESC, month DESC');
		
		$query = $this->db->get();
		
		$months = array();
		foreach ($query->result() as $row) {
			$months[] = array(
				'year' => $row->year,
				'month' => $row->month,
				'qso_count' => $row->qso_count
			);
		}
		
		return $months;
	}
}
