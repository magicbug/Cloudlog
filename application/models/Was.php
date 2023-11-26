<?php

class was extends CI_Model {

    public $stateString = 'AK,AL,AR,AZ,CA,CO,CT,DE,FL,GA,HI,IA,ID,IL,IN,KS,KY,LA,MA,MD,ME,MI,MN,MO,MS,MT,NC,ND,NE,NH,NJ,NM,NV,NY,OH,OK,OR,PA,RI,SC,SD,TN,TX,UT,VA,VT,WA,WI,WV,WY';

    function get_was_array($bands, $postdata) {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if (!$logbooks_locations_array) {
            return null;
        }

        $location_list = "'".implode("','",$logbooks_locations_array)."'";

        $stateArray = explode(',', $this->stateString);

        $states = array(); // Used for keeping track of which states that are not worked

        $qsl = "";
        if ($postdata['confirmed'] != NULL) {
            if ($postdata['qsl'] != NULL ) {
                $qsl .= "Q";
            }
            if ($postdata['lotw'] != NULL ) {
                $qsl .= "L";
            }
            if ($postdata['eqsl'] != NULL ) {
                $qsl .= "E";
            }
        }

        foreach ($stateArray as $state) {                   // Generating array for use in the table
            $states[$state]['count'] = 0;                   // Inits each state's count
        }


        foreach ($bands as $band) {
            foreach ($stateArray as $state) {                   // Generating array for use in the table
                $bandWas[$state][$band] = '-';                  // Sets all to dash to indicate no result
            }

            if ($postdata['worked'] != NULL) {
                $wasBand = $this->getWasWorked($location_list, $band, $postdata);
                foreach ($wasBand as $line) {
                    $bandWas[$line->col_state][$band] = '<div class="bg-danger awardsBgDanger"><a href=\'javascript:displayContacts("' . $line->col_state . '","' . $band . '","'. $postdata['mode'] . '","WAS", "")\'>W</a></div>';
                    $states[$line->col_state]['count']++;
                }
            }
            if ($postdata['confirmed'] != NULL) {
                $wasBand = $this->getWasConfirmed($location_list, $band, $postdata);
                foreach ($wasBand as $line) {
                    $bandWas[$line->col_state][$band] = '<div class="bg-success awardsBgSuccess"><a href=\'javascript:displayContacts("' . $line->col_state . '","' . $band . '","'. $postdata['mode'] . '","WAS", "'.$qsl.'")\'>C</a></div>';
                    $states[$line->col_state]['count']++;
                }
            }
        }

        // We want to remove the worked states in the list, since we do not want to display them
        if ($postdata['worked'] == NULL) {
            $wasBand = $this->getWasWorked($location_list, $postdata['band'], $postdata);
            foreach ($wasBand as $line) {
                unset($bandWas[$line->col_state]);
            }
        }

        // We want to remove the confirmed states in the list, since we do not want to display them
        if ($postdata['confirmed'] == NULL) {
            $wasBand = $this->getWasConfirmed($location_list, $postdata['band'], $postdata);
            foreach ($wasBand as $line) {
                unset($bandWas[$line->col_state]);
            }
        }

        if ($postdata['notworked'] == NULL) {
            foreach ($stateArray as $state) {
                if ($states[$state]['count'] == 0) {
                    unset($bandWas[$state]);
                };
            }
        }

        if (isset($bandWas)) {
            return $bandWas;
        }
        else {
            return 0;
        }
    }

