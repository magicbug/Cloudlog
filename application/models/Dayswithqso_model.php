<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dayswithqso_model extends CI_Model
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    function getDaysWithQso()
    {
        $CI =& get_instance();
        $CI->load->model('Stations');
        $station_id = $CI->Stations->find_active();

        $sql = "select year(col_qso_date) Year, count(distinct col_qso_date) Days from "
            .$this->config->item('table_name'). " thcv
            where station_id = " . $station_id . " and col_qso_date is not null group by year";

        $query = $this->db->query($sql);

        return $query->result();
    }

}