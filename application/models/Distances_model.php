<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Distances_model extends CI_Model
{

	function get_distances($postdata, $measurement_base) {
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

				$this->db->select('COL_PRIMARY_KEY,COL_DISTANCE,col_call callsign, col_gridsquare grid');
				$this->db->where('LENGTH(col_gridsquare) >', 0);

				if ($postdata['band'] == 'sat') {
					$this->db->where('col_prop_mode', $postdata['band']);
					if ($postdata['sat'] != 'All') {
						$this->db->where('col_sat_name', $postdata['sat']);
					}
				}
				elseif ($postdata['band'] != 'all') {
					$this->db->where('col_band', $postdata['band']);
				}

				if ($postdata['mode'] != 'all') {
					$this->db->group_start()->where('col_mode', $postdata['mode'])->or_where('col_submode', $postdata['mode'])->group_end();
				}

				if ($postdata['pwr'] != 'all') {
					if ($postdata['pwr']) {
						$this->db->where('col_tx_pwr', $postdata['pwr']);
					} else {
						$this->db->where('col_tx_pwr is NULL');
					}
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
						$calls = array_unique($calls);
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
		$this->load->library('Qra');
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

		if (!$this->valid_locator(substr($stationgrid, 0, 6))) {
			header('Content-Type: application/json');
			echo json_encode(array('Error' => 'Error. There is a problem with the gridsquare set in your profile!'));
			exit;
		} else {
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
				'Grids' => '',
				'Avg_distance' => ''
			);

			$avg_distance = 0;

			foreach ($qsoArray as $qso) {
				$qrb['Qsos']++;                                                        // Counts up number of qsos
				$bearingdistance = $this->qra->distance($stationgrid, $qso['grid'], $measurement_base);
				$avg_distance += ($bearingdistance - $avg_distance) / $qrb['Qsos'];    // Calculates running average of distance
				if ($bearingdistance != $qso['COL_DISTANCE']) {
					$data = array('COL_DISTANCE' => $bearingdistance);
	  				$this->db->where('COL_PRIMARY_KEY', $qso['COL_PRIMARY_KEY']);
	  				$this->db->update($this->config->item('table_name'), $data);
				}
				$arrayplacement = (int)($bearingdistance / 50);                         // Resolution is 50, calculates where to put result in array
				if ($bearingdistance > $qrb['Distance']) {                              // Saves the longest QSO
					$qrb['Distance'] = $bearingdistance;
					$qrb['Callsign'] = $qso['callsign'];
					$qrb['Grid'] = $qso['grid'];
				}
				$dataarray[$arrayplacement]['count']++;                                 // Used for counting total qsos plotted
				if ($dataarray[$arrayplacement]['callcount'] < 5) {                     // Used for tooltip in graph, set limit to 5 calls shown
					if (strpos($dataarray[$arrayplacement]['calls'], $qso['callsign']) === false) {   // Avoids duplicated callsigns
						if ($dataarray[$arrayplacement]['callcount'] > 0) {
							$dataarray[$arrayplacement]['calls'] .= ', ';
						}
						$dataarray[$arrayplacement]['calls'] .= $qso['callsign'];
						$dataarray[$arrayplacement]['callcount']++;
					}
				}
			}

			$qrb['Avg_distance'] = round($avg_distance, 1);

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
	 * Used to fetch QSOs from the logbook in the awards
	 */
	public function qso_details($distance, $band, $sat, $mode, $power){
		$distarray = $this->getdistparams($distance);
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		$this->db->join('station_profile', 'station_profile.station_id = '.$this->config->item('table_name').'.station_id');
		$this->db->join('dxcc_entities', 'dxcc_entities.adif = '.$this->config->item('table_name').'.COL_DXCC', 'left outer');
		$this->db->join('lotw_users', 'lotw_users.callsign = '.$this->config->item('table_name').'.col_call', 'left outer');
		$this->db->where('COL_DISTANCE >=', $distarray[0]);
		$this->db->where('COL_DISTANCE <=', $distarray[1]);
		$this->db->where('LENGTH(col_gridsquare) >', 0);

		$this->db->where_in($this->config->item('table_name').'.station_id', $logbooks_locations_array);

		if ($band != 'all') {
			if ($band != "sat") {
				$this->db->where('COL_PROP_MODE !=', 'SAT');
				$this->db->where('COL_BAND', $band);
			} else {
				$this->db->where('COL_PROP_MODE', "SAT");
				if ($sat != 'All') {
					$this->db->where('COL_SAT_NAME', $sat);
				}
			}
		}

		if ($mode != 'all') {
			$this->db->group_start()->where('COL_MODE', $mode)->or_where('COL_SUBMODE', $mode)->group_end();
		}

		if ($power != 'all') {
			if ($power) {
				$this->db->where('COL_TX_PWR', $power);
			} else {
				$this->db->where('COL_TX_PWR is NULL');
			}
		}

		$this->db->order_by("COL_TIME_ON", "desc");

		return $this->db->get($this->config->item('table_name'));
	}

	function getdistparams($distance) {
		$temp = explode('-', $distance);
		$regex = '[a-zA-Z]+';
		preg_match("%{$regex}%i", $temp[0], $unit);

		$result = [];
		$result[0] = filter_var($temp[0], FILTER_SANITIZE_NUMBER_INT);
		$result[1] = filter_var($temp[1], FILTER_SANITIZE_NUMBER_INT);

		if ($unit[0] == 'mi') {
			$result[0] *= 1.609344;
			$result[1] *= 1.609344;
		}
		if ($unit[0] == 'nmi') {
			$result[0] *= 1.852;
			$result[1] *= 1.852;
		}

		return $result;
	}
}
