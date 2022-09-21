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

		$location_list = "'".implode("','",$logbooks_locations_array)."'";

        $this->load->model('bands');

		$bandslots = $this->bands->get_worked_bands('uscounties');

		$bandslots_list = "'".implode("','",$bandslots)."'";

        $sql = "select count(distinct COL_CNTY) countycountworked, coalesce(x.countycountconfirmed, 0) countycountconfirmed, thcv.COL_STATE
                from " . $this->config->item('table_name') . " thcv
                 left outer join (
                        select count(distinct COL_CNTY) countycountconfirmed, COL_STATE
                        from " . $this->config->item('table_name') .
            " where station_id in (" . $location_list . ")" .
            " and col_band in (" . $bandslots_list . ")" .
            " and COL_DXCC in ('291', '6', '110')
                    and coalesce(COL_CNTY, '') <> ''
                    and COL_BAND != 'SAT'
                    and (col_qsl_rcvd='Y' or col_eqsl_qsl_rcvd='Y')
                    group by COL_STATE
                    order by COL_STATE
                ) x on thcv.COL_STATE = x.COL_STATE
                 where station_id in (" . $location_list . ")" .
                 " and col_band in (" . $bandslots_list . ")" .
            " and COL_DXCC in ('291', '6', '110')
                and coalesce(COL_CNTY, '') <> ''
                and COL_BAND != 'SAT'
                group by thcv.COL_STATE, countycountconfirmed
                order by thcv.COL_STATE";

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

		$location_list = "'".implode("','",$logbooks_locations_array)."'";

        $this->load->model('bands');

		$bandslots = $this->bands->get_worked_bands('uscounties');

		$bandslots_list = "'".implode("','",$bandslots)."'";

        $sql = "select distinct COL_CNTY, COL_STATE
                from " . $this->config->item('table_name') . " thcv
                 where station_id in (" . $location_list . ")" .
                 " and col_band in (" . $bandslots_list . ")" .
                " and COL_DXCC in ('291', '6', '110')
                and coalesce(COL_CNTY, '') <> ''
                and COL_BAND != 'SAT'";

        if ($state != 'All') {
            $sql .= " and COL_STATE = '" . $state . "'";
        }

        if ($confirmationtype != 'none') {
            $sql .= " and (col_qsl_rcvd='Y' or col_eqsl_qsl_rcvd='Y')";
        }

        $sql .= " order by thcv.COL_STATE";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

}
