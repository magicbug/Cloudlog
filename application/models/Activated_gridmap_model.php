<?php

class Activated_gridmap_model extends CI_Model {

    function get_band_confirmed($band, $mode, $qsl, $lotw, $eqsl, $qrz, $sat, $sat_orbit = 'All') {
        $CI =& get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if (!$logbooks_locations_array) {
            return null;
        }

        $location_list = implode(',', array_map('intval', $logbooks_locations_array));
        $band = $this->db->escape_str($band);
        $mode = $this->db->escape_str($mode);
        $sat = $this->db->escape_str($sat);
        $sat_orbit = $this->db->escape_str($sat_orbit);

        $sql = 'SELECT DISTINCT station_gridsquare AS GRID_SQUARES, COL_BAND FROM '
           . 'station_profile JOIN '.$this->config->item('table_name').' on '.$this->config->item('table_name').'.station_id = station_profile.station_id '
           . 'WHERE station_profile.station_gridsquare != "" '
	   . 'AND station_profile.station_id in ('.$location_list.')';

        if ($band != 'All') {
            if ($band == 'SAT') {
                $sql .= " and col_prop_mode ='" . $band . "'";
                $sql .= $this->getSatelliteOrbitFilterSql($sat_orbit);
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

    function get_band($band, $mode, $qsl, $lotw, $eqsl, $qrz, $sat, $sat_orbit = 'All') {
        $CI =& get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if (!$logbooks_locations_array) {
            return null;
        }

        $location_list = implode(',', array_map('intval', $logbooks_locations_array));
        $band = $this->db->escape_str($band);
        $mode = $this->db->escape_str($mode);
        $sat = $this->db->escape_str($sat);
        $sat_orbit = $this->db->escape_str($sat_orbit);

        $sql = 'SELECT DISTINCT station_gridsquare AS GRID_SQUARES, COL_BAND FROM '
           . 'station_profile JOIN '.$this->config->item('table_name').' on '.$this->config->item('table_name').'.station_id = station_profile.station_id '
           . 'WHERE station_profile.station_gridsquare != "" '
	   . 'AND station_profile.station_id in ('.$location_list.')';

        if ($band != 'All') {
            if ($band == 'SAT') {
                $sql .= " and col_prop_mode ='" . $band . "'";
                $sql .= $this->getSatelliteOrbitFilterSql($sat_orbit);
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

    function get_band_worked_vucc_squares($band, $mode, $qsl, $lotw, $eqsl, $qrz, $sat, $sat_orbit = 'All') {
        $CI =& get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if (!$logbooks_locations_array) {
            return null;
        }

        $location_list = implode(',', array_map('intval', $logbooks_locations_array));
        $band = $this->db->escape_str($band);
        $mode = $this->db->escape_str($mode);
        $sat = $this->db->escape_str($sat);
        $sat_orbit = $this->db->escape_str($sat_orbit);

		$sql = 'SELECT distinct COL_VUCC_GRIDS, COL_BAND FROM '
			.$this->config->item('table_name')
			.' WHERE station_id in ('
			.$location_list.') AND COL_VUCC_GRIDS != ""';

        if ($band != 'All') {
            if ($band == 'SAT') {
                $sql .= " and col_prop_mode ='" . $band . "'";
                $sql .= $this->getSatelliteOrbitFilterSql($sat_orbit);
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

    function get_band_confirmed_vucc_squares($band, $mode, $qsl, $lotw, $eqsl, $qrz, $sat, $sat_orbit = 'All') {
        $CI =& get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));
        
        if (!$logbooks_locations_array) {
            return null;
        }

        $location_list = implode(',', array_map('intval', $logbooks_locations_array));
        $band = $this->db->escape_str($band);
        $mode = $this->db->escape_str($mode);
        $sat = $this->db->escape_str($sat);
        $sat_orbit = $this->db->escape_str($sat_orbit);

		$sql = 'SELECT distinct COL_VUCC_GRIDS, COL_BAND FROM '
			.$this->config->item('table_name')
			.' WHERE station_id in ('
			.$location_list.') AND COL_VUCC_GRIDS != ""';
        
        if ($band != 'All') {
            if ($band == 'SAT') {
                $sql .= " and col_prop_mode ='" . $band . "'";
                $sql .= $this->getSatelliteOrbitFilterSql($sat_orbit);
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

    function get_satellite_breakdown($band, $mode, $qsl, $lotw, $eqsl, $qrz, $sat, $sat_orbit = 'All', $confirmed = false) {
        $CI =& get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if (!$logbooks_locations_array) {
            return array(
                'total' => 0,
                'LEO' => 0,
                'MEO' => 0,
                'GEO' => 0,
            );
        }

        $location_list = implode(',', array_map('intval', $logbooks_locations_array));
        $band = $this->db->escape_str($band);
        $mode = $this->db->escape_str($mode);
        $sat = $this->db->escape_str($sat);
        $sat_orbit = $this->db->escape_str($sat_orbit);

        $sql = 'SELECT DISTINCT station_gridsquare AS GRID_SQUARES, COL_SAT_NAME FROM ' . $this->config->item('table_name')
            . ' JOIN station_profile ON ' . $this->config->item('table_name') . '.station_id = station_profile.station_id'
            . ' WHERE station_profile.station_gridsquare != ""'
            . ' AND station_profile.station_id in (' . $location_list . ')'
            . ' AND col_prop_mode =\'SAT\'';

        if ($band != 'All') {
            if ($band == 'SAT') {
                $sql .= $this->getSatelliteOrbitFilterSql($sat_orbit);
                if ($sat != 'All') {
                    $sql .= " and col_sat_name ='" . $sat . "'";
                }
            } else {
                $sql .= " and col_prop_mode !='SAT'";
                $sql .= " and col_band ='" . $band . "'";
            }
        }

        if ($mode != 'All') {
            $sql .= " and (col_mode ='" . $mode . "' or col_submode ='" . $mode . "')";
        }

        if ($confirmed) {
            $sql .= $this->addQslToQuery($qsl, $lotw, $eqsl, $qrz);
        }

        $query = $this->db->query($sql);
        $categories = array(
            'LEO' => array(),
            'MEO' => array(),
            'GEO' => array(),
        );
        $unique_grids = array();

        if ($query && $query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $grid = strtoupper(substr((string) $row->GRID_SQUARES, 0, 4));
                $satellite = strtoupper(trim((string) $row->COL_SAT_NAME));
                $category = $this->get_satellite_category($satellite);

                if ($grid !== '' && !in_array($grid, $unique_grids, true)) {
                    $unique_grids[] = $grid;
                }

                if ($grid !== '' && !in_array($grid, $categories[$category], true)) {
                    $categories[$category][] = $grid;
                }
            }
        }

        return array(
            'total' => count($unique_grids),
            'LEO' => count($categories['LEO']),
            'MEO' => count($categories['MEO']),
            'GEO' => count($categories['GEO']),
        );
    }

    function get_satellite_category($satellite_name) {
        if ($satellite_name === 'IO-117') {
            return 'MEO';
        }

        if ($satellite_name === 'QO-100') {
            return 'GEO';
        }

        return 'LEO';
    }

    function getSatelliteOrbitFilterSql($sat_orbit) {
        if ($sat_orbit === 'MEO') {
            return " and col_sat_name = 'IO-117'";
        }

        if ($sat_orbit === 'GEO') {
            return " and col_sat_name = 'QO-100'";
        }

        if ($sat_orbit === 'LEO') {
            return " and col_sat_name not in ('IO-117', 'QO-100')";
        }

        return '';
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

        $location_list = implode(',', array_map('intval', $logbooks_locations_array));

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
