<?php

class LOTW extends CI_Model {

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	
	function empty_table($table) {
		$this->db->empty_table($table); 
	}
}
?>