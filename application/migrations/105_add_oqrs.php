<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 *   This adds an option to enable grid lookup
 *   by location entered
*/

class Migration_add_oqrs extends CI_Migration {

	public function up()
	{
		if (!$this->db->field_exists('oqrs', 'station_profile')) {
			$fields = array(
				'oqrs int DEFAULT 0',
			);

			$this->dbforge->add_column('station_profile', $fields);
		}

		if (!$this->db->field_exists('oqrs_text', 'station_profile')) {
			$fields = array(
				'oqrs_text text DEFAULT ""',
			);

			$this->dbforge->add_column('station_profile', $fields);
		}
	}

	public function down()
	{
		if ($this->db->field_exists('oqrs', 'station_profile')) {
			$this->dbforge->drop_column('station_profile', 'oqrs');
		}
		if ($this->db->field_exists('oqrs_text', 'station_profile')) {
			$this->dbforge->drop_column('station_profile', 'oqrs_text');
		}
	}
}