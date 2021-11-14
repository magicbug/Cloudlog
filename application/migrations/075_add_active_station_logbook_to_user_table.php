<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
*   This migration creates a table called options which will hold global options needed within cloudlog
*   removing the need for lots of configuration files.
*/

class Migration_add_active_station_logbook_to_user_table extends CI_Migration {

	public function up()
	{
		$fields = array(
			'active_station_logbook int(11)',
		);

		if (!$this->db->field_exists('active_station_logbook', 'users')) {
			$this->dbforge->add_column('users', $fields);
		}
	}

	public function down()
	{
		$this->dbforge->drop_column('users', 'active_station_logbook');
	}
}
