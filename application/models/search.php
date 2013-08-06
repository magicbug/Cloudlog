<?php

class Search extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}
	
	function callsign_iota($reference) {
		$this->db->where('COL_IOTA', $reference);
		$query = $this->db->get($this->config->item('table_name')); 
		
		return $query;
	}

}

?>