    /*
     * Function gets worked and confirmed summary on each band on the active stationprofile
     */
    function get_was_summary($bands, $postdata)
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
            $wasSummary['worked'][$band] = $worked[0]->count;
            $wasSummary['confirmed'][$band] = $confirmed[0]->count;
        }

        $workedTotal = $this->getSummaryByBand($postdata['band'], $postdata, $location_list);
        $confirmedTotal = $this->getSummaryByBandConfirmed($postdata['band'], $postdata, $location_list);

        $wasSummary['worked']['Total'] = $workedTotal[0]->count;
        $wasSummary['confirmed']['Total'] = $confirmedTotal[0]->count;

        return $wasSummary;
    }

    function getSummaryByBand($band, $postdata, $location_list)
    {
        $sql = "SELECT count(distinct thcv.col_state) as count FROM " . $this->config->item('table_name') . " thcv";

        $sql .= " where station_id in (" . $location_list . ")";

        if ($band == 'SAT') {
            $sql .= " and thcv.col_prop_mode ='" . $band . "'";
        } else if ($band == 'All') {
            $this->load->model('bands');

			$bandslots = $this->bands->get_worked_bands('was');
	
			$bandslots_list = "'".implode("','",$bandslots)."'";
			
			$sql .= " and thcv.col_band in (" . $bandslots_list . ")" .
					" and thcv.col_prop_mode !='SAT'";
        } else {
            $sql .= " and thcv.col_prop_mode !='SAT'";
            $sql .= " and thcv.col_band ='" . $band . "'";
        }

        if ($postdata['mode'] != 'All') {
			$sql .= " and (col_mode = '" . $postdata['mode'] . "' or col_submode = '" . $postdata['mode'] . "')";
		}

        $sql .= $this->addStateToQuery();

        $query = $this->db->query($sql);

        return $query->result();
    }

    function getSummaryByBandConfirmed($band, $postdata, $location_list)
    {
        $sql = "SELECT count(distinct thcv.col_state) as count FROM " . $this->config->item('table_name') . " thcv";

        $sql .= " where station_id in (" . $location_list . ")";

        if ($band == 'SAT') {
            $sql .= " and thcv.col_prop_mode ='" . $band . "'";
        } else if ($band == 'All') {
            $this->load->model('bands');

			$bandslots = $this->bands->get_worked_bands('was');
	
			$bandslots_list = "'".implode("','",$bandslots)."'";
			
			$sql .= " and thcv.col_band in (" . $bandslots_list . ")" .
					" and thcv.col_prop_mode !='SAT'";
        } else {
            $sql .= " and thcv.col_prop_mode !='SAT'";
            $sql .= " and thcv.col_band ='" . $band . "'";
        }

        if ($postdata['mode'] != 'All') {
			$sql .= " and (col_mode = '" . $postdata['mode'] . "' or col_submode = '" . $postdata['mode'] . "')";
		}

        $sql .= $this->addQslToQuery($postdata);

        $sql .= $this->addStateToQuery();

        $query = $this->db->query($sql);

        return $query->result();
    }

    /*
     * Function returns all worked, but not confirmed states
     * $postdata contains data from the form, in this case Lotw or QSL are used
     */
    function getWasWorked($location_list, $band, $postdata) {
        $sql = "SELECT distinct col_state FROM " . $this->config->item('table_name') . " thcv
        where station_id in (" . $location_list . ")";

		if ($postdata['mode'] != 'All') {
			$sql .= " and (col_mode = '" . $postdata['mode'] . "' or col_submode = '" . $postdata['mode'] . "')";
		}

        $sql .= $this->addStateToQuery();

        $sql .= $this->addBandToQuery($band);

        $sql .= " and not exists (select 1 from ". $this->config->item('table_name') .
            " where station_id in (". $location_list . ")" .
            " and col_state = thcv.col_state";

		if ($postdata['mode'] != 'All') {
			$sql .= " and (col_mode = '" . $postdata['mode'] . "' or col_submode = '" . $postdata['mode'] . "')";
		}

        $sql .= $this->addBandToQuery($band);

        $sql .= $this->addQslToQuery($postdata);

        $sql .= $this->addStateToQuery();

        $sql .= ")";

        $query = $this->db->query($sql);

        return $query->result();
    }

    /*
     * Function returns all confirmed states on given band and on LoTW or QSL
     * $postdata contains data from the form, in this case Lotw or QSL are used
     */
    function getWasConfirmed($location_list, $band, $postdata) {
        $sql = "SELECT distinct col_state FROM " . $this->config->item('table_name') . " thcv
            where station_id in (" . $location_list . ")";

		if ($postdata['mode'] != 'All') {
			$sql .= " and (col_mode = '" . $postdata['mode'] . "' or col_submode = '" . $postdata['mode'] . "')";
		}

        $sql .= $this->addStateToQuery();

        $sql .= $this->addBandToQuery($band);

        $sql .= $this->addQslToQuery($postdata);

        $query = $this->db->query($sql);

        return $query->result();
    }

    function addQslToQuery($postdata) {
        $sql = '';
        $qsl = array();
        if ($postdata['lotw'] != NULL || $postdata['qsl'] != NULL || $postdata['eqsl'] != NULL) {
            $sql .= ' and (';
            if ($postdata['qsl'] != NULL) {
                array_push($qsl, "col_qsl_rcvd = 'Y'");
            }
            if ($postdata['lotw'] != NULL) {
                array_push($qsl, "col_lotw_qsl_rcvd = 'Y'");
            }
            if ($postdata['eqsl'] != NULL) {
                array_push($qsl, "col_eqsl_qsl_rcvd = 'Y'");
            }
            $sql .= implode(' or ', $qsl);
            $sql .= ')';
        }
        return $sql;
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
        } else {
            $this->load->model('bands');

			$bandslots = $this->bands->get_worked_bands('was');
	
			$bandslots_list = "'".implode("','",$bandslots)."'";
			
			$sql .= " and col_band in (" . $bandslots_list . ")" .
					" and col_prop_mode !='SAT'";
        }
        return $sql;
    }

    function addStateToQuery() {
        $sql = '';
        $sql .= " and COL_DXCC in ('291', '6', '110')";
        $sql .= " and COL_STATE in ('AK','AL','AR','AZ','CA','CO','CT','DE','FL','GA','HI','IA','ID','IL','IN','KS','KY','LA','MA','MD','ME','MI','MN','MO','MS','MT','NC','ND','NE','NH','NJ','NM','NV','NY','OH','OK','OR','PA','RI','SC','SD','TN','TX','UT','VA','VT','WA','WI','WV','WY')";
        return $sql;
    }
}
?>
