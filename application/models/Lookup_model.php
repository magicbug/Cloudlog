<?php

class Lookup_model extends CI_Model{

	function __construct(){
		// Call the Model constructor
		parent::__construct();
	}

	function getSearchResult($station_id, $type, $dxcc, $was, $cqz, $sota, $grid, $iota, $bands){
		$modes = $this->get_worked_modes($station_id);

		return $this->getResultFromDatabase($station_id, $type, $dxcc, $was, $cqz, $sota, $grid, $iota, $modes, $bands);
	}

	function getResultFromDatabase($station_id, $type, $dxcc, $was, $cqz, $sota, $grid, $iota, $modes, $bands) {
		// Creating an empty array with all the bands and modes from the database
		foreach ($modes as $mode) {
			foreach ($bands as $band) {
				$resultArray[$mode][$band] = '-';
			}
		}

		// Populating array with worked band/mode combinations
		$worked = $this->getQueryData($station_id, $type, $dxcc, $was, $cqz, $sota, $grid, $iota,'worked');
		foreach ($worked as $w) {
			$resultArray[$w->col_mode][$w->col_band] = 'W';
		}

		// Populating array with confirmed band/mode combinations
		$confirmed = $this->getQueryData($station_id, $type, $dxcc, $was, $cqz, $sota, $grid, $iota,'confirmed');
		foreach ($confirmed as $c) {
			$resultArray[$c->col_mode][$c->col_band] = 'C';
		}

		return $resultArray;
	}

	/*
	 * Builds query depending on what we are searching for
	 */
	function getQueryData($station_id, $type, $dxcc, $was, $cqz, $sota, $grid, $iota, $confirmedtype) {
		$sql = "SELECT distinct col_band, lower(col_mode) as col_mode FROM " . $this->config->item('table_name') . " thcv";

		$sql .= " where station_id = " . $station_id;

		$sql .= " and coalesce(col_submode, '') = ''";

		$sql .= " and col_prop_mode != 'SAT'";

		switch ($type) 	{
			case 'dxcc': $sql .= " and col_dxcc = " . $dxcc; 																break;
			case 'iota': $sql .= " and col_iota = '" . $iota . "'"; 														break;
			case 'grid': $sql .= " and (col_gridsquare like '%" . $grid . "%' or col_vucc_grids like '%" . $grid . "%')" ; 	break;
			case 'cqz':  $sql .= " and col_cqz = " . $cqz; 																	break;
			case 'was':  $sql .= " and col_state = '" . $was . "' and COL_DXCC in ('291', '6', '110')";; 					break;
			case 'sota': $sql .= " and col_sota_ref = '" . $sota . "'"; 													break;
			default: break;
		}

		if ($confirmedtype == 'confirmed') {
			$sql .= " and (col_qsl_rcvd = 'Y' or col_lotw_qsl_rcvd = 'Y')";
		}

		$sql .= " union SELECT distinct col_band, lower(col_submode) as col_mode FROM " . $this->config->item('table_name') . " thcv";

		$sql .= " where station_id = " . $station_id;

		$sql .= " and coalesce(col_submode, '') <> ''";

		$sql .= " and col_prop_mode != 'SAT'";

		switch ($type) 	{
			case 'dxcc': $sql .= " and col_dxcc = " . $dxcc; 																break;
			case 'iota': $sql .= " and col_iota = '" . $iota . "'"; 														break;
			case 'grid': $sql .= " and (col_gridsquare like '%" . $grid . "%' or col_vucc_grids like '%" . $grid . "%')" ; 	break;
			case 'cqz':  $sql .= " and col_cqz = " . $cqz; 																	break;
			case 'was':  $sql .= " and col_state = '" . $was . "' and COL_DXCC in ('291', '6', '110')";; 					break;
			case 'sota': $sql .= " and col_sota_ref = '" . $sota . "'"; 													break;
			default: break;
		}

		if ($confirmedtype == 'confirmed') {
			$sql .= " and (col_qsl_rcvd = 'Y' or col_lotw_qsl_rcvd = 'Y')";
		}

		$sql .= " union SELECT distinct 'SAT' col_band, lower(col_mode) as col_mode FROM " . $this->config->item('table_name') . " thcv";

		$sql .= " where station_id = " . $station_id;

		$sql .= " and coalesce(col_submode, '') = ''";

		$sql .= " and col_prop_mode = 'SAT'";

		switch ($type) 	{
			case 'dxcc': $sql .= " and col_dxcc = " . $dxcc; 																break;
			case 'iota': $sql .= " and col_iota = '" . $iota . "'"; 														break;
			case 'grid': $sql .= " and (col_gridsquare like '%" . $grid . "%' or col_vucc_grids like '%" . $grid . "%')" ; 	break;
			case 'cqz':  $sql .= " and col_cqz = " . $cqz; 																	break;
			case 'was':  $sql .= " and col_state = '" . $was . "' and COL_DXCC in ('291', '6', '110')";; 					break;
			case 'sota': $sql .= " and col_sota_ref = '" . $sota . "'"; 													break;
			default: break;
		}

		if ($confirmedtype == 'confirmed') {
			$sql .= " and (col_qsl_rcvd = 'Y' or col_lotw_qsl_rcvd = 'Y')";
		}

		$sql .= " union SELECT distinct 'SAT' col_band, lower(col_submode) as col_mode FROM " . $this->config->item('table_name') . " thcv";

		$sql .= " where station_id = " . $station_id;

		$sql .= " and coalesce(col_submode, '') <> ''";

		$sql .= " and col_prop_mode = 'SAT'";

		switch ($type) 	{
			case 'dxcc': $sql .= " and col_dxcc = " . $dxcc; 																break;
			case 'iota': $sql .= " and col_iota = '" . $iota . "'"; 														break;
			case 'grid': $sql .= " and (col_gridsquare like '%" . $grid . "%' or col_vucc_grids like '%" . $grid . "%')" ; 	break;
			case 'cqz':  $sql .= " and col_cqz = " . $cqz; 																	break;
			case 'was':  $sql .= " and col_state = '" . $was . "' and COL_DXCC in ('291', '6', '110')";; 					break;
			case 'sota': $sql .= " and col_sota_ref= '" . $sota . "'"; 														break;
			default: break;
		}

		if ($confirmedtype == 'confirmed') {
			$sql .= " and (col_qsl_rcvd = 'Y' or col_lotw_qsl_rcvd = 'Y')";
		}

		$query = $this->db->query($sql);

		return $query->result();
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

	/*
	 * Get's the worked bands from the log
	 */
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

	/*
	 * Get's the worked modes from the log
	 */
	function get_worked_modes($station_id)
	{
		// get all worked modes from database
		$data = $this->db->query(
			"SELECT distinct LOWER(`COL_MODE`) as `COL_MODE` FROM `" . $this->config->item('table_name') . "` WHERE station_id = " . $station_id . " order by COL_MODE ASC"
		);
		$results = array();
		foreach ($data->result() as $row) {
			array_push($results, $row->COL_MODE);
		}

		$data = $this->db->query(
			"SELECT distinct LOWER(`COL_SUBMODE`) as `COL_SUBMODE` FROM `" . $this->config->item('table_name') . "` WHERE station_id = " . $station_id . " and coalesce(COL_SUBMODE, '') <> '' order by COL_SUBMODE ASC"
		);
		foreach ($data->result() as $row) {
			if (!in_array($row, $results)) {
				array_push($results, $row->COL_SUBMODE);
			}
		}

		return $results;
	}
}
