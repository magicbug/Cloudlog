<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Activators_model extends CI_Model
{
   public function canonicalize_callsign($callsign) {
      $callsign = strtoupper(trim((string) $callsign));
      $callsign = str_replace('Ø', '0', $callsign);

      if ($callsign === '') {
         return '';
      }

      if (strpos($callsign, '/') !== false) {
         $parts = explode('/', $callsign);
         usort($parts, function ($a, $b) {
            return strlen($b) - strlen($a);
         });
         $callsign = $parts[0];
      }

      if (preg_match('/^M(M|W)?(\d[A-Z0-9]+)$/', $callsign, $matches)) {
         $callsign = 'M' . $matches[2];
      }

      return $callsign;
   }

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
         $this->db->order_by('count', 'DESC', false);

      $query = $this->db->get();

            return $this->merge_activator_rows($query->result(), 'grids', (int) $mincount);
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

        return $this->merge_activator_rows($query->result(), 'vucc');
    }

   private function merge_activator_rows($rows, $mode = 'grids', $mincount = 0) {
      $merged = array();

      foreach ($rows as $row) {
         $canonical_call = $this->canonicalize_callsign($row->call);

         if (!isset($merged[$canonical_call])) {
            $merged[$canonical_call] = array(
               'call' => $canonical_call,
               'count' => 0,
               'grids' => array(),
               'vucc_grids' => array(),
            );
         }

         if ($mode === 'vucc') {
            $grid_values = array_filter(array_map('trim', explode(',', (string) $row->vucc_grids)));
            foreach ($grid_values as $grid_value) {
               $canonical_grid = strtoupper($grid_value);
               if (!in_array($canonical_grid, $merged[$canonical_call]['vucc_grids'], true)) {
                  $merged[$canonical_call]['vucc_grids'][] = $canonical_grid;
               }
            }
            $merged[$canonical_call]['count'] = count($merged[$canonical_call]['vucc_grids']);
         } else {
            $grid_values = array_filter(array_map('trim', explode(',', (string) $row->grids)));
            foreach ($grid_values as $grid_value) {
               $canonical_grid = strtoupper($grid_value);
               if (!in_array($canonical_grid, $merged[$canonical_call]['grids'], true)) {
                  $merged[$canonical_call]['grids'][] = $canonical_grid;
               }
            }
            $merged[$canonical_call]['count'] = count($merged[$canonical_call]['grids']);
         }
      }

      $results = array();
      foreach ($merged as $item) {
         sort($item['grids']);
         sort($item['vucc_grids']);
         $result = new stdClass();
         $result->call = $item['call'];
         $result->count = $item['count'];
         $result->grids = implode(', ', $item['grids']);
         $result->vucc_grids = implode(', ', $item['vucc_grids']);
         $results[] = $result;
      }

      usort($results, function ($left, $right) {
         if ($left->count === $right->count) {
            return strcmp($left->call, $right->call);
         }

         return $right->count <=> $left->count;
      });

      if ($mincount > 1) {
         $results = array_values(array_filter($results, function ($row) use ($mincount) {
            return $row->count >= $mincount;
         }));
      }

      return $results;
    }
	function get_max_activated_grids() {
      $activators = $this->get_activators('All', 1, 'both');

      if (!$activators) {
			return 0;
      }

      return isset($activators[0]->count) ? $activators[0]->count : 0;
	}

}
