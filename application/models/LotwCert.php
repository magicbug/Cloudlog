<?php

class LotwCert extends CI_Model {

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	/*
	|--------------------------------------------------------------------------
	| Function: lotw_certs
	|--------------------------------------------------------------------------
	| 
	| Returns all lotw_certs for a selected user via the $user_id parameter
	|
	*/
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