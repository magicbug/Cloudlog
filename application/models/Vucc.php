<?php

class VUCC extends CI_Model
{
    /*
     *  Fetches worked and confirmed gridsquare on each band and total
     */
    function get_vucc_array($data) {
        $vuccArray = $this->fetchVucc($data);

        if (isset($vuccArray)) {
            return $vuccArray;
        } else {
            return 0;
        }
    }

    /*
     * Builds the array to display worked/confirmed vucc on award page
     */
    function fetchVucc($data) {
        $totalGridConfirmed = array();
        $totalGridWorked = array();

        foreach($data['worked_bands'] as $band) {

            // Getting all the worked grids
            $col_gridsquare_worked = $this->get_vucc_summary($band, 'none');

            $workedGridArray = array();
            foreach ($col_gridsquare_worked as $workedgrid) {
                array_push($workedGridArray, $workedgrid['gridsquare']);
                if(!in_array($workedgrid['gridsquare'], $totalGridWorked)){
                    array_push($totalGridWorked, $workedgrid['gridsquare']);
                }
            }

            $col_vucc_grids_worked = $this->get_vucc_summary_col_vucc($band, 'none');

            foreach ($col_vucc_grids_worked as $gridSplit) {
                $grids = explode(",", $gridSplit['col_vucc_grids']);
                foreach($grids as $key) {
                    $grid_four = strtoupper(substr(trim($key),0,4));

                    if(!in_array($grid_four, $workedGridArray)){
                        array_push($workedGridArray, $grid_four);
                    }

                    if(!in_array($grid_four, $totalGridWorked)){
                        array_push($totalGridWorked, $grid_four);
                    }
                }
            }

            // Getting all the confirmed grids
            $col_gridsquare_confirmed = $this->get_vucc_summary($band, 'both');

            $confirmedGridArray = array();
            foreach ($col_gridsquare_confirmed as $confirmedgrid) {
                array_push($confirmedGridArray, $confirmedgrid['gridsquare']);
                if(!in_array($confirmedgrid['gridsquare'], $totalGridConfirmed)){
                    array_push($totalGridConfirmed, $confirmedgrid['gridsquare']);
                }
            }

            $col_vucc_grids_confirmed = $this->get_vucc_summary_col_vucc($band, 'both');

            foreach ($col_vucc_grids_confirmed as $gridSplit) {
                $grids = explode(",", $gridSplit['col_vucc_grids']);
                foreach($grids as $key) {
                    $grid_four = strtoupper(substr(trim($key),0,4));

                    if(!in_array($grid_four, $confirmedGridArray)){
                        array_push($confirmedGridArray, $grid_four);
                    }

                    if(!in_array($grid_four, $totalGridConfirmed)){
                        array_push($totalGridConfirmed, $grid_four);
                    }
                }
            }

            $vuccArray[$band]['worked'] = count($workedGridArray);
            $vuccArray[$band]['confirmed'] = count($confirmedGridArray);
        }

        $vuccArray['All']['worked'] = count($totalGridWorked);
        $vuccArray['All']['confirmed'] = count($totalGridConfirmed);

        if ($vuccArray['All']['worked'] == 0) {
            return null;
        }

        return $vuccArray;
    }

