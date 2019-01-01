<?php

class DXCC extends CI_Model {

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function show_stats(){

        $data = $this->db->query(
            "select COL_COUNTRY, COL_MODE, lcase(COL_BAND) as COL_BAND, count(COL_COUNTRY) as cnt
            from TABLE_HRD_CONTACTS_V01 
            group by COL_COUNTRY, COL_MODE, COL_BAND"
            );

        $results = array();
        $last_country = "";
        foreach($data->result() as $row){
            if ($last_country != $row->COL_COUNTRY){
                // new row
                $results[$row->COL_COUNTRY] = array("160m"=>0, 
                                                "80m"=>0, 
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
                                                "70cm"=>0);
                $last_country = $row->COL_COUNTRY;
            }

            // update stats
            $results[$row->COL_COUNTRY][$row->COL_BAND] += $row->cnt;
        }

        // print_r($results);
        // return;

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
					FROM dxcc
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
