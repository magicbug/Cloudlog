<?php

class Ja_gridmaster_model extends CI_Model {

    private $ja_grids = ['PL14', 'PL24', 'PL36', 'PL37', 'PL46', 'PL47', 'PL48', 'PL49', 'PL55', 'PL58', 'PM40', 'PM41', 'PM42', 'PM43',
        'PM44', 'PM50', 'PM51', 'PM52', 'PM53', 'PM54', 'PM62', 'PM63', 'PM64', 'PM65', 'PM66', 'PM73', 'PM74', 'PM75', 'PM76', 'PM83',
        'PM84', 'PM85', 'PM86', 'PM87', 'PM92', 'PM93', 'PM94', 'PM95', 'PM96', 'PM97', 'PM98', 'PM99', 'PN90', 'PN91', 'PN92', 'QL16',
        'QL17', 'QM05', 'QM06', 'QM07', 'QM08', 'QM09', 'QM19', 'QN00', 'QN01', 'QN02', 'QN03', 'QN04', 'QN05', 'QN11', 'QN12', 'QN13',
        'QN14', 'QN15', 'QN22', 'QN23', 'QN24', 'PL80', 'PM91', 'QL04', 'QL05', 'QL07', 'QL09', 'QL64', 'QM00', 'QM01', 'PL15', 'PL25',
        'PL54', 'PM57', 'QN33', 'QN34', 'QN35', 'QN45'
    ];

    function get_lotw() {
        $CI =& get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if (!$logbooks_locations_array) {
            return null;
        }
        $location_list = "'".implode("','",$logbooks_locations_array)."'";

        $sql = 'SELECT distinct substring(COL_GRIDSQUARE,1,4) as GRID_SQUARES FROM '
           .$this->config->item('table_name')
           .' WHERE station_id in ('.$location_list.')'
           ." and COL_LOTW_QSL_RCVD = 'Y'"
           ." and COL_PROP_MODE = 'SAT'"
           .' AND substring(COL_GRIDSQUARE,1,4) in (\''.implode('\',\'', $this->ja_grids).'\')';
        return $this->db->query($sql);
    }

    function get_paper() {
        $CI =& get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if (!$logbooks_locations_array) {
            return null;
        }
        $location_list = "'".implode("','",$logbooks_locations_array)."'";

        $sql = 'SELECT distinct substring(COL_GRIDSQUARE,1,4) as GRID_SQUARES FROM '
           .$this->config->item('table_name')
           .' WHERE station_id in ('.$location_list.')'
           ." and COL_QSL_RCVD = 'Y'"
           ." and COL_PROP_MODE = 'SAT'"
           .' AND substring(COL_GRIDSQUARE,1,4) in (\''.implode('\',\'', $this->ja_grids).'\')';
        return $this->db->query($sql);
    }

    function get_worked() {
        $CI =& get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if (!$logbooks_locations_array) {
            return null;
        }
        $location_list = "'".implode("','",$logbooks_locations_array)."'";

        $sql = 'SELECT distinct substring(COL_GRIDSQUARE,1,4) as GRID_SQUARES FROM '
           .$this->config->item('table_name')
           .' WHERE station_id in ('.$location_list.')'
           ." and COL_PROP_MODE = 'SAT'"
           .' AND substring(COL_GRIDSQUARE,1,4) in (\''.implode('\',\'', $this->ja_grids).'\')';
        return $this->db->query($sql);
    }

    function get_vucc_lotw() {
        $CI =& get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if (!$logbooks_locations_array) {
            return null;
        }
        $location_list = "'".implode("','",$logbooks_locations_array)."'";

        $sql = 'SELECT distinct COL_VUCC_GRIDS as VUCC_GRIDS FROM '
           .$this->config->item('table_name')
           .' WHERE station_id in ('.$location_list.')'
           ." and COL_LOTW_QSL_RCVD = 'Y'"
           ." and COL_VUCC_GRIDS != ''"
           ." and COL_VUCC_GRIDS IS NOT NULL"
           ." and COL_PROP_MODE = 'SAT'";
        $query = $this->db->query($sql);
        $vucc_grids = [];
        foreach ($query->result() as $row) {
           $grids = explode(',', $row->VUCC_GRIDS);
           foreach ($grids as $grid) {
              if (in_array(substr($grid, 0, 4), $this->ja_grids)) {
                 if (!in_array(substr($grid, 0, 4), $vucc_grids)) {
                    $vucc_grids[] = substr($grid, 0, 4);
                 }
              }
           }
        }
        return $vucc_grids;
    }

    function get_vucc_paper() {
        $CI =& get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if (!$logbooks_locations_array) {
            return null;
        }
        $location_list = "'".implode("','",$logbooks_locations_array)."'";

        $sql = 'SELECT distinct COL_VUCC_GRIDS as VUCC_GRIDS FROM '
           .$this->config->item('table_name')
           .' WHERE station_id in ('.$location_list.')'
           ." and COL_QSL_RCVD = 'Y'"
           ." and COL_VUCC_GRIDS != ''"
           ." and COL_VUCC_GRIDS IS NOT NULL"
           ." and COL_PROP_MODE = 'SAT'";
        $query = $this->db->query($sql);
        $vucc_grids = [];
        foreach ($query->result() as $row) {
           $grids = explode(',', $row->VUCC_GRIDS);
           foreach ($grids as $grid) {
              if (in_array(substr($grid, 0, 4), $this->ja_grids)) {
                 if (!in_array(substr($grid, 0, 4), $vucc_grids)) {
                    $vucc_grids[] = substr($grid, 0, 4);
                 }
              }
           }
        }
        return $vucc_grids;
    }

    function get_vucc_worked() {
        $CI =& get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if (!$logbooks_locations_array) {
            return null;
        }
        $location_list = "'".implode("','",$logbooks_locations_array)."'";

        $sql = 'SELECT distinct COL_VUCC_GRIDS as VUCC_GRIDS FROM '
           .$this->config->item('table_name')
           .' WHERE station_id in ('.$location_list.')'
           ." and COL_VUCC_GRIDS != ''"
           ." and COL_VUCC_GRIDS IS NOT NULL"
           ." and COL_PROP_MODE = 'SAT'";
        $query = $this->db->query($sql);
        $vucc_grids = [];
        foreach ($query->result() as $row) {
           $grids = explode(',', $row->VUCC_GRIDS);
           foreach ($grids as $grid) {
              if (in_array(substr($grid, 0, 4), $this->ja_grids)) {
                 if (!in_array(substr($grid, 0, 4), $vucc_grids)) {
                    $vucc_grids[] = substr($grid, 0, 4);
                 }
              }
           }
        }
        return $vucc_grids;
    }

    function get_grid_count() {
       return count($this->ja_grids);
    }

    function get_grids() {
       return $this->ja_grids;
    }

}
