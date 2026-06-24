<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Activators_model extends CI_Model
{
   private function normalize_location_ids($location_list) {
      if (!is_array($location_list)) {
         return array();
      }

      $ids = array();
      foreach ($location_list as $id) {
         if (is_numeric($id)) {
            $ids[] = (int) $id;
         }
      }

      return array_values(array_unique($ids));
   }

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

      $location_ids = $this->normalize_location_ids($logbooks_locations_array);
      if (empty($location_ids)) {
         return null;
      }

      $this->db->select("COL_CALL as `call`, COUNT(DISTINCT(SUBSTR(COL_GRIDSQUARE,1,4))) AS `count`, GROUP_CONCAT(DISTINCT SUBSTR(`COL_GRIDSQUARE`,1,4) ORDER BY `COL_GRIDSQUARE` SEPARATOR ', ') AS `grids`", false);
      $this->db->from($this->config->item('table_name'));
      $this->db->where_in('station_id', $location_ids);
        if ($band != 'All') {
            if ($band == 'SAT') {
               switch ($leogeo) {
               case 'both' :
              $this->db->where('col_prop_mode', $band);
                  break;
               case 'leo' :
              $this->db->where('col_prop_mode', $band);
              $this->db->where('col_sat_name !=', 'QO-100');
                  break;
               case 'geo' :
              $this->db->where('col_prop_mode', $band);
              $this->db->where('col_sat_name', 'QO-100');
                  break;
               default :
              $this->db->where('col_prop_mode', $band);
                  break;
               }
            }
            else {
            $this->db->where('col_prop_mode !=', 'SAT');
            $this->db->where('COL_BAND', $band);
            }
        }
      $this->db->where('COL_GRIDSQUARE !=', '');
      $this->db->group_by('COL_CALL');
      $this->db->having('COUNT(DISTINCT(SUBSTR(COL_GRIDSQUARE,1,4))) >=', (int) $mincount, false);
      $this->db->order_by('count', 'DESC', false);

      $query = $this->db->get();

        return $query->result();
    }

    function get_activators_vucc($band, $leogeo)  {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if (!$logbooks_locations_array) {
            return null;
        }

      $location_ids = $this->normalize_location_ids($logbooks_locations_array);
      if (empty($location_ids)) {
         return null;
      }

      $this->db->select('COL_CALL AS `call`, GROUP_CONCAT(COL_VUCC_GRIDS) AS `vucc_grids`', false);
      $this->db->from($this->config->item('table_name'));
      $this->db->where_in('station_id', $location_ids);
        if ($band != 'All') {
            if ($band == 'SAT') {
               switch ($leogeo) {
               case 'both' :
              $this->db->where('col_prop_mode', $band);
                  break;
               case 'leo' :
              $this->db->where('col_prop_mode', $band);
              $this->db->where('col_sat_name !=', 'QO-100');
                  break;
               case 'geo' :
              $this->db->where('col_prop_mode', $band);
              $this->db->where('col_sat_name', 'QO-100');
                  break;
               default :
              $this->db->where('col_prop_mode', $band);
                  break;
               }
            }
            else {
            $this->db->where('col_prop_mode !=', 'SAT');
            $this->db->where('COL_BAND', $band);
            }
        }
      $this->db->where('COL_VUCC_GRIDS !=', '');
      $this->db->group_by('COL_CALL');

      $query = $this->db->get();

        return $query->result();
    }
	function get_max_activated_grids() {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		if (!$logbooks_locations_array) {
			return array();
		}

      $location_ids = $this->normalize_location_ids($logbooks_locations_array);
      if (empty($location_ids)) {
         return array();
      }

      $this->db->select('COUNT(DISTINCT(SUBSTR(COL_GRIDSQUARE,1,4))) AS `count`', false);
      $this->db->from($this->config->item('table_name'));
      $this->db->where_in('station_id', $location_ids);
      $this->db->where('COL_GRIDSQUARE !=', '');
      $this->db->group_by('COL_CALL');
      $this->db->order_by('count', 'DESC', false);
      $this->db->limit(1);
      $data = $this->db->get();
		foreach($data->result() as $row){
			$max =  $row->count;
		}

		return $max;
	}

}
