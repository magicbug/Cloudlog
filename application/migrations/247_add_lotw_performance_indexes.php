<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_add_lotw_performance_indexes extends CI_Migration
{

    public function up()
    {
        $this->db->db_debug = false;
        
        $table_name = $this->config->item('table_name');
        
        // Add index on COL_LOTW_QSLRDATE for lotw_last_qsl_date() query
        // Dramatically improves performance when finding the last LOTW confirmation date
        $lotw_qslrdate_index_exists = $this->db->query("SHOW INDEX FROM $table_name WHERE Key_name = 'idx_lotw_qslrdate'")->num_rows();
        
        if ($lotw_qslrdate_index_exists == 0) {
            $sql = "ALTER TABLE $table_name ADD INDEX `idx_lotw_qslrdate` (`COL_LOTW_QSLRDATE`)";
            $this->db->query($sql);
        }
        
        // Add composite index for get_lotw_qsos_to_upload() query
        // This index covers the WHERE and ORDER BY clauses for efficient LOTW upload preparation
        // Format: (station_id, COL_LOTW_QSL_SENT, COL_TIME_ON)
        $lotw_upload_index_exists = $this->db->query("SHOW INDEX FROM $table_name WHERE Key_name = 'idx_lotw_qsos_to_upload'")->num_rows();
        
        if ($lotw_upload_index_exists == 0) {
            $sql = "ALTER TABLE $table_name ADD INDEX `idx_lotw_qsos_to_upload` (`station_id`, `COL_LOTW_QSL_SENT`, `COL_TIME_ON`)";
            $this->db->query($sql);
        }
        
        // Add composite index for LOTW/QRZ confirmation matching in batch downloads
        // Speeds up import_check(), lotw_update_batch(), and QRZ process_qrz_batch() JOIN operations
        // This index benefits both LOTW and QRZ download processing
        // Format: (COL_TIME_ON, COL_CALL, COL_BAND, COL_STATION_CALLSIGN)
        $confirmation_match_index_exists = $this->db->query("SHOW INDEX FROM $table_name WHERE Key_name = 'idx_confirmation_match'")->num_rows();
        
        if ($confirmation_match_index_exists == 0) {
            $sql = "ALTER TABLE $table_name ADD INDEX `idx_confirmation_match` (`COL_TIME_ON`, `COL_CALL`, `COL_BAND`, `COL_STATION_CALLSIGN`)";
            $this->db->query($sql);
        }
        
        $this->db->db_debug = true;
    }

    public function down()
    {
        $this->db->db_debug = false;
        
        $table_name = $this->config->item('table_name');
        
        // Drop the LOTW QSLRDATE index if it exists
        $lotw_qslrdate_index_exists = $this->db->query("SHOW INDEX FROM $table_name WHERE Key_name = 'idx_lotw_qslrdate'")->num_rows();
        
        if ($lotw_qslrdate_index_exists > 0) {
            $sql = "ALTER TABLE $table_name DROP INDEX `idx_lotw_qslrdate`";
            $this->db->query($sql);
        }
        
        // Drop the LOTW upload index if it exists
        $lotw_upload_index_exists = $this->db->query("SHOW INDEX FROM $table_name WHERE Key_name = 'idx_lotw_qsos_to_upload'")->num_rows();
        
        if ($lotw_upload_index_exists > 0) {
            $sql = "ALTER TABLE $table_name DROP INDEX `idx_lotw_qsos_to_upload`";
            $this->db->query($sql);
        }
        
        // Drop the eQSL QSLRDATE index if it exists
        $eqsl_qslrdate_index_exists = $this->db->query("SHOW INDEX FROM $table_name WHERE Key_name = 'idx_eqsl_qslrdate'")->num_rows();
        
        if ($eqsl_qslrdate_index_exists > 0) {
            $sql = "ALTER TABLE $table_name DROP INDEX `idx_eqsl_qslrdate`";
            $this->db->query($sql);
        }
        
        // Drop the LOTW/QRZ/eQSL confirmation match index if it exists
        $confirmation_match_index_exists = $this->db->query("SHOW INDEX FROM $table_name WHERE Key_name = 'idx_confirmation_match'")->num_rows();
        
        if ($confirmation_match_index_exists > 0) {
            $sql = "ALTER TABLE $table_name DROP INDEX `idx_confirmation_match`";
            $this->db->query($sql);
        }
        
        $this->db->db_debug = true;
    }
}
