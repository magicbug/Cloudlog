<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
 *   This adds the hrdlog username to the station profile as this is needed 
 *   for special callsigns
*/

class Migration_hrdlog_username extends CI_Migration
{
	public function up()
	{
		if (!$this->db->field_exists('hrdlog_username', 'station_profile')) {
			$fields = array(
				'hrdlog_username VARCHAR(20) DEFAULT NULL AFTER hrdlog_code',
			);
			$this->dbforge->add_column('station_profile', $fields);
		}

		// SELECT all rows where hrdlog_code is not empty
		$this->db->where("(hrdlog_code IS NOT NULL AND hrdlog_code != '')");
		$query = $this->db->get('station_profile');
		$rows = $query->result();

		// Iterate through all selected rows
		foreach ($rows as $row) {
			// Extract the username using the regex pattern
			$regex = '/^((\d|[A-Z])+\/)?((\d|[A-Z]){3,})(\/(\d|[A-Z])+)?(\/(\d|[A-Z])+)?$/';
			preg_match($regex, $row->station_callsign, $matches);
			$username = $matches[3];

			// Update the row with the extracted username
			$this->db->where('station_id', $row->station_id);
			$this->db->update('station_profile', array('hrdlog_username' => $username));
		}
	}

	public function down()
	{
		if ($this->db->field_exists('hrdlog_username', 'station_profile')) {
			$this->dbforge->drop_column('station_profile', 'hrdlog_username');
		}
	}
}
