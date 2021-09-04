<?php

class Dxatlas_model extends CI_Model
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

	/*
	 *  Fetches worked and confirmed gridsquare on each band and total
	 */
	function get_gridsquares($band, $mode, $dxcc, $cqz, $propagation, $fromdate, $todate) {
		$gridArray = $this->fetchGrids($band, $mode, $dxcc, $cqz, $propagation, $fromdate, $todate);

		if (isset($gridArray)) {
			return $gridArray;
		} else {
			return 0;
		}
	}

	/*
	 * Builds the array to display worked/confirmed vucc on awward page
	 */
	function fetchGrids($band, $mode, $dxcc, $cqz, $propagation, $fromdate, $todate) {
		// Getting all the worked grids
		$col_gridsquare_worked = $this->get_grids($band, $mode, $dxcc, $cqz, $propagation, $fromdate, $todate, 'none');

		$workedGridArray = array();
		foreach ($col_gridsquare_worked as $workedgrid) {
			array_push($workedGridArray, $workedgrid['gridsquare']);
		}

		$col_vucc_grids_worked = $this->get_grids_col_vucc($band, $mode, $dxcc, $cqz, $propagation, $fromdate, $todate, 'none');

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
		$col_gridsquare_confirmed = $this->get_grids($band, $mode, $dxcc, $cqz, $propagation, $fromdate, $todate, 'both');

		$confirmedGridArray = array();
		foreach ($col_gridsquare_confirmed as $confirmedgrid) {
			array_push($confirmedGridArray, $confirmedgrid['gridsquare']);
			if(in_array($confirmedgrid['gridsquare'], $workedGridArray)){
				$index = array_search($confirmedgrid['gridsquare'],$workedGridArray);
				unset($workedGridArray[$index]);
			}
		}

		$col_vucc_grids_confirmed = $this->get_grids_col_vucc($band, $mode, $dxcc, $cqz, $propagation, $fromdate, $todate, 'both');

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
	 *  Gets the grid from col_vucc_grids
	 * $band = the band chosen
	 * $confirmationMethod - qsl, lotw or both, use anything else to skip confirmed
	 */
	function get_grids_col_vucc($band, $mode, $dxcc, $cqz, $propagation, $fromdate, $todate, $confirmationMethod) {
		$station_id = $this->get_station_id();

		$sql = "select col_vucc_grids
            from " . $this->config->item('table_name') .
			" where station_id =" . $station_id .
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
	function get_grids($band, $mode, $dxcc, $cqz, $propagation, $fromdate, $todate, $confirmationMethod) {
		$station_id = $this->get_station_id();
		$sql = "select distinct upper(substring(col_gridsquare, 1, 4)) gridsquare
            from " . $this->config->item('table_name') .
			" where station_id =" . $station_id .
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

	function get_station_id() {
		$CI =& get_instance();
		$CI->load->model('Stations');
		return $CI->Stations->find_active();
	}
}
?>
