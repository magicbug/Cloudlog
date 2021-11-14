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

		if (isset($row->option_value)) {
			return $row->option_value;
		}

		return null;
	}

	/*
	*
	* Saves an option to the database
	*
	* Parameters
	* - option_name: name of the option with no spaces
	* - option_value: the value of the option name
	* - autoload: this is whether it needs to be loaded every page load set to yes or no
	*/
	function save($option_name, $option_value, $autoload) {
		$this->db->where('option_name', $option_name);
		$query = $this->db->get('options');

		if($query->num_rows() > 0) {
			// Update the Entry
			return FALSE;
		} else {
			$data = array(
				'option_name' => $option_name,
				'option_value' => $option_value,
				'autoload' => $autoload,
			);

			// Save to database
			$this->db->insert('options', $data);

			return TRUE;
		}
	}

	/*
	*
	* Saves an update to option
	*
	* Parameters
	* - option_name: name of the option with no spaces
	* - option_value: the value of the option name
	*/
	function update($option_name, $option_value) {
		$this->db->where('option_name', $option_name);
		$query = $this->db->get('options');

		$data = array(
			'option_name' => $option_name,
			'option_value' => $option_value,
		);

		if($query->num_rows() > 0) {
			// Update the Entry
			$this->db->where('option_name', $option_name);
			$this->db->update('options', $data);

			return TRUE;
		} else {
			// Save to database
			$this->db->insert('options', $data);

			return FALSE;
		}
	}

}

?>
