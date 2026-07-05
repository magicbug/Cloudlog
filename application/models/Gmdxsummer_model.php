<?php

class Gmdxsummer_model extends CI_Model
{
    private const START_DATE = '2026-05-11 00:00:00';

    public function get_week($end_date, $band, $mode)
    {
        $table_name = $this->config->item('table_name');

        $CI = &get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if (!$logbooks_locations_array) {
            return 0;
        }
        $location_list = implode(',', array_map('intval', $logbooks_locations_array));

        $query = $this->db->query("
            SELECT COUNT(DISTINCT SUBSTRING(COL_GRIDSQUARE, 1, 4)) AS count
            FROM " . $table_name . "
            WHERE station_id in (" . $location_list . ") AND COL_MODE = ? AND COL_BAND = ?
            AND COL_GRIDSQUARE IS NOT NULL AND COL_GRIDSQUARE != ''
            AND (COL_TIME_ON >= ? AND COL_TIME_ON <= ?)
        ", array($mode, $band, self::START_DATE, $end_date));

        return $query->row()->count;
    }

    public function get_week_voice($end_date, $band)
    {
        $table_name = $this->config->item('table_name');

        $CI = &get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if (!$logbooks_locations_array) {
            return 0;
        }
        $location_list = implode(',', array_map('intval', $logbooks_locations_array));

        $query = $this->db->query("
        SELECT COUNT(DISTINCT SUBSTRING(COL_GRIDSQUARE, 1, 4)) AS count
        FROM " . $table_name . "
        WHERE station_id in (".$location_list.") AND COL_MODE IN ('SSB', 'AM', 'FM') AND COL_BAND = ?
        AND COL_GRIDSQUARE IS NOT NULL AND COL_GRIDSQUARE != ''
        AND (COL_TIME_ON >= ? AND COL_TIME_ON <= ?)
        ", array($band, self::START_DATE, $end_date));


        return $query->row()->count;
    }

    public function get_week_digital($end_date, $band)
    {
        $table_name = $this->config->item('table_name');

        $CI = &get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if (!$logbooks_locations_array) {
            return 0;
        }
        $location_list = implode(',', array_map('intval', $logbooks_locations_array));

        $query = $this->db->query("
        SELECT COUNT(DISTINCT SUBSTRING(COL_GRIDSQUARE, 1, 4)) AS count
        FROM " . $table_name . "
        WHERE station_id in (".$location_list.") AND COL_MODE NOT IN ('CW', 'FM', 'SSB', 'AM') AND COL_BAND = ?
        AND COL_GRIDSQUARE IS NOT NULL AND COL_GRIDSQUARE != ''
        AND (COL_TIME_ON >= ? AND COL_TIME_ON <= ?)
        ", array($band, self::START_DATE, $end_date));

        return $query->row()->count;
    }

    public function get_week_combined($end_date, $band)
    {
        $table_name = $this->config->item('table_name');

        $CI = &get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if (!$logbooks_locations_array) {
            return 0;
        }
        $location_list = implode(',', array_map('intval', $logbooks_locations_array));

		$query = $this->db->query("
        SELECT COUNT(DISTINCT CONCAT(
            UPPER(SUBSTRING(COL_GRIDSQUARE, 1, 4)),
            '-',
            CASE
                WHEN COL_MODE = 'CW' THEN 'CW'
                WHEN COL_MODE IN ('SSB', 'AM', 'FM') THEN 'VOICE'
                ELSE 'DIGITAL'
            END
        )) AS count
        FROM " . $table_name . "
		WHERE station_id in (".$location_list.") AND COL_BAND = ?
        AND COL_GRIDSQUARE IS NOT NULL AND COL_GRIDSQUARE != ''
		AND (COL_TIME_ON >= ? AND COL_TIME_ON <= ?)
		", array($band, self::START_DATE, $end_date));

        return $query->row()->count;
    }
}
