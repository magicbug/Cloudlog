<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_cron_jobs_table extends CI_Migration {

    public function up()
    {
        if (!$this->db->table_exists('cron_jobs')) {
            $this->dbforge->add_field(array(
                'id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE,
                ),
                'job_key' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 64,
                    'null' => FALSE,
                ),
                'description' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => FALSE,
                ),
                'interval_seconds' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'null' => FALSE,
                    'default' => 3600,
                ),
                'enabled' => array(
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'null' => FALSE,
                    'default' => 1,
                ),
                'legacy_uri' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => FALSE,
                ),
                'last_started_at' => array(
                    'type' => 'DATETIME',
                    'null' => TRUE,
                ),
                'last_finished_at' => array(
                    'type' => 'DATETIME',
                    'null' => TRUE,
                ),
                'last_status' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 32,
                    'null' => TRUE,
                ),
                'last_error' => array(
                    'type' => 'TEXT',
                    'null' => TRUE,
                ),
                'next_run_at' => array(
                    'type' => 'DATETIME',
                    'null' => TRUE,
                ),
                'last_source' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 16,
                    'null' => TRUE,
                ),
            ));

            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->add_key('job_key', TRUE);
            $this->dbforge->create_table('cron_jobs');
        }

        $jobs = array(
            array(
                'job_key' => 'clublog_upload',
                'description' => 'Upload QSOs to Club Log',
                'interval_seconds' => 21600,
                'enabled' => 1,
                'legacy_uri' => 'clublog/upload',
            ),
            array(
                'job_key' => 'lotw_sync',
                'description' => 'LoTW upload and download',
                'interval_seconds' => 3600,
                'enabled' => 1,
                'legacy_uri' => 'lotw/lotw_upload',
            ),
            array(
                'job_key' => 'hrdlog_upload',
                'description' => 'Upload QSOs to HRDLog.net',
                'interval_seconds' => 21600,
                'enabled' => 1,
                'legacy_uri' => 'hrdlog/upload',
            ),
            array(
                'job_key' => 'qrz_upload',
                'description' => 'Upload QSOs to QRZ.com',
                'interval_seconds' => 21600,
                'enabled' => 1,
                'legacy_uri' => 'qrz/upload',
            ),
            array(
                'job_key' => 'lotw_load_users',
                'description' => 'Download LoTW user activity CSV',
                'interval_seconds' => 604800,
                'enabled' => 1,
                'legacy_uri' => 'lotw/load_users',
            ),
            array(
                'job_key' => 'update_clublog_scp',
                'description' => 'Update Club Log SCP database file',
                'interval_seconds' => 604800,
                'enabled' => 1,
                'legacy_uri' => 'update/update_clublog_scp',
            ),
            array(
                'job_key' => 'update_dok',
                'description' => 'Update DOK reference data',
                'interval_seconds' => 2592000,
                'enabled' => 1,
                'legacy_uri' => 'update/update_dok',
            ),
            array(
                'job_key' => 'update_sota',
                'description' => 'Update SOTA reference data',
                'interval_seconds' => 2592000,
                'enabled' => 1,
                'legacy_uri' => 'update/update_sota',
            ),
            array(
                'job_key' => 'update_wwff',
                'description' => 'Update WWFF reference data',
                'interval_seconds' => 2592000,
                'enabled' => 1,
                'legacy_uri' => 'update/update_wwff',
            ),
            array(
                'job_key' => 'plugins_cron',
                'description' => 'Run enabled plugin cron hooks',
                'interval_seconds' => 3600,
                'enabled' => 1,
                'legacy_uri' => 'plugins/cron',
            ),
            array(
                'job_key' => 'update_dxcc',
                'description' => 'Update DXCC country data',
                'interval_seconds' => 86400,
                'enabled' => 1,
                'legacy_uri' => 'update/dxcc',
            ),
            array(
                'job_key' => 'update_lotw_users',
                'description' => 'Import LoTW users into the database',
                'interval_seconds' => 604800,
                'enabled' => 1,
                'legacy_uri' => 'update/lotw_users',
            ),
            array(
                'job_key' => 'update_pota',
                'description' => 'Update POTA reference data',
                'interval_seconds' => 2592000,
                'enabled' => 1,
                'legacy_uri' => 'update/update_pota',
            ),
            array(
                'job_key' => 'eqsl_sync',
                'description' => 'eQSL upload and download sync',
                'interval_seconds' => 21600,
                'enabled' => 1,
                'legacy_uri' => 'eqsl/sync',
            ),
            array(
                'job_key' => 'webadif_upload',
                'description' => 'Upload QSOs via webADIF (QO-100 Dx Club)',
                'interval_seconds' => 21600,
                'enabled' => 1,
                'legacy_uri' => 'webadif/upload',
            ),
        );

        foreach ($jobs as $job) {
            $exists = $this->db->where('job_key', $job['job_key'])->count_all_results('cron_jobs');
            if ($exists === 0) {
                $this->db->insert('cron_jobs', $job);
            }
        }

        if (!$this->db->where('option_name', 'cron_scheduler_enabled')->count_all_results('options')) {
            $this->db->insert('options', array(
                'option_name' => 'cron_scheduler_enabled',
                'option_value' => 'false',
                'autoload' => 'yes',
            ));
        }

        if (!$this->db->where('option_name', 'cron_scheduler_key')->count_all_results('options')) {
            $this->db->insert('options', array(
                'option_name' => 'cron_scheduler_key',
                'option_value' => '',
                'autoload' => 'yes',
            ));
        }
    }

    public function down()
    {
        if ($this->db->table_exists('cron_jobs')) {
            $this->dbforge->drop_table('cron_jobs');
        }

        $this->db->where('option_name', 'cron_scheduler_enabled');
        $this->db->delete('options');

        $this->db->where('option_name', 'cron_scheduler_key');
        $this->db->delete('options');
    }
}
