<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_rda_award extends CI_Migration {

	public function up()
	{
		if (!$this->db->field_exists('rda', 'awardxuser')) {
			$this->dbforge->add_column('awardxuser', array(
				'rda' => array(
					'type' => 'TINYINT',
					'constraint' => 1,
					'default' => 1,
				),
			));
		}

		if ($this->db->table_exists('cron_jobs')) {
			$exists = $this->db->where('job_key', 'update_rda')->count_all_results('cron_jobs');
			if (!$exists) {
				$this->db->insert('cron_jobs', array(
					'job_key' => 'update_rda',
					'description' => 'Update RDA district list',
					'interval_seconds' => 2592000,
					'enabled' => 1,
					'legacy_uri' => 'update/update_rda',
				));
			}
		}
	}

	public function down()
	{
		if ($this->db->field_exists('rda', 'awardxuser')) {
			$this->dbforge->drop_column('awardxuser', 'rda');
		}
		if ($this->db->table_exists('cron_jobs')) {
			$this->db->where('job_key', 'update_rda')->delete('cron_jobs');
		}
	}
}
