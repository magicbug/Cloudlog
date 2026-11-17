<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
	Club Log log matches. A match is a QSO both stations uploaded to Club Log, which the RSGB
	and IOTA accept as a confirmation. The column names follow Wavelog so logs move between the two.
*/
class Migration_add_clublog_download extends CI_Migration {

	public function up()
	{
		$table = $this->config->item('table_name');
		if (!$this->db->field_exists('COL_CLUBLOG_QSO_DOWNLOAD_STATUS', $table)) {
			$this->dbforge->add_column($table, array(
				'COL_CLUBLOG_QSO_DOWNLOAD_DATE DATETIME NULL DEFAULT NULL',
				'COL_CLUBLOG_QSO_DOWNLOAD_STATUS VARCHAR(10) NULL DEFAULT NULL',
			));
			$this->db->query("ALTER TABLE " . $table . " ADD INDEX `idx_clublog_download_status` (`COL_CLUBLOG_QSO_DOWNLOAD_STATUS`)");
		}

		if ($this->db->table_exists('cron_jobs') && !$this->db->where('job_key', 'clublog_download')->count_all_results('cron_jobs')) {
			$this->db->insert('cron_jobs', array(
				'job_key' => 'clublog_download',
				'description' => 'Download Club Log log matches (confirmations)',
				'interval_seconds' => 86400,
				'enabled' => 1,
				'legacy_uri' => 'clublog/download',
			));
		}
	}

	public function down()
	{
		$table = $this->config->item('table_name');
		if ($this->db->field_exists('COL_CLUBLOG_QSO_DOWNLOAD_STATUS', $table)) {
			$this->dbforge->drop_column($table, 'COL_CLUBLOG_QSO_DOWNLOAD_STATUS');
		}
		if ($this->db->field_exists('COL_CLUBLOG_QSO_DOWNLOAD_DATE', $table)) {
			$this->dbforge->drop_column($table, 'COL_CLUBLOG_QSO_DOWNLOAD_DATE');
		}
		if ($this->db->table_exists('cron_jobs')) {
			$this->db->where('job_key', 'clublog_download')->delete('cron_jobs');
		}
	}
}