    /*
     *  Gets the grid from col_vucc_grids
     * $band = the band chosen
     * $confirmationMethod - qsl, lotw or both, use anything else to skip confirmed
     */
    function get_vucc_summary_col_vucc($band, $confirmationMethod) {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if (!$logbooks_locations_array) {
            return null;
        }

		$location_list = "'".implode("','",$logbooks_locations_array)."'";

        $sql = "select col_vucc_grids
            from " . $this->config->item('table_name') .
            " where station_id in (" . $location_list . ")" .
            " and col_vucc_grids <> '' ";

        if ($confirmationMethod == 'both') {
            $sql .= " and (col_qsl_rcvd='Y' or col_lotw_qsl_rcvd='Y')";
        }
        else if ($confirmationMethod == 'qsl') {
            $sql .= " and col_qsl_rcvd='Y'";
        }
        else if ($confirmationMethod == 'lotw') {
            $sql .= " and col_lotw_qsl_rcvd='Y'";
        }

        if ($band != 'All') {
            if ($band == 'SAT') {
                $sql .= " and col_prop_mode ='" . $band . "'";
            } else {
                $sql .= " and col_prop_mode !='SAT'";
                $sql .= " and col_band ='" . $band . "'";
            }
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*
     * Gets the grid from col_gridsquare
     * $band = the band chosen
     * $confirmationMethod - qsl, lotw or both, use anything else to skip confirmed
     */
    function get_vucc_summary($band, $confirmationMethod) {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if (!$logbooks_locations_array) {
            return null;
        }

		$location_list = "'".implode("','",$logbooks_locations_array)."'";

        $sql = "select distinct upper(substring(col_gridsquare, 1, 4)) gridsquare
            from " . $this->config->item('table_name') .
            " where station_id in (" . $location_list . ")" .
            " and col_gridsquare <> ''";

        if ($confirmationMethod == 'both') {
            $sql .= " and (col_qsl_rcvd='Y' or col_lotw_qsl_rcvd='Y')";
        }
        else if ($confirmationMethod == 'qsl') {
            $sql .= " and col_qsl_rcvd='Y'";
        }
        else if ($confirmationMethod == 'lotw') {
            $sql .= " and col_lotw_qsl_rcvd='Y'";
        }

        if ($band != 'All') {
            if ($band == 'SAT') {
                $sql .= " and col_prop_mode ='" . $band . "'";
            } else {
                $sql .= " and col_prop_mode !='SAT'";
                $sql .= " and col_band ='" . $band . "'";
            }
        }

        $query = $this->db->query($sql);

        return $query->result_array();
    }

    /*
     * Makes a list of all gridsquares on chosen band with info about lotw and qsl
     */
    function vucc_details($band, $type) {

        if ($type == 'worked') {
            $workedGridArray = $this->getWorkedGridsList($band, 'none');
            $vuccBand = $this->removeConfirmedGrids($band, $workedGridArray);
        } else if ($type == 'confirmed') {
            $workedGridArray = $this->getWorkedGridsList($band, 'both');
            $vuccBand = $this->markConfirmedGrids($band, $workedGridArray);
        } else {
            $workedGridArray = $this->getWorkedGridsList($band, 'none');
            $vuccBand = $this->markConfirmedGrids($band, $workedGridArray);
        }

        if (!isset($vuccBand)) {
            return 0;
        } else {
            ksort($vuccBand);
            return $vuccBand;
        }
    }

    function removeConfirmedGrids($band, $workedGridArray) {
        $vuccDataQsl = $this->get_vucc_summary($band, 'qsl');

        foreach ($vuccDataQsl as $grid) {
            if (($key = array_search($grid['gridsquare'], $workedGridArray)) !== false) {
                unset($workedGridArray[$key]);
            }
        }

        $vuccDataLotw = $this->get_vucc_summary($band, 'lotw');

        foreach ($vuccDataLotw as $grid) {
            if (($key = array_search($grid['gridsquare'], $workedGridArray)) !== false) {
                unset($workedGridArray[$key]);
            }
        }

        $col_vucc_grids_confirmed_qsl = $this->get_vucc_summary_col_vucc($band, 'lotw');

        foreach ($col_vucc_grids_confirmed_qsl as $gridSplit) {
            $grids = explode(",", $gridSplit['col_vucc_grids']);
            foreach($grids as $key) {
                $grid_four = strtoupper(substr(trim($key),0,4));
                if (($key = array_search($grid_four, $workedGridArray)) !== false) {
                    unset($workedGridArray[$key]);
                }
            }
        }

        $col_vucc_grids_confirmed_lotw = $this->get_vucc_summary_col_vucc($band, 'qsl');

        foreach ($col_vucc_grids_confirmed_lotw as $gridSplit) {
            $grids = explode(",", $gridSplit['col_vucc_grids']);
            foreach($grids as $key) {
                $grid_four = strtoupper(substr(trim($key),0,4));
                if (($key = array_search($grid_four, $workedGridArray)) !== false) {
                    unset($workedGridArray[$key]);
                }
            }
        }
        foreach ($workedGridArray as $grid) {
            $this->load->model('logbook_model');
            $result = $this->logbook_model->vucc_qso_details($grid, $band);
            $callsignlist = '';
            foreach($result->result() as $call) {
                $callsignlist .= $call->COL_CALL . '<br/>';
            }
            $vuccBand[$grid]['call'] = $callsignlist;
        }

        if (isset($vuccBand)) {
            return $vuccBand;
        } else {
            return null;
        }
    }

    function markConfirmedGrids($band, $workedGridArray) {
        foreach ($workedGridArray as $grid) {
            $vuccBand[$grid]['qsl'] = '';
            $vuccBand[$grid]['lotw'] = '';
        }

        $vuccDataQsl = $this->get_vucc_summary($band, 'qsl');

        foreach ($vuccDataQsl as $grid) {
            $vuccBand[$grid['gridsquare']]['qsl'] = 'Y';
        }

        $vuccDataLotw = $this->get_vucc_summary($band, 'lotw');

        foreach ($vuccDataLotw as $grid) {
            $vuccBand[$grid['gridsquare']]['lotw'] = 'Y';
        }

        $col_vucc_grids_confirmed_qsl = $this->get_vucc_summary_col_vucc($band, 'lotw');

        foreach ($col_vucc_grids_confirmed_qsl as $gridSplit) {
            $grids = explode(",", $gridSplit['col_vucc_grids']);
            foreach($grids as $key) {
                $grid_four = strtoupper(substr(trim($key),0,4));
                $vuccBand[$grid_four]['lotw'] = 'Y';
            }
        }

        $col_vucc_grids_confirmed_lotw = $this->get_vucc_summary_col_vucc($band, 'qsl');

        foreach ($col_vucc_grids_confirmed_lotw as $gridSplit) {
            $grids = explode(",", $gridSplit['col_vucc_grids']);
            foreach($grids as $key) {
                $grid_four = strtoupper(substr(trim($key),0,4));
                $vuccBand[$grid_four]['qsl'] = 'Y';
            }
        }

        return $vuccBand;
    }

    function getWorkedGridsList($band, $confirmationMethod) {

        $col_gridsquare_worked = $this->get_vucc_summary($band, $confirmationMethod);

        $workedGridArray = array();
        foreach ($col_gridsquare_worked as $workedgrid) {
            array_push($workedGridArray, $workedgrid['gridsquare']);
        }

        $col_vucc_grids_worked = $this->get_vucc_summary_col_vucc($band, $confirmationMethod);

        foreach ($col_vucc_grids_worked as $gridSplit) {
            $grids = explode(",", $gridSplit['col_vucc_grids']);
            foreach($grids as $key) {
                $grid_four = strtoupper(substr(trim($key),0,4));

                if(!in_array($grid_four, $workedGridArray)){
                    array_push($workedGridArray, $grid_four);
                }
            }
        }

        return $workedGridArray;
    }

    /*
    * Builds the array to display worked/confirmed vucc on dashboard page
    */
    function fetchVuccSummary() {
        $totalGridConfirmed = array();
        $totalGridWorked = array();
    
            // Getting all the worked grids
            $col_gridsquare_worked = $this->get_vucc_summary('All', 'none');
    
            $workedGridArray = array();
            if ($col_gridsquare_worked != null) {
                foreach ($col_gridsquare_worked as $workedgrid) {
                    array_push($workedGridArray, $workedgrid['gridsquare']);
                    if(!in_array($workedgrid['gridsquare'], $totalGridWorked)){
                        array_push($totalGridWorked, $workedgrid['gridsquare']);
                    }
                }
            }
    
            $col_vucc_grids_worked = $this->get_vucc_summary_col_vucc('All', 'none');
    
            if ($col_vucc_grids_worked != null) {
                foreach ($col_vucc_grids_worked as $gridSplit) {
                    $grids = explode(",", $gridSplit['col_vucc_grids']);
                    foreach($grids as $key) {
                        $grid_four = strtoupper(substr(trim($key),0,4));
        
                        if(!in_array($grid_four, $workedGridArray)){
                            array_push($workedGridArray, $grid_four);
                        }
        
                        if(!in_array($grid_four, $totalGridWorked)){
                            array_push($totalGridWorked, $grid_four);
                        }
                    }
                }
            }
    
            // Getting all the confirmed grids
            $col_gridsquare_confirmed = $this->get_vucc_summary('All', 'both');
    
            if ($col_gridsquare_confirmed != null) {
                $confirmedGridArray = array();
                foreach ($col_gridsquare_confirmed as $confirmedgrid) {
                    array_push($confirmedGridArray, $confirmedgrid['gridsquare']);
                    if(!in_array($confirmedgrid['gridsquare'], $totalGridConfirmed)){
                        array_push($totalGridConfirmed, $confirmedgrid['gridsquare']);
                    }
                }
            }
    
            $col_vucc_grids_confirmed = $this->get_vucc_summary_col_vucc('All', 'both');
    
            if ($col_vucc_grids_confirmed != null) {
                foreach ($col_vucc_grids_confirmed as $gridSplit) {
                    $grids = explode(",", $gridSplit['col_vucc_grids']);
                    foreach($grids as $key) {
                        $grid_four = strtoupper(substr(trim($key),0,4));
        
                        if(!in_array($grid_four, $confirmedGridArray)){
                            array_push($confirmedGridArray, $grid_four);
                        }
        
                        if(!in_array($grid_four, $totalGridConfirmed)){
                            array_push($totalGridConfirmed, $grid_four);
                        }
                    }
                }
            }
    
        $vuccArray['All']['worked'] = count($totalGridWorked);
        $vuccArray['All']['confirmed'] = count($totalGridConfirmed);
    
        return $vuccArray;
    }
}
?>
