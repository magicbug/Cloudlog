<?php

class DOK extends CI_Model {

	public $bandslots = array("160m"=>0,
                           "80m"=>0,
                           "60m"=>0,
                           "40m"=>0,
                           "30m"=>0,
                           "20m"=>0,
                           "17m"=>0,
                           "15m"=>0,
                           "12m"=>0,
                           "10m"=>0,
                           "6m" =>0,
                           "4m" =>0,
                           "2m" =>0,
                           "70cm"=>0,
                           "23cm"=>0,
                           "13cm"=>0,
                           "9cm"=>0,
                           "6cm"=>0,
                           "3cm"=>0,
                           "1.25cm"=>0);

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function get_worked_bands() {
		// get all worked slots from database
		$data = $this->db->query(
			"SELECT distinct LOWER(`COL_BAND`) as `COL_BAND` FROM `".$this->config->item('table_name')."` WHERE COL_DARC_DOK IS NOT NULL AND COL_DARC_DOK != ''  AND COL_DXCC = 230 "
		);
		$worked_slots = array();
		foreach($data->result() as $row){
			array_push($worked_slots, $row->COL_BAND);
		}


		// bring worked-slots in order of defined $bandslots
		$results = array();
		foreach(array_keys($this->bandslots) as $slot) {
			if(in_array($slot, $worked_slots)) {
				array_push($results, $slot);
			} 
		}
		return $results;
	}

	function show_stats(){

        $data = $this->db->query(
            "select upper(COL_DARC_DOK) as COL_DARC_DOK, COL_MODE, lcase(COL_BAND) as COL_BAND, count(COL_DARC_DOK) as cnt
            from ".$this->config->item('table_name')." WHERE COL_DARC_DOK IS NOT NULL AND COL_DARC_DOK != '' AND COL_DXCC = 230
            group by COL_DARC_DOK, COL_MODE, COL_BAND"
            );

        $results = array();
        $last_dok = "";
        foreach($data->result() as $row){
            if ($last_dok != $row->COL_DARC_DOK){
                // new row
                $results[$row->COL_DARC_DOK] = $this->bandslots;
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
				FROM `dxccexceptions`
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
