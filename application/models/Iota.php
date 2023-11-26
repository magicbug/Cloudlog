<?php

class IOTA extends CI_Model {

    function get_iota_array($iotaArray, $bands, $postdata) {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if (!$logbooks_locations_array) {
            return null;
        }

		$location_list = "'".implode("','",$logbooks_locations_array)."'";

        foreach ($bands as $band) {             	// Looping through bands and iota to generate the array needed for display
            foreach ($iotaArray as $iota) {
                $iotaMatrix[$iota->tag]['prefix'] = $iota->prefix;
                $iotaMatrix[$iota->tag]['name'] = $iota->name;
                if ($postdata['includedeleted'])
                    $iotaMatrix[$iota->tag]['Deleted'] = isset($iota->status) && $iota->status == 'D' ? "<div class='alert-danger'>Y</div>" : '';
                $iotaMatrix[$iota->tag][$band] = '-';
            }

            // If worked is checked, we add worked iotas to the array
            if ($postdata['worked'] != NULL) {
                $workedIota = $this->getIotaBandWorked($location_list, $band, $postdata);
                foreach ($workedIota as $wiota) {
                    $iotaMatrix[$wiota->tag][$band] = '<div class="bg-danger awardsBgDanger"><a href=\'javascript:displayContacts("'.$wiota->tag.'","'. $band . '","'. $postdata['mode'] . '","IOTA")\'>W</a></div>';
                }
            }

            // If confirmed is checked, we add confirmed iotas to the array
            if ($postdata['confirmed'] != NULL) {
                $confirmedIota = $this->getIotaBandConfirmed($location_list, $band, $postdata);
                foreach ($confirmedIota as $ciota) {
                    $iotaMatrix[$ciota->tag][$band] = '<div class="bg-success awardsBgSuccess"><a href=\'javascript:displayContacts("'.$ciota->tag.'","'. $band . '","'. $postdata['mode'] . '","IOTA")\'>C</a></div>';
                }
            }
        }

        // We want to remove the worked iotas in the list, since we do not want to display them
        if ($postdata['worked'] == NULL) {
            $workedIota = $this->getIotaWorked($location_list, $postdata);
            foreach ($workedIota as $wiota) {
                if (array_key_exists($wiota->tag, $iotaMatrix)) {
                    unset($iotaMatrix[$wiota->tag]);
                }
            }
        }

        // We want to remove the confirmed iotas in the list, since we do not want to display them
        if ($postdata['confirmed'] == NULL) {
            $confirmedIOTA = $this->getIotaConfirmed($location_list, $postdata);
            foreach ($confirmedIOTA as $ciota) {
                if (array_key_exists($ciota->tag, $iotaMatrix)) {
                    unset($iotaMatrix[$ciota->tag]);
                }
            }
        }

        if (isset($iotaMatrix)) {
            return $iotaMatrix;
        }
        else {
            return 0;
        }
    }

