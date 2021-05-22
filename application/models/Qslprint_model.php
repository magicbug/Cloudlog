<?php

class Qslprint_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	function mark_qsos_printed() {
		$CI =& get_instance();
		$CI->load->model('Stations');
		$station_id = $CI->Stations->find_active();

		$data = array(
	        'COL_QSLSDATE' => date('Y-m-d'),
	        'COL_QSL_SENT' => "Y",
	        'COL_QSL_SENT_VIA' => "B",
		);

		$this->db->where_in("COL_QSL_SENT", array("R","Q"));
		$this->db->where("station_id", $station_id);
		$this->db->update($this->config->item('table_name'), $data);
	}

	function get_qsos_for_print() {
		$CI =& get_instance();
		$CI->load->model('Stations');

		$active_station_id = $this->stations->find_active();

		$this->db->where($this->config->item('table_name').'.station_id', $active_station_id);
		$this->db->join('station_profile', 'station_profile.station_id = '.$this->config->item('table_name').'.station_id');
		$this->db->where_in('COL_QSL_SENT', array('R', 'Q'));
		$this->db->order_by("COL_TIME_ON", "ASC");
		$query = $this->db->get($this->config->item('table_name'));

		return $query;
	}

	function delete_from_qsl_queue($id) {
		$CI =& get_instance();
		$CI->load->model('Stations');
		$station_id = $CI->Stations->find_active();

		$data = array(
			'COL_QSL_SENT' => "N",
		);

		$this->db->where("COL_PRIMARY_KEY", $id);
		//$this->db->where("station_id", $station_id);
		$this->db->update($this->config->item('table_name'), $data);

		return true;
	}
}

?>
