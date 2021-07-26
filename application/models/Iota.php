<?php

class IOTA extends CI_Model {

    public $bandslots = array("160m"=>0,
        "80m"=>0,
        "60m"=>0,
        "40m"=>0,
        "30m"=>0,
        "20m"=>0,
        "17m"=>0,
        "15m"=>0,
        "12m"=>0,
        "10m"=>0,
        "6m" =>0,
        "4m" =>0,
        "2m" =>0,
        "70cm"=>0,
        "23cm"=>0,
        "13cm"=>0,
        "9cm"=>0,
        "6cm"=>0,
        "3cm"=>0,
        "1.25cm"=>0,
        "SAT"=>0,
    );

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    function get_worked_bands() {
        $CI =& get_instance();
        $CI->load->model('Stations');
        $station_id = $CI->Stations->find_active();

        // get all worked slots from database
        $data = $this->db->query(
            "SELECT distinct LOWER(`COL_BAND`) as `COL_BAND` FROM `".$this->config->item('table_name')."` WHERE station_id = ".$station_id." AND COL_PROP_MODE != \"SAT\""
        );
        $worked_slots = array();
        foreach($data->result() as $row){
            array_push($worked_slots, $row->COL_BAND);
        }

        $SAT_data = $this->db->query(
            "SELECT distinct LOWER(`COL_PROP_MODE`) as `COL_PROP_MODE` FROM `".$this->config->item('table_name')."` WHERE station_id = ".$station_id." AND COL_PROP_MODE = \"SAT\""
        );

        foreach($SAT_data->result() as $row){
            array_push($worked_slots, strtoupper($row->COL_PROP_MODE));
        }

        // bring worked-slots in order of defined $bandslots
        $results = array();
        foreach(array_keys($this->bandslots) as $slot) {
            if(in_array($slot, $worked_slots)) {
                array_push($results, $slot);
            }
        }
        return $results;
    }

    function get_iota_array($iotaArray, $bands, $postdata) {
        $CI =& get_instance();
        $CI->load->model('Stations');
        $station_id = $CI->Stations->find_active();

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
                $workedIota = $this->getIotaBandWorked($station_id, $band, $postdata);
                foreach ($workedIota as $wiota) {
                    $iotaMatrix[$wiota->tag][$band] = '<div class="alert-danger"><a href=\'javascript:displayContacts("'.$wiota->tag.'","'. $band . '","'. $postdata['mode'] . '","IOTA")\'>W</a></div>';
                }
            }

