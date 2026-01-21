<?php

class Sig extends CI_Model {

	function get_all($type, $filters = array()) {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		if (!$logbooks_locations_array) {
            return null;
        }

		$this->load->model('bands');

		$bandslots = $this->bands->get_worked_bands('sig');

		$this->db->where_in("station_id", $logbooks_locations_array);
		$this->db->where_in("col_band", $bandslots);
		$this->db->order_by("COL_TIME_ON", "DESC");
		$this->db->where('COL_SIG =', $type);

		// Apply band/mode filters if provided
		if (!empty($filters['band']) && strtolower($filters['band']) !== 'all') {
			$this->db->where("LOWER(COL_BAND) =", strtolower($filters['band']));
		}

		if (!empty($filters['mode']) && strtolower($filters['mode']) !== 'all') {
			$this->db->where("LOWER(COL_MODE) =", strtolower($filters['mode']));
		}

		// Apply confirmation filter
		if (!empty($filters['confirmed_only']) && ($filters['confirmed_only'] === true || $filters['confirmed_only'] === 'true' || $filters['confirmed_only'] === 1 || $filters['confirmed_only'] === '1')) {
			$this->db->where("(COL_QSL_RCVD = 'Y' OR COL_EQSL_QSL_RCVD = 'Y' OR COL_LOTW_QSL_RCVD = 'Y')");
		}

		return $this->db->get($this->config->item('table_name'));
	}

	function get_all_sig_types($filters = array()) {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		if (!$logbooks_locations_array) {
            return null;
        }

		$location_list = "'".implode("','",$logbooks_locations_array)."'";

		$this->load->model('bands');

		$bandslots = $this->bands->get_worked_bands('sig');

		$bandslots_list = "'".implode("','",$bandslots)."'";

		// Build dynamic WHERE clause for filters
		$where_clause = " where col_sig <> '' and station_id in (" . $location_list . ") and col_band in (" . $bandslots_list . ")";

		if (!empty($filters['band']) && strtolower($filters['band']) !== 'all') {
			$where_clause .= " and LOWER(col_band) = '" . strtolower($filters['band']) . "'";
		}

		if (!empty($filters['mode']) && strtolower($filters['mode']) !== 'all') {
			$where_clause .= " and LOWER(col_mode) = '" . strtolower($filters['mode']) . "'";
		}

		if (!empty($filters['confirmed_only']) && ($filters['confirmed_only'] === true || $filters['confirmed_only'] === 'true' || $filters['confirmed_only'] === 1 || $filters['confirmed_only'] === '1')) {
			$where_clause .= " and (COL_QSL_RCVD = 'Y' OR COL_EQSL_QSL_RCVD = 'Y' OR COL_LOTW_QSL_RCVD = 'Y')";
		}

        $sql = "select col_sig, count(*) qsos, count(distinct col_sig_info) refs from " . $this->config->item('table_name') . $where_clause . " group by col_sig order by col_sig ASC";

        $query = $this->db->query($sql);

        return $query->result();
    }

	/**
	 * Get confirmed SIG references for a specific SIG type with optional filters
	 */
	function get_confirmed_sig_refs($type, $filters = array()) {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		if (!$logbooks_locations_array) {
			return 0;
		}

		$location_list = "'".implode("','",$logbooks_locations_array)."'";

		$this->load->model('bands');
		$bandslots = $this->bands->get_worked_bands('sig');
		$bandslots_list = "'".implode("','",$bandslots)."'";

		$where_clause = " where col_sig = '" . $this->db->escape_str($type) . "' and station_id in (" . $location_list . ") and col_band in (" . $bandslots_list . ")";
		$where_clause .= " and (COL_QSL_RCVD = 'Y' OR COL_EQSL_QSL_RCVD = 'Y' OR COL_LOTW_QSL_RCVD = 'Y')";

		if (!empty($filters['band']) && strtolower($filters['band']) !== 'all') {
			$where_clause .= " and LOWER(col_band) = '" . strtolower($filters['band']) . "'";
		}

		if (!empty($filters['mode']) && strtolower($filters['mode']) !== 'all') {
			$where_clause .= " and LOWER(col_mode) = '" . strtolower($filters['mode']) . "'";
		}

		$sql = "select count(distinct col_sig_info) as confirmed_refs from " . $this->config->item('table_name') . $where_clause;
		$query = $this->db->query($sql);
		$result = $query->row();

		return $result ? $result->confirmed_refs : 0;
	}

	/**
	 * Get worked SIG references for a specific SIG type with optional filters
	 */
	function get_worked_sig_refs($type, $filters = array()) {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		if (!$logbooks_locations_array) {
			return 0;
		}

		$location_list = "'".implode("','",$logbooks_locations_array)."'";

		$this->load->model('bands');
		$bandslots = $this->bands->get_worked_bands('sig');
		$bandslots_list = "'".implode("','",$bandslots)."'";

		$where_clause = " where col_sig = '" . $this->db->escape_str($type) . "' and station_id in (" . $location_list . ") and col_band in (" . $bandslots_list . ")";

		if (!empty($filters['band']) && strtolower($filters['band']) !== 'all') {
			$where_clause .= " and LOWER(col_band) = '" . strtolower($filters['band']) . "'";
		}

		if (!empty($filters['mode']) && strtolower($filters['mode']) !== 'all') {
			$where_clause .= " and LOWER(col_mode) = '" . strtolower($filters['mode']) . "'";
		}

		$sql = "select count(distinct col_sig_info) as worked_refs from " . $this->config->item('table_name') . $where_clause;
		$query = $this->db->query($sql);
		$result = $query->row();

		return $result ? $result->worked_refs : 0;
	}

	/**
	 * Get distinct worked modes for SIG QSOs
	 */
	function get_worked_modes() {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		if (!$logbooks_locations_array) {
            return array();
        }

		$location_list = "'".implode("','",$logbooks_locations_array)."'";

		// Get distinct modes from SIG QSOs
		$sql = "SELECT DISTINCT UPPER(COL_MODE) as COL_MODE FROM " . $this->config->item('table_name') . 
		       " WHERE col_sig <> '' AND station_id in (" . $location_list . ") " .
		       " ORDER BY COL_MODE ASC";
		
		$query = $this->db->query($sql);
		
		$modes = array();
		foreach ($query->result() as $row) {
			$modes[] = $row->COL_MODE;
		}

		return $modes;
	}


}
