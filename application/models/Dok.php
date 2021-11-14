<?php

class DOK extends CI_Model {

	function show_stats() {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		if (!$logbooks_locations_array) {
			return null;
		}

		$this->load->model('bands');

		$location_list = "'".implode("','",$logbooks_locations_array)."'";

        $data = $this->db->query(
            "select upper(COL_DARC_DOK) as COL_DARC_DOK, COL_MODE, lcase(COL_BAND) as COL_BAND, count(COL_DARC_DOK) as cnt
            from ".$this->config->item('table_name')." WHERE station_id in (" . $location_list . ") AND COL_DARC_DOK IS NOT NULL AND COL_DARC_DOK != '' AND COL_DXCC = 230
            group by COL_DARC_DOK, COL_MODE, COL_BAND"
            );

        $results = array();
        $last_dok = "";
        foreach($data->result() as $row){
            if ($last_dok != $row->COL_DARC_DOK){
                // new row
                $results[$row->COL_DARC_DOK] = $this->bands->bandslots;
                $last_dok = $row->COL_DARC_DOK;
            }

            // update stats
            if (!isset($results[$row->COL_DARC_DOK]))
                $results[$row->COL_DARC_DOK] = [];

            if (!isset($results[$row->COL_DARC_DOK][$row->COL_BAND]))
                $results[$row->COL_DARC_DOK][$row->COL_BAND] = 0;

            $results[$row->COL_DARC_DOK][$row->COL_BAND] += $row->cnt;
        }

        return $results;
	}

	/**
	*	Function: mostactive
	*	Information: Returns the most active band
	**/
	function info($callsign)
	{
		$exceptions = $this->db->query('
				SELECT *
				FROM `dxcc_exceptions`
				WHERE `prefix` = \''.$callsign.'\'
				LIMIT 1
			');

		if ($exceptions->num_rows() > 0)
		{
			return $exceptions;
		} else {

			$query = $this->db->query('
					SELECT *
					FROM dxcc_entities
					WHERE prefix = SUBSTRING( \''.$callsign.'\', 1, LENGTH( prefix ) )
					ORDER BY LENGTH( prefix ) DESC
					LIMIT 1
				');

			return $query;
		}
	}

    function search(){
        print_r($this->input->get());
        return;
    }

	function empty_table($table) {
		$this->db->empty_table($table);
	}

	function list() {
		$this->db->order_by('name', 'ASC');
		return $this->db->get('dxcc_entities');
	}
}
?>
