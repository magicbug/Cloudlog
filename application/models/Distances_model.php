<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Distances_model extends CI_Model
{

    function get_distances($postdata, $measurement_base)
    {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if (!$logbooks_locations_array) {
            header('Content-Type: application/json');
            echo json_encode(array('Error' => 'No QSOs found to plot.'));
            return;
        }

		$result = array();

		foreach ($logbooks_locations_array as $station_id) {

			$station_gridsquare = $this->find_gridsquare($station_id);

			if ($station_gridsquare != null) {
				$gridsquare = explode(',', $station_gridsquare); // We need to convert to an array, since a user can enter several gridsquares

				$this->db->select('col_call callsign, col_gridsquare grid');
				$this->db->where('LENGTH(col_gridsquare) >', 0);

				if ($postdata['band'] == 'sat') {
					$this->db->where('col_prop_mode', $postdata['band']);
					if ($postdata['sat'] != 'All') {
						$this->db->where('col_sat_name', $postdata['sat']);
					}
				}
				else {
					$this->db->where('col_band', $postdata['band']);
				}

				$this->db->where('station_id', $station_id);
				$queryresult = $this->db->get($this->config->item('table_name'));

                if ($queryresult->result_array()) {
                    $temp = $this->plot($queryresult->result_array(), $gridsquare, $measurement_base);
 
                    $result = $this->mergeresult($result, $temp);

                }

			}

		}

		if ($result) {
			header('Content-Type: application/json');
			echo json_encode($result);
		}
		else {
			header('Content-Type: application/json');
			echo json_encode(array('Error' => 'No QSOs found to plot.'));
		}

    }

    /*
     * We merge the result from several station_id's. They can have different gridsquares, so we need to use the correct gridsquare to calculate the correct distance.
     */
    function mergeresult($result, $add) {
    	if (sizeof($result) > 0) {
			if ($result['qrb']['Distance'] < $add['qrb']['Distance']) {
				$result['qrb']['Distance'] = $add['qrb']['Distance'];
				$result['qrb']['Grid'] 	   = $add['qrb']['Grid'];
				$result['qrb']['Callsign'] = $add['qrb']['Callsign'];
			}
			$result['qrb']['Qsos'] += $add['qrb']['Qsos'];

			for ($i = 0; $i <= 399; $i++) {
                
                if(isset($result['qsodata'][$i]['count'])) {
				    $result['qsodata'][$i]['count'] += $add['qsodata'][$i]['count'];
                }

                if(isset($result['qsodata'][$i]['callcount'])) {
                    if ($result['qsodata'][$i]['callcount'] < 5 && $add['qsodata'][$i]['callcount'] > 0) {
                        $calls = explode(',', $add['qsodata'][$i]['calls']);
                        foreach ($calls as $c) {
                            if ($result['qsodata'][$i]['callcount'] < 5) {
                                if ($result['qsodata'][$i]['callcount'] > 0) {
                                    $result['qsodata'][$i]['calls'] .= ', ';
                                }
                                $result['qsodata'][$i]['calls'] .= $c;
                                $result['qsodata'][$i]['callcount']++;
                            }
                        }
                    }
                }
			}
			return $result;
		}

    	return $add;
	}

	/*
	 * Fetches the gridsquare from the station_id
	 */
	function find_gridsquare($station_id) {
		$this->db->where('station_id', $station_id);

        $result = $this->db->get('station_profile')->row_array();

        if ($result) {
            return $result['station_gridsquare'];
        }

		return null;
	}

    // This functions takes query result from the database and extracts grids from the qso,
    // then calculates distance between homelocator and locator given in qso.
    // It builds an array, which has 50km intervals, then inputs each length into the correct spot
    // The function returns a json-encoded array.
    function plot($qsoArray, $gridsquare, $measurement_base) {

        $stationgrid = strtoupper($gridsquare[0]);              // We use only the first entered gridsquare from the active profile
        if (strlen($stationgrid) == 4) $stationgrid .= 'MM';    // adding center of grid if only 4 digits are specified

        switch ($measurement_base) {
            case 'M':
                $unit = "mi";
                $dist = '13000';
                break;
            case 'K':
                $unit = "km";
                $dist = '20000';
                break;
            case 'N':
                $unit = "nmi";
                $dist = '11000';
                break;
            default:
                $unit = "km";
                $dist = '20000';
        }

        if (!$this->valid_locator($stationgrid)) {
            header('Content-Type: application/json');
            echo json_encode(array('Error' => 'Error. There is a problem with the gridsquare set in your profile!'));
            exit;
        }
        else {
            // Making the array we will use for plotting, we save occurrences of the length of each qso in the array
            $j = 0;
            for ($i = 0; $j < $dist; $i++) {
                $dataarray[$i]['dist'] =  $j . $unit . ' - ' . ($j + 50) . $unit;
                $dataarray[$i]['count'] = 0;
                $dataarray[$i]['calls'] = '';
                $dataarray[$i]['callcount'] = 0;
                $j += 50;
            }

            $qrb = array (					                                            // Used for storing the QSO with the longest QRB
                'Callsign' => '',
                'Grid' => '',
                'Distance' => '',
                'Qsos' => '',
                'Grids' => ''
            );

            foreach ($qsoArray as $qso) {
                $qrb['Qsos']++;                                                        // Counts up number of qsos
                $bearingdistance = $this->bearing_dist($stationgrid, $qso['grid'], $measurement_base);     // Calculates distance based on grids
                $arrayplacement = $bearingdistance / 50;                                // Resolution is 50, calculates where to put result in array
                if ($bearingdistance > $qrb['Distance']) {                              // Saves the longest QSO
                    $qrb['Distance'] = $bearingdistance;
                    $qrb['Callsign'] = $qso['callsign'];
                    $qrb['Grid'] = $qso['grid'];
                }
                $dataarray[$arrayplacement]['count']++;                                               // Used for counting total qsos plotted
                if ($dataarray[$arrayplacement]['callcount'] < 5) {                     // Used for tooltip in graph, set limit to 5 calls shown
                    if ($dataarray[$arrayplacement]['callcount'] > 0) {
                        $dataarray[$arrayplacement]['calls'] .= ', ';
                    }
                    $dataarray[$arrayplacement]['calls'] .= $qso['callsign'];
                    $dataarray[$arrayplacement]['callcount']++;
                }
            }

			$data['ok'] = 'OK';
			$data['qrb'] = $qrb;
			$data['qsodata'] = $dataarray;
			$data['unit'] = $unit;

            return $data;
        }
    }

    /*
     * Checks the validity of the locator
     * Input: locator
     * Returns: bool
     */
    function valid_locator ($loc) {
        $regex = '^[A-R]{2}[0-9]{2}[A-X]{2}$';
        if (preg_match("%{$regex}%i", $loc)) {
            return true;
        }
        else {
            return false;
        }
    }

    /*
     * Converts locator to latitude and longitude
     * Input: locator
     * Returns: array with longitude and latitude
     */
    function loc_to_latlon ($loc) {
        /* lat */
        $l[0] =
            (ord(substr($loc, 1, 1))-65) * 10 - 90 +
            (ord(substr($loc, 3, 1))-48) +
            (ord(substr($loc, 5, 1))-65) / 24 + 1/48;
        $l[0] = $this->deg_to_rad($l[0]);
        /* lon */
        $l[1] =
            (ord(substr($loc, 0, 1))-65) * 20 - 180 +
            (ord(substr($loc, 2, 1))-48) * 2 +
            (ord(substr($loc, 4, 1))-65) / 12 + 1/24;
        $l[1] = $this->deg_to_rad($l[1]);

        return $l;
    }

    function deg_to_rad ($deg) {
        return (M_PI * $deg/180);
    }

    function bearing_dist($loc1, $loc2, $measurement_base) {
        $loc1 = strtoupper($loc1);
        $loc2 = strtoupper($loc2);

        if (strlen($loc1) == 4) $loc1 .= 'MM';
        if (strlen($loc2) == 4) $loc2 .= 'MM';

        if (!$this->valid_locator($loc1) || !$this->valid_locator($loc2)) {
            return 0;
        }

        $l1 = $this->loc_to_latlon($loc1);
        $l2 = $this->loc_to_latlon($loc2);

        $co = cos($l1[1] - $l2[1]) * cos($l1[0]) * cos($l2[0]) + sin($l1[0]) * sin($l2[0]);
        $ca = atan2(sqrt(1 - $co*$co), $co);

        switch ($measurement_base) {
            case 'M':
                return round(6371*$ca/1.609344);
            case 'K':
                return round(6371*$ca);
            case 'N':
                return round(6371*$ca/1.852);
            default:
                return round(6371*$ca);
        }
    }
}
