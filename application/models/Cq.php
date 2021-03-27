<?php

class CQ extends CI_Model{

    function __construct(){
        // Call the Model constructor
        parent::__construct();
    }

    function get_zones($station_id){
        $data = $this->db->query(
            "select COL_CQZ, count(COL_CQZ)
            from ".$this->config->item('table_name')."
            where COL_CQZ is not null and station_id = ".$station_id."
            group by COL_CQZ order by COL_CQZ"
        );

        return $data->result();
    }

    public $bandslots = array("160m" => 0,
        "80m" => 0,
        "60m" => 0,
        "40m" => 0,
        "30m" => 0,
        "20m" => 0,
        "17m" => 0,
        "15m" => 0,
        "12m" => 0,
        "10m" => 0,
        "6m" => 0,
        "4m" => 0,
        "2m" => 0,
        "70cm" => 0,
        "23cm" => 0,
        "13cm" => 0,
        "9cm" => 0,
        "6cm" => 0,
        "3cm" => 0,
        "1.25cm" => 0,
        "SAT" => 0,
    );

    function get_worked_bands($station_id)
    {
        // get all worked slots from database
        $data = $this->db->query(
            "SELECT distinct LOWER(`COL_BAND`) as `COL_BAND` FROM `" . $this->config->item('table_name') . "` WHERE station_id = " . $station_id . " AND COL_PROP_MODE != \"SAT\""
        );
        $worked_slots = array();
        foreach ($data->result() as $row) {
            array_push($worked_slots, $row->COL_BAND);
        }

        $SAT_data = $this->db->query(
            "SELECT distinct LOWER(`COL_PROP_MODE`) as `COL_PROP_MODE` FROM `" . $this->config->item('table_name') . "` WHERE station_id = " . $station_id . " AND COL_PROP_MODE = \"SAT\""
        );

        foreach ($SAT_data->result() as $row) {
            array_push($worked_slots, strtoupper($row->COL_PROP_MODE));
        }

        // bring worked-slots in order of defined $bandslots
        $results = array();
        foreach (array_keys($this->bandslots) as $slot) {
            if (in_array($slot, $worked_slots)) {
                array_push($results, $slot);
            }
        }
        return $results;
    }

    function get_cq_array($bands, $postdata, $station_id) {
        $cqZ = array(); // Used for keeping track of which zones that are not worked

        for ($i = 1; $i <= 40; $i++) {
            $cqZ[$i]['count'] = 0;                   // Inits each cq zone's count
        }

        foreach ($bands as $band) {
            for ($i = 1; $i <= 40; $i++) {
                $bandCq[$i][$band] = '-';                  // Sets all to dash to indicate no result
            }
        }

		if ($postdata['worked'] != NULL) {
			$cqBand = $this->getCQWorked($station_id, $postdata);
			foreach ($cqBand as $line) {
				$bandCq[$line->col_cqz][$line->col_band] = '<div class="alert-danger"><a href=\'javascript:displayCqContacts("' . str_replace("&", "%26", $line->col_cqz) . '","' . $line->col_band . '")\'>W</a></div>';
				$cqZ[$line->col_cqz]['count']++;
			}
		}

		if ($postdata['confirmed'] != NULL) {
			$cqBand = $this->getCQConfirmed($station_id, $postdata);
			foreach ($cqBand as $line) {
				$bandCq[$line->col_cqz][$line->col_band] = '<div class="alert-success"><a href=\'javascript:displayCqContacts("' . str_replace("&", "%26", $line->col_cqz) . '","' . $line->col_band . '")\'>C</a></div>';
				$cqZ[$line->col_cqz]['count']++;
			}
		}

        // We want to remove the worked zones in the list, since we do not want to display them
        if ($postdata['worked'] == NULL) {
            $cqBand = $this->getCQWorked($station_id, $postdata);
            foreach ($cqBand as $line) {
                unset($bandCq[$line->col_cqz]);
            }
        }

        // We want to remove the confirmed zones in the list, since we do not want to display them
        if ($postdata['confirmed'] == NULL) {
            $cqBand = $this->getCQConfirmed($station_id, $postdata);
            foreach ($cqBand as $line) {
                unset($bandCq[$line->col_cqz]);
            }
        }

        if ($postdata['notworked'] == NULL) {
            for ($i = 1; $i <= 40; $i++) {
                if ($cqZ[$i]['count'] == 0) {
                    unset($bandCq[$i]);
                };
            }
        }

        if (isset($bandCq)) {
            return $bandCq;
        } else {
            return 0;
        }
    }

	/*
	 * Function returns all worked, but not confirmed states
	 * $postdata contains data from the form, in this case Lotw or QSL are used
	 */
	function getCQWorked($station_id, $postdata) {
		$sql = "SELECT distinct col_cqz, col_band FROM " . $this->config->item('table_name') .
			" thcv where station_id = " . $station_id . " and col_cqz <> ''";

		$sql .= $this->addBandToQuery($postdata['band']);

		$sql .= " and not exists (select 1 from " . $this->config->item('table_name') .
			" where station_id = " . $station_id .
			" and col_cqz = thcv.col_cqz and col_cqz <> '' and col_band = thcv.col_band ";

		$sql .= $this->addBandToQuery($postdata['band']);

		$sql .= $this->addQslToQuery($postdata);

		$sql .= ")";

		$sql .= " union SELECT distinct col_cqz, 'SAT' as col_band FROM " . $this->config->item('table_name') .
			" thcv where station_id = " . $station_id . " and col_cqz <> ''";

		$sql .= $this->addBandToQuery('SAT');

		$sql .= " and not exists (select 1 from " . $this->config->item('table_name') .
			" where station_id = " . $station_id .
			" and col_cqz = thcv.col_cqz and col_cqz <> '' ";

		$sql .= $this->addBandToQuery('SAT');

		$sql .= $this->addQslToQuery($postdata);

		$sql .= ")";

		$query = $this->db->query($sql);
		return $query->result();
	}

