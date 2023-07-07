<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_hrdlog_fields extends CI_Migration {

	public function up()
	{
		if (!$this->db->field_exists('hrdlogrealtime', 'station_profile')) {
			$fields = array(
				'hrdlogrealtime tinyint(1)'
			);
			$this->dbforge->add_column('station_profile', $fields);
		}

		if (!$this->db->field_exists('hrdlog_code', 'station_profile')) {
			$fields = array(
				'hrdlog_code varchar(20) DEFAULT NULL',
			);
			$this->dbforge->add_column('station_profile', $fields);
		}

		if ( (!$this->db->field_exists('COL_HRDLOG_QSO_UPLOAD_DATE', $this->config->item('table_name'))) &&
			(!$this->db->field_exists('COL_HRDLOG_QSO_UPLOAD_STATUS', $this->config->item('table_name')))) {
			$fields = array(
				'COL_HRDLOG_QSO_UPLOAD_DATE datetime default NULL',
				'COL_HRDLOG_QSO_UPLOAD_STATUS varchar(10) default NULL'
			);
			$this->dbforge->add_column($this->config->item('table_name'), $fields);
		}
	}

	public function down()
	{
		if ($this->db->field_exists('hrdlogrealtime', 'station_profile')) {
			$this->dbforge->drop_column('station_profile', 'hrdlogrealtime');
		}
		if ($this->db->field_exists('hrdlog_code', 'station_profile')) {
			$this->dbforge->drop_column('station_profile', 'hrdlog_code');
		}
	}
}
