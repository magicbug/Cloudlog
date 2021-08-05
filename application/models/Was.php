<?php

class was extends CI_Model {

    public $stateString = 'AK,AL,AR,AZ,CA,CO,CT,DE,FL,GA,HI,IA,ID,IL,IN,KS,KY,LA,MA,MD,ME,MI,MN,MO,MS,MT,NC,ND,NE,NH,NJ,NM,NV,NY,OH,OK,OR,PA,RI,SC,SD,TN,TX,UT,VA,VT,WA,WI,WV,WY';

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

    function get_was_array($bands, $postdata) {
        $CI =& get_instance();
        $CI->load->model('Stations');
        $station_id = $CI->Stations->find_active();

        $stateArray = explode(',', $this->stateString);

        $states = array(); // Used for keeping track of which states that are not worked

        foreach ($stateArray as $state) {                   // Generating array for use in the table
            $states[$state]['count'] = 0;                   // Inits each state's count
        }


        foreach ($bands as $band) {
            foreach ($stateArray as $state) {                   // Generating array for use in the table
                $bandWas[$state][$band] = '-';                  // Sets all to dash to indicate no result
            }

            if ($postdata['worked'] != NULL) {
                $wasBand = $this->getWasWorked($station_id, $band, $postdata);
                foreach ($wasBand as $line) {
                    $bandWas[$line->col_state][$band] = '<div class="alert-danger"><a href=\'javascript:displayContacts("' . $line->col_state . '","' . $band . '","'. $postdata['mode'] . '","WAS")\'>W</a></div>';
                    $states[$line->col_state]['count']++;
                }
            }
            if ($postdata['confirmed'] != NULL) {
                $wasBand = $this->getWasConfirmed($station_id, $band, $postdata);
                foreach ($wasBand as $line) {
                    $bandWas[$line->col_state][$band] = '<div class="alert-success"><a href=\'javascript:displayContacts("' . $line->col_state . '","' . $band . '","'. $postdata['mode'] . '","WAS")\'>C</a></div>';
                    $states[$line->col_state]['count']++;
                }
            }
        }

        // We want to remove the worked states in the list, since we do not want to display them
        if ($postdata['worked'] == NULL) {
            $wasBand = $this->getWasWorked($station_id, $postdata['band'], $postdata);
            foreach ($wasBand as $line) {
                unset($bandWas[$line->col_state]);
            }
        }

        // We want to remove the confirmed states in the list, since we do not want to display them
        if ($postdata['confirmed'] == NULL) {
            $wasBand = $this->getWasConfirmed($station_id, $postdata['band'], $postdata);
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
    function get_was_summary($bands)
    {
        $CI =& get_instance();
        $CI->load->model('Stations');
        $station_id = $CI->Stations->find_active();

        foreach ($bands as $band) {
            $worked = $this->getSummaryByBand($band, $station_id);
            $confirmed = $this->getSummaryByBandConfirmed($band, $station_id);
            $wasSummary['worked'][$band] = $worked[0]->count;
            $wasSummary['confirmed'][$band] = $confirmed[0]->count;
        }

        $workedTotal = $this->getSummaryByBand('All', $station_id);
        $confirmedTotal = $this->getSummaryByBandConfirmed('All', $station_id);

        $wasSummary['worked']['Total'] = $workedTotal[0]->count;
        $wasSummary['confirmed']['Total'] = $confirmedTotal[0]->count;

        return $wasSummary;
    }

    function getSummaryByBand($band, $station_id)
    {
        $sql = "SELECT count(distinct thcv.col_state) as count FROM " . $this->config->item('table_name') . " thcv";

        $sql .= " where station_id = " . $station_id;

        if ($band == 'SAT') {
            $sql .= " and thcv.col_prop_mode ='" . $band . "'";
        } else if ($band == 'All') {
            $sql .= " and thcv.col_prop_mode !='SAT'";
        } else {
            $sql .= " and thcv.col_prop_mode !='SAT'";
            $sql .= " and thcv.col_band ='" . $band . "'";
        }

        $sql .= $this->addStateToQuery();

        $query = $this->db->query($sql);

        return $query->result();
    }

    function getSummaryByBandConfirmed($band, $station_id)
    {
        $sql = "SELECT count(distinct thcv.col_state) as count FROM " . $this->config->item('table_name') . " thcv";

        $sql .= " where station_id = " . $station_id;

        if ($band == 'SAT') {
            $sql .= " and thcv.col_prop_mode ='" . $band . "'";
        } else if ($band == 'All') {
            $sql .= " and thcv.col_prop_mode !='SAT'";
        } else {
            $sql .= " and thcv.col_prop_mode !='SAT'";
            $sql .= " and thcv.col_band ='" . $band . "'";
        }

        $sql .= $this->addStateToQuery();

        $sql .= " and (col_qsl_rcvd = 'Y' or col_lotw_qsl_rcvd = 'Y')";

        $query = $this->db->query($sql);

        return $query->result();
    }

    /*
     * Function returns all worked, but not confirmed states
     * $postdata contains data from the form, in this case Lotw or QSL are used
     */
    function getWasWorked($station_id, $band, $postdata) {
        $sql = "SELECT distinct col_state FROM " . $this->config->item('table_name') . " thcv
        where station_id = " . $station_id;

		if ($postdata['mode'] != 'All') {
			$sql .= " and (col_mode = '" . $postdata['mode'] . "' or col_submode = '" . $postdata['mode'] . "')";
		}

        $sql .= $this->addStateToQuery();

        $sql .= $this->addBandToQuery($band);

        $sql .= " and not exists (select 1 from ". $this->config->item('table_name') .
            " where station_id = ". $station_id .
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
    function getWasConfirmed($station_id, $band, $postdata) {
        $sql = "SELECT distinct col_state FROM " . $this->config->item('table_name') . " thcv
            where station_id = " . $station_id;

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

    function addStateToQuery() {
        $sql = '';
        $sql .= " and COL_DXCC in ('291', '6', '110')";
        $sql .= " and COL_STATE in ('AK','AL','AR','AZ','CA','CO','CT','DE','FL','GA','HI','IA','ID','IL','IN','KS','KY','LA','MA','MD','ME','MI','MN','MO','MS','MT','NC','ND','NE','NH','NJ','NM','NV','NY','OH','OK','OR','PA','RI','SC','SD','TN','TX','UT','VA','VT','WA','WI','WV','WY')";
        return $sql;
    }
}
?>
