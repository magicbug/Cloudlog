<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
 * Migration: Add QRZCALL.EU support to Cloudlog
 *
 * Adds two columns to station_profile for storing the user's Personal Access
 * Token and real-time upload preference, and two columns to the QSO table
 * for tracking upload status.
 */

class Migration_add_qrzcall_to_cloudlog extends CI_Migration
{
    public function up()
    {
        // --- station_profile: QRZCALL.EU Personal Access Token ---
        if (!$this->db->field_exists('qrzcallapikey', 'station_profile')) {
            $fields = [
                'qrzcallapikey varchar(100) DEFAULT NULL',
            ];
            $this->dbforge->add_column('station_profile', $fields);
        }

        // --- station_profile: real-time upload toggle ---
        if (!$this->db->field_exists('qrzcallrealtime', 'station_profile')) {
            $fields = [
                'qrzcallrealtime tinyint(1) DEFAULT 0',
            ];
            $this->dbforge->add_column('station_profile', $fields);
        }

        // --- QSO table: upload status (NULL/N = pending, Y = uploaded, M = modified/re-upload) ---
        if (!$this->db->field_exists('COL_QRZCALL_QSO_UPLOAD_STATUS', $this->config->item('table_name'))) {
            $fields = [
                'COL_QRZCALL_QSO_UPLOAD_STATUS varchar(1) DEFAULT NULL',
            ];
            $this->dbforge->add_column($this->config->item('table_name'), $fields);
        }

        // --- QSO table: timestamp of last successful upload ---
        if (!$this->db->field_exists('COL_QRZCALL_QSO_UPLOAD_DATE', $this->config->item('table_name'))) {
            $fields = [
                'COL_QRZCALL_QSO_UPLOAD_DATE datetime DEFAULT NULL',
            ];
            $this->dbforge->add_column($this->config->item('table_name'), $fields);
        }
    }

    public function down()
    {
        if ($this->db->field_exists('qrzcallapikey', 'station_profile')) {
            $this->dbforge->drop_column('station_profile', 'qrzcallapikey');
        }

        if ($this->db->field_exists('qrzcallrealtime', 'station_profile')) {
            $this->dbforge->drop_column('station_profile', 'qrzcallrealtime');
        }

        if ($this->db->field_exists('COL_QRZCALL_QSO_UPLOAD_STATUS', $this->config->item('table_name'))) {
            $this->dbforge->drop_column($this->config->item('table_name'), 'COL_QRZCALL_QSO_UPLOAD_STATUS');
        }

        if ($this->db->field_exists('COL_QRZCALL_QSO_UPLOAD_DATE', $this->config->item('table_name'))) {
            $this->dbforge->drop_column($this->config->item('table_name'), 'COL_QRZCALL_QSO_UPLOAD_DATE');
        }
    }
}
