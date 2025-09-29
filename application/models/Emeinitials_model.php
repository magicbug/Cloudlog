<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Emeinitials_model extends CI_Model
{
    function get_initials($band, $mode) {
        // Input validation
        if (!is_string($band) || !is_string($mode)) {
            log_message('error', 'Invalid input types for band or mode parameters in get_initials');
            return null;
        }

        $CI =& get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if (!$logbooks_locations_array) {
            return null;
        }

        // Ensure location_list is properly escaped for IN clause
        $escaped_locations = array();
        foreach ($logbooks_locations_array as $location) {
            $escaped_locations[] = $this->db->escape($location);
        }
        $location_list = implode(', ', $escaped_locations);

        $result = $this->get_initials_data($band, $mode, $location_list);

        return $result;
    }

    public function get_initials_data($band, $mode, $location_list) {
        // Input validation and sanitization
        if (!is_string($band) || !is_string($mode)) {
            log_message('error', 'Invalid input types for band or mode parameters in get_initials_data');
            return [];
        }
        
        // Sanitize inputs - allow only alphanumeric characters, spaces, and common ham radio characters
        $band = preg_replace('/[^a-zA-Z0-9\s\-]/', '', trim($band));
        $mode = preg_replace('/[^a-zA-Z0-9\s\-]/', '', trim($mode));
        
        // Validate that band and mode are not empty after sanitization (unless they were 'All')
        if (($band !== 'All' && empty($band)) || ($mode !== 'All' && empty($mode))) {
            log_message('error', 'Invalid band or mode parameter after sanitization in get_initials_data');
            return [];
        }

        // Use CodeIgniter's Query Builder for safe parameterized queries
        $this->db->select('COL_CALL as callsign, date(COL_TIME_ON) as date, upper(COL_GRIDSQUARE) as gridsquare, COL_STATE as state');
        $this->db->from($this->config->item('table_name') . ' thcv');
        $this->db->where('station_id in (' . $location_list . ')', null, false);
        $this->db->where('col_prop_mode', 'EME');

        if ($band != 'All') {
            $this->db->where('col_band', $band);
        }

        if ($mode != 'All') {
            $this->db->group_start();
            $this->db->where('col_mode', $mode);
            $this->db->or_where('col_submode', $mode);
            $this->db->group_end();
        }

        $this->db->order_by('COL_TIME_ON');
        $query = $this->db->get();

        // Create a new array after applying rules for initials
        $inits_array = [];

        foreach ($query->result() as $init) {
            $calls_array = $this->find_initials($inits_array, $init);

            // Is this a new callsign? If so, add it to the initials array
            if (empty($calls_array)) {
                $new_init = new stdClass();
                $new_init->callsign   = $init->callsign;
                $new_init->date       = $init->date;
                $new_init->gridsquare = $init->gridsquare;
                $new_init->state      = $init->state;
                $new_init->count      = 1;
                array_push($inits_array, $new_init);
            }
            else {
                // The callsign isn't unique, now check the location of the entries with the same callsign
                $dup_init = $this->find_duplicate($calls_array, $init);

                // Dissimilar locations, we can add this initial to our list, it's new
                if (empty($dup_init)) {
                    $new_init = new stdClass();
                    $new_init->callsign   = $init->callsign;
                    $new_init->date       = $init->date;
                    $new_init->gridsquare = $init->gridsquare;
                    $new_init->state      = $init->state;
                    $new_init->count      = 1;
                    array_push($inits_array, $new_init);
                } else {
                    // The location is a duplicate, increment its count by one
                    $dup_init[0]->count++;
                }
            }
        }
      
        return $inits_array;
    }

    // Simple test to see if the callsign already appears in the initials array
    private function find_initials($array, $initial) {
        $found_array = [];

        foreach ($array as $data) {
            if ($data->callsign === $initial->callsign) {
                array_push($found_array, $data);
            }
        }
      
        return $found_array;
    }

    // We know that the elements in $array have the same callsign, now check the locator, and state.
    private function find_duplicate($array, $initial) {
        $found_array = [];

        foreach ($array as $data) {
            $found = false;

            if (empty($data->gridsquare) || empty($initial->gridsquare)) {
                // Impossible to do any sort of grid matching, so we mark them as matched
                $found = true;
            }
            else if (strlen($data->gridsquare) == 6 && strlen($initial->gridsquare) == 6) {
                // Are the grids an exact match?
                if (strncmp($data->gridsquare, $initial->gridsquare, 6) == 0) {
                    $found = true;
                }
            }
            else {
                // Are the grids a partial match?
                if (strncmp($data->gridsquare, $initial->gridsquare, 4) == 0) {
                    $found = true;
                }
            }

            if (!empty($data->state) && !empty($initial->state)) {
                // Are the states an exact match?
                if ($data->state === $initial->state) {
                    $found = true;
                }
                else {
                    // Even if the locator matches, a different state means that it's an initial
                    $found = false;
                }
            }

            if ($found) {
                array_push($found_array, $data);
                return $found_array;
            }
        }

        return $found_array;
    }
}
