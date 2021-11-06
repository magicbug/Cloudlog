<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Accumulate_model extends CI_Model
{
    function get_accumulated_data($band, $award, $mode, $period) {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		$location_list = "'".implode("','",$logbooks_locations_array)."'";

        switch ($award) {
            case 'dxcc': $result = $this->get_accumulated_dxcc($band, $mode, $period, $location_list); break;
            case 'was':  $result = $this->get_accumulated_was($band, $mode, $period, $location_list);  break;
            case 'iota': $result = $this->get_accumulated_iota($band, $mode, $period, $location_list); break;
            case 'waz':  $result = $this->get_accumulated_waz($band, $mode, $period, $location_list);  break;
        }

        return $result;
    }

    function get_accumulated_dxcc($band, $mode, $period, $location_list) {
        if ($period == "year") {
            $sql = "SELECT year(col_time_on) as year,
                (select count(distinct b.col_dxcc) from " .
                $this->config->item('table_name') .
                " as b where year(col_time_on) <= year and b.station_id in (". $location_list . ")";
        }
        else if ($period == "month") {
            $sql = "SELECT date_format(col_time_on, '%Y%m') as year,
                (select count(distinct b.col_dxcc) from " .
                $this->config->item('table_name') .
                " as b where date_format(col_time_on, '%Y%m') <= year and b.station_id in (". $location_list . ")";
        }

        if ($band != 'All') {
            if ($band == 'SAT') {
                $sql .= " and col_prop_mode ='" . $band . "'";
            } else {
                $sql .= " and col_prop_mode !='SAT'";
                $sql .= " and col_band ='" . $band . "'";
            }
        }

        if ($mode != 'All') {
			$sql .= " and (col_mode ='" . $mode . "' or col_submode ='" . $mode . "')";
        }

        $sql .=" and b.col_dxcc > 0) total  from " . $this->config->item('table_name') . " as a
                      where a.station_id in (". $location_list . ")";

        if ($band != 'All') {
            if ($band == 'SAT') {
                $sql .= " and col_prop_mode ='" . $band . "'";
            } else {
                $sql .= " and col_prop_mode !='SAT'";
                $sql .= " and col_band ='" . $band . "'";
            }
        }

        if ($mode != 'All') {
            $sql .= " and (col_mode ='" . $mode . "' or col_submode ='" . $mode . "')";
        }

        $sql .= " and col_dxcc > 0";

        if ($period == "year") {
            $sql .= " group by year(a.col_time_on)
                    order by year(a.col_time_on)";
        }
        else if ($period == "month") {
            $sql .= " group by date_format(a.col_time_on, '%Y%m')
                    order by date_format(a.col_time_on, '%Y%m')";
        }

        $query = $this->db->query($sql);

        return $query->result();
    }

    function get_accumulated_was($band, $mode, $period, $location_list) {
        if ($period == "year") {
            $sql = "SELECT year(col_time_on) as year,
                (select count(distinct b.col_state) from " .
                $this->config->item('table_name') .
                " as b where year(col_time_on) <= year and b.station_id in (". $location_list . ")";
        }
        else if ($period == "month") {
            $sql = "SELECT date_format(col_time_on, '%Y%m') as year,
                (select count(distinct b.col_state) from " .
                $this->config->item('table_name') .
                " as b where date_format(col_time_on, '%Y%m') <= year and b.station_id in (". $location_list . ")";
        }

        if ($band != 'All') {
            if ($band == 'SAT') {
                $sql .= " and col_prop_mode ='" . $band . "'";
            } else {
                $sql .= " and col_prop_mode !='SAT'";
                $sql .= " and col_band ='" . $band . "'";
            }
        }

        if ($mode != 'All') {
			$sql .= " and (col_mode ='" . $mode . "' or col_submode ='" . $mode . "')";
        }

        $sql .= " and COL_DXCC in ('291', '6', '110')";
        $sql .= " and COL_STATE in ('AK','AL','AR','AZ','CA','CO','CT','DE','FL','GA','HI','IA','ID','IL','IN','KS','KY','LA','MA','MD','ME','MI','MN','MO','MS','MT','NC','ND','NE','NH','NJ','NM','NV','NY','OH','OK','OR','PA','RI','SC','SD','TN','TX','UT','VA','VT','WA','WI','WV','WY')";

        $sql .=") total  from " . $this->config->item('table_name') . " as a
                      where a.station_id in (". $location_list . ")";

        if ($band != 'All') {
            if ($band == 'SAT') {
                $sql .= " and col_prop_mode ='" . $band . "'";
            } else {
                $sql .= " and col_prop_mode !='SAT'";
                $sql .= " and col_band ='" . $band . "'";
            }
        }

        if ($mode != 'All') {
			$sql .= " and (col_mode ='" . $mode . "' or col_submode ='" . $mode . "')";
        }

        $sql .= " and COL_DXCC in ('291', '6', '110')";
        $sql .= " and COL_STATE in ('AK','AL','AR','AZ','CA','CO','CT','DE','FL','GA','HI','IA','ID','IL','IN','KS','KY','LA','MA','MD','ME','MI','MN','MO','MS','MT','NC','ND','NE','NH','NJ','NM','NV','NY','OH','OK','OR','PA','RI','SC','SD','TN','TX','UT','VA','VT','WA','WI','WV','WY')";

        if ($period == "year") {
            $sql .= " group by year(a.col_time_on)
                    order by year(a.col_time_on)";
        }
        else if ($period == "month") {
            $sql .= " group by date_format(a.col_time_on, '%Y%m')
                    order by date_format(a.col_time_on, '%Y%m')";
        }

        $query = $this->db->query($sql);

        return $query->result();
    }

    function get_accumulated_iota($band, $mode, $period, $location_list) {
        if ($period == "year") {
            $sql = "SELECT year(col_time_on) as year,
                (select count(distinct b.col_iota) from " .
                $this->config->item('table_name') .
                " as b where year(col_time_on) <= year and b.station_id in (". $location_list . ")";
        }
        else if ($period == "month") {
            $sql = "SELECT date_format(col_time_on, '%Y%m') as year,
                (select count(distinct b.col_iota) from " .
                $this->config->item('table_name') .
                " as b where date_format(col_time_on, '%Y%m') <= year and b.station_id in (". $location_list . ")";
        }

        if ($band != 'All') {
            if ($band == 'SAT') {
                $sql .= " and col_prop_mode ='" . $band . "'";
            } else {
                $sql .= " and col_prop_mode !='SAT'";
                $sql .= " and col_band ='" . $band . "'";
            }
        }

        if ($mode != 'All') {
			$sql .= " and (col_mode ='" . $mode . "' or col_submode ='" . $mode . "')";
        }

        $sql .=") total  from " . $this->config->item('table_name') . " as a
                      where a.station_id in (". $location_list . ")";

        if ($band != 'All') {
            if ($band == 'SAT') {
                $sql .= " and col_prop_mode ='" . $band . "'";
            } else {
                $sql .= " and col_prop_mode !='SAT'";
                $sql .= " and col_band ='" . $band . "'";
            }
        }

        if ($mode != 'All') {
			$sql .= " and (col_mode ='" . $mode . "' or col_submode ='" . $mode . "')";
        }

        if ($period == "year") {
            $sql .= " group by year(a.col_time_on)
                    order by year(a.col_time_on)";
        }
        else if ($period == "month") {
            $sql .= " group by date_format(a.col_time_on, '%Y%m')
                    order by date_format(a.col_time_on, '%Y%m')";
        }

        $query = $this->db->query($sql);

        return $query->result();
    }

    function get_accumulated_waz($band, $mode, $period, $location_list) {
        if ($period == "year") {
            $sql = "SELECT year(col_time_on) as year,
                (select count(distinct b.col_cqz) from " .
                $this->config->item('table_name') .
                " as b where year(col_time_on) <= year and b.station_id in (". $location_list . ")";
        }
        else if ($period == "month") {
            $sql = "SELECT date_format(col_time_on, '%Y%m') as year,
                (select count(distinct b.col_cqz) from " .
                $this->config->item('table_name') .
                " as b where date_format(col_time_on, '%Y%m') <= year and b.station_id in (". $location_list . ")";
        }

        if ($band != 'All') {
            if ($band == 'SAT') {
                $sql .= " and col_prop_mode ='" . $band . "'";
            } else {
                $sql .= " and col_prop_mode !='SAT'";
                $sql .= " and col_band ='" . $band . "'";
            }
        }

        if ($mode != 'All') {
			$sql .= " and (col_mode ='" . $mode . "' or col_submode ='" . $mode . "')";
        }

        $sql .=") total  from " . $this->config->item('table_name') . " as a
                      where a.station_id in (". $location_list . ")";

        if ($band != 'All') {
            if ($band == 'SAT') {
                $sql .= " and col_prop_mode ='" . $band . "'";
            } else {
                $sql .= " and col_prop_mode !='SAT'";
                $sql .= " and col_band ='" . $band . "'";
            }
        }

        if ($mode != 'All') {
			$sql .= " and (col_mode ='" . $mode . "' or col_submode ='" . $mode . "')";
        }

        if ($period == "year") {
            $sql .= " group by year(a.col_time_on)
                    order by year(a.col_time_on)";
        }
        else if ($period == "month") {
            $sql .= " group by date_format(a.col_time_on, '%Y%m')
                    order by date_format(a.col_time_on, '%Y%m')";
        }

        $query = $this->db->query($sql);

        return $query->result();
    }

    function get_worked_bands() {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		$location_list = "'".implode("','",$logbooks_locations_array)."'";

        $data = $this->db->query(
            "SELECT distinct LOWER(`COL_BAND`) as `COL_BAND` FROM `" . $this->config->item('table_name') . "` WHERE station_id in (" . $location_list . ") AND COL_PROP_MODE != \"SAT\""
        );
        $worked_slots = array();
        foreach ($data->result() as $row) {
            array_push($worked_slots, $row->COL_BAND);
        }

        $SAT_data = $this->db->query(
            "SELECT distinct LOWER(`COL_PROP_MODE`) as `COL_PROP_MODE` FROM `" . $this->config->item('table_name') . "` WHERE station_id in (" . $location_list . ") AND COL_PROP_MODE = \"SAT\""
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
}
