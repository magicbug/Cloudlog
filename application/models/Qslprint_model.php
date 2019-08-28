<?php

class Qslprint_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	function mark_qsos_printed() {
		$data = array(
	        'COL_QSLSDATE' => date('Y-m-d'),
	        'COL_QSL_SENT' => "Y",
	        'COL_QSL_SENT_VIA' => "B",
		);

		$this->db->where("COL_QSL_SENT", "R");
		$this->db->update($this->config->item('table_name'), $data);
	}
}

?>