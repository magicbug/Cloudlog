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
}

?>