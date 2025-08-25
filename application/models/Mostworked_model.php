<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mostworked_model extends CI_Model
{
    /*
     * Get callsigns worked more than a minimum number of times from the active logbook
     */
    function get_most_worked_callsigns($filters = array())
    {
        $CI =& get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if (!$logbooks_locations_array) {
            return array();
        }

        $location_list = "'" . implode("','", $logbooks_locations_array) . "'";
        
        // Set defaults
        $min_qsos = isset($filters['min_qsos']) ? intval($filters['min_qsos']) : 5;
        $band = isset($filters['band']) ? $filters['band'] : 'all';
        $mode = isset($filters['mode']) ? $filters['mode'] : 'all';
        $satellite = isset($filters['satellite']) ? $filters['satellite'] : 'all';
        $fromdate = isset($filters['fromdate']) ? $filters['fromdate'] : '';
        $todate = isset($filters['todate']) ? $filters['todate'] : '';

        $sql = "SELECT 
                    CASE 
                        WHEN col_call REGEXP '/[PMAQLBR]$|/MM$|/AM$|/QRP$|/LH$|/BCN$' THEN 
                            SUBSTRING(col_call, 1, LOCATE('/', col_call) - 1)
                        ELSE 
                            col_call 
                    END as callsign,
                    COUNT(*) as contact_count,
                    MIN(col_time_on) as first_qso,
                    MAX(col_time_on) as last_qso,
                    GROUP_CONCAT(DISTINCT col_band ORDER BY col_band) as bands,
                    GROUP_CONCAT(DISTINCT COALESCE(col_submode, col_mode) ORDER BY col_mode) as modes
                FROM " . $this->config->item('table_name') . " 
                WHERE station_id IN (" . $location_list . ") 
                AND col_call IS NOT NULL 
                AND col_call != ''";

        // Apply filters
        if ($band != 'all') {
            if ($band == 'SAT') {
                $sql .= " AND col_prop_mode = 'SAT'";
            } else {
                $sql .= " AND col_band = '" . $this->db->escape_str($band) . "'";
                $sql .= " AND (col_prop_mode != 'SAT' OR col_prop_mode IS NULL)";
            }
        }

        if ($mode != 'all') {
            $sql .= " AND (col_mode = '" . $this->db->escape_str($mode) . "' OR col_submode = '" . $this->db->escape_str($mode) . "')";
        }

        if ($satellite != 'all') {
            $sql .= " AND col_sat_name = '" . $this->db->escape_str($satellite) . "'";
        }

        if (!empty($fromdate)) {
            $sql .= " AND DATE(col_time_on) >= '" . $this->db->escape_str($fromdate) . "'";
        }

        if (!empty($todate)) {
            $sql .= " AND DATE(col_time_on) <= '" . $this->db->escape_str($todate) . "'";
        }

        $sql .= " GROUP BY CASE 
                        WHEN col_call REGEXP '/[PMAQLBR]$|/MM$|/AM$|/QRP$|/LH$|/BCN$' THEN 
                            SUBSTRING(col_call, 1, LOCATE('/', col_call) - 1)
                        ELSE 
                            col_call 
                    END
                  HAVING contact_count >= " . $min_qsos . "
                  ORDER BY contact_count DESC, callsign ASC";

        $query = $this->db->query($sql);

        return $query->result();
    }

    /*
     * Get list of modes from the active logbook
     */
    function get_modes()
    {
        $CI =& get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if (!$logbooks_locations_array) {
            return array();
        }

        $location_list = "'" . implode("','", $logbooks_locations_array) . "'";

        $sql = "SELECT DISTINCT COALESCE(col_submode, col_mode) as mode 
                FROM " . $this->config->item('table_name') . " 
                WHERE station_id IN (" . $location_list . ") 
                AND COALESCE(col_submode, col_mode) IS NOT NULL 
                AND COALESCE(col_submode, col_mode) != ''
                ORDER BY mode";

        $query = $this->db->query($sql);
        
        return $query->result();
    }

    /*
     * Get list of satellites from the active logbook
     */
    function get_satellites()
    {
        $CI =& get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if (!$logbooks_locations_array) {
            return array();
        }

        $location_list = "'" . implode("','", $logbooks_locations_array) . "'";

        $sql = "SELECT DISTINCT col_sat_name as satellite 
                FROM " . $this->config->item('table_name') . " 
                WHERE station_id IN (" . $location_list . ") 
                AND col_sat_name IS NOT NULL 
                AND col_sat_name != ''
                ORDER BY col_sat_name";

        $query = $this->db->query($sql);
        
        return $query->result();
    }

    /*
     * Get list of bands from the active logbook
     */
    function get_bands()
    {
        $CI =& get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if (!$logbooks_locations_array) {
            return array();
        }

        $location_list = "'" . implode("','", $logbooks_locations_array) . "'";

        $sql = "SELECT DISTINCT col_band as band 
                FROM " . $this->config->item('table_name') . " 
                WHERE station_id IN (" . $location_list . ") 
                AND col_band IS NOT NULL 
                AND col_band != ''
                AND (col_prop_mode != 'SAT' OR col_prop_mode IS NULL)
                ORDER BY 
                    CASE col_band
                        WHEN '160m' THEN 1
                        WHEN '80m' THEN 2
                        WHEN '60m' THEN 3
                        WHEN '40m' THEN 4
                        WHEN '30m' THEN 5
                        WHEN '20m' THEN 6
                        WHEN '17m' THEN 7
                        WHEN '15m' THEN 8
                        WHEN '12m' THEN 9
                        WHEN '10m' THEN 10
                        WHEN '6m' THEN 11
                        WHEN '4m' THEN 12
                        WHEN '2m' THEN 13
                        WHEN '1.25m' THEN 14
                        WHEN '70cm' THEN 15
                        ELSE 999
                    END, col_band";

        $query = $this->db->query($sql);
        
        $results = array();
        foreach($query->result() as $row) {
            $results[] = $row->band;
        }
        
        return $results;
    }

    /*
     * Get detailed contact information for a specific callsign
     */
    function get_callsign_details($callsign)
    {
        $CI =& get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if (!$logbooks_locations_array) {
            return array();
        }

        $location_list = "'" . implode("','", $logbooks_locations_array) . "'";

        $this->db->select('col_time_on, col_band, col_mode, col_submode, col_rst_sent, col_rst_rcvd, col_country, col_qsl_sent, col_qsl_rcvd');
        $this->db->from($this->config->item('table_name'));
        $this->db->where('station_id IN (' . $location_list . ')', NULL, FALSE);
        $this->db->where('col_call', $callsign);
        $this->db->order_by('col_time_on', 'DESC');

        $query = $this->db->get();

        return $query->result();
    }
}