	/*
	* Function returns all confirmed states on given band and on LoTW or QSL
	* $postdata contains data from the form, in this case Lotw or QSL are used
	*/
	function getCQConfirmed($station_id, $postdata) {
		$sql = "SELECT distinct col_cqz, col_band FROM " . $this->config->item('table_name') . " thcv
        where station_id = " . $station_id . " and col_cqz <> ''";

		$sql .= $this->addBandToQuery($postdata['band']);

		$sql .= $this->addQslToQuery($postdata);

		$sql .= " union SELECT distinct col_cqz, 'SAT' as col_band FROM " . $this->config->item('table_name') . " thcv
        where station_id = " . $station_id . " and col_cqz <> ''";

		$sql .= $this->addBandToQuery('SAT');

		$sql .= $this->addQslToQuery($postdata);

		$query = $this->db->query($sql);

		return $query->result();
	}

    function addQslToQuery($postdata) {
        $sql = '';
        if ($postdata['lotw'] != NULL and $postdata['qsl'] == NULL) {
            $sql .= " and col_lotw_qsl_rcvd = 'Y'";
        }

        if ($postdata['qsl'] != NULL and $postdata['lotw'] == NULL) {
            $sql .= " and col_qsl_rcvd = 'Y'";
        }

        if ($postdata['qsl'] != NULL && $postdata['lotw'] != NULL) {
            $sql .= " and (col_qsl_rcvd = 'Y' or col_lotw_qsl_rcvd = 'Y')";
        }
        return $sql;
    }

    function addBandToQuery($band) {
		if ($band == 'SAT') {
			return " and col_prop_mode ='" . $band . "'";
		}

		if ($band == 'All') {
			return " and col_prop_mode !='SAT'";
		}

        return " and col_prop_mode !='SAT' and col_band ='" . $band . "'";
    }

    /*
    * Function gets worked and confirmed summary on each band on the active stationprofile
    */
    function get_cq_summary($bands, $station_id) {
        foreach ($bands as $band) {
            $cqSummary['worked'][$band] = 0;
            $cqSummary['confirmed'][$band] = 0;
        }

		$worked = $this->getSummaryByBand($station_id);

		foreach ($worked as $w) {
			$cqSummary['worked'][$w->col_band] = $w->count;
		}

		$confirmed = $this->getSummaryByBandConfirmed($station_id);

		foreach ($confirmed as $c) {
			$cqSummary['confirmed'][$c->col_band] = $c->count;
		}

        $workedTotal = $this->getSummaryTotal($station_id);
        $confirmedTotal = $this->getSummaryTotalConfirmed($station_id);

        $cqSummary['worked']['Total'] = $workedTotal[0]->count;
        $cqSummary['confirmed']['Total'] = $confirmedTotal[0]->count;

        return $cqSummary;
    }

    function getSummaryByBand($station_id) {
        $sql = "SELECT count(distinct thcv.col_cqz) as count, col_band FROM " . $this->config->item('table_name') . " thcv" .
				" where station_id = " . $station_id .
				" and thcv.col_prop_mode !='SAT'" .
				" group by col_band";

		$sql .= " union SELECT count(distinct thcv.col_cqz) as count, 'SAT' as col_band FROM " . $this->config->item('table_name') . " thcv" .
			" where station_id = " . $station_id .
			" and thcv.col_prop_mode ='SAT'";

        $query = $this->db->query($sql);

        return $query->result();
    }

	function getSummaryTotal($station_id) {
		$sql = "SELECT count(distinct thcv.col_cqz) as count" .
			" FROM " . $this->config->item('table_name') . " thcv" .
			" where station_id = " . $station_id .
			" and thcv.col_prop_mode !='SAT'";

		$query = $this->db->query($sql);

		return $query->result();
	}

    function getSummaryByBandConfirmed($station_id){
		$sql = "SELECT count(distinct thcv.col_cqz) as count, col_band FROM " . $this->config->item('table_name') . " thcv" .
			" where station_id = " . $station_id .
			" and thcv.col_prop_mode !='SAT'" .
			" and (col_qsl_rcvd = 'Y' or col_lotw_qsl_rcvd = 'Y')" .
			" group by col_band";

		$sql .= " union SELECT count(distinct thcv.col_cqz) as count, 'SAT' as col_band FROM " . $this->config->item('table_name') . " thcv" .
			" where station_id = " . $station_id .
			" and thcv.col_prop_mode ='SAT'" .
			" and (col_qsl_rcvd = 'Y' or col_lotw_qsl_rcvd = 'Y')";

        $query = $this->db->query($sql);

        return $query->result();
    }

	function getSummaryTotalConfirmed($station_id){
		$sql = "SELECT count(distinct thcv.col_cqz) as count" .
				" FROM " . $this->config->item('table_name') . " thcv" .
				" where station_id = " . $station_id .
				" and (col_qsl_rcvd = 'Y' or col_lotw_qsl_rcvd = 'Y')" .
				" and thcv.col_prop_mode !='SAT'";

		$query = $this->db->query($sql);

		return $query->result();
	}

}
