<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_optimize_qso_indexes extends CI_Migration
{

    public function up()
    {
        $this->db->db_debug = false;
        
        // Get the table name from config
        $table_name = $this->config->item('table_name');
        
        // Add efficient composite index for QSO sorting (station_id, COL_TIME_ON, COL_PRIMARY_KEY)
        $efficient_sort_exists = $this->db->query("SHOW INDEX FROM {$table_name} WHERE Key_name = 'idx_efficient_sort'")->num_rows();
        
        if ($efficient_sort_exists == 0) {
            $sql = "ALTER TABLE {$table_name} ADD INDEX `idx_efficient_sort` (`station_id`, `COL_TIME_ON`, `COL_PRIMARY_KEY`)";
            $this->db->query($sql);
        }
        
        // Drop the single-column index on station_id if it exists
        $station_id_index_exists = $this->db->query("SHOW INDEX FROM {$table_name} WHERE Key_name = 'station_id'")->num_rows();
        
        if ($station_id_index_exists > 0) {
            $sql = "ALTER TABLE {$table_name} DROP INDEX `station_id`";
            $this->db->query($sql);
        }
        
        // Drop the single-column index on COL_TIME_ON if it exists
        $time_on_index_exists = $this->db->query("SHOW INDEX FROM {$table_name} WHERE Key_name = 'HRD_IDX_COL_TIME_ON'")->num_rows();
        
        if ($time_on_index_exists > 0) {
            $sql = "ALTER TABLE {$table_name} DROP INDEX `HRD_IDX_COL_TIME_ON`";
            $this->db->query($sql);
        }
        
        $this->db->db_debug = true;
    }

    public function down()
    {
        $this->db->db_debug = false;
        
        // Get the table name from config
        $table_name = $this->config->item('table_name');
        
        // Drop the composite index if it exists
        $efficient_sort_exists = $this->db->query("SHOW INDEX FROM {$table_name} WHERE Key_name = 'idx_efficient_sort'")->num_rows();
        
        if ($efficient_sort_exists > 0) {
            $sql = "ALTER TABLE {$table_name} DROP INDEX `idx_efficient_sort`";
            $this->db->query($sql);
        }
        
        // Re-create the single-column station_id index if it doesn't exist
        $station_id_index_exists = $this->db->query("SHOW INDEX FROM {$table_name} WHERE Key_name = 'station_id'")->num_rows();
        
        if ($station_id_index_exists == 0) {
            $sql = "ALTER TABLE {$table_name} ADD INDEX `station_id` (`station_id`)";
            $this->db->query($sql);
        }
        
        // Re-create the single-column COL_TIME_ON index if it doesn't exist
        $time_on_index_exists = $this->db->query("SHOW INDEX FROM {$table_name} WHERE Key_name = 'HRD_IDX_COL_TIME_ON'")->num_rows();
        
        if ($time_on_index_exists == 0) {
            $sql = "ALTER TABLE {$table_name} ADD INDEX `HRD_IDX_COL_TIME_ON` (`COL_TIME_ON`)";
            $this->db->query($sql);
        }
        
        $this->db->db_debug = true;
    }
}