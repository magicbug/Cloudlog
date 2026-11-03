<?php

class Counties extends CI_Model
{

    const STATE_EXPRESSION = "UPPER(TRIM(CASE WHEN COALESCE(COL_STATE, '') <> '' THEN COL_STATE WHEN COL_CNTY REGEXP '^[A-Za-z]{2},' THEN SUBSTRING_INDEX(COL_CNTY, ',', 1) WHEN COL_DXCC = 6 THEN 'AK' WHEN COL_DXCC = 110 THEN 'HI' ELSE '' END))";
    const COUNTY_EXPRESSION = "TRIM(SUBSTRING_INDEX(COL_CNTY, ',', -1))";

    public $mode_groups = array('SSB', 'CW', 'Digital');

    function __construct() {
        $this->load->library('usaca');
    }

    /*
     * Worked and confirmed MARAC USA-CA counties for the active logbook.
     * Confirmation is not required by MARAC; it is shown for information and includes QSL, eQSL and LoTW.
     * Satellite QSOs do not count.
     */
    function get_counties_array() {
        $rows = $this->fetch_rows();
        if ($rows === null) {
            return 0;
        }

        $targets = $this->usaca->state_targets();
        $summary = array(
            'states' => array(),
            'worked' => 0,
            'confirmed' => 0,
            'target' => $this->usaca->total(),
            'tiers' => $this->usaca->tiers,
            'bands' => array(),
            'modes' => array(),
            'city_credits' => array(),
            'unknown' => array(),
        );
        foreach ($targets as $state => $target) {
            $summary['states'][$state] = array('worked' => 0, 'confirmed' => 0, 'target' => $target);
        }

        $worked = $confirmed = array();
        $band_worked = $band_confirmed = array();
        $mode_worked = $mode_confirmed = array();
        $cities = array();

        foreach ($rows as $row) {
            $mapped = $this->usaca->map($row['state'], $row['county']);
            if ($mapped === null) {
                continue;
            }
            $is_confirmed = (int)$row['confirmed'] === 1;

            if ($mapped['type'] === 'city') {
                $id = $row['state'] . '|' . $mapped['name'];
                if (!isset($cities[$id])) {
                    $cities[$id] = array('state' => $row['state'], 'city' => $mapped['name'], 'neighbors' => $mapped['neighbors'], 'confirmed' => false);
                }
                $cities[$id]['confirmed'] = $cities[$id]['confirmed'] || $is_confirmed;
                continue;
            }
            if ($mapped['type'] === 'unknown') {
                $summary['unknown'][$row['state'] . '|' . strtolower($mapped['name'])] = $row['state'] . ', ' . $mapped['name'];
                continue;
            }

            $id = $row['state'] . '|' . $mapped['name'];
            $band = strtolower($row['band']);
            $mode = $this->mode_group($row['mode'], $row['submode']);

            $worked[$id] = $row['state'];
            $band_worked[$band][$id] = true;
            if ($mode !== null) {
                $mode_worked[$mode][$id] = true;
            }
            if ($is_confirmed) {
                $confirmed[$id] = $row['state'];
                $band_confirmed[$band][$id] = true;
                if ($mode !== null) {
                    $mode_confirmed[$mode][$id] = true;
                }
            }
        }

        foreach ($worked as $state) {
            $summary['states'][$state]['worked']++;
        }
        foreach ($confirmed as $state) {
            $summary['states'][$state]['confirmed']++;
        }
        $summary['worked'] = count($worked);
        $summary['confirmed'] = count($confirmed);

        foreach ($this->bands->get_worked_bands('uscounties') as $band) {
            $band = strtolower($band);
            if (isset($band_worked[$band])) {
                $summary['bands'][$band] = array('worked' => count($band_worked[$band]), 'confirmed' => isset($band_confirmed[$band]) ? count($band_confirmed[$band]) : 0);
            }
        }
        foreach ($this->mode_groups as $mode) {
            $summary['modes'][$mode] = array('worked' => isset($mode_worked[$mode]) ? count($mode_worked[$mode]) : 0, 'confirmed' => isset($mode_confirmed[$mode]) ? count($mode_confirmed[$mode]) : 0);
        }

        foreach ($cities as $city) {
            $city['open_neighbors'] = array();
            foreach ($city['neighbors'] as $neighbor) {
                if (!isset($worked[$city['state'] . '|' . $neighbor])) {
                    $city['open_neighbors'][] = $neighbor;
                }
            }
            $summary['city_credits'][] = $city;
        }
        ksort($summary['unknown']);

        return $summary;
    }

