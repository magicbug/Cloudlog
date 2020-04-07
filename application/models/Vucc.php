<?php

class VUCC extends CI_Model
{

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

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    function get_worked_bands()
    {
        $CI =& get_instance();
        $CI->load->model('Stations');
        $station_id = $CI->Stations->find_active();

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
     * Builds the array to display worked/confirmed vucc on awward page
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

        return $vuccArray;
    }

    /*
     *  Gets the grid from col_vucc_grids
     * $band = the band chosen
     * $confirmationMethod - qsl, lotw or both, use anything else to skip confirmed
     */
    function get_vucc_summary_col_vucc($band, $confirmationMethod) {
        $station_id = $this->get_station_id();

        $sql = "select col_vucc_grids
            from " . $this->config->item('table_name') .
            " where station_id =" . $station_id .
            " and (LENGTH(col_vucc_grids) > 0) ";

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
        $station_id = $this->get_station_id();
        $sql = "select distinct upper(substring(col_gridsquare, 1, 4)) gridsquare
            from " . $this->config->item('table_name') .
            " where station_id =" . $station_id .
            " and (LENGTH(col_gridsquare) > 0)";

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
    function vucc_details($band) {
        $col_gridsquare_worked = $this->get_vucc_summary($band, 'none');

        $workedGridArray = array();
        foreach ($col_gridsquare_worked as $workedgrid) {
            array_push($workedGridArray, $workedgrid['gridsquare']);
        }

        $col_vucc_grids_worked = $this->get_vucc_summary_col_vucc($band, 'none');

        foreach ($col_vucc_grids_worked as $gridSplit) {
            $grids = explode(",", $gridSplit['col_vucc_grids']);
            foreach($grids as $key) {
                $grid_four = strtoupper(substr(trim($key),0,4));

                if(!in_array($grid_four, $workedGridArray)){
                    array_push($workedGridArray, $grid_four);
                }
            }
        }

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

        if (count($vuccBand) == 0) {
            return 0;
        } else {
            ksort($vuccBand);
            return $vuccBand;
        }
    }

    function get_station_id() {
        $CI =& get_instance();
        $CI->load->model('Stations');
        return $CI->Stations->find_active();
    }
}
?>