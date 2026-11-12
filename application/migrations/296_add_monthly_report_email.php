<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
	Opt-in monthly activity report email. The job runs daily and sends the previous calendar
	month during the first week; the send log keeps one row per user per month.
*/
class Migration_add_monthly_report_email extends CI_Migration {

	public function up()
	{
		if (!$this->db->table_exists('monthly_report_email_log')) {
			$this->dbforge->add_field(array(
				'id' => array(
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => TRUE,
					'auto_increment' => TRUE,
				),
				'user_id' => array(
					'type' => 'INT',
					'constraint' => 11,
					'null' => FALSE,
				),
				'period' => array(
					'type' => 'CHAR',
					'constraint' => 7,
					'null' => FALSE,
				),
				'logbook_id' => array(
					'type' => 'INT',
					'constraint' => 11,
					'null' => TRUE,
				),
				'status' => array(
					'type' => 'VARCHAR',
					'constraint' => 32,
					'null' => FALSE,
				),
				'sent_at' => array(
					'type' => 'DATETIME',
					'null' => FALSE,
				),
				'error' => array(
					'type' => 'TEXT',
					'null' => TRUE,
				),
			));
			$this->dbforge->add_key('id', TRUE);
			$this->dbforge->create_table('monthly_report_email_log');
			$this->db->query("ALTER TABLE monthly_report_email_log ADD UNIQUE INDEX `idx_user_period` (`user_id`, `period`)");
		}

		if ($this->db->table_exists('cron_jobs') && !$this->db->where('job_key', 'monthly_report_email')->count_all_results('cron_jobs')) {
			$this->db->insert('cron_jobs', array(
				'job_key' => 'monthly_report_email',
				'description' => 'Email opted-in users their monthly activity report',
				'interval_seconds' => 86400,
				'enabled' => 1,
				'legacy_uri' => 'monthlyreport/send_scheduled',
			));
		}
	}

	public function down()
	{
		if ($this->db->table_exists('monthly_report_email_log')) {
			$this->dbforge->drop_table('monthly_report_email_log');
		}
		if ($this->db->table_exists('cron_jobs')) {
			$this->db->where('job_key', 'monthly_report_email')->delete('cron_jobs');
		}
	}
}
