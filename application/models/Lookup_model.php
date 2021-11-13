<?php

class Lookup_model extends CI_Model{

	function getSearchResult($queryinfo){
		$modes = $this->get_worked_modes($queryinfo['location_list']);

		return $this->getResultFromDatabase($queryinfo, $modes);
	}

	function getResultFromDatabase($queryinfo, $modes) {
		// Creating an empty array with all the bands and modes from the database
		foreach ($modes as $mode) {
			foreach ($queryinfo['bands'] as $band) {
				$resultArray[$mode][$band] = '-';
			}
		}

		// Populating array with worked band/mode combinations
		$worked = $this->getQueryData($queryinfo, 'worked');
		foreach ($worked as $w) {
			$resultArray[$w->col_mode][$w->col_band] = 'W';
		}

		// Populating array with confirmed band/mode combinations
		$confirmed = $this->getQueryData($queryinfo, 'confirmed');
		foreach ($confirmed as $c) {
			$resultArray[$c->col_mode][$c->col_band] = 'C';
		}

		return $resultArray;
	}

	/*
	 * Builds query depending on what we are searching for
	 */
	function getQueryData($queryinfo, $confirmedtype) {
		// If user inputs longer grid than 4 chars, we use only the first 4
		if (strlen($queryinfo['grid']) > 4) {
			$fixedgrid = substr($queryinfo['grid'], 0, 4);
		}
		else {
			$fixedgrid = $queryinfo['grid'];
		}

		$sqlquerytypestring = '';

		switch ($queryinfo['type']) 	{
			case 'dxcc': $sqlquerytypestring .= " and col_dxcc = " . $queryinfo['dxcc']; 																break;
			case 'iota': $sqlquerytypestring .= " and col_iota = '" . $queryinfo['iota'] . "'"; 														break;
			case 'grid': $sqlquerytypestring .= " and (col_gridsquare like '%" . $fixedgrid . "%' or col_vucc_grids like '%" . $fixedgrid . "%')" ; 	break;
			case 'cqz':  $sqlquerytypestring .= " and col_cqz = " . $queryinfo['cqz']; 																	break;
			case 'was':  $sqlquerytypestring .= " and col_state = '" . $queryinfo['was'] . "' and COL_DXCC in ('291', '6', '110')";; 					break;
			case 'sota': $sqlquerytypestring .= " and col_sota_ref = '" . $queryinfo['sota'] . "'"; 													break;
			case 'wwff': $sqlquerytypestring .= " and col_sig = 'WWFF' and col_sig_info = '" . $queryinfo['wwff'] . "'"; 								break;
			default: break;
		}

		$sqlqueryconfirmationstring = '';

		if ($confirmedtype == 'confirmed') {
			$sqlqueryconfirmationstring .= " and (col_qsl_rcvd = 'Y' or col_lotw_qsl_rcvd = 'Y')";
		}

		// Fetching info for all modes and bands except satellite
		$sql = "SELECT distinct col_band, lower(col_mode) as col_mode FROM " . $this->config->item('table_name') . " thcv";

		$sql .= " where station_id in (" . $queryinfo['location_list'] . ")";

		$sql .= " and coalesce(col_submode, '') = ''";

		$sql .= " and col_prop_mode != 'SAT'";

		$sql .= $sqlquerytypestring;

		$sql .= $sqlqueryconfirmationstring;

		// Fetching info for all sub_modes and bands except satellite
		$sql .= " union SELECT distinct col_band, lower(col_submode) as col_mode FROM " . $this->config->item('table_name') . " thcv";

		$sql .= " where station_id in (" . $queryinfo['location_list'] . ")";

		$sql .= " and coalesce(col_submode, '') <> ''";

		$sql .= " and col_prop_mode != 'SAT'";

		$sql .= $sqlquerytypestring;

		$sql .= $sqlqueryconfirmationstring;

		// Fetching info for all modes on satellite
		$sql .= " union SELECT distinct 'SAT' col_band, lower(col_mode) as col_mode FROM " . $this->config->item('table_name') . " thcv";

		$sql .= " where station_id in (" . $queryinfo['location_list'] . ")";

		$sql .= " and coalesce(col_submode, '') = ''";

		$sql .= " and col_prop_mode = 'SAT'";

		$sql .= $sqlquerytypestring;

		$sql .= $sqlqueryconfirmationstring;

		// Fetching info for all sub_modes on satellite
		$sql .= " union SELECT distinct 'SAT' col_band, lower(col_submode) as col_mode FROM " . $this->config->item('table_name') . " thcv";

		$sql .= " where station_id in (" . $queryinfo['location_list'] . ")";

		$sql .= " and coalesce(col_submode, '') <> ''";

		$sql .= " and col_prop_mode = 'SAT'";

		$sql .= $sqlquerytypestring;

		$sql .= $sqlqueryconfirmationstring;

		$query = $this->db->query($sql);

		return $query->result();
	}

	/*
	 * Get's the worked modes from the log
	 */
	function get_worked_modes($location_list)
	{
		// get all worked modes from database
		$data = $this->db->query(
			"SELECT distinct LOWER(`COL_MODE`) as `COL_MODE` FROM `" . $this->config->item('table_name') . "` WHERE station_id in (" . $location_list . ") order by COL_MODE ASC"
		);
		$results = array();
		foreach ($data->result() as $row) {
			array_push($results, $row->COL_MODE);
		}

		$data = $this->db->query(
			"SELECT distinct LOWER(`COL_SUBMODE`) as `COL_SUBMODE` FROM `" . $this->config->item('table_name') . "` WHERE station_id in (" . $location_list . ") and coalesce(COL_SUBMODE, '') <> '' order by COL_SUBMODE ASC"
		);
		foreach ($data->result() as $row) {
			if (!in_array($row, $results)) {
				array_push($results, $row->COL_SUBMODE);
			}
		}

		return $results;
	}
}
