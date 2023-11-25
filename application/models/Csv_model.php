<?php

class Csv_model extends CI_Model
{

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
	 *
	 */
	function get_qsos($station_id, $band, $mode, $dxcc, $cqz, $propagation, $fromdate, $todate) {
		$sql = "";

		$sql .= "SELECT station_callsign, COL_MY_SOTA_REF, COL_QSO_DATE, COL_TIME_ON, COL_BAND, COL_MODE, COL_CALL, COL_SOTA_REF, COL_COMMENT
			FROM ".$this->config->item('table_name').
			" JOIN station_profile on station_profile.station_id = ".$this->config->item('table_name').".station_id".
			" WHERE (COL_SOTA_REF <> '' OR COL_MY_SOTA_REF <> '')";

		if ($station_id != "All") {
			$sql .= ' and ' . $this->config->item('table_name'). '.station_id = ' . $station_id;
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

		$sql .= ' ORDER BY `COL_TIME_ON` ASC';

		$query = $this->db->query($sql);

		return $query->result_array();
	}
}
?>
