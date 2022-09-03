<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Accumulate_model extends CI_Model
{
    function get_accumulated_data($band, $award, $mode, $period) {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if (!$logbooks_locations_array) {
            return array();
        }

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
            $sql = "select year(thcv.col_time_on) year";
        }
        else if ($period == "month") {
            $sql = "select date_format(col_time_on, '%Y%m') year";
        }

        $sql .= ", coalesce(y.tot, 0) tot 
            from " . $this->config->item('table_name') . " thcv
            left outer join (
                select count(col_dxcc) as tot, year
            from (select distinct ";

        if ($period == "year") {
            $sql .= "year(col_time_on)";
        }
        else if ($period == "month") {
            $sql .= "date_format(col_time_on, '%Y%m')";
        }

        $sql .= " year, col_dxcc
        from " . $this->config->item('table_name') . 
        " where col_dxcc > 0 and station_id in (". $location_list . ")";

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

        $sql .= " order by year
        ) x 
        where not exists (select 1 from " . $this->config->item('table_name') . " where";
        
        if ($period == "year") {
            $sql .= " year(col_time_on) < year";;
        }
        else if ($period == "month") {
            $sql .= " date_format(col_time_on, '%Y%m') < year";;
        }
        
        $sql .= " and col_dxcc = x.col_dxcc";
        
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

        $sql .= " and station_id in (". $location_list . "))
        group by year
        order by year";

        if ($period == "year") {
            $sql .= " ) y on year(thcv.col_time_on) = y.year";
        }
        else if ($period == "month") {
            $sql .= " ) y on date_format(col_time_on, '%Y%m') = y.year";
        }
        
        $sql .= " where thcv.col_dxcc > 0";

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
        
        $sql .= " and station_id in (". $location_list . ")";

        if ($period == "year") {
            $sql .= " group by year(thcv.col_time_on), y.tot
            order by year(thcv.col_time_on)";
        }

        else if ($period == "month") {
            $sql .= " group by date_format(col_time_on, '%Y%m'), y.tot
            order by date_format(col_time_on, '%Y%m')";
        }

        $query = $this->db->query($sql);

        return $this->count_and_add_accumulated_total($query->result());
    }

    function count_and_add_accumulated_total($array) {
        $counter = 0;
        for ($i = 0; $i < count($array); $i++) {
            $array[$i]->total = $array[$i]->tot + $counter;
            $counter = $array[$i]->total;
        }
        return $array;
    }

    function get_accumulated_was($band, $mode, $period, $location_list) {
        if ($period == "year") {
            $sql = "select year(thcv.col_time_on) year";
        }
        else if ($period == "month") {
            $sql = "select date_format(col_time_on, '%Y%m') year";
        }

        $sql .= ", coalesce(y.tot, 0) tot 
            from " . $this->config->item('table_name') . " thcv
            left outer join (
                select count(col_state) as tot, year
            from (select distinct ";

        if ($period == "year") {
            $sql .= "year(col_time_on)";
        }
        else if ($period == "month") {
            $sql .= "date_format(col_time_on, '%Y%m')";
        }

        $sql .= " year, col_state
        from " . $this->config->item('table_name') . 
        " where station_id in (". $location_list . ")";

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

        $sql .= " order by year
        ) x 
        where not exists (select 1 from " . $this->config->item('table_name') . " where";
        
        if ($period == "year") {
            $sql .= " year(col_time_on) < year";;
        }
        else if ($period == "month") {
            $sql .= " date_format(col_time_on, '%Y%m') < year";;
        }
        
        $sql .= " and col_state = x.col_state";
        
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

        $sql .= " and station_id in (". $location_list . "))
        group by year
        order by year";

        if ($period == "year") {
            $sql .= " ) y on year(thcv.col_time_on) = y.year";
        }
        else if ($period == "month") {
            $sql .= " ) y on date_format(col_time_on, '%Y%m') = y.year";
        }
        
        $sql .= " where station_id in (". $location_list . ")";

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
            $sql .= " group by year(thcv.col_time_on), y.tot
            order by year(thcv.col_time_on)";
        }

        else if ($period == "month") {
            $sql .= " group by date_format(col_time_on, '%Y%m'), y.tot
            order by date_format(col_time_on, '%Y%m')";
        }

        $query = $this->db->query($sql);

        return $this->count_and_add_accumulated_total($query->result());
    }

    function get_accumulated_iota($band, $mode, $period, $location_list) {
        if ($period == "year") {
            $sql = "select year(thcv.col_time_on) year";
        }
        else if ($period == "month") {
            $sql = "select date_format(col_time_on, '%Y%m') year";
        }

        $sql .= ", coalesce(y.tot, 0) tot 
            from " . $this->config->item('table_name') . " thcv
            left outer join (
                select count(col_iota) as tot, year
            from (select distinct ";

        if ($period == "year") {
            $sql .= "year(col_time_on)";
        }
        else if ($period == "month") {
            $sql .= "date_format(col_time_on, '%Y%m')";
        }

        $sql .= " year, col_iota
        from " . $this->config->item('table_name') . 
        " where station_id in (". $location_list . ")";

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

        $sql .= " order by year
        ) x 
        where not exists (select 1 from " . $this->config->item('table_name') . " where";
        
        if ($period == "year") {
            $sql .= " year(col_time_on) < year";;
        }
        else if ($period == "month") {
            $sql .= " date_format(col_time_on, '%Y%m') < year";;
        }
        
        $sql .= " and col_iota = x.col_iota";
        
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

        $sql .= " and station_id in (". $location_list . "))
        group by year
        order by year";

        if ($period == "year") {
            $sql .= " ) y on year(thcv.col_time_on) = y.year";
        }
        else if ($period == "month") {
            $sql .= " ) y on date_format(col_time_on, '%Y%m') = y.year";
        }
        
        $sql .= " where station_id in (". $location_list . ")";

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
            $sql .= " group by year(thcv.col_time_on), y.tot
            order by year(thcv.col_time_on)";
        }

        else if ($period == "month") {
            $sql .= " group by date_format(col_time_on, '%Y%m'), y.tot
            order by date_format(col_time_on, '%Y%m')";
        }

        $query = $this->db->query($sql);

        return $this->count_and_add_accumulated_total($query->result());
    }

    function get_accumulated_waz($band, $mode, $period, $location_list) {
        if ($period == "year") {
            $sql = "select year(thcv.col_time_on) year";
        }
        else if ($period == "month") {
            $sql = "select date_format(col_time_on, '%Y%m') year";
        }

        $sql .= ", coalesce(y.tot, 0) tot 
            from " . $this->config->item('table_name') . " thcv
            left outer join (
                select count(col_cqz) as tot, year
            from (select distinct ";

        if ($period == "year") {
            $sql .= "year(col_time_on)";
        }
        else if ($period == "month") {
            $sql .= "date_format(col_time_on, '%Y%m')";
        }

        $sql .= " year, col_cqz
        from " . $this->config->item('table_name') . 
        " where station_id in (". $location_list . ")";

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

        $sql .= " order by year
        ) x 
        where not exists (select 1 from " . $this->config->item('table_name') . " where";
        
        if ($period == "year") {
            $sql .= " year(col_time_on) < year";;
        }
        else if ($period == "month") {
            $sql .= " date_format(col_time_on, '%Y%m') < year";;
        }
        
        $sql .= " and col_cqz = x.col_cqz";
        
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

        $sql .= " and station_id in (". $location_list . "))
        group by year
        order by year";

        if ($period == "year") {
            $sql .= " ) y on year(thcv.col_time_on) = y.year";
        }
        else if ($period == "month") {
            $sql .= " ) y on date_format(col_time_on, '%Y%m') = y.year";
        }
        
        $sql .= " where station_id in (". $location_list . ")";

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
            $sql .= " group by year(thcv.col_time_on), y.tot
            order by year(thcv.col_time_on)";
        }

        else if ($period == "month") {
            $sql .= " group by date_format(col_time_on, '%Y%m'), y.tot
            order by date_format(col_time_on, '%Y%m')";
        }

        $query = $this->db->query($sql);

        return $this->count_and_add_accumulated_total($query->result());
    }
}