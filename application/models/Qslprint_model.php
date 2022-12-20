<?php

class Qslprint_model extends CI_Model {

	function mark_qsos_printed($station_id2 = NULL) {
		$CI =& get_instance();
		$CI->load->model('Stations');
		$station_id = $CI->Stations->find_active();

		$data = array(
			'COL_QSLSDATE' => date('Y-m-d'),
			'COL_QSL_SENT' => "Y",
			'COL_QSL_SENT_VIA' => "B",
		);

		if ($station_id2 == NULL) {
			$this->db->where("station_id", $station_id);
		} else if ($station_id2 == 'All') {
			// get all stations of user
			$stations = $CI->Stations->all_of_user();
			$station_ids = array();
			foreach ($stations->result() as $row) {
				array_push($station_ids, $row->station_id);
			}

			// filter by all stations
			$this->db->where_in("station_id", $station_ids);
		} else if ($station_id2 != 'All') {
			// be sure that station belongs to user
			if (!$CI->Stations->check_station_is_accessible($station_id2)) {
				return;
			}
			$this->db->where("station_id", $station_id2);
		}

		$this->db->where_in("COL_QSL_SENT", array("R","Q"));

		$this->db->update($this->config->item('table_name'), $data);
	}

	/*
	 * We list out the QSL's ready for print.
	 * station_id is not provided when loading page.
	 * It will be provided when calling the function when the dropdown is changed and the javascript fires
	 */
	function get_qsos_for_print($station_id = 'All') {
		if ($station_id != 'All') {
			$this->db->where($this->config->item('table_name').'.station_id', $station_id);
		}

		$this->db->join('station_profile', 'station_profile.station_id = '.$this->config->item('table_name').'.station_id');
		// always filter user. this ensures that even if the station_id is from another user no inaccesible QSOs will be returned
		$this->db->where('station_profile.user_id', $this->session->userdata('user_id'));
		$this->db->where_in('COL_QSL_SENT', array('R', 'Q'));
		$this->db->order_by("COL_TIME_ON", "ASC");
		$query = $this->db->get($this->config->item('table_name'));

		return $query;
	}

	function get_qsos_for_print_ajax($station_id) {
		$query = $this->get_qsos_for_print($station_id);

		return $query;
	}

	function delete_from_qsl_queue($id) {
		// be sure that QSO belongs to user
		$CI =& get_instance();
		$CI->load->model('logbook_model');
		if (!$CI->logbook_model->check_qso_is_accessible($id)) {
			return;
		}

		$data = array(
			'COL_QSL_SENT' => "N",
		);

		$this->db->where("COL_PRIMARY_KEY", $id);
		$this->db->update($this->config->item('table_name'), $data);

		return true;
	}

	function add_qso_to_print_queue($id) {
		// be sure that QSO belongs to user
		$CI =& get_instance();
		$CI->load->model('logbook_model');
		if (!$CI->logbook_model->check_qso_is_accessible($id)) {
			return;
		}

		$data = array(
			'COL_QSL_SENT' => "R",
		);

		$this->db->where("COL_PRIMARY_KEY", $id);
		$this->db->update($this->config->item('table_name'), $data);

		return true;
	}

	function open_qso_list($callsign) {
		$this->db->join('station_profile', 'station_profile.station_id = '.$this->config->item('table_name').'.station_id');
		// always filter user. this ensures that no inaccesible QSOs will be returned
		$this->db->where('station_profile.user_id', $this->session->userdata('user_id'));
		$this->db->where('COL_CALL like "%'.$callsign.'%"');
		$this->db->where('coalesce(COL_QSL_SENT, "") not in ("R", "Q")');
		$this->db->order_by("COL_TIME_ON", "ASC");
		$query = $this->db->get($this->config->item('table_name'));

		return $query;
	}

}

?>
