<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 *   This adds an option to enable public search per logbook
*/

class Migration_add_public_search_option extends CI_Migration {

	public function up()
	{
		if (!$this->db->field_exists('public_search', 'station_logbooks')) {
			$fields = array(
				'public_search integer DEFAULT 0 AFTER public_slug',
			);

			$this->dbforge->add_column('station_logbooks', $fields);
		}
	}

	public function down()
	{
		if ($this->db->field_exists('public_search', 'logbooks')) {
			$this->dbforge->drop_column('logbooks', 'public_search');
		}
	}
}
