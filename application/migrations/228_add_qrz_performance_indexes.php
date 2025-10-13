<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_add_qrz_performance_indexes extends CI_Migration
{

    public function up()
    {
        $this->db->db_debug = false;
        
        // Get the table name from config
        $table_name = $this->config->item('table_name');
        
        // Add composite index for QRZ upload queries (station_id, COL_QRZCOM_QSO_UPLOAD_STATUS, COL_TIME_ON)
        try {
            $qrz_upload_index_exists = $this->db->query("SHOW INDEX FROM {$table_name} WHERE Key_name = 'idx_qrz_upload_status'")->num_rows();
            
            if ($qrz_upload_index_exists == 0) {
                $sql = "ALTER TABLE {$table_name} ADD INDEX `idx_qrz_upload_status` (`station_id`, `COL_QRZCOM_QSO_UPLOAD_STATUS`, `COL_TIME_ON`)";
                $this->db->query($sql);
            }
        } catch (Exception $e) {
            // Index might already exist due to concurrent execution, ignore duplicate key errors
            if (strpos($e->getMessage(), 'Duplicate key name') === false) {
                throw $e;
            }
        }
        
        // Add index for QRZ upload date filtering
        try {
            $qrz_upload_date_index_exists = $this->db->query("SHOW INDEX FROM {$table_name} WHERE Key_name = 'idx_qrz_upload_date'")->num_rows();
            
            if ($qrz_upload_date_index_exists == 0) {
                $sql = "ALTER TABLE {$table_name} ADD INDEX `idx_qrz_upload_date` (`COL_QRZCOM_QSO_UPLOAD_DATE`)";
                $this->db->query($sql);
            }
        } catch (Exception $e) {
            // Index might already exist due to concurrent execution, ignore duplicate key errors
            if (strpos($e->getMessage(), 'Duplicate key name') === false) {
                throw $e;
            }
        }
        
        // Add index for QRZ download status filtering
        try {
            $qrz_download_status_index_exists = $this->db->query("SHOW INDEX FROM {$table_name} WHERE Key_name = 'idx_qrz_download_status'")->num_rows();
            
            if ($qrz_download_status_index_exists == 0) {
                $sql = "ALTER TABLE {$table_name} ADD INDEX `idx_qrz_download_status` (`COL_QRZCOM_QSO_DOWNLOAD_STATUS`)";
                $this->db->query($sql);
            }
        } catch (Exception $e) {
            // Index might already exist due to concurrent execution, ignore duplicate key errors
            if (strpos($e->getMessage(), 'Duplicate key name') === false) {
                throw $e;
            }
        }
        
        // Add index for QRZ download date filtering
        try {
            $qrz_download_date_index_exists = $this->db->query("SHOW INDEX FROM {$table_name} WHERE Key_name = 'idx_qrz_download_date'")->num_rows();
            
            if ($qrz_download_date_index_exists == 0) {
                $sql = "ALTER TABLE {$table_name} ADD INDEX `idx_qrz_download_date` (`COL_QRZCOM_QSO_DOWNLOAD_DATE`)";
                $this->db->query($sql);
            }
        } catch (Exception $e) {
            // Index might already exist due to concurrent execution, ignore duplicate key errors
            if (strpos($e->getMessage(), 'Duplicate key name') === false) {
                throw $e;
            }
        }
        
        // Add composite index for callsign lookups with time and band (used in QRZ download processing)
        try {
            $call_time_band_index_exists = $this->db->query("SHOW INDEX FROM {$table_name} WHERE Key_name = 'idx_call_time_band'")->num_rows();
            
            if ($call_time_band_index_exists == 0) {
                $sql = "ALTER TABLE {$table_name} ADD INDEX `idx_call_time_band` (`COL_CALL`, `COL_TIME_ON`, `COL_BAND`)";
                $this->db->query($sql);
            }
        } catch (Exception $e) {
            // Index might already exist due to concurrent execution, ignore duplicate key errors
            if (strpos($e->getMessage(), 'Duplicate key name') === false) {
                throw $e;
            }
        }
        
        $this->db->db_debug = true;
    }

    public function down()
    {
        $this->db->db_debug = false;
        
        // Get the table name from config
        $table_name = $this->config->item('table_name');
        
        // Drop the QRZ upload status composite index if it exists
        $qrz_upload_index_exists = $this->db->query("SHOW INDEX FROM {$table_name} WHERE Key_name = 'idx_qrz_upload_status'")->num_rows();
        
        if ($qrz_upload_index_exists > 0) {
            $sql = "ALTER TABLE {$table_name} DROP INDEX `idx_qrz_upload_status`";
            $this->db->query($sql);
        }
        
        // Drop the QRZ upload date index if it exists
        $qrz_upload_date_index_exists = $this->db->query("SHOW INDEX FROM {$table_name} WHERE Key_name = 'idx_qrz_upload_date'")->num_rows();
        
        if ($qrz_upload_date_index_exists > 0) {
            $sql = "ALTER TABLE {$table_name} DROP INDEX `idx_qrz_upload_date`";
            $this->db->query($sql);
        }
        
        // Drop the QRZ download status index if it exists
        $qrz_download_status_index_exists = $this->db->query("SHOW INDEX FROM {$table_name} WHERE Key_name = 'idx_qrz_download_status'")->num_rows();
        
        if ($qrz_download_status_index_exists > 0) {
            $sql = "ALTER TABLE {$table_name} DROP INDEX `idx_qrz_download_status`";
            $this->db->query($sql);
        }
        
        // Drop the QRZ download date index if it exists
        $qrz_download_date_index_exists = $this->db->query("SHOW INDEX FROM {$table_name} WHERE Key_name = 'idx_qrz_download_date'")->num_rows();
        
        if ($qrz_download_date_index_exists > 0) {
            $sql = "ALTER TABLE {$table_name} DROP INDEX `idx_qrz_download_date`";
            $this->db->query($sql);
        }
        
        // Drop the call/time/band composite index if it exists
        $call_time_band_index_exists = $this->db->query("SHOW INDEX FROM {$table_name} WHERE Key_name = 'idx_call_time_band'")->num_rows();
        
        if ($call_time_band_index_exists > 0) {
            $sql = "ALTER TABLE {$table_name} DROP INDEX `idx_call_time_band`";
            $this->db->query($sql);
        }
        
        $this->db->db_debug = true;
    }
}