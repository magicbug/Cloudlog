<?php

class LotwCert extends CI_Model {

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function lotw_certs($user_id) {
		$this->db->where('user_id', $user_id);
		$query = $this->db->get('lotw_certs');
			
		return $query;
	}
	
	function empty_table($table) {
		$this->db->empty_table($table); 
	}
}
?>