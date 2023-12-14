<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_add_clublog_realtime extends CI_Migration
{
	public function up()
	{
		$fields = array(
			'clublogrealtime TINYINT NOT NULL DEFAULT 0 AFTER `webadifrealtime`',
		);

		if (!$this->db->field_exists('clublogrealtime', 'station_profile')) {
			$this->dbforge->add_column('station_profile', $fields);
		}
	}

	public function down()
	{

		if ($this->db->field_exists('clublogrealtime', 'station_profile')) {
			$this->dbforge->drop_column('station_profile', 'clublogrealtime');
		}	
	}
}
