<?php

class Clublog_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	function get_clublog_auth_info($username) {
		$this->db->select('user_name, user_clublog_name, user_clublog_password');
		$this->db->where('user_name', $username);
		$query = $this->db->get($this->config->item('auth_table'));
		return $row = $query->row_array();
	}

	function mark_qsos_sent($station_id) {
		$data = array(
	        'COL_CLUBLOG_QSO_UPLOAD_DATE' => date('Y-m-d'),
	        'COL_CLUBLOG_QSO_UPLOAD_STATUS' => "Y",
		);

		$this->db->where("station_id", $station_id);
		$this->db->where("COL_CLUBLOG_QSO_UPLOAD_STATUS", null);
		$this->db->or_where("COL_CLUBLOG_QSO_UPLOAD_STATUS", "");
    	$this->db->or_where("COL_CLUBLOG_QSO_UPLOAD_STATUS", "N");
		$this->db->update($this->config->item('table_name'), $data);
	}

	function mark_all_qsos_notsent($station_id) {
		$data = array(
	        'COL_CLUBLOG_QSO_UPLOAD_DATE' => null,
	        'COL_CLUBLOG_QSO_UPLOAD_STATUS' => "N",
		);

		$this->db->where("station_id", $station_id);
		$this->db->where("COL_CLUBLOG_QSO_UPLOAD_STATUS", "Y");
		$this->db->update($this->config->item('table_name'), $data);
	}
}

?>