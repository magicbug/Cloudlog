<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_add_station_power extends CI_Migration
{
	public function up()
	{
		$fields = array(
			'station_power SMALLINT NULL DEFAULT NULL AFTER `station_callsign`',
		);

		if (!$this->db->field_exists('station_power', 'station_profile')) {
			$this->dbforge->add_column('station_profile', $fields);
		}
	}

	public function down()
	{

		if ($this->db->field_exists('station_power', 'station_profile')) {
			$this->dbforge->drop_column('station_profile', 'station_power');
		}	
	}
}
