<?php

/*
	Handles retrieving QSOs that have a Wacral (www.wacral.org) membership number stored in the comment field
	eg WACRAL:1245
*/

class Wacral extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}
	
	function get_all() {
		$this->db->order_by("COL_COMMENT", "ASC"); 
		$this->db->like('COL_COMMENT', 'WACRAL:');
		
		return $this->db->get($this->config->item('table_name'));
	}
}

?>