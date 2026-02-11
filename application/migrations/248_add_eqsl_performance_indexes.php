<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_add_eqsl_performance_indexes extends CI_Migration
{

    public function up()
    {
        $this->db->db_debug = false;
        
        $table_name = $this->config->item('table_name');
        
        // Add index on COL_EQSL_QSLRDATE for eQSL last confirmation date queries
        // Improves performance for eqsl_last_qsl_rcvd_date() queries
        $eqsl_qslrdate_index_exists = $this->db->query("SHOW INDEX FROM $table_name WHERE Key_name = 'idx_eqsl_qslrdate'")->num_rows();
        
        if ($eqsl_qslrdate_index_exists == 0) {
            $sql = "ALTER TABLE $table_name ADD INDEX `idx_eqsl_qslrdate` (`COL_EQSL_QSLRDATE`)";
            $this->db->query($sql);
        }
        
        // Add composite index for eQSL confirmation matching in batch imports
        // eQSL matching requires MODE and station_id in addition to the basic fields
        // This index speeds up eqsl_update_batch() operations which use 15-minute time windows
        // Format: (COL_TIME_ON, COL_CALL, COL_BAND, COL_MODE, COL_STATION_CALLSIGN, station_id)
        $eqsl_match_index_exists = $this->db->query("SHOW INDEX FROM $table_name WHERE Key_name = 'idx_eqsl_confirmation_match'")->num_rows();
        
        if ($eqsl_match_index_exists == 0) {
            $sql = "ALTER TABLE $table_name ADD INDEX `idx_eqsl_confirmation_match` (`COL_TIME_ON`, `COL_CALL`, `COL_BAND`, `COL_MODE`, `COL_STATION_CALLSIGN`, `station_id`)";
            $this->db->query($sql);
        }
        
        $this->db->db_debug = true;
    }

    public function down()
    {
        $this->db->db_debug = false;
        
        $table_name = $this->config->item('table_name');
        
        // Drop the eQSL QSLRDATE index if it exists
        $eqsl_qslrdate_index_exists = $this->db->query("SHOW INDEX FROM $table_name WHERE Key_name = 'idx_eqsl_qslrdate'")->num_rows();
        
        if ($eqsl_qslrdate_index_exists > 0) {
            $sql = "ALTER TABLE $table_name DROP INDEX `idx_eqsl_qslrdate`";
            $this->db->query($sql);
        }
        
        // Drop the eQSL confirmation match index if it exists
        $eqsl_match_index_exists = $this->db->query("SHOW INDEX FROM $table_name WHERE Key_name = 'idx_eqsl_confirmation_match'")->num_rows();
        
        if ($eqsl_match_index_exists > 0) {
            $sql = "ALTER TABLE $table_name DROP INDEX `idx_eqsl_confirmation_match`";
            $this->db->query($sql);
        }
        
        $this->db->db_debug = true;
    }
}
