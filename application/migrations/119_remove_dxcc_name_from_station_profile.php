<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_remove_dxcc_name_from_station_profile extends CI_Migration
{
	public function up()
	{
		if ($this->db->field_exists('station_country', 'station_profile')) {
			$this->dbforge->drop_column('station_profile', 'station_country');
		}	
	}

	public function down()
	{
		$fields = array(
			'station_country VARCHAR(255) NULL DEFAULT NULL AFTER `station_dxcc`',
		);

		if (!$this->db->field_exists('station_country', 'station_profile')) {
			$this->dbforge->add_column('station_profile', $fields);
		}

		$sql = 'UPDATE `station_profile` JOIN `dxcc_entities` ON `station_profile`.`station_dxcc` = `dxcc_entities`.`adif` SET `station_profile`.`station_country` = `dxcc_entities`.`name`;';
		$this->db->query($sql);
	}
}