            // If confirmed is checked, we add confirmed iotas to the array
            if ($postdata['confirmed'] != NULL) {
                $confirmedIota = $this->getIotaBandConfirmed($station_id, $band, $postdata);
                foreach ($confirmedIota as $ciota) {
                    $iotaMatrix[$ciota->tag][$band] = '<div class="alert-success"><a href=\'javascript:displayContacts("'.$ciota->tag.'","'. $band . '","'. $postdata['mode'] . '","IOTA")\'>C</a></div>';
                }
            }
        }

        // We want to remove the worked iotas in the list, since we do not want to display them
        if ($postdata['worked'] == NULL) {
            $workedIota = $this->getIotaWorked($station_id, $postdata);
            foreach ($workedIota as $wiota) {
                if (array_key_exists($wiota->tag, $iotaMatrix)) {
                    unset($iotaMatrix[$wiota->tag]);
                }
            }
        }

        // We want to remove the confirmed iotas in the list, since we do not want to display them
        if ($postdata['confirmed'] == NULL) {
            $confirmedIOTA = $this->getIotaConfirmed($station_id, $postdata);
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

    function getIotaBandConfirmed($station_id, $band, $postdata) {
        $sql = "SELECT distinct col_iota as tag FROM " . $this->config->item('table_name') . " thcv
            join iota on thcv.col_iota = iota.tag
            where station_id = " . $station_id .
            " and thcv.col_iota is not null
            and (col_qsl_rcvd = 'Y' or col_lotw_qsl_rcvd = 'Y')";

		if ($postdata['mode'] != 'All') {
			$sql .= " and (col_mode = '" . $postdata['mode'] . "' or col_submode = '" . $postdata['mode'] . "')";
		}

        if ($band == 'SAT') {
            $sql .= " and col_prop_mode ='" . $band . "'";
        }
        else {
            $sql .= " and col_prop_mode !='SAT'";
            $sql .= " and col_band ='" . $band . "'";
        }

        if ($postdata['includedeleted'] == NULL) {
            $sql .= " and coalesce(iota.status, '') <> 'D'";
        }

        $sql .= $this->addContinentsToQuery($postdata);

        $query = $this->db->query($sql);

        return $query->result();
    }

    function getIotaBandWorked($station_id, $band, $postdata) {
        $sql = 'SELECT distinct col_iota as tag FROM ' . $this->config->item('table_name'). ' thcv
            join iota on thcv.col_iota = iota.tag
            where station_id = ' . $station_id .
            ' and thcv.col_iota is not null';

		if ($postdata['mode'] != 'All') {
			$sql .= " and (col_mode = '" . $postdata['mode'] . "' or col_submode = '" . $postdata['mode'] . "')";
		}

        if ($band == 'SAT') {
            $sql .= " and col_prop_mode ='" . $band . "'";
        }
        else {
            $sql .= " and col_prop_mode !='SAT'";
            $sql .= " and col_band ='" . $band . "'";
        }

        if ($postdata['includedeleted'] == NULL) {
            $sql .= " and coalesce(iota.status, '') <> 'D'";
        }

        $sql .= $this->addContinentsToQuery($postdata);

        $query = $this->db->query($sql);

        return $query->result();
    }

    function fetchIota($postdata) {
        $CI =& get_instance();
        $CI->load->model('Stations');
        $station_id = $CI->Stations->find_active();

        $sql = "select tag, name, prefix, dxccid, status from iota where 1=1";

        if ($postdata['includedeleted'] == NULL) {
            $sql .= " and coalesce(iota.status, '') <> 'D'";
        }

        $sql .= $this->addContinentsToQuery($postdata);

        if ($postdata['notworked'] == NULL) {
            $sql .= " and exists (select 1 from " . $this->config->item('table_name') . " where station_id = ". $station_id . " and col_iota = iota.tag";

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

    function getIotaWorked($station_id, $postdata) {
        $sql = "SELECT distinct col_iota as tag FROM " . $this->config->item('table_name') . " thcv
            join iota on thcv.col_iota = iota.tag
            where station_id = " . $station_id .
            " and thcv.col_iota is not null
            and not exists (select 1 from ". $this->config->item('table_name') . " where station_id = ". $station_id .
            " and col_iota = thcv.col_iota";

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

        $sql .= " and (col_qsl_rcvd = 'Y' or col_lotw_qsl_rcvd = 'Y'))";

        if ($postdata['band'] != 'All') {
            if ($postdata['band'] == 'SAT') {
                $sql .= " and col_prop_mode ='" . $postdata['band'] . "'";
            }
            else {
                $sql .= " and col_prop_mode !='SAT'";
                $sql .= " and col_band ='" . $postdata['band'] . "'";
            }
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

    function getIotaConfirmed($station_id, $postdata) {
        $sql = "SELECT distinct col_iota as tag FROM " . $this->config->item('table_name') . " thcv
            join iota on thcv.col_iota = iota.tag
            where station_id = " . $station_id .
            " and thcv.col_iota is not null
            and (col_qsl_rcvd = 'Y' or col_lotw_qsl_rcvd = 'Y')";

		if ($postdata['mode'] != 'All') {
			$sql .= " and (col_mode = '" . $postdata['mode'] . "' or col_submode = '" . $postdata['mode'] . "')";
		}

        if ($postdata['includedeleted'] == NULL) {
            $sql .= " and coalesce(iota.status, '') <> 'D'";
        }

        $sql .= $this->addContinentsToQuery($postdata);

        if ($postdata['band'] != 'All') {
            if ($postdata['band'] == 'SAT') {
                $sql .= " and col_prop_mode ='" . $postdata['band'] . "'";
            }
            else {
                $sql .= " and col_prop_mode !='SAT'";
                $sql .= " and col_band ='" . $postdata['band'] . "'";
            }
        }

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
    function get_iota_summary($bands)
    {
        $CI =& get_instance();
        $CI->load->model('Stations');
        $station_id = $CI->Stations->find_active();

        foreach ($bands as $band) {
            $worked = $this->getSummaryByBand($band, $station_id);
            $confirmed = $this->getSummaryByBandConfirmed($band, $station_id);
            $iotaSummary['worked'][$band] = $worked[0]->count;
            $iotaSummary['confirmed'][$band] = $confirmed[0]->count;
        }

        $workedTotal = $this->getSummaryByBand('All', $station_id);
        $confirmedTotal = $this->getSummaryByBandConfirmed('All', $station_id);

        $iotaSummary['worked']['Total'] = $workedTotal[0]->count;
        $iotaSummary['confirmed']['Total'] = $confirmedTotal[0]->count;

        return $iotaSummary;
    }

    function getSummaryByBand($band, $station_id)
    {
        $sql = "SELECT count(distinct thcv.col_iota) as count FROM " . $this->config->item('table_name') . " thcv";

        $sql .= " where station_id = " . $station_id;

        if ($band == 'SAT') {
            $sql .= " and thcv.col_prop_mode ='" . $band . "'";
        } else if ($band == 'All') {
            $sql .= " and thcv.col_prop_mode !='SAT'";
        } else {
            $sql .= " and thcv.col_prop_mode !='SAT'";
            $sql .= " and thcv.col_band ='" . $band . "'";

        }
        $query = $this->db->query($sql);

        return $query->result();
    }

    function getSummaryByBandConfirmed($band, $station_id)
    {
        $sql = "SELECT count(distinct thcv.col_iota) as count FROM " . $this->config->item('table_name') . " thcv";

        $sql .= " where station_id = " . $station_id;

        if ($band == 'SAT') {
            $sql .= " and thcv.col_prop_mode ='" . $band . "'";
        } else if ($band == 'All') {
            $sql .= " and thcv.col_prop_mode !='SAT'";
        } else {
            $sql .= " and thcv.col_prop_mode !='SAT'";
            $sql .= " and thcv.col_band ='" . $band . "'";
        }

        $sql .= " and (col_qsl_rcvd = 'Y' or col_lotw_qsl_rcvd = 'Y')";

        $query = $this->db->query($sql);

        return $query->result();
    }
}
?>
