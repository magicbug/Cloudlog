<?php

class Activated_gridmap_model extends CI_Model {

    function get_band_confirmed($band, $mode, $qsl, $lotw, $eqsl, $qrz, $sat) {
        $CI =& get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if (!$logbooks_locations_array) {
            return null;
        }

		$location_list = "'".implode("','",$logbooks_locations_array)."'";

        $sql = 'SELECT DISTINCT station_gridsquare AS GRID_SQUARES, COL_BAND FROM '
           . 'station_profile JOIN '.$this->config->item('table_name').' on '.$this->config->item('table_name').'.station_id = station_profile.station_id '
           . 'WHERE station_profile.station_gridsquare != "" '
	   . 'AND station_profile.station_id in ('.$location_list.')';

        if ($band != 'All') {
            if ($band == 'SAT') {
                $sql .= " and col_prop_mode ='" . $band . "'";
                if ($sat != 'All') {
                    $sql .= " and col_sat_name ='" . $sat . "'";
                }
            }
            else {
                $sql .= " and col_prop_mode !='SAT'";
                $sql .= " and col_band ='" . $band . "'";
            }
        }

        if ($mode != 'All') {
			$sql .= " and (col_mode ='" . $mode . "' or col_submode ='" . $mode . "')";
        }

        $sql .= $this->addQslToQuery($qsl, $lotw, $eqsl, $qrz);
		return $this->db->query($sql);
	}

    function get_band($band, $mode, $qsl, $lotw, $eqsl, $qrz, $sat) {
        $CI =& get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if (!$logbooks_locations_array) {
            return null;
        }

        $location_list = "'".implode("','",$logbooks_locations_array)."'";

        $sql = 'SELECT DISTINCT station_gridsquare AS GRID_SQUARES, COL_BAND FROM '
           . 'station_profile JOIN '.$this->config->item('table_name').' on '.$this->config->item('table_name').'.station_id = station_profile.station_id '
           . 'WHERE station_profile.station_gridsquare != "" '
	   . 'AND station_profile.station_id in ('.$location_list.')';

        if ($band != 'All') {
            if ($band == 'SAT') {
                $sql .= " and col_prop_mode ='" . $band . "'";
                if ($sat != 'All') {
                    $sql .= " and col_sat_name ='" . $sat . "'";
                }
            }
            else {
                $sql .= " and col_prop_mode !='SAT'";
                $sql .= " and col_band ='" . $band . "'";
            }
        }

        if ($mode != 'All') {
			$sql .= " and (col_mode ='" . $mode . "' or col_submode ='" . $mode . "')";
        }

        return $this->db->query($sql);
    }

    function get_band_worked_vucc_squares($band, $mode, $qsl, $lotw, $eqsl, $qrz, $sat) {
        $CI =& get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if (!$logbooks_locations_array) {
            return null;
        }

        $location_list = "'".implode("','",$logbooks_locations_array)."'";

		$sql = 'SELECT distinct COL_VUCC_GRIDS, COL_BAND FROM '
			.$this->config->item('table_name')
			.' WHERE station_id in ('
			.$location_list.') AND COL_VUCC_GRIDS != ""';

        if ($band != 'All') {
            if ($band == 'SAT') {
                $sql .= " and col_prop_mode ='" . $band . "'";
                if ($sat != 'All') {
                    $sql .= " and col_sat_name ='" . $sat . "'";
                }
            }
            else {
                $sql .= " and col_prop_mode !='SAT'";
                $sql .= " and col_band ='" . $band . "'";
            }
        }

        if ($mode != 'All') {
			$sql .= " and (col_mode ='" . $mode . "' or col_submode ='" . $mode . "')";
        }

        return null;
        return $this->db->query($sql);
    }

    function get_band_confirmed_vucc_squares($band, $mode, $qsl, $lotw, $eqsl, $qrz, $sat) {
        $CI =& get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));
        
        if (!$logbooks_locations_array) {
            return null;
        }

		$location_list = "'".implode("','",$logbooks_locations_array)."'";

		$sql = 'SELECT distinct COL_VUCC_GRIDS, COL_BAND FROM '
			.$this->config->item('table_name')
			.' WHERE station_id in ('
			.$location_list.') AND COL_VUCC_GRIDS != ""';
        
        if ($band != 'All') {
            if ($band == 'SAT') {
                $sql .= " and col_prop_mode ='" . $band . "'";
                if ($sat != 'All') {
                    $sql .= " and col_sat_name ='" . $sat . "'";
                }
            }
            else {
                $sql .= " and col_prop_mode !='SAT'";
                $sql .= " and col_band ='" . $band . "'";
            }
        }

        if ($mode != 'All') {
			$sql .= " and (col_mode ='" . $mode . "' or col_submode ='" . $mode . "')";
        }

		$sql .= $this->addQslToQuery($qsl, $lotw, $eqsl, $qrz);

        return null;
		return $this->db->query($sql);
	}

// Adds confirmation to query
    function addQslToQuery($qsl, $lotw, $eqsl, $qrz) {
	    $sql = '';
	    if ($lotw == "true") {
		    $sql .= " or col_lotw_qsl_sent = 'Y'";
	    }

	    if ($qsl == "true") {
		    $sql .= " or col_qsl_sent = 'Y'";
	    }

	    if ($eqsl == "true") {
		    $sql .= " or col_eqsl_qsl_sent = 'Y'";
	    }

	    if ($qrz == "true") {
		    $sql .= " or col_qrzcom_qso_upload_status = 'Y'";
	    }
	    if ($sql != '') {
		    $sql='and (1=0 '.$sql.')';
	    }
	    return $sql;
    }


    /*
	 * Get's the worked modes from the log
	 */
	function get_worked_modes() {
        $CI =& get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));
        
        if (!$logbooks_locations_array) {
            return null;
        }

		$location_list = "'".implode("','",$logbooks_locations_array)."'";

		// get all worked modes from database
		$data = $this->db->query(
			"SELECT distinct LOWER(`COL_MODE`) as `COL_MODE` FROM `" . $this->config->item('table_name') . "` WHERE station_id in (" . $location_list . ") order by COL_MODE ASC"
		);
		$results = array();
		foreach ($data->result() as $row) {
			array_push($results, $row->COL_MODE);
		}

		$data = $this->db->query(
			"SELECT distinct LOWER(`COL_SUBMODE`) as `COL_SUBMODE` FROM `" . $this->config->item('table_name') . "` WHERE station_id in (" . $location_list . ") and coalesce(COL_SUBMODE, '') <> '' order by COL_SUBMODE ASC"
		);
		foreach ($data->result() as $row) {
			if (!in_array($row, $results)) {
				array_push($results, $row->COL_SUBMODE);
			}
		}

        asort($results);

		return $results;
	}
}
