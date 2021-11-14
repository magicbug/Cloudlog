<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Timeplotter_model extends CI_Model
{

    function getTimes($postdata) {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if (!$logbooks_locations_array) {
            header('Content-Type: application/json');
            $data['error'] = 'No QSOs found to plot!';
            echo json_encode($data);
            return;
        }

        $this->db->select('time(col_time_on) time, col_call as callsign');

        if ($postdata['band'] != 'All') {
            if ($postdata['band'] == 'SAT') {
                $this->db->where('col_prop_mode', $postdata['band']);
            }
            else {
                $this->db->where('col_band', $postdata['band']);
            }
        }

        if ($postdata['dxcc'] != 'All') {
            $this->db->where('col_dxcc', $postdata['dxcc']);
        }

        if ($postdata['cqzone'] != 'All') {
            $this->db->where('col_cqz', $postdata['cqzone']);
        }

        $this->db->where_in('station_id', $logbooks_locations_array);
        $datearray = $this->db->get($this->config->item('table_name'));
        $this->plot($datearray->result_array());
    }

    /*
    * Function generates the array, checks for array entries, and adds them before returning data ready for plot
    */
    function plot($log) {

        $start = "00:00";
        $end = "23:59";

        $tStart = strtotime($start);
        $tEnd = strtotime($end);
        $tNow = $tStart;
        $i = 0;

        while($tNow <= $tEnd){                          // Generates the time array
            $label = date("H:i",$tNow).'z - ';
            $tNow = strtotime('+30 minutes',$tNow);
            $label .= date("H:i",$tNow).'z';
            $dataarray[$i]['time'] =  $label;   // Used in x-axis of graph to show label for the timeslot
            $dataarray[$i]['count'] = '0'; // Used to hold number of contacts found in the timeslot
            $dataarray[$i]['calls'] = ''; // Used for holding callsigns of contacts in that timeslot
            $dataarray[$i]['callcount'] = '0'; // Used for counting how many callsigns stored in that timeslot
            $i++;
        }

        foreach ($log as $line) {       // Looping through all the timestamps found in the log
            $time = $line['time'];									// Resolution is 30, calculates where to put result in array
            $dt = new DateTime("1970-01-01 $time", new DateTimeZone('UTC'));
            $arrayplacement = (int)$dt->getTimestamp();
            $arrayplacement = floor($arrayplacement / 1800);
            $dataarray[$arrayplacement]['count']++;

            $callCount = $dataarray[$arrayplacement]['callcount'];

            if ($callCount < 5) {   // We only save a max of 5 calls to show in the graph
                if ($callCount > 0) {
                    $dataarray[$arrayplacement]['calls'] .= ', ';
                }
                $dataarray[$arrayplacement]['calls'] .= $line['callsign'];
                $dataarray[$arrayplacement]['callcount']++;
            }
        }

        if (count($log) != 0) {  // If we have a result from the log
            header('Content-Type: application/json');
            $data['qsocount'] = count($log);
            $data['ok'] = 'OK';
            $data['qsodata'] = $dataarray;
            echo json_encode($data);
        }
        else {
            header('Content-Type: application/json');
            $data['error'] = 'No QSOs found to plot!';
            echo json_encode($data);
        }

    }
}
