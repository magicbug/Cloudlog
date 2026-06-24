<?php

class Counties extends CI_Model
{

    /*
     *  Fetches worked and confirmed counties
     */
    function get_counties_array() {
        $countiesArray = $this->get_counties_summary();

        if (isset($countiesArray)) {
            return $countiesArray;
        } else {
            return 0;
        }
        return 0;
    }

    /*
     * Returns a result of worked/confirmed US Counties, grouped by STATE
     * QSL card and EQSL is valid for award. Satellite does not count.
     * No band split, as it only count the number of counties in the award.
     */
    function get_counties_summary() {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if (!$logbooks_locations_array) {
            return null;
        }

        $location_list = implode(',', array_map('intval', $logbooks_locations_array));

        $this->load->model('bands');

		$bandslots = $this->bands->get_worked_bands('uscounties');

        $bandslots_list = "'".implode("','", array_map(array($this->db, 'escape_str'), $bandslots))."'";

        // Normalize county/state values so imported variants group consistently.
        $normalizedCountyExpression = "LOWER(TRIM(SUBSTRING_INDEX(COL_CNTY, ',', -1)))";
        $normalizedStateExpression = "UPPER(TRIM(CASE WHEN COALESCE(COL_STATE, '') <> '' THEN COL_STATE WHEN COL_CNTY REGEXP '^[A-Za-z]{2},' THEN SUBSTRING_INDEX(COL_CNTY, ',', 1) ELSE '' END))";
        $normalizedStateExpressionOuter = "UPPER(TRIM(CASE WHEN COALESCE(thcv.COL_STATE, '') <> '' THEN thcv.COL_STATE WHEN thcv.COL_CNTY REGEXP '^[A-Za-z]{2},' THEN SUBSTRING_INDEX(thcv.COL_CNTY, ',', 1) ELSE '' END))";

        $sql = "select count(distinct " . $normalizedCountyExpression . ") countycountworked, coalesce(x.countycountconfirmed, 0) countycountconfirmed, " . $normalizedStateExpressionOuter . " as COL_STATE
                from " . $this->config->item('table_name') . " thcv
                 left outer join (
              select count(distinct " . $normalizedCountyExpression . ") countycountconfirmed, " . $normalizedStateExpression . " as COL_STATE
                        from " . $this->config->item('table_name') .
            " where station_id in (" . $location_list . ")" .
            " and col_band in (" . $bandslots_list . ")" .
            " and COL_DXCC in ('291', '6', '110')
                    and coalesce(COL_CNTY, '') <> ''
                    and " . $normalizedStateExpression . " <> ''
                    and COL_BAND != 'SAT'
                    and (col_qsl_rcvd='Y' or col_eqsl_qsl_rcvd='Y')
                    group by " . $normalizedStateExpression . "
                    order by COL_STATE
                ) x on " . $normalizedStateExpressionOuter . " = x.COL_STATE
                 where station_id in (" . $location_list . ")" .
                 " and col_band in (" . $bandslots_list . ")" .
            " and COL_DXCC in ('291', '6', '110')
                and coalesce(COL_CNTY, '') <> ''
                and " . $normalizedStateExpressionOuter . " <> ''
                and COL_BAND != 'SAT'
                group by " . $normalizedStateExpressionOuter . ", countycountconfirmed
                order by " . $normalizedStateExpressionOuter;

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*
    * Makes a list of all counties in given state
    */
    function counties_details($state, $type) {
        if ($type == 'worked') {
            $counties = $this->get_counties($state, 'none');
        } else if ($type == 'confirmed') {
            $counties = $this->get_counties($state, 'confirmed');
        }
        if (!isset($counties)) {
            return 0;
        } else {
            ksort($counties);
            return $counties;
        }
    }

    function get_counties($state, $confirmationtype) {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if (!$logbooks_locations_array) {
            return null;
        }

        $location_list = implode(',', array_map('intval', $logbooks_locations_array));

        $this->load->model('bands');

		$bandslots = $this->bands->get_worked_bands('uscounties');

        $bandslots_list = "'".implode("','", array_map(array($this->db, 'escape_str'), $bandslots))."'";

        $normalizedCountySelect = "TRIM(SUBSTRING_INDEX(COL_CNTY, ',', -1))";
        $normalizedCountyOrder = "LOWER(TRIM(SUBSTRING_INDEX(COL_CNTY, ',', -1)))";
        $normalizedStateExpression = "UPPER(TRIM(CASE WHEN COALESCE(COL_STATE, '') <> '' THEN COL_STATE WHEN COL_CNTY REGEXP '^[A-Za-z]{2},' THEN SUBSTRING_INDEX(COL_CNTY, ',', 1) ELSE '' END))";

        $sql = "select MIN(" . $normalizedCountySelect . ") as COL_CNTY, " . $normalizedStateExpression . " as COL_STATE
                from " . $this->config->item('table_name') . " thcv
                 where station_id in (" . $location_list . ")" .
                 " and col_band in (" . $bandslots_list . ")" .
                " and COL_DXCC in ('291', '6', '110')
                and coalesce(COL_CNTY, '') <> ''
                and " . $normalizedStateExpression . " <> ''
                and COL_BAND != 'SAT'";

        if ($state != 'All') {
            $sql .= " and " . $normalizedStateExpression . " = " . $this->db->escape(strtoupper($state));
        }

        if ($confirmationtype != 'none') {
            $sql .= " and (col_qsl_rcvd='Y' or col_eqsl_qsl_rcvd='Y')";
        }

        $sql .= " group by " . $normalizedCountyOrder . ", " . $normalizedStateExpression;
        $sql .= " order by " . $normalizedStateExpression . ", " . $normalizedCountySelect;

        $query = $this->db->query($sql);
        return $query->result_array();
    }

}