    /*
     * Lists the award counties (and multi-neighbor independent city contacts) for a state or 'All'.
     */
    function counties_details($state, $type) {
        $rows = $this->fetch_rows($state);
        if ($rows === null) {
            return 0;
        }

        $counties = array();
        $cities = array();
        foreach ($rows as $row) {
            if ($type == 'confirmed' && (int)$row['confirmed'] !== 1) {
                continue;
            }
            $mapped = $this->usaca->map($row['state'], $row['county']);
            if ($mapped === null || $mapped['type'] === 'unknown') {
                continue;
            }
            $id = $row['state'] . '|' . $mapped['name'];
            if ($mapped['type'] === 'city') {
                $cities[$id] = array('COL_STATE' => $row['state'], 'COL_CNTY' => $mapped['name'], 'neighbors' => $mapped['neighbors']);
            } else {
                $counties[$id] = array('COL_STATE' => $row['state'], 'COL_CNTY' => $mapped['name']);
            }
        }
        ksort($counties, SORT_NATURAL | SORT_FLAG_CASE);
        ksort($cities, SORT_NATURAL | SORT_FLAG_CASE);

        return array('counties' => array_values($counties), 'cities' => array_values($cities));
    }

    /*
     * Logged county names (lower case) in a state that map to the given award county or independent city.
     */
    function logged_names($state, $county) {
        $rows = $this->fetch_rows($state);
        $names = array();
        if ($rows === null) {
            return $names;
        }
        foreach ($rows as $row) {
            $mapped = $this->usaca->map($row['state'], $row['county']);
            if ($mapped !== null && strcasecmp($mapped['name'], trim($county)) === 0) {
                $names[strtolower($row['county'])] = true;
            }
        }
        return array_keys($names);
    }

    /*
     * Distinct state / logged county / band / mode combinations with a confirmed flag.
     * Rows with no county text after the state prefix are excluded.
     */
    private function fetch_rows($state = 'All') {
        $this->load->model('logbooks_model');
        $logbooks_locations_array = $this->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if (!$logbooks_locations_array) {
            return null;
        }

        $location_list = implode(',', array_map('intval', $logbooks_locations_array));

        $this->load->model('bands');
        $bandslots = $this->bands->get_worked_bands('uscounties');
        $bandslots_list = "'" . implode("','", array_map(array($this->db, 'escape_str'), $bandslots)) . "'";

        $sql = "select " . self::STATE_EXPRESSION . " as state, " . self::COUNTY_EXPRESSION . " as county, COL_BAND as band, COL_MODE as mode, COL_SUBMODE as submode,
                max(case when col_qsl_rcvd = 'Y' or col_eqsl_qsl_rcvd = 'Y' or col_lotw_qsl_rcvd = 'Y' then 1 else 0 end) as confirmed
                from " . $this->config->item('table_name') . "
                where station_id in (" . $location_list . ")
                and col_band in (" . $bandslots_list . ")
                and COL_DXCC in ('291', '6', '110')
                and " . self::COUNTY_EXPRESSION . " <> ''
                and " . self::STATE_EXPRESSION . " <> ''
                and COL_BAND != 'SAT'
                and coalesce(COL_PROP_MODE, '') != 'SAT'";

        if ($state != 'All') {
            $sql .= " and " . self::STATE_EXPRESSION . " = " . $this->db->escape(strtoupper($state));
        }

        $sql .= " group by state, county, band, mode, submode";

        return $this->db->query($sql)->result_array();
    }

    /*
     * MARAC endorsement mode groups. AM, FM, image modes and digital voice only count toward the basic award.
     */
    function mode_group($mode, $submode) {
        $mode = strtoupper(trim((string)$mode));
        $submode = strtoupper(trim((string)$submode));

        if ($mode === 'CW') {
            return 'CW';
        }
        if (in_array($mode, array('SSB', 'USB', 'LSB'), true) || in_array($submode, array('USB', 'LSB'), true)) {
            return 'SSB';
        }
        if ($mode === '' || in_array($mode, array('AM', 'FM', 'ATV', 'SSTV', 'FAX', 'DIGITALVOICE', 'DSTAR', 'C4FM', 'DMR', 'FREEDV', 'M17'), true)) {
            return null;
        }
        return 'Digital';
    }

}
