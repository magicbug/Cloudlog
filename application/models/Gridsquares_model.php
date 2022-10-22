<?php

class Gridsquares_model extends CI_Model {

    function get_worked_sat_squares($StationLocationsArray = null) {
        if($StationLocationsArray == null) {
            $CI =& get_instance();
            $CI->load->model('logbooks_model');
            $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));
        } else {
            $logbooks_locations_array = $StationLocationsArray;
        }

        $this->db->select('distinct substring(COL_GRIDSQUARE,1,6) as SAT_SQUARE, COL_SAT_NAME', FALSE);
        $this->db->where_in('station_id', $logbooks_locations_array);
        $this->db->where('COL_GRIDSQUARE !=', '');
        $this->db->where('COL_SAT_NAME !=', '');

        return $this->db->get($this->config->item('table_name'));
	}

	function get_confirmed_sat_squares($StationLocationsArray = null) {
		if($StationLocationsArray == null) {
            $CI =& get_instance();
            $CI->load->model('logbooks_model');
            $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));
        } else {
            $logbooks_locations_array = $StationLocationsArray;
        }

        if (!$logbooks_locations_array) {
            return null;
        }

		$location_list = "'".implode("','",$logbooks_locations_array)."'";

		$sql = 'SELECT distinct substring(COL_GRIDSQUARE,1,6) as SAT_SQUARE, COL_SAT_NAME FROM '
			. $this->config->item('table_name')
			. ' WHERE station_id in (' . $location_list . ') AND COL_GRIDSQUARE != "" AND COL_SAT_NAME != "" AND (COL_LOTW_QSL_RCVD = "Y" OR COL_QSL_RCVD = "Y")';

		return $this->db->query($sql);
	}


	function get_confirmed_sat_vucc_squares($StationLocationsArray = null) {
		if($StationLocationsArray == null) {
            $CI =& get_instance();
            $CI->load->model('logbooks_model');
            $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));
        } else {
            $logbooks_locations_array = $StationLocationsArray;
        }

        if (!$logbooks_locations_array) {
            return null;
        }

		$location_list = "'".implode("','",$logbooks_locations_array)."'";

		$sql = 'SELECT COL_VUCC_GRIDS, COL_SAT_NAME FROM '
			. $this->config->item('table_name')
			. ' WHERE station_id in (' . $location_list . ') AND COL_VUCC_GRIDS != "" AND COL_SAT_NAME != "" AND (COL_LOTW_QSL_RCVD = "Y" OR COL_QSL_RCVD = "Y") AND (COL_LOTW_QSL_RCVD = "Y" OR COL_QSL_RCVD = "Y")';

		return $this->db->query($sql);
	}

    function get_worked_sat_vucc_squares($StationLocationsArray = null) {
        if($StationLocationsArray == null) {
            $CI =& get_instance();
            $CI->load->model('logbooks_model');
            $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));
        } else {
            $logbooks_locations_array = $StationLocationsArray;
        }

        if (!$logbooks_locations_array) {
            return null;
        }

	    $this->db->select('COL_PRIMARY_KEY, COL_VUCC_GRIDS, COL_SAT_NAME', FALSE);
        $this->db->where_in('station_id', $logbooks_locations_array);
    	$this->db->where('COL_VUCC_GRIDS !=', "");
    	$this->db->where('COL_SAT_NAME !=', "");
		return $this->db->get($this->config->item('table_name'));
	}

    function get_band($band, $StationLocationsArray = null) {
        if($StationLocationsArray == null) {
            $CI =& get_instance();
            $CI->load->model('logbooks_model');
            $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));
        } else {
            $logbooks_locations_array = $StationLocationsArray;
        }

        if (!$logbooks_locations_array) {
            return null;
        }

        $this->db->select('distinct substring(COL_GRIDSQUARE,1,6) as GRID_SQUARES, COL_BAND', FALSE);
        $this->db->where_in('station_id', $logbooks_locations_array);
        $this->db->where('COL_GRIDSQUARE !=', '');

        if ($band != 'All') {
            $this->db->where('COL_BAND', $band);
            $this->db->where('COL_PROP_MODE !=', "SAT");
            $this->db->where('COL_PROP_MODE !=', "INTERNET");
            $this->db->where('COL_PROP_MODE !=', "ECH");
            $this->db->where('COL_PROP_MODE !=', "RPT");
            $this->db->where('COL_SAT_NAME =', "");
        }

        return $this->db->get($this->config->item('table_name'));
    }

	function get_band_confirmed($band, $StationLocationsArray = null) {
		if($StationLocationsArray == null) {
            $CI =& get_instance();
            $CI->load->model('logbooks_model');
            $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));
        } else {
            $logbooks_locations_array = $StationLocationsArray;
        }
        
        if (!$logbooks_locations_array) {
            return null;
        }

		$location_list = "'".implode("','",$logbooks_locations_array)."'";

		$sql = 'SELECT distinct substring(COL_GRIDSQUARE,1,6) as GRID_SQUARES, COL_BAND FROM '
			.$this->config->item('table_name')
			.' WHERE station_id in ('
			.$location_list.') AND COL_GRIDSQUARE != ""';
		if ($band != 'All') {
			$sql .= ' AND COL_BAND = "' . $band
				.'"
            AND COL_PROP_MODE != "SAT"
            AND COL_PROP_MODE != "INTERNET"
            AND COL_PROP_MODE != "ECH"
            AND COL_PROP_MODE != "RPT"
            AND COL_SAT_NAME = ""';
		}

		$sql .= ' AND (COL_LOTW_QSL_RCVD = "Y" OR COL_QSL_RCVD = "Y")';

		return $this->db->query($sql);
	}

    function get_band_worked_vucc_squares($band, $StationLocationsArray = null) {
        if($StationLocationsArray == null) {
            $CI =& get_instance();
            $CI->load->model('logbooks_model');
            $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));
        } else {
            $logbooks_locations_array = $StationLocationsArray;
        }

        if (!$logbooks_locations_array) {
            return null;
        }

        $this->db->select('distinct COL_VUCC_GRIDS, COL_BAND', FALSE);
        $this->db->where_in('station_id', $logbooks_locations_array);
        $this->db->where('COL_VUCC_GRIDS !=', '');

        if ($band != 'All') {
            $this->db->where('COL_BAND', $band);
            $this->db->where('COL_PROP_MODE !=', "SAT");
            $this->db->where('COL_PROP_MODE !=', "INTERNET");
            $this->db->where('COL_PROP_MODE !=', "ECH");
            $this->db->where('COL_PROP_MODE !=', "RPT");
            $this->db->where('COL_SAT_NAME =', "");
        }

        return $this->db->get($this->config->item('table_name'));
    }

	function get_band_confirmed_vucc_squares($band, $StationLocationsArray = null) {
		if($StationLocationsArray == null) {
            $CI =& get_instance();
            $CI->load->model('logbooks_model');
            $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));
        } else {
            $logbooks_locations_array = $StationLocationsArray;
        }
        
        if (!$logbooks_locations_array) {
            return null;
        }

		$location_list = "'".implode("','",$logbooks_locations_array)."'";

		$sql = 'SELECT distinct COL_VUCC_GRIDS, COL_BAND FROM '
			.$this->config->item('table_name')
			.' WHERE station_id in ('
			.$location_list.') AND COL_VUCC_GRIDS != ""';
		if ($band != 'All') {
			$sql .= ' AND COL_BAND = "' . $band
				.'"
            AND COL_PROP_MODE != "SAT"
            AND COL_PROP_MODE != "INTERNET"
            AND COL_PROP_MODE != "ECH"
            AND COL_PROP_MODE != "RPT"
            AND COL_SAT_NAME = ""';
		}

		$sql .= ' AND (COL_LOTW_QSL_RCVD = "Y" OR COL_QSL_RCVD = "Y")';

		return $this->db->query($sql);
	}

    function search_band($band, $gridsquare, $StationLocationsArray = null) {
        if($StationLocationsArray == null) {
            $CI =& get_instance();
            $CI->load->model('logbooks_model');
            $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));
        } else {
            $logbooks_locations_array = $StationLocationsArray;
        }
        $location_list = "'".implode("','",$logbooks_locations_array)."'";

        $sql = 'SELECT COL_CALL, COL_TIME_ON, COL_BAND, COL_MODE, COL_GRIDSQUARE, COL_VUCC_GRIDS FROM '
            .$this->config->item('table_name')
            .' WHERE station_id IN (' . $location_list . ') '
			. ' AND (COL_GRIDSQUARE LIKE "%'.$gridsquare.'%" or COL_VUCC_GRIDS LIKE "%'.$gridsquare.'%")';

        if ($band != 'All') {
            $sql .= ' AND COL_BAND = "' . $band
                .'"
            AND COL_PROP_MODE != "SAT"
            AND COL_PROP_MODE != "INTERNET"
            AND COL_PROP_MODE != "ECH"
            AND COL_PROP_MODE != "RPT"
            AND COL_SAT_NAME = ""';
        }

        $result = $this->db->query($sql);

        //print_r($result);
        return json_encode($result->result());
    }

    function search_sat($gridsquare, $StationLocationsArray = null) {
        if($StationLocationsArray == null) {
            $CI =& get_instance();
            $CI->load->model('logbooks_model');
            $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));
        } else {
            $logbooks_locations_array = $StationLocationsArray;
        }
        $location_list = "'".implode("','",$logbooks_locations_array)."'";

        $sql = 'SELECT COL_CALL, COL_TIME_ON, COL_BAND, COL_MODE, COL_SAT_NAME, COL_GRIDSQUARE, COL_VUCC_GRIDS FROM ' .
				$this->config->item('table_name').
				' WHERE station_id IN ('.$location_list. ')' .
				' AND (COL_GRIDSQUARE LIKE "%'.$gridsquare.'%" or COL_VUCC_GRIDS LIKE "%'.$gridsquare.'%")'.
				' AND COL_PROP_MODE = "SAT"';

        $result = $this->db->query($sql);

        //print_r($result);
        return json_encode($result->result());
    }
}
