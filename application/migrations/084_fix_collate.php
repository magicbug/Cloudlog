<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_fix_collate extends CI_Migration
{
	public function up()
	{
		$tables = array(
			$this->config->item('table_name'),
			'adif_modes',
			'api',
			'cat',
			'config',
			'contest',
			'dxcc_entities',
			'dxcc_exceptions',
			'dxcc_prefixes',
			'eQSL_images',
			'iota',
			'lotw_certs',
			'migrations',
			'notes',
			'queries',
			'options',
			'qsl_images',
			'station_logbooks',
			'station_logbooks_relationship',
			'station_profile',
			'timezones',
			'users'
        );
		foreach ($tables as $table) {
			$this->db->query('ALTER TABLE ' . $table . ' CONVERT TO CHARACTER SET ' . $this->db->char_set . ' COLLATE ' . $this->db->dbcollat);
		}
	}

	public function down()
	{
		// Not Possible
	}
}
