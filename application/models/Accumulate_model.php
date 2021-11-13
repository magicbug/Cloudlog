<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Accumulate_model extends CI_Model
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

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
}