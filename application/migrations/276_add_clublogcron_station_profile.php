<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_add_clublogcron_station_profile extends CI_Migration
{
	public function up()
	{
		$fields = array(
			'clublogcron TINYINT NOT NULL DEFAULT 0 AFTER `clublogrealtime`',
		);

		if (!$this->db->field_exists('clublogcron', 'station_profile')) {
			$this->dbforge->add_column('station_profile', $fields);
		}
	}

	public function down()
	{
		if ($this->db->field_exists('clublogcron', 'station_profile')) {
			$this->dbforge->drop_column('station_profile', 'clublogcron');
		}
	}
}
