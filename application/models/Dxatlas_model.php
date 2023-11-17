<?php

class Dxatlas_model extends CI_Model
{

	/*
	 *  Fetches worked and confirmed gridsquare from the logbook
	 */
	function get_gridsquares($station_id, $band, $mode, $dxcc, $cqz, $propagation, $fromdate, $todate) {
		$gridArray = $this->fetchGrids($station_id, $band, $mode, $dxcc, $cqz, $propagation, $fromdate, $todate);

		if (isset($gridArray)) {
			return $gridArray;
		} else {
			return 0;
		}
	}

	/*
	 * Builds the array for worked and confirmed gridsquares
	 */
	function fetchGrids($station_id, $band, $mode, $dxcc, $cqz, $propagation, $fromdate, $todate) {

		// Getting all the worked grids
		$col_gridsquare_worked = $this->get_grids($station_id, $band, $mode, $dxcc, $cqz, $propagation, $fromdate, $todate, 'none', 'single');

		$workedGridArray = array();
		foreach ($col_gridsquare_worked as $workedgrid) {
			array_push($workedGridArray, $workedgrid['gridsquare']);
		}

		$col_vucc_grids_worked = $this->get_grids($station_id, $band, $mode, $dxcc, $cqz, $propagation, $fromdate, $todate, 'none', 'multi');

		foreach ($col_vucc_grids_worked as $gridSplit) {
			$grids = explode(",", $gridSplit['col_vucc_grids']);
			foreach($grids as $key) {
				$grid_four = strtoupper(substr(trim($key),0,4));

				if(!in_array($grid_four, $workedGridArray)){
					array_push($workedGridArray, $grid_four);
				}
			}
		}

		// Getting all the confirmed grids
		$col_gridsquare_confirmed = $this->get_grids($station_id, $band, $mode, $dxcc, $cqz, $propagation, $fromdate, $todate, 'both', 'single');

		$confirmedGridArray = array();
		foreach ($col_gridsquare_confirmed as $confirmedgrid) {
			array_push($confirmedGridArray, $confirmedgrid['gridsquare']);
			if(in_array($confirmedgrid['gridsquare'], $workedGridArray)){
				$index = array_search($confirmedgrid['gridsquare'],$workedGridArray);
				unset($workedGridArray[$index]);
			}
		}

		$col_vucc_grids_confirmed = $this->get_grids($station_id, $band, $mode, $dxcc, $cqz, $propagation, $fromdate, $todate, 'both', 'multi');

		foreach ($col_vucc_grids_confirmed as $gridSplit) {
			$grids = explode(",", $gridSplit['col_vucc_grids']);
			foreach($grids as $key) {
				$grid_four = strtoupper(substr(trim($key),0,4));

				if(!in_array($grid_four, $confirmedGridArray)){
					array_push($confirmedGridArray, $grid_four);
				}
				if(in_array($grid_four, $workedGridArray)){
					$index = array_search($grid_four,$workedGridArray);
					unset($workedGridArray[$index]);
				}
			}
		}

		$vuccArray['worked'] = $workedGridArray;
		$vuccArray['confirmed'] = $confirmedGridArray;

		return $vuccArray;
	}

	/*
	 * Gets the grids from the datbase
	 *
	 * Filters:
	 *
	 * $band = filter on band
	 * $mode = filter on mode
	 * $dxcc = filter on dxx
	 * $cqz = filter on cq zone
	 * $propagation = Filter on propagation
	 * $fromdate = Date range from
	 * $todate = Date range to
	 * $column = Chooses if we fetch from col_gridsquare (only single grids) or col_vucc_grids (multisquares)
	 * $confirmationMethod - qsl, lotw or both, use anything else to skip confirmed
	 *
	 */
	function get_grids($station_id, $band, $mode, $dxcc, $cqz, $propagation, $fromdate, $todate, $confirmationMethod, $column) {
		$sql = "";

		if ($column == 'single') {
			$sql .= "select distinct upper(substring(col_gridsquare, 1, 4)) gridsquare
					from " . $this->config->item('table_name') .
					' join station_profile on station_profile.station_id = ' . $this->config->item('table_name').'.station_id' .
				" where col_gridsquare <> ''";
		}
		else if ($column == 'multi') {
			$sql .= "select col_vucc_grids
            	 from " . $this->config->item('table_name') .
				 ' join station_profile on station_profile.station_id = ' . $this->config->item('table_name').'.station_id' .
				" where col_vucc_grids <> '' ";
		}

		if ($station_id != "All") {
			$sql .= ' and ' . $this->config->item('table_name'). '.station_id = ' . $station_id;
		}

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

		if ($mode != 'All') {
			$sql .= " and (COL_MODE = '" . $mode . "' or COL_SUBMODE = '" . $mode . "')";
		}

		if ($dxcc != 'All') {
			$sql .= " and COL_DXCC ='" . $dxcc . "'";
		}

		if ($cqz != 'All') {
			$sql .= " and COL_CQZ ='" . $cqz . "'";
		}

		if ($propagation != 'All') {
			$sql .= " and COL_PROP_MODE ='" . $propagation . "'";
		}

		// If date is set, we format the date and add it to the where-statement
		if ($fromdate != "") {
			$sql .= " and date(COL_TIME_ON) >='" . $fromdate . "'";
		}
		if ($todate != "") {
			$sql .= " and date(COL_TIME_ON) <='" . $todate . "'";
		}

		$sql .= ' and station_profile.user_id = ' . $this->session->userdata('user_id');

		$query = $this->db->query($sql);

		return $query->result_array();
	}
}
?>
