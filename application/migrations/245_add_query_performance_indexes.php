<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_add_query_performance_indexes extends CI_Migration
{

    public function up()
    {
        $this->db->db_debug = false;
        
        $table_name = $this->config->item('table_name');
        
        // Add index on COL_MODE for get_modes() and mode filtering
        $mode_index_exists = $this->db->query("SHOW INDEX FROM $table_name WHERE Key_name = 'idx_mode'")->num_rows();
        
        if ($mode_index_exists == 0) {
            $sql = "ALTER TABLE $table_name ADD INDEX `idx_mode` (`COL_MODE`)";
            $this->db->query($sql);
        }
        
        // Add index on COL_SAT_NAME for satellite-related queries
        $sat_index_exists = $this->db->query("SHOW INDEX FROM $table_name WHERE Key_name = 'idx_sat_name'")->num_rows();
        
        if ($sat_index_exists == 0) {
            $sql = "ALTER TABLE $table_name ADD INDEX `idx_sat_name` (`COL_SAT_NAME`)";
            $this->db->query($sql);
        }
        
        // Add index on COL_CONT for continent statistics
        $cont_index_exists = $this->db->query("SHOW INDEX FROM $table_name WHERE Key_name = 'idx_continent'")->num_rows();
        
        if ($cont_index_exists == 0) {
            $sql = "ALTER TABLE $table_name ADD INDEX `idx_continent` (`COL_CONT`)";
            $this->db->query($sql);
        }
        
        // Add composite index for QSL statistics (frequently queried together)
        $qsl_stats_index_exists = $this->db->query("SHOW INDEX FROM $table_name WHERE Key_name = 'idx_qsl_stats'")->num_rows();
        
        if ($qsl_stats_index_exists == 0) {
            $sql = "ALTER TABLE $table_name ADD INDEX `idx_qsl_stats` (`station_id`, `COL_QSL_RCVD`, `COL_LOTW_QSL_RCVD`, `COL_EQSL_QSL_RCVD`)";
            $this->db->query($sql);
        }
        
        // Add index on COL_HRDLOG_QSO_UPLOAD_STATUS for HRDLog upload queries
        $hrdlog_upload_index_exists = $this->db->query("SHOW INDEX FROM $table_name WHERE Key_name = 'idx_hrdlog_upload'")->num_rows();
        
        if ($hrdlog_upload_index_exists == 0) {
            $sql = "ALTER TABLE $table_name ADD INDEX `idx_hrdlog_upload` (`station_id`, `COL_HRDLOG_QSO_UPLOAD_STATUS`)";
            $this->db->query($sql);
        }
        
        // Add index on COL_QRZCOM_QSO_UPLOAD_STATUS for QRZ upload queries
        $qrz_upload_index_exists = $this->db->query("SHOW INDEX FROM $table_name WHERE Key_name = 'idx_qrz_upload'")->num_rows();
        
        if ($qrz_upload_index_exists == 0) {
            $sql = "ALTER TABLE $table_name ADD INDEX `idx_qrz_upload` (`station_id`, `COL_QRZCOM_QSO_UPLOAD_STATUS`)";
            $this->db->query($sql);
        }
        
        $this->db->db_debug = true;
    }

    public function down()
    {
        $this->db->db_debug = false;
        
        $table_name = $this->config->item('table_name');
        
        // Drop the mode index if it exists
        $mode_index_exists = $this->db->query("SHOW INDEX FROM $table_name WHERE Key_name = 'idx_mode'")->num_rows();
        
        if ($mode_index_exists > 0) {
            $sql = "ALTER TABLE $table_name DROP INDEX `idx_mode`";
            $this->db->query($sql);
        }
        
        // Drop the satellite name index if it exists
        $sat_index_exists = $this->db->query("SHOW INDEX FROM $table_name WHERE Key_name = 'idx_sat_name'")->num_rows();
        
        if ($sat_index_exists > 0) {
            $sql = "ALTER TABLE $table_name DROP INDEX `idx_sat_name`";
            $this->db->query($sql);
        }
        
        // Drop the continent index if it exists
        $cont_index_exists = $this->db->query("SHOW INDEX FROM $table_name WHERE Key_name = 'idx_continent'")->num_rows();
        
        if ($cont_index_exists > 0) {
            $sql = "ALTER TABLE $table_name DROP INDEX `idx_continent`";
            $this->db->query($sql);
        }
        
        // Drop the QSL stats composite index if it exists
        $qsl_stats_index_exists = $this->db->query("SHOW INDEX FROM $table_name WHERE Key_name = 'idx_qsl_stats'")->num_rows();
        
        if ($qsl_stats_index_exists > 0) {
            $sql = "ALTER TABLE $table_name DROP INDEX `idx_qsl_stats`";
            $this->db->query($sql);
        }
        
        // Drop the HRDLog upload index if it exists
        $hrdlog_upload_index_exists = $this->db->query("SHOW INDEX FROM $table_name WHERE Key_name = 'idx_hrdlog_upload'")->num_rows();
        
        if ($hrdlog_upload_index_exists > 0) {
            $sql = "ALTER TABLE $table_name DROP INDEX `idx_hrdlog_upload`";
            $this->db->query($sql);
        }
        
        // Drop the QRZ upload index if it exists
        $qrz_upload_index_exists = $this->db->query("SHOW INDEX FROM $table_name WHERE Key_name = 'idx_qrz_upload'")->num_rows();
        
        if ($qrz_upload_index_exists > 0) {
            $sql = "ALTER TABLE $table_name DROP INDEX `idx_qrz_upload`";
            $this->db->query($sql);
        }
        
        $this->db->db_debug = true;
    }
}
