<?php

class Wab extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}
	
	function get_all() {
		$this->db->like('COL_COMMENT', 'WAB:');
		
		return $this->db->get($this->config->item('table_name'));
	}
}

?>