<?php

class Activated_grids_model extends CI_Model {

    function get_activated_sat_squares() {
        $CI =& get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if (!$logbooks_locations_array) {
            return null;
        }

        $sql = 'SELECT DISTINCT station_gridsquare AS SAT_SQUARE, COL_SAT_NAME FROM '
           . 'station_profile JOIN '.$this->config->item('table_name').' on '.$this->config->item('table_name').'.station_id = station_profile.station_id '
           . 'WHERE station_profile.station_gridsquare != "" AND '.$this->config->item('table_name').'.COL_SAT_NAME != ""';

        return $this->db->query($sql);
	}

	function get_activated_confirmed_sat_squares() {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if (!$logbooks_locations_array) {
            return null;
        }

		$location_list = "'".implode("','",$logbooks_locations_array)."'";

      $sql = 'SELECT DISTINCT station_gridsquare AS SAT_SQUARE, COL_SAT_NAME FROM '
         . 'station_profile JOIN '.$this->config->item('table_name').' on '.$this->config->item('table_name').'.station_id = station_profile.station_id '
         . 'WHERE station_profile.station_gridsquare != "" AND '.$this->config->item('table_name').'.COL_SAT_NAME != "" '
         . 'AND (COL_LOTW_QSL_SENT = "Y" OR COL_QSL_SENT = "Y");';

		return $this->db->query($sql);
	}

    function get_band($band) {
        $CI =& get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if (!$logbooks_locations_array) {
            return null;
        }

        $location_list = "'".implode("','",$logbooks_locations_array)."'";

        $sql = 'SELECT DISTINCT station_gridsquare AS GRID_SQUARES, COL_BAND FROM '
           . 'station_profile JOIN '.$this->config->item('table_name').' on '.$this->config->item('table_name').'.station_id = station_profile.station_id '
           . 'WHERE station_profile.station_gridsquare != "" ';

        if ($band != 'All') {
            $sql .= 'AND COL_BAND = "'.$band.'" '
               . 'AND COL_PROP_MODE != "SAT" '
               . 'AND COL_PROP_MODE != "INTERNET" '
               . 'AND COL_PROP_MODE != "ECH" '
               . 'AND COL_PROP_MODE != "RPT" '
               . 'AND COL_SAT_NAME = "" ';
        }

        return $this->db->query($sql);
    }

	function get_band_confirmed($band) {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if (!$logbooks_locations_array) {
            return null;
        }

		$location_list = "'".implode("','",$logbooks_locations_array)."'";

        $sql = 'SELECT DISTINCT station_gridsquare AS GRID_SQUARES, COL_BAND FROM '
           . 'station_profile JOIN '.$this->config->item('table_name').' on '.$this->config->item('table_name').'.station_id = station_profile.station_id '
           . 'WHERE station_profile.station_gridsquare != "" ';

        if ($band != 'All') {
            $sql .= 'AND COL_BAND = "'.$band.'" '
               . 'AND COL_PROP_MODE != "SAT" '
               . 'AND COL_PROP_MODE != "INTERNET" '
               . 'AND COL_PROP_MODE != "ECH" '
               . 'AND COL_PROP_MODE != "RPT" '
               . 'AND COL_SAT_NAME = "" ';
        }

        $sql .= ' AND (COL_LOTW_QSL_SENT = "Y" OR COL_QSL_SENT = "Y")';

		return $this->db->query($sql);
	}

    function search_band($band, $gridsquare) {
        $CI =& get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        $location_list = "'".implode("','",$logbooks_locations_array)."'";

        $sql = 'SELECT COL_CALL, COL_TIME_ON, COL_BAND, COL_MODE, COL_GRIDSQUARE, COL_VUCC_GRIDS FROM '
            .$this->config->item('table_name')
            .' WHERE station_id IN (' . $location_list . ') '
			. ' AND (COL_MY_GRIDSQUARE LIKE "%'.$gridsquare.'%")';

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

    function search_sat($gridsquare) {
        $CI =& get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        $location_list = "'".implode("','",$logbooks_locations_array)."'";

        $sql = 'SELECT COL_CALL, COL_TIME_ON, COL_BAND, COL_MODE, COL_SAT_NAME, COL_GRIDSQUARE, COL_VUCC_GRIDS FROM ' .
				$this->config->item('table_name').
				' WHERE station_id IN ('.$location_list. ')' .
				' AND (COL_MY_GRIDSQUARE LIKE "%'.$gridsquare.'%")'.
				' AND COL_PROP_MODE = "SAT"';

        $result = $this->db->query($sql);

        //print_r($result);
        return json_encode($result->result());
    }
}
