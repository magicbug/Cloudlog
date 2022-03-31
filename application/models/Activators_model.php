<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Activators_model extends CI_Model
{
    function get_activators($band, $mincount, $leogeo)  {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

      if ($mincount == '' || $mincount == 0 || ! is_numeric($mincount)) {
         $mincount = 2;
      }

        if (!$logbooks_locations_array) {
            return null;
        }

		$location_list = "'".implode("','",$logbooks_locations_array)."'";

		$sql = "select COL_CALL as `call`, COUNT(DISTINCT(SUBSTR(COL_GRIDSQUARE,1,4))) AS `count`, GROUP_CONCAT(DISTINCT SUBSTR(`COL_GRIDSQUARE`,1,4) ORDER BY `COL_GRIDSQUARE` SEPARATOR ', ') AS `grids` from ".$this->config->item('table_name')." WHERE station_id in (" . $location_list . ")";
        if ($band != 'All') {
            if ($band == 'SAT') {
               switch ($leogeo) {
               case 'both' :
                  $sql .= " and col_prop_mode ='" . $band . "'";
                  break;
               case 'leo' :
                  $sql .= " and col_prop_mode = '" . $band . "'";
                  $sql .= " and col_sat_name != 'QO-100'";
                  break;
               case 'geo' :
                  $sql .= " and col_prop_mode = '" . $band . "'";
                  $sql .= " and col_sat_name = 'QO-100'";
                  break;
               default :
                  $sql .= " and col_prop_mode ='" . $band . "'";
                  break;
               }
            }
            else {
                $sql .= " and col_prop_mode !='SAT'";
                $sql .= " and COL_BAND ='" . $band . "'";
            }
        }
		$sql .= " AND `COL_GRIDSQUARE` != '' GROUP BY `COL_CALL` HAVING `count` >= ".$mincount." ORDER BY `count` DESC;";

        $query = $this->db->query($sql);

        return $query->result();
    }

    function get_activators_vucc($band, $leogeo)  {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if (!$logbooks_locations_array) {
            return null;
        }

		$location_list = "'".implode("','",$logbooks_locations_array)."'";

      $sql = "SELECT DISTINCT COL_CALL AS `call`, GROUP_CONCAT(COL_VUCC_GRIDS) AS `vucc_grids` FROM ".$this->config->item('table_name')." WHERE station_id in (" . $location_list . ")";
        if ($band != 'All') {
            if ($band == 'SAT') {
               switch ($leogeo) {
               case 'both' :
                  $sql .= " and col_prop_mode ='" . $band . "'";
                  break;
               case 'leo' :
                  $sql .= " and col_prop_mode = '" . $band . "'";
                  $sql .= " and col_sat_name != 'QO-100'";
                  break;
               case 'geo' :
                  $sql .= " and col_prop_mode = '" . $band . "'";
                  $sql .= " and col_sat_name = 'QO-100'";
                  break;
               default :
                  $sql .= " and col_prop_mode ='" . $band . "'";
                  break;
               }
            }
            else {
                $sql .= " and col_prop_mode !='SAT'";
                $sql .= " and COL_BAND ='" . $band . "'";
            }
        }
      $sql .= " AND COL_VUCC_GRIDS != '' GROUP BY COL_CALL;";

        $query = $this->db->query($sql);

        return $query->result();
    }
	function get_max_activated_grids() {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		if (!$logbooks_locations_array) {
			return array();
		}

		$location_list = "'".implode("','",$logbooks_locations_array)."'";

		// Get max no of activated grids of single operator
		$data = $this->db->query(
			"select COUNT(DISTINCT(SUBSTR(COL_GRIDSQUARE,1,4))) AS `count` from TABLE_HRD_CONTACTS_V01 WHERE station_id in (" . $location_list . ") AND `COL_GRIDSQUARE` != '' GROUP BY `COL_CALL` ORDER BY `count` DESC LIMIT 1"
		);
		foreach($data->result() as $row){
			$max =  $row->count;
		}

		return $max;
	}

}
