<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_add_lotw_composite_indexes extends CI_Migration
{
    public function up()
    {
        $this->db->db_debug = false;
        
        // Get the table name from config
        $table_name = $this->config->item('table_name');
        
        // Add composite index for LoTW upload queries
        // This optimizes the query in get_lotw_qsos_to_upload() which filters by:
        // station_id, COL_LOTW_QSL_SENT, COL_TIME_ON range, and COL_PROP_MODE
        $lotw_upload_index_exists = $this->db->query("SHOW INDEX FROM {$table_name} WHERE Key_name = 'idx_lotw_upload'")->num_rows();
        
        if ($lotw_upload_index_exists == 0) {
            $sql = "ALTER TABLE {$table_name} ADD INDEX `idx_lotw_upload` (`station_id`, `COL_LOTW_QSL_SENT`, `COL_TIME_ON`, `COL_PROP_MODE`)";
            $this->db->query($sql);
            log_message('info', 'Migration 218: Added idx_lotw_upload composite index to ' . $table_name);
        }
        
        // Add composite index for LoTW certificate lookups
        // This optimizes the query in lotw_cert_details() which filters by cert_dxcc_id and callsign
        if ($this->db->table_exists('lotw_certs')) {
            $cert_lookup_index_exists = $this->db->query("SHOW INDEX FROM lotw_certs WHERE Key_name = 'idx_lotw_cert_lookup'")->num_rows();
            
            if ($cert_lookup_index_exists == 0) {
                $sql = "ALTER TABLE lotw_certs ADD INDEX `idx_lotw_cert_lookup` (`cert_dxcc_id`, `callsign`)";
                $this->db->query($sql);
                log_message('info', 'Migration 218: Added idx_lotw_cert_lookup composite index to lotw_certs');
            }
        }
        
        $this->db->db_debug = true;
    }

    public function down()
    {
        $this->db->db_debug = false;
        
        $table_name = $this->config->item('table_name');
        
        // Drop LoTW upload index if it exists
        $lotw_upload_index_exists = $this->db->query("SHOW INDEX FROM {$table_name} WHERE Key_name = 'idx_lotw_upload'")->num_rows();
        if ($lotw_upload_index_exists > 0) {
            $sql = "ALTER TABLE {$table_name} DROP INDEX `idx_lotw_upload`";
            $this->db->query($sql);
            log_message('info', 'Migration 218: Dropped idx_lotw_upload index from ' . $table_name);
        }
        
        // Drop certificate lookup index if it exists
        if ($this->db->table_exists('lotw_certs')) {
            $cert_lookup_index_exists = $this->db->query("SHOW INDEX FROM lotw_certs WHERE Key_name = 'idx_lotw_cert_lookup'")->num_rows();
            if ($cert_lookup_index_exists > 0) {
                $sql = "ALTER TABLE lotw_certs DROP INDEX `idx_lotw_cert_lookup`";
                $this->db->query($sql);
                log_message('info', 'Migration 218: Dropped idx_lotw_cert_lookup index from lotw_certs');
            }
        }
        
        $this->db->db_debug = true;
    }
}