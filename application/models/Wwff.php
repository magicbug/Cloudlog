<?php

class Wwff extends CI_Model {

	function get_all() {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		if (!$logbooks_locations_array) {
            return null;
        }

		$this->load->model('bands');

		$bandslots = $this->bands->get_worked_bands('wwff');

		if(!$bandslots) return null;

		$this->db->where_in("station_id", $logbooks_locations_array);
		$this->db->where_in("col_band", $bandslots);
		$this->db->order_by("COL_WWFF_REF", "ASC");
		$this->db->where('COL_WWFF_REF !=', '');

		return $this->db->get($this->config->item('table_name'));
	}

	function get_all_filtered($postdata = array()) {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		if (!$logbooks_locations_array) {
            return null;
        }

		$this->load->model('bands');
		$bandslots = $this->bands->get_worked_bands('wwff');

		if(!$bandslots) return null;

		$this->db->where_in("station_id", $logbooks_locations_array);
		$this->db->where('COL_WWFF_REF !=', '');

		// Apply band filter
		if (isset($postdata['band']) && $postdata['band'] != 'All') {
			if ($postdata['band'] == 'SAT') {
				$this->db->where('col_prop_mode', 'SAT');
			} else {
				$this->db->where('col_band', $postdata['band']);
				$this->db->where('col_prop_mode !=', 'SAT');
			}
		} else {
			$this->db->where_in("col_band", $bandslots);
		}

		// Apply mode filter
		if (isset($postdata['mode']) && $postdata['mode'] != 'All') {
			$this->db->where("(col_mode = '" . $this->db->escape_str($postdata['mode']) . "' or col_submode = '" . $this->db->escape_str($postdata['mode']) . "')", NULL, FALSE);
		}

		// Apply QSL filter
		if (isset($postdata['qsl']) || isset($postdata['lotw']) || isset($postdata['eqsl'])) {
			$qsl_conditions = array();
			if (isset($postdata['qsl']) && $postdata['qsl']) {
				$qsl_conditions[] = "col_qsl_rcvd = 'Y'";
			}
			if (isset($postdata['lotw']) && $postdata['lotw']) {
				$qsl_conditions[] = "col_lotw_qsl_rcvd = 'Y'";
			}
			if (isset($postdata['eqsl']) && $postdata['eqsl']) {
				$qsl_conditions[] = "col_eqsl_qsl_rcvd = 'Y'";
			}
			if (!empty($qsl_conditions)) {
				$this->db->where("(" . implode(" or ", $qsl_conditions) . ")", NULL, FALSE);
			}
		}

		$this->db->order_by("COL_WWFF_REF", "ASC");
		$this->db->order_by("COL_TIME_ON", "DESC");

		return $this->db->get($this->config->item('table_name'));
	}

	function get_wwff_summary($postdata = array()) {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		if (!$logbooks_locations_array) {
            return null;
        }

		$this->load->model('bands');
		$bandslots = $this->bands->get_worked_bands('wwff');

		if(!$bandslots) return null;

		// Count unique parks worked
		$this->db->select('count(distinct COL_WWFF_REF) as count');
		$this->db->where_in("station_id", $logbooks_locations_array);
		$this->db->where('COL_WWFF_REF !=', '');

		if (isset($postdata['band']) && $postdata['band'] != 'All') {
			if ($postdata['band'] == 'SAT') {
				$this->db->where('col_prop_mode', 'SAT');
			} else {
				$this->db->where('col_band', $postdata['band']);
				$this->db->where('col_prop_mode !=', 'SAT');
			}
		} else {
			$this->db->where_in("col_band", $bandslots);
		}

		if (isset($postdata['mode']) && $postdata['mode'] != 'All') {
			$this->db->where("(col_mode = '" . $this->db->escape_str($postdata['mode']) . "' or col_submode = '" . $this->db->escape_str($postdata['mode']) . "')", NULL, FALSE);
		}

		$result = $this->db->get($this->config->item('table_name'));
		$total_parks = ($result->num_rows() > 0) ? $result->row()->count : 0;

		// Count confirmed parks (with QSL)
		$this->db->select('count(distinct COL_WWFF_REF) as count');
		$this->db->where_in("station_id", $logbooks_locations_array);
		$this->db->where('COL_WWFF_REF !=', '');

		if (isset($postdata['band']) && $postdata['band'] != 'All') {
			if ($postdata['band'] == 'SAT') {
				$this->db->where('col_prop_mode', 'SAT');
			} else {
				$this->db->where('col_band', $postdata['band']);
				$this->db->where('col_prop_mode !=', 'SAT');
			}
		} else {
			$this->db->where_in("col_band", $bandslots);
		}

		if (isset($postdata['mode']) && $postdata['mode'] != 'All') {
			$this->db->where("(col_mode = '" . $this->db->escape_str($postdata['mode']) . "' or col_submode = '" . $this->db->escape_str($postdata['mode']) . "')", NULL, FALSE);
		}

		$qsl_conditions = array();
		if (isset($postdata['qsl']) && $postdata['qsl']) {
			$qsl_conditions[] = "col_qsl_rcvd = 'Y'";
		}
		if (isset($postdata['lotw']) && $postdata['lotw']) {
			$qsl_conditions[] = "col_lotw_qsl_rcvd = 'Y'";
		}
		if (isset($postdata['eqsl']) && $postdata['eqsl']) {
			$qsl_conditions[] = "col_eqsl_qsl_rcvd = 'Y'";
		}
		if (!empty($qsl_conditions)) {
			$this->db->where("(" . implode(" or ", $qsl_conditions) . ")", NULL, FALSE);
		}

		$result = $this->db->get($this->config->item('table_name'));
		$confirmed_parks = ($result->num_rows() > 0) ? $result->row()->count : 0;

		return array(
			'total_parks' => $total_parks,
			'confirmed_parks' => $confirmed_parks
		);
	}
}

?>
