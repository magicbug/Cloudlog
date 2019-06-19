<?php

class Clublog_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	function get_clublog_auth_info($username) {
		$this->db->select('user_name, user_clublog_name, user_clublog_password, user_clublog_callsign');
		$this->db->where('user_name', $username);
		$query = $this->db->get($this->config->item('auth_table'));
		return $row = $query->row_array();
	}

	function mark_qsos_sent() {
		$data = array(
	        'COL_CLUBLOG_QSO_UPLOAD_DATE' => date('Y-m-d'),
	        'COL_CLUBLOG_QSO_UPLOAD_STATUS' => "Y",
		);

		$this->db->where("COL_CLUBLOG_QSO_UPLOAD_STATUS", "");
    	$this->db->or_where("COL_CLUBLOG_QSO_UPLOAD_STATUS", "N");
		$this->db->update($this->config->item('table_name'), $data);
	}
}

?>