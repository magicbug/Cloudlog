<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Timeline_model extends CI_Model
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    function get_timeline($band, $mode, $award)  {
        $CI =& get_instance();
        $CI->load->model('Stations');
        $station_id = $CI->Stations->find_active();

        switch ($award) {
            case 'dxcc': $result = $this->get_timeline_dxcc($band, $mode, $station_id); break;
            case 'was':  $result = $this->get_timeline_was($band, $mode, $station_id);  break;
            case 'iota': $result = $this->get_timeline_iota($band, $mode, $station_id); break;
            case 'waz':  $result = $this->get_timeline_waz($band, $mode, $station_id);  break;
        }

        return $result;
    }

    public function get_timeline_dxcc($band, $mode, $station_id) {
        $sql = "select min(date(COL_TIME_ON)) date, prefix, col_country, end, adif from "
            .$this->config->item('table_name'). " thcv
            join dxcc_entities on thcv.col_dxcc = dxcc_entities.adif
            where station_id = " . $station_id;

        if ($band != 'All') {
            if ($band == 'SAT') {
                $sql .= " and col_prop_mode ='" . $band . "'";
            }
            else {
                $sql .= " and col_prop_mode !='SAT'";
                $sql .= " and col_band ='" . $band . "'";
            }
        }

        if ($mode != 'All') {
            $sql .= " and col_mode ='" . $mode . "'";
        }

        $sql .= " group by col_dxcc, col_country
                order by date desc";

        $query = $this->db->query($sql);

        return $query->result();
    }

    public function get_timeline_was($band, $mode, $station_id) {
        $sql = "select min(date(COL_TIME_ON)) date, col_state from "
            .$this->config->item('table_name'). " thcv
            where station_id = " . $station_id;

        if ($band != 'All') {
            if ($band == 'SAT') {
                $sql .= " and col_prop_mode ='" . $band . "'";
            }
            else {
                $sql .= " and col_prop_mode !='SAT'";
                $sql .= " and col_band ='" . $band . "'";
            }
        }

        if ($mode != 'All') {
            $sql .= " and col_mode ='" . $mode . "'";
        }

        $sql .= " and COL_DXCC in ('291', '6', '110')";
        $sql .= " and COL_STATE in ('AK','AL','AR','AZ','CA','CO','CT','DE','FL','GA','HI','IA','ID','IL','IN','KS','KY','LA','MA','MD','ME','MI','MN','MO','MS','MT','NC','ND','NE','NH','NJ','NM','NV','NY','OH','OK','OR','PA','RI','SC','SD','TN','TX','UT','VA','VT','WA','WI','WV','WY')";

        $sql .= " group by col_state
                order by date desc";

        $query = $this->db->query($sql);

        return $query->result();
    }

    public function get_timeline_iota($band, $mode, $station_id) {
        $sql = "select min(date(COL_TIME_ON)) date,  col_iota, name, prefix from "
            .$this->config->item('table_name'). " thcv
            join iota on thcv.col_iota = iota.tag
            where station_id = " . $station_id;

        if ($band != 'All') {
            if ($band == 'SAT') {
                $sql .= " and col_prop_mode ='" . $band . "'";
            }
            else {
                $sql .= " and col_prop_mode !='SAT'";
                $sql .= " and col_band ='" . $band . "'";
            }
        }

        if ($mode != 'All') {
            $sql .= " and col_mode ='" . $mode . "'";
        }

        $sql .= " and col_iota <> '' group by col_iota, name, prefix
                order by date desc";

        $query = $this->db->query($sql);

        return $query->result();
    }

    public function get_timeline_waz($band, $mode, $station_id) {
        $sql = "select min(date(COL_TIME_ON)) date, col_cqz from "
            .$this->config->item('table_name'). " thcv
            where station_id = " . $station_id;

        if ($band != 'All') {
            if ($band == 'SAT') {
                $sql .= " and col_prop_mode ='" . $band . "'";
            }
            else {
                $sql .= " and col_prop_mode !='SAT'";
                $sql .= " and col_band ='" . $band . "'";
            }
        }

        if ($mode != 'All') {
            $sql .= " and col_mode ='" . $mode . "'";
        }

        $sql .= " and col_cqz <> '' group by col_cqz
                order by date desc";

        $query = $this->db->query($sql);

        return $query->result();
    }

    public $bandslots = array("160m" => 0,
        "80m" => 0,
        "60m" => 0,
        "40m" => 0,
        "30m" => 0,
        "20m" => 0,
        "17m" => 0,
        "15m" => 0,
        "12m" => 0,
        "10m" => 0,
        "6m" => 0,
        "4m" => 0,
        "2m" => 0,
        "70cm" => 0,
        "23cm" => 0,
        "13cm" => 0,
        "9cm" => 0,
        "6cm" => 0,
        "3cm" => 0,
        "1.25cm" => 0,
        "SAT" => 0,
    );

    function get_worked_bands()
    {
        $CI =& get_instance();
        $CI->load->model('Stations');
        $station_id = $CI->Stations->find_active();

        // get all worked slots from database
        $data = $this->db->query(
            "SELECT distinct LOWER(`COL_BAND`) as `COL_BAND` FROM `" . $this->config->item('table_name') . "` WHERE station_id = " . $station_id . " AND COL_PROP_MODE != \"SAT\""
        );
        $worked_slots = array();
        foreach ($data->result() as $row) {
            array_push($worked_slots, $row->COL_BAND);
        }

        $SAT_data = $this->db->query(
            "SELECT distinct LOWER(`COL_PROP_MODE`) as `COL_PROP_MODE` FROM `" . $this->config->item('table_name') . "` WHERE station_id = " . $station_id . " AND COL_PROP_MODE = \"SAT\""
        );

        foreach ($SAT_data->result() as $row) {
            array_push($worked_slots, strtoupper($row->COL_PROP_MODE));
        }

        // bring worked-slots in order of defined $bandslots
        $results = array();
        foreach (array_keys($this->bandslots) as $slot) {
            if (in_array($slot, $worked_slots)) {
                array_push($results, $slot);
            }
        }
        return $results;
    }

}
