<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Timeline_model extends CI_Model
{
    function get_timeline($band, $mode, $award)  {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if (!$logbooks_locations_array) {
            return null;
        }

		$location_list = "'".implode("','",$logbooks_locations_array)."'";

        switch ($award) {
            case 'dxcc': $result = $this->get_timeline_dxcc($band, $mode, $location_list); break;
            case 'was':  $result = $this->get_timeline_was($band, $mode, $location_list);  break;
            case 'iota': $result = $this->get_timeline_iota($band, $mode, $location_list); break;
            case 'waz':  $result = $this->get_timeline_waz($band, $mode, $location_list);  break;
        }

        return $result;
    }

    public function get_timeline_dxcc($band, $mode, $location_list) {
        $sql = "select min(date(COL_TIME_ON)) date, prefix, col_country, end, adif from "
            .$this->config->item('table_name'). " thcv
            join dxcc_entities on thcv.col_dxcc = dxcc_entities.adif
            where station_id in (" . $location_list . ")";

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

    public function get_timeline_was($band, $mode, $location_list) {
        $sql = "select min(date(COL_TIME_ON)) date, col_state from "
            .$this->config->item('table_name'). " thcv
            where station_id in (" . $location_list . ")";

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

    public function get_timeline_iota($band, $mode, $location_list) {
        $sql = "select min(date(COL_TIME_ON)) date,  col_iota, name, prefix from "
            .$this->config->item('table_name'). " thcv
            join iota on thcv.col_iota = iota.tag
            where station_id in (" . $location_list . ")";

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

    public function get_timeline_waz($band, $mode, $location_list) {
        $sql = "select min(date(COL_TIME_ON)) date, col_cqz from "
            .$this->config->item('table_name'). " thcv
            where station_id in (" . $location_list . ")";

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
 
}
