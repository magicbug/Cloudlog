<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_add_qrz_down extends CI_Migration
{
	public function up() {
                if (!$this->db->field_exists('COL_QRZCOM_QSO_DOWNLOAD_STATUS', $this->config->item('table_name'))) {
                        $fields = array(
                                'COLUMN COL_QRZCOM_QSO_DOWNLOAD_DATE DATETIME NULL DEFAULT NULL',
                                'COLUMN COL_QRZCOM_QSO_DOWNLOAD_STATUS VARCHAR(10) DEFAULT NULL',
                        );
                        $this->dbforge->add_column($this->config->item('table_name'), $fields);
		}
	}

	public function down() {
                if ($this->db->field_exists('COL_QRZCOM_QSO_DOWNLOAD_STATUS', $this->config->item('table_name'))) {
                        $this->dbforge->drop_column($this->config->item('table_name'), 'COL_QRZCOM_QSO_DOWNLOAD_STATUS');
                }
                if ($this->db->field_exists('COL_QRZCOM_QSO_DOWNLOAD_DATE', $this->config->item('table_name'))) {
                        $this->dbforge->drop_column($this->config->item('table_name'), 'COL_QRZCOM_QSO_DOWNLOAD_DATE');
                }
	}
}
