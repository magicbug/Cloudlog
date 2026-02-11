<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Add indexes to optimize dashboard query performance
 * 
 * This migration adds composite indexes to speed up:
 * 1. Countries statistics query (DXCC aggregation)
 * 2. VUCC gridsquare queries with band filtering
 * 3. Date-based QSL statistics
 */
class Migration_add_dashboard_performance_indexes extends CI_Migration
{

    public function up()
    {
        $this->db->db_debug = false;
        
        $table_name = $this->config->item('table_name');
        
        // Optimize countries statistics query
        // Helps: COUNT(DISTINCT COL_COUNTRY) with JOIN to dxcc_entities
        // Covers station_id filter + COL_DXCC validation + COL_COUNTRY grouping
        $dxcc_stats_index_exists = $this->db->query("SHOW INDEX FROM $table_name WHERE Key_name = 'idx_dxcc_stats'")->num_rows();
        
        if ($dxcc_stats_index_exists == 0) {
            $sql = "ALTER TABLE $table_name ADD INDEX `idx_dxcc_stats` (`station_id`, `COL_DXCC`, `COL_COUNTRY`)";
            $this->db->query($sql);
        }
        
        // Optimize VUCC gridsquare queries for non-SAT bands
        // Covers: station_id + col_gridsquare + col_prop_mode + col_band filters
        // Used by get_vucc_summary() for VHF/UHF/SHF gridsquare statistics
        $vucc_grid_index_exists = $this->db->query("SHOW INDEX FROM $table_name WHERE Key_name = 'idx_vucc_gridsquare'")->num_rows();
        
        if ($vucc_grid_index_exists == 0) {
            $sql = "ALTER TABLE $table_name ADD INDEX `idx_vucc_gridsquare` (`station_id`, `col_gridsquare`(4), `col_prop_mode`, `col_band`)";
            $this->db->query($sql);
        }
        
        // Optimize VUCC col_vucc_grids queries
        // Covers: station_id + col_vucc_grids filter + prop_mode + QSL confirmation status
        $vucc_grids_index_exists = $this->db->query("SHOW INDEX FROM $table_name WHERE Key_name = 'idx_vucc_grids'")->num_rows();
        
        if ($vucc_grids_index_exists == 0) {
            $sql = "ALTER TABLE $table_name ADD INDEX `idx_vucc_grids` (`station_id`, `col_vucc_grids`(20), `col_prop_mode`)";
            $this->db->query($sql);
        }
        
        $this->db->db_debug = true;
    }

    public function down()
    {
        $this->db->db_debug = false;
        
        $table_name = $this->config->item('table_name');
        
        // Drop the DXCC stats index if it exists
        $dxcc_stats_index_exists = $this->db->query("SHOW INDEX FROM $table_name WHERE Key_name = 'idx_dxcc_stats'")->num_rows();
        
        if ($dxcc_stats_index_exists > 0) {
            $sql = "ALTER TABLE $table_name DROP INDEX `idx_dxcc_stats`";
            $this->db->query($sql);
        }
        
        // Drop the VUCC gridsquare index if it exists
        $vucc_grid_index_exists = $this->db->query("SHOW INDEX FROM $table_name WHERE Key_name = 'idx_vucc_gridsquare'")->num_rows();
        
        if ($vucc_grid_index_exists > 0) {
            $sql = "ALTER TABLE $table_name DROP INDEX `idx_vucc_gridsquare`";
            $this->db->query($sql);
        }
        
        // Drop the VUCC grids index if it exists
        $vucc_grids_index_exists = $this->db->query("SHOW INDEX FROM $table_name WHERE Key_name = 'idx_vucc_grids'")->num_rows();
        
        if ($vucc_grids_index_exists > 0) {
            $sql = "ALTER TABLE $table_name DROP INDEX `idx_vucc_grids`";
            $this->db->query($sql);
        }
        
        $this->db->db_debug = true;
    }
}
