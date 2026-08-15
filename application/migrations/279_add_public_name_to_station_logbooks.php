<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
 * Optional public display name for visitor pages, separate from the internal logbook name.
 */

class Migration_add_public_name_to_station_logbooks extends CI_Migration
{

	public function up()
	{
		if (!$this->db->field_exists('public_name', 'station_logbooks')) {
			$fields = array(
				'public_name VARCHAR(100) NULL DEFAULT NULL AFTER public_slug',
			);

			$this->dbforge->add_column('station_logbooks', $fields);
		}
	}

	public function down()
	{
		if ($this->db->field_exists('public_name', 'station_logbooks')) {
			$this->dbforge->drop_column('station_logbooks', 'public_name');
		}
	}
}
