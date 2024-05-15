<?php

class Gmdxsummer_model extends CI_Model
{

    public function get_week($end_date, $band, $mode)
    {
        $table_name = $this->config->item('table_name');

        $CI = &get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        $location_list = "'" . implode("','", $logbooks_locations_array) . "'";

        $query = $this->db->query("
            SELECT COUNT(DISTINCT SUBSTRING(COL_GRIDSQUARE, 1, 4)) AS count
            FROM " . $table_name . "
            WHERE station_id in (" . $location_list . ") AND COL_MODE = '" . $mode . "' AND COL_BAND = '" . $band . "'
            AND (COL_TIME_ON >= '2024-05-13 00:00:00' AND COL_TIME_ON <= '" . $end_date . "')
        ");

        return $query->row()->count;
    }

    public function get_week_voice($end_date, $band)
    {
        $table_name = $this->config->item('table_name');

        $CI = &get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        $location_list = "'" . implode("','", $logbooks_locations_array) . "'";

        $query = $this->db->query("
        SELECT COUNT(DISTINCT SUBSTRING(COL_GRIDSQUARE, 1, 4)) AS count
        FROM " . $table_name . "
        WHERE station_id in (".$location_list.") AND COL_MODE IN ('SSB', 'AM', 'FM') AND COL_BAND = '" . $band . "'
        AND (COL_TIME_ON >= '2024-05-13 00:00:00' AND COL_TIME_ON <= '" . $end_date . "')
        ");


        return $query->row()->count;
    }

    public function get_week_digital($end_date, $band)
    {
        $table_name = $this->config->item('table_name');

        $CI = &get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        $location_list = "'" . implode("','", $logbooks_locations_array) . "'";

        $query = $this->db->query("
        SELECT COUNT(DISTINCT SUBSTRING(COL_GRIDSQUARE, 1, 4)) AS count
        FROM " . $table_name . "
        WHERE station_id in (".$location_list.") AND COL_MODE NOT IN ('CW', 'FM', 'SSB', 'AM') AND COL_BAND = '" . $band . "'
        AND (COL_TIME_ON >= '2024-05-13 00:00:00' AND COL_TIME_ON <= '" . $end_date . "')
        ");

        return $query->row()->count;
    }

    public function get_week_combined($end_date, $band)
    {
        $table_name = $this->config->item('table_name');

        $CI = &get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        $location_list = "'" . implode("','", $logbooks_locations_array) . "'";

        $query = $this->db->query("
        SELECT COUNT(DISTINCT SUBSTRING(COL_GRIDSQUARE, 1, 4)) AS count
        FROM " . $table_name . "
        WHERE station_id in (".$location_list.") AND COL_BAND = '" . $band . "'
        AND (COL_TIME_ON >= '2024-05-13 00:00:00' AND COL_TIME_ON <= '" . $end_date . "')
        ");

        return $query->row()->count;
    }
}
