<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 *   This adds the hrdlog username to the station profile as this is needed 
 *   for special callsigns
*/

class Migration_hrdlog_username extends CI_Migration {

	public function up()
	{
		if (!$this->db->field_exists('hrdlog_username', 'station_profile')) {
			$fields = array(
				'hrdlog_username VARCHAR(20) DEFAULT NULL AFTER hrdlog_code',
			);
			$this->dbforge->add_column('station_profile', $fields);
		}
	}

	public function down()
	{
		if ($this->db->field_exists('hrdlog_username', 'station_profile')) {
			$this->dbforge->drop_column('station_profile', 'hrdlog_username');
		}
	}
}