    function getIotaBandConfirmed($location_list, $band, $postdata) {
        $sql = "SELECT distinct col_iota as tag FROM " . $this->config->item('table_name') . " thcv
            join iota on thcv.col_iota = iota.tag
            where station_id in (" . $location_list .
            ") and thcv.col_iota is not null
            and (col_qsl_rcvd = 'Y' or col_lotw_qsl_rcvd = 'Y')";

		if ($postdata['mode'] != 'All') {
			$sql .= " and (col_mode = '" . $postdata['mode'] . "' or col_submode = '" . $postdata['mode'] . "')";
		}

        $sql .= $this->addBandToQuery($band);

        if ($postdata['includedeleted'] == NULL) {
            $sql .= " and coalesce(iota.status, '') <> 'D'";
        }

        $sql .= $this->addContinentsToQuery($postdata);

        $query = $this->db->query($sql);

        return $query->result();
    }

    function getIotaBandWorked($location_list, $band, $postdata) {
        $sql = 'SELECT distinct col_iota as tag FROM ' . $this->config->item('table_name'). ' thcv
            join iota on thcv.col_iota = iota.tag
            where station_id in (' . $location_list .
            ') and thcv.col_iota is not null';

		if ($postdata['mode'] != 'All') {
			$sql .= " and (col_mode = '" . $postdata['mode'] . "' or col_submode = '" . $postdata['mode'] . "')";
		}

        $sql .= $this->addBandToQuery($band);

        if ($postdata['includedeleted'] == NULL) {
            $sql .= " and coalesce(iota.status, '') <> 'D'";
        }

        $sql .= $this->addContinentsToQuery($postdata);

        $query = $this->db->query($sql);

        return $query->result();
    }

    function fetchIota($postdata) {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if (!$logbooks_locations_array) {
            return null;
        }

		$location_list = "'".implode("','",$logbooks_locations_array)."'";

        $sql = "select tag, name, prefix, dxccid, status, lat1, lat2, lon1, lon2 from iota where 1=1";

        if ($postdata['includedeleted'] == NULL) {
            $sql .= " and coalesce(iota.status, '') <> 'D'";
        }

        $sql .= $this->addContinentsToQuery($postdata);

        if ($postdata['notworked'] == NULL) {
            $sql .= " and exists (select 1 from " . $this->config->item('table_name') . " where station_id in (". $location_list . ") and col_iota = iota.tag";

			if ($postdata['mode'] != 'All') {
				$sql .= " and (col_mode = '" . $postdata['mode'] . "' or col_submode = '" . $postdata['mode'] . "')";
			}

            if ($postdata['band'] != 'All') {
                if ($postdata['band'] == 'SAT') {
                    $sql .= " and col_prop_mode ='" . $postdata['band'] . "'";
                }
                else {
                    $sql .= " and col_prop_mode !='SAT'";
                    $sql .= " and col_band ='" . $postdata['band'] . "'";
                }
            }
            $sql .= ")";
        }

        $sql .= ' order by tag';
        $query = $this->db->query($sql);

        return $query->result();
    }

    function getIotaWorked($location_list, $postdata) {
        $sql = "SELECT distinct col_iota as tag FROM " . $this->config->item('table_name') . " thcv
            join iota on thcv.col_iota = iota.tag
            where station_id in (" . $location_list .
            ") and thcv.col_iota is not null
            and not exists (select 1 from ". $this->config->item('table_name') . " where station_id = ". $location_list .
            " and col_iota = thcv.col_iota";

		if ($postdata['mode'] != 'All') {
			$sql .= " and (col_mode = '" . $postdata['mode'] . "' or col_submode = '" . $postdata['mode'] . "')";
		}

        $sql .= $this->addBandToQuery($postdata['band']);

        $sql .= " and (col_qsl_rcvd = 'Y' or col_lotw_qsl_rcvd = 'Y'))";

        $sql .= $this->addBandToQuery($postdata['band']);

        if ($postdata['includedeleted'] == NULL) {
            $sql .= " and coalesce(iota.status, '') <> 'D'";
        }

		if ($postdata['mode'] != 'All') {
			$sql .= " and (col_mode = '" . $postdata['mode'] . "' or col_submode = '" . $postdata['mode'] . "')";
		}

        $sql .= $this->addContinentsToQuery($postdata);

        $query = $this->db->query($sql);

        return $query->result();
    }

    function getIotaConfirmed($location_list, $postdata) {
        $sql = "SELECT distinct col_iota as tag FROM " . $this->config->item('table_name') . " thcv
            join iota on thcv.col_iota = iota.tag
            where station_id in (" . $location_list .
            ") and thcv.col_iota is not null
            and (col_qsl_rcvd = 'Y' or col_lotw_qsl_rcvd = 'Y')";

		if ($postdata['mode'] != 'All') {
			$sql .= " and (col_mode = '" . $postdata['mode'] . "' or col_submode = '" . $postdata['mode'] . "')";
		}

        if ($postdata['includedeleted'] == NULL) {
            $sql .= " and coalesce(iota.status, '') <> 'D'";
        }

        $sql .= $this->addContinentsToQuery($postdata);

        $sql .= $this->addBandToQuery($postdata['band']);

        $query = $this->db->query($sql);

        return $query->result();
    }

    // Made function instead of repeating this several times
    function addContinentsToQuery($postdata) {
        $sql = '';
        if ($postdata['Africa'] == NULL) {
            $sql .= " and left(tag, 2) <> 'AF'";
        }

        if ($postdata['Europe'] == NULL) {
            $sql .= " and left(tag, 2) <> 'EU'";
        }

        if ($postdata['Asia'] == NULL) {
            $sql .= " and left(tag, 2) <> 'AS'";
        }

        if ($postdata['SouthAmerica'] == NULL) {
            $sql .= " and left(tag, 2) <> 'SA'";
        }

        if ($postdata['NorthAmerica'] == NULL) {
            $sql .= " and left(tag, 2) <> 'NA'";
        }

        if ($postdata['Oceania'] == NULL) {
            $sql .= " and left(tag, 2) <> 'OC'";
        }

        if ($postdata['Antarctica'] == NULL) {
            $sql .= " and left(tag, 2) <> 'AN'";
        }
        return $sql;
    }

    /*
     * Function gets worked and confirmed summary on each band on the active stationprofile
     */
    function get_iota_summary($bands, $postdata)
    {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if (!$logbooks_locations_array) {
            return null;
        }

		$location_list = "'".implode("','",$logbooks_locations_array)."'";

        foreach ($bands as $band) {
            $worked = $this->getSummaryByBand($band, $postdata, $location_list);
            $confirmed = $this->getSummaryByBandConfirmed($band, $postdata, $location_list);
            $iotaSummary['worked'][$band] = $worked[0]->count;
            $iotaSummary['confirmed'][$band] = $confirmed[0]->count;
        }

        $workedTotal = $this->getSummaryByBand($postdata['band'], $postdata, $location_list);
        $confirmedTotal = $this->getSummaryByBandConfirmed($postdata['band'], $postdata, $location_list);

        $iotaSummary['worked']['Total'] = $workedTotal[0]->count;
        $iotaSummary['confirmed']['Total'] = $confirmedTotal[0]->count;

        return $iotaSummary;
    }

    function getSummaryByBand($band, $postdata, $location_list)
    {
        $sql = "SELECT count(distinct thcv.col_iota) as count FROM " . $this->config->item('table_name') . " thcv";
        $sql .= ' join iota on thcv.col_iota = iota.tag';

        $sql .= " where station_id in (" . $location_list . ")";

        if ($band == 'SAT') {
            $sql .= " and thcv.col_prop_mode ='" . $band . "'";
        } else if ($band == 'All') {
            $this->load->model('bands');

			$bandslots = $this->bands->get_worked_bands('iota');
	
			$bandslots_list = "'".implode("','",$bandslots)."'";
			
			$sql .= " and thcv.col_band in (" . $bandslots_list . ")" .
					" and thcv.col_prop_mode !='SAT'";
        } else {
            $sql .= " and thcv.col_prop_mode !='SAT'";
            $sql .= " and thcv.col_band ='" . $band . "'";
        }

        if ($postdata['includedeleted'] == NULL) {
            $sql .= " and coalesce(iota.status, '') <> 'D'";
        }

		if ($postdata['mode'] != 'All') {
			$sql .= " and (col_mode = '" . $postdata['mode'] . "' or col_submode = '" . $postdata['mode'] . "')";
		}

        $sql .= $this->addContinentsToQuery($postdata);

        $query = $this->db->query($sql);

        return $query->result();
    }

    function getSummaryByBandConfirmed($band, $postdata, $location_list)
    {
        $sql = "SELECT count(distinct thcv.col_iota) as count FROM " . $this->config->item('table_name') . " thcv";
        $sql .= ' join iota on thcv.col_iota = iota.tag';

        $sql .= " where station_id in (" . $location_list . ")";

        if ($band == 'SAT') {
            $sql .= " and thcv.col_prop_mode ='" . $band . "'";
        } else if ($band == 'All') {
            $this->load->model('bands');

			$bandslots = $this->bands->get_worked_bands('iota');
	
			$bandslots_list = "'".implode("','",$bandslots)."'";
			
			$sql .= " and thcv.col_band in (" . $bandslots_list . ")" .
					" and thcv.col_prop_mode !='SAT'";
        } else {
            $sql .= " and thcv.col_prop_mode !='SAT'";
            $sql .= " and thcv.col_band ='" . $band . "'";
        }

        if ($postdata['includedeleted'] == NULL) {
            $sql .= " and coalesce(iota.status, '') <> 'D'";
        }

		if ($postdata['mode'] != 'All') {
			$sql .= " and (col_mode = '" . $postdata['mode'] . "' or col_submode = '" . $postdata['mode'] . "')";
		}

        $sql .= $this->addContinentsToQuery($postdata);

        $sql .= " and (col_qsl_rcvd = 'Y' or col_lotw_qsl_rcvd = 'Y')";

        $query = $this->db->query($sql);

        return $query->result();
    }

    function addBandToQuery($band) {
        $sql = '';
        if ($band != 'All') {
            if ($band == 'SAT') {
                $sql .= " and col_prop_mode ='" . $band . "'";
            } else {
                $sql .= " and col_prop_mode !='SAT'";
                $sql .= " and col_band ='" . $band . "'";
            }
        }
        return $sql;
    }
}
?>
