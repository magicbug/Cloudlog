<?php

class Worked_all_britain_model extends CI_Model
{
    function get_worked_squares () {
        $CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		if (!$logbooks_locations_array) {
            return null;
        }
        $this->db->select("COL_SIG_INFO");
		$this->db->where_in("station_id", $logbooks_locations_array);
		$this->db->where("COL_SIG", 'WAB');

        // return result as an array
        $query = $this->db->get($this->config->item('table_name'));
        $worked_squares[] = null;
        // run through the query results
        foreach ($query->result() as $row) {
            $worked_squares[] = "Small Square ".$row->COL_SIG_INFO." Boundry Box";
        }

        // return the rows as an array
        return $worked_squares;
    }

    function get_confirmed_squares () {
        $CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		if (!$logbooks_locations_array) {
            return null;
        }
        $this->db->select("COL_SIG_INFO");
		$this->db->where_in("station_id", $logbooks_locations_array);
		$this->db->where("COL_SIG", 'WAB');

        // check if col_qsl_rcvd or col_eqsl_qsl_rcvd or COL_LOTW_QSL_RCVD is 'Y'
        $this->db->where("(col_qsl_rcvd='Y' or col_eqsl_qsl_rcvd='Y' or COL_LOTW_QSL_RCVD='Y')");

        // return result as an array
        $query = $this->db->get($this->config->item('table_name'));
        $worked_squares[] = null;
        // run through the query results
        foreach ($query->result() as $row) {
            $worked_squares[] = "Small Square ".$row->COL_SIG_INFO." Boundry Box";
        }

        // return the rows as an array
        return $worked_squares;
    }
}