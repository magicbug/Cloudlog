<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_add_gridsquare_prefix_index extends CI_Migration
{

    public function up()
    {
        $this->db->db_debug = false;
        
        $table_name = $this->config->item('table_name');
        
        // Add prefix index on COL_GRIDSQUARE for VUCC and grid searches (first 4 characters)
        // This dramatically improves performance for LIKE queries on gridsquares (e.g., WHERE COL_GRIDSQUARE LIKE 'IO91%')
        $gridsquare_index_exists = $this->db->query("SHOW INDEX FROM $table_name WHERE Key_name = 'idx_gridsquare_prefix'")->num_rows();
        
        if ($gridsquare_index_exists == 0) {
            $sql = "ALTER TABLE $table_name ADD INDEX `idx_gridsquare_prefix` (`COL_GRIDSQUARE`(4))";
            $this->db->query($sql);
        }
        
        $this->db->db_debug = true;
    }

    public function down()
    {
        $this->db->db_debug = false;
        
        $table_name = $this->config->item('table_name');
        
        // Drop the gridsquare prefix index if it exists
        $gridsquare_index_exists = $this->db->query("SHOW INDEX FROM $table_name WHERE Key_name = 'idx_gridsquare_prefix'")->num_rows();
        
        if ($gridsquare_index_exists > 0) {
            $sql = "ALTER TABLE $table_name DROP INDEX `idx_gridsquare_prefix`";
            $this->db->query($sql);
        }
        
        $this->db->db_debug = true;
    }
}
