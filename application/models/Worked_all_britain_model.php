<?php

class Worked_all_britain_model extends CI_Model
{
    public function get_worked_squares($filters = [])
    {
        $CI = &get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if (!$logbooks_locations_array) {
            return null;
        }

        $this->db->select('COL_SIG_INFO');
        $this->db->where_in('station_id', $logbooks_locations_array);
        $this->db->where('COL_SIG', 'WAB');

        $this->apply_band_mode_filters($filters);

        if (!empty($filters['confirmed_only'])) {
            $this->apply_confirmation_filter();
        }

        $query = $this->db->get($this->config->item('table_name'));
        $worked_squares = [];
        foreach ($query->result() as $row) {
            $worked_squares[] = 'Small Square ' . $row->COL_SIG_INFO . ' Boundry Box';
        }

        return $worked_squares;
    }

    public function get_confirmed_squares($filters = [])
    {
        $CI = &get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if (!$logbooks_locations_array) {
            return null;
        }

        $this->db->select('COL_SIG_INFO');
        $this->db->where_in('station_id', $logbooks_locations_array);
        $this->db->where('COL_SIG', 'WAB');

        $this->apply_band_mode_filters($filters);
        $this->apply_confirmation_filter();

        $query = $this->db->get($this->config->item('table_name'));
        $confirmed_squares = [];
        foreach ($query->result() as $row) {
            $confirmed_squares[] = 'Small Square ' . $row->COL_SIG_INFO . ' Boundry Box';
        }

        return $confirmed_squares;
    }

    public function get_wab_qsos($filters = [])
    {
        $CI = &get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if (!$logbooks_locations_array) {
            return null;
        }

        $this->db->join('station_profile', 'station_profile.station_id = ' . $this->config->item('table_name') . '.station_id');
        $this->db->join('lotw_users lotw', 'lotw.callsign = ' . $this->config->item('table_name') . '.col_call', 'left');
        $this->db->join('dxcc_entities', 'dxcc_entities.adif = ' . $this->config->item('table_name') . '.col_dxcc', 'left outer');
        $this->db->where_in($this->config->item('table_name') . '.station_id', $logbooks_locations_array);
        $this->db->where('COL_SIG', 'WAB');

        if (!empty($filters['square'])) {
            $this->db->where('COL_SIG_INFO', $filters['square']);
        }

        $this->apply_band_mode_filters($filters);

        if (!empty($filters['confirmed_only'])) {
            $this->apply_confirmation_filter();
        }

        $this->db->order_by('COL_TIME_ON', 'DESC');

        return $this->db->get($this->config->item('table_name'));
    }

    private function apply_band_mode_filters($filters)
    {
        if (!empty($filters['band']) && $filters['band'] !== 'All') {
            $this->db->where('LOWER(COL_BAND)', strtolower($filters['band']));
        }

        if (!empty($filters['mode']) && $filters['mode'] !== 'All') {
            $this->db->group_start();
            $this->db->where('LOWER(COL_MODE)', strtolower($filters['mode']));
            $this->db->or_where('LOWER(COL_SUBMODE)', strtolower($filters['mode']));
            $this->db->group_end();
        }
    }

    private function apply_confirmation_filter()
    {
        $this->db->where("(col_qsl_rcvd='Y' or col_eqsl_qsl_rcvd='Y' or COL_LOTW_QSL_RCVD='Y')");
    }
}