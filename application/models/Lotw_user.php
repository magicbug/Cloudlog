<?php

class Lotw_user extends CI_Model {

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	
	function empty_table($table) {
		$this->db->empty_table($table); 
	}

	function add_lotwuser($callsign, $date) {

		$data = array(
		        'callsign' => $callsign,
		        'upload_date' => $date
		);



		$this->db->insert('lotw_userlist', $data);
	}
}
?>