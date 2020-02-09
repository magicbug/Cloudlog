<?php

class was extends CI_Model {

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
        "1.25cm"=>0,
        "SAT"=>0,
    );

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    function get_worked_bands() {
        $CI =& get_instance();
        $CI->load->model('Stations');
        $station_id = $CI->Stations->find_active();

        // get all bands where we have worked states, need to filter on correct dxcc and state (as Cloudlog aren't always setting correct dxcc on import)
        $data = $this->db->query(
            "SELECT distinct LOWER(`COL_BAND`) as `COL_BAND` FROM `".$this->config->item('table_name')."` WHERE COL_DXCC in ('291', '6', '110') 
            and COL_STATE in ('AK','AL','AR','AZ','CA','CO','CT','DE','FL','GA','HI','IA','ID','IL','IN','KS','KY','LA','MA','MD','ME','MI','MN','MO','MS','MT','NC','ND','NE','NH','NJ','NM','NV','NY','OH','OK','OR','PA','RI','SC','SD','TN','TX','UT','VA','VT','WA','WI','WV','WY')
            and station_id = ".$station_id." AND COL_PROP_MODE != \"SAT\""
        );

        $worked_slots = array();
        foreach($data->result() as $row){
            array_push($worked_slots, $row->COL_BAND);
        }

        $SAT_data = $this->db->query(
            "SELECT distinct LOWER(`COL_PROP_MODE`) as `COL_PROP_MODE` FROM `".$this->config->item('table_name')."` WHERE COL_DXCC in ('291', '6', '110') 
            and COL_STATE in ('AK','AL','AR','AZ','CA','CO','CT','DE','FL','GA','HI','IA','ID','IL','IN','KS','KY','LA','MA','MD','ME','MI','MN','MO','MS','MT','NC','ND','NE','NH','NJ','NM','NV','NY','OH','OK','OR','PA','RI','SC','SD','TN','TX','UT','VA','VT','WA','WI','WV','WY') 
            and station_id = ".$station_id." AND COL_PROP_MODE = \"SAT\""
        );

        foreach($SAT_data->result() as $row){
            array_push($worked_slots, strtoupper($row->COL_PROP_MODE));
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
        $CI =& get_instance();
        $CI->load->model('Stations');
        $station_id = $CI->Stations->find_active();

        $data = $this->db->query(
            "select COL_STATE, COL_MODE, lcase(COL_BAND) as COL_BAND, count(COL_STATE) as cnt
            from ".$this->config->item('table_name')."
            where station_id = ".$station_id." AND COL_PROP_MODE != \"SAT\"
            and COL_DXCC in ('291', '6', '110') 
            and COL_STATE in ('AK','AL','AR','AZ','CA','CO','CT','DE','FL','GA','HI','IA','ID','IL','IN','KS','KY','LA','MA','MD','ME','MI','MN','MO','MS','MT','NC','ND','NE','NH','NJ','NM','NV','NY','OH','OK','OR','PA','RI','SC','SD','TN','TX','UT','VA','VT','WA','WI','WV','WY') 
            group by COL_STATE, COL_MODE, COL_BAND"
        );

        $results = array();
        $last_state = "";
        foreach($data->result() as $row){
            if ($last_state != $row->COL_STATE){
                // new row
                $results[$row->COL_STATE] = $this->bandslots;
                $last_state = $row->COL_STATE;
            }

            // update stats
            if (!isset($results[$row->COL_STATE]))
                $results[$row->COL_STATE] = [];

            if (!isset($results[$row->COL_STATE][$row->COL_BAND]))
                $results[$row->COL_STATE][$row->COL_BAND] = 0;

            $results[$row->COL_STATE][$row->COL_BAND] += $row->cnt;
        }

        // Satellite WAS
        $satellite_data = $this->db->query(
            "select COL_STATE, COL_PROP_MODE as COL_PROP_MODE, count(COL_STATE) as cnt
				from ".$this->config->item('table_name')."
				where station_id = ".$station_id." AND COL_PROP_MODE = \"SAT\"
				and COL_DXCC in ('291', '6', '110') 
				and COL_STATE in ('AK','AL','AR','AZ','CA','CO','CT','DE','FL','GA','HI','IA','ID','IL','IN','KS','KY','LA','MA','MD','ME','MI','MN','MO','MS','MT','NC','ND','NE','NH','NJ','NM','NV','NY','OH','OK','OR','PA','RI','SC','SD','TN','TX','UT','VA','VT','WA','WI','WV','WY') 
				group by COL_STATE"
        );

        foreach($satellite_data->result() as $row){
            if ($last_state != $row->COL_STATE){
                // new row
                $results[$row->COL_STATE] = $this->bandslots;
                $last_state = $row->COL_STATE;
            }

            // update stats
            if (!isset($results[$row->COL_STATE]))
                $results[$row->COL_STATE] = [];

            if (!isset($results[$row->COL_STATE][$row->COL_PROP_MODE]))
                $results[$row->COL_STATE][$row->COL_PROP_MODE] = 0;

            $results[$row->COL_STATE][$row->COL_PROP_MODE] += $row->cnt;
        }

        return $results;
    }
}
?>