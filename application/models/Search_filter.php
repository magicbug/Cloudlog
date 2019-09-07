<?php

class Search_filter extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	function get_table_columns() {
		$query = $this->db->query('DESCRIBE '.$this->config->item('table_name'));

		return $query;
	}

}

?>