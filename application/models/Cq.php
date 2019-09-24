<?php

class CQ extends CI_Model{

    function __construct(){
        // Call the Model constructor
        parent::__construct();
    }


    function get_zones(){
        $CI =& get_instance();
        $CI->load->model('Stations');
        $station_id = $CI->Stations->find_active();

        $data = $this->db->query(
            "select COL_CQZ, count(COL_CQZ)
            from ".$this->config->item('table_name')."
            where COL_CQZ is not null and station_id = ".$station_id." 
            group by COL_CQZ order by COL_CQZ"
        );

        return $data->result();
    }
}
