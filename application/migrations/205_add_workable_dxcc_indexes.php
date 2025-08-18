<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_add_workable_dxcc_indexes extends CI_Migration
{

    public function up()
    {
        // Add composite index for workable DXCC queries
        // This will greatly improve performance for COL_COUNTRY + station_id + COL_PROP_MODE queries
        $this->db->db_debug = false;

        // Check if index already exists
        $index_exists = $this->db->query("SHOW INDEX FROM " . $this->config->item('table_name') . " WHERE Key_name = 'idx_workable_dxcc'")->num_rows();

        if ($index_exists == 0) {
            $sql = "ALTER TABLE " . $this->config->item('table_name') . " ADD INDEX `idx_workable_dxcc` (`COL_COUNTRY`, `station_id`, `COL_PROP_MODE`)";
            $this->db->query($sql);
        }

        // Add index for confirmation status columns
        $conf_index_exists = $this->db->query("SHOW INDEX FROM " . $this->config->item('table_name') . " WHERE Key_name = 'idx_qsl_confirmations'")->num_rows();

        if ($conf_index_exists == 0) {
            $sql = "ALTER TABLE " . $this->config->item('table_name') . " ADD INDEX `idx_qsl_confirmations` (`COL_QSL_RCVD`, `COL_LOTW_QSL_RCVD`, `COL_EQSL_QSL_RCVD`, `COL_QRZCOM_QSO_DOWNLOAD_STATUS`)";
            $this->db->query($sql);
        }

        $this->db->db_debug = true;
    }

    public function down()
    {
        $this->db->db_debug = false;

        // Drop the indexes if they exist
        $index_exists = $this->db->query("SHOW INDEX FROM " . $this->config->item('table_name') . " WHERE Key_name = 'idx_workable_dxcc'")->num_rows();
        if ($index_exists > 0) {
            $this->db->query("ALTER TABLE " . $this->config->item('table_name') . " DROP INDEX `idx_workable_dxcc`");
        }

        $conf_index_exists = $this->db->query("SHOW INDEX FROM " . $this->config->item('table_name') . " WHERE Key_name = 'idx_qsl_confirmations'")->num_rows();
        if ($conf_index_exists > 0) {
            $this->db->query("ALTER TABLE " . $this->config->item('table_name') . " DROP INDEX `idx_qsl_confirmations`");
        }

        $this->db->db_debug = true;
    }
}
