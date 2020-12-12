<?php

/*
	Class: Options_model
	This model handles all database interactions for the options table 
	used for global settings within cloudlog.
*/

class Options_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}
    
    // Returns all options that are autoload yes
	function get_autoloads() {
		$this->db->where('autoload', "yes");
		return $this->db->get('options');
	}

	// Return option value for an option
	function item($option_name) {
		$this->db->where('option_name', $option_name);
		$query = $this->db->get('options');
		$row = $query->row();

		return $row->option_value;
	}

}

?>