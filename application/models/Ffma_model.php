<?php

class Ffma_model extends CI_Model {

    private $us_grids = ['EN29', 'CN78', 'CN88', 'CN98', 'DN08', 'DN18', 'DN28', 'DN38', 'DN48', 'DN58', 'DN68', 'DN78', 'DN88', 'DN98',
       'EN08', 'EN18', 'EN28', 'EN38', 'EN48', 'EN58', 'CN77', 'CN87', 'CN97', 'DN07', 'DN17', 'DN27', 'DN37', 'DN47', 'DN57', 'DN67',
       'DN77', 'DN87', 'DN97', 'EN07', 'EN17', 'EN27', 'EN37', 'EN47', 'EN57', 'EN67', 'FN57', 'FN67', 'CN76', 'CN86', 'CN96', 'DN06',
       'DN16', 'DN26', 'DN36', 'DN46', 'DN56', 'DN66', 'DN76', 'DN86', 'DN96', 'EN06', 'EN16', 'EN26', 'EN36', 'EN46', 'EN56', 'EN66',
       'EN76', 'EN86', 'FN46', 'FN56', 'FN66', 'CN75', 'CN85', 'CN95', 'DN05', 'DN15', 'DN25', 'DN35', 'DN45', 'DN55', 'DN65', 'DN75',
       'DN85', 'DN95', 'EN05', 'EN15', 'EN25', 'EN35', 'EN45', 'EN55', 'EN65', 'EN75', 'EN85', 'FN25', 'FN35', 'FN45', 'FN55', 'FN65',
       'CN74', 'CN84', 'CN94', 'DN04', 'DN14', 'DN24', 'DN34', 'DN44', 'DN54', 'DN64', 'DN74', 'DN84', 'DN94', 'EN04', 'EN14', 'EN24',
       'EN34', 'EN44', 'EN54', 'EN64', 'EN74', 'EN84', 'FN14', 'FN24', 'FN34', 'FN44', 'FN54', 'FN64', 'CN73', 'CN83', 'CN93', 'DN03',
       'DN13', 'DN23', 'DN33', 'DN43', 'DN53', 'DN63', 'DN73', 'DN83', 'DN93', 'EN03', 'EN13', 'EN23', 'EN33', 'EN43', 'EN53', 'EN63',
       'EN73', 'EN83', 'FN03', 'FN13', 'FN23', 'FN33', 'FN43', 'FN53', 'CN72', 'CN82', 'CN92', 'DN02', 'DN12', 'DN22', 'DN32', 'DN42',
       'DN52', 'DN62', 'DN72', 'DN82', 'DN92', 'EN02', 'EN12', 'EN22', 'EN32', 'EN42', 'EN52', 'EN62', 'EN72', 'EN82', 'EN92', 'FN02',
       'FN12', 'FN22', 'FN32', 'FN42', 'CN71', 'CN81', 'CN91', 'DN01', 'DN11', 'DN21', 'DN31', 'DN41', 'DN51', 'DN61', 'DN71', 'DN81',
       'DN91', 'EN01', 'EN11', 'EN21', 'EN31', 'EN41', 'EN51', 'EN61', 'EN71', 'EN81', 'EN91', 'FN01', 'FN11', 'FN21', 'FN31', 'FN41',
       'FN51', 'CN70', 'CN80', 'CN90', 'DN00', 'DN10', 'DN20', 'DN30', 'DN40', 'DN50', 'DN60', 'DN70', 'DN80', 'DN90', 'EN00', 'EN10',
       'EN20', 'EN30', 'EN40', 'EN50', 'EN60', 'EN70', 'EN80', 'EN90', 'FN00', 'FN10', 'FN20', 'FN30', 'CM79', 'CM89', 'CM99', 'DM09',
       'DM19', 'DM29', 'DM39', 'DM49', 'DM59', 'DM69', 'DM79', 'DM89', 'DM99', 'EM09', 'EM19', 'EM29', 'EM39', 'EM49', 'EM59', 'EM69',
       'EM79', 'EM89', 'EM99', 'FM09', 'FM19', 'FM29', 'CM88', 'CM98', 'DM08', 'DM18', 'DM28', 'DM38', 'DM48', 'DM58', 'DM68', 'DM78',
       'DM88', 'DM98', 'EM08', 'EM18', 'EM28', 'EM38', 'EM48', 'EM58', 'EM68', 'EM78', 'EM88', 'EM98', 'FM08', 'FM18', 'FM28', 'CM87',
       'CM97', 'DM07', 'DM17', 'DM27', 'DM37', 'DM47', 'DM57', 'DM67', 'DM77', 'DM87', 'DM97', 'EM07', 'EM17', 'EM27', 'EM37', 'EM47',
       'EM57', 'EM67', 'EM77', 'EM87', 'EM97', 'FM07', 'FM17', 'FM27', 'CM86', 'CM96', 'DM06', 'DM16', 'DM26', 'DM36', 'DM46', 'DM56',
       'DM66', 'DM76', 'DM86', 'DM96', 'EM06', 'EM16', 'EM26', 'EM36', 'EM46', 'EM56', 'EM66', 'EM76', 'EM86', 'EM96', 'FM06', 'FM16',
       'FM26', 'CM95', 'DM05', 'DM15', 'DM25', 'DM35', 'DM45', 'DM55', 'DM65', 'DM75', 'DM85', 'DM95', 'EM05', 'EM15', 'EM25', 'EM35',
       'EM45', 'EM55', 'EM65', 'EM75', 'EM85', 'EM95', 'FM05', 'FM15', 'FM25', 'CM94', 'DM04', 'DM14', 'DM24', 'DM34', 'DM44', 'DM54',
       'DM64', 'DM74', 'DM84', 'DM94', 'EM04', 'EM14', 'EM24', 'EM34', 'EM44', 'EM54', 'EM64', 'EM74', 'EM84', 'EM94', 'FM04', 'FM14',
       'CM93', 'DM03', 'DM13', 'DM23', 'DM33', 'DM43', 'DM53', 'DM63', 'DM73', 'DM83', 'DM93', 'EM03', 'EM13', 'EM23', 'EM33', 'EM43',
       'EM53', 'EM63', 'EM73', 'EM83', 'EM93', 'FM03', 'FM13', 'DM02', 'DM12', 'DM22', 'DM32', 'DM42', 'DM52', 'DM62', 'DM72', 'DM82',
       'DM92', 'EM02', 'EM12', 'EM22', 'EM32', 'EM42', 'EM52', 'EM62', 'EM72', 'EM82', 'EM92', 'FM02', 'DM31', 'DM41', 'DM51', 'DM61',
       'DM71', 'DM81', 'DM91', 'EM01', 'EM11', 'EM21', 'EM31', 'EM41', 'EM51', 'EM61', 'EM71', 'EM81', 'EM91', 'DM70', 'DM80', 'DM90',
       'EM00', 'EM10', 'EM20', 'EM30', 'EM40', 'EM50', 'EM60', 'EM70', 'EM80', 'EM90', 'DL79', 'DL89', 'DL99', 'EL09', 'EL19', 'EL29',
       'EL39', 'EL49', 'EL59', 'EL79', 'EL89', 'EL99', 'DL88', 'DL98', 'EL08', 'EL18', 'EL28', 'EL58', 'EL88', 'EL98', 'EL07', 'EL17',
       'EL87', 'EL97', 'EL06', 'EL16', 'EL86', 'EL96', 'EL15', 'EL95', 'EL84', 'EL94'
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
           ." and COL_BAND = '6M'"
           .' AND substring(COL_GRIDSQUARE,1,4) in (\''.implode('\',\'', $this->us_grids).'\')';
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
           ." and COL_BAND = '6M'"
           .' AND substring(COL_GRIDSQUARE,1,4) in (\''.implode('\',\'', $this->us_grids).'\')';
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
           ." and COL_BAND = '6M'"
           .' AND substring(COL_GRIDSQUARE,1,4) in (\''.implode('\',\'', $this->us_grids).'\')';
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
           ." and COL_BAND = '6M'";
        $query = $this->db->query($sql);
        $vucc_grids = [];
        foreach ($query->result() as $row) {
           $grids = explode(',', $row->VUCC_GRIDS);
           foreach ($grids as $grid) {
              if (in_array(substr($grid, 0, 4), $this->us_grids)) {
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
           ." and COL_BAND = '6M'";
        $query = $this->db->query($sql);
        $vucc_grids = [];
        foreach ($query->result() as $row) {
           $grids = explode(',', $row->VUCC_GRIDS);
           foreach ($grids as $grid) {
              if (in_array(substr($grid, 0, 4), $this->us_grids)) {
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
           ." and COL_BAND = '6M'";
        $query = $this->db->query($sql);
        $vucc_grids = [];
        foreach ($query->result() as $row) {
           $grids = explode(',', $row->VUCC_GRIDS);
           foreach ($grids as $grid) {
              if (in_array(substr($grid, 0, 4), $this->us_grids)) {
                 if (!in_array(substr($grid, 0, 4), $vucc_grids)) {
                    $vucc_grids[] = substr($grid, 0, 4);
                 }
              }
           }
        }
        return $vucc_grids;
    }

}
