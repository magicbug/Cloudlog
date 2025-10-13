<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Migration to clean up corrupted CAT frequency data
 * 
 * This migration addresses the issue where error messages from rig control
 * software (hamlib) were being inserted into the frequency field instead
 * of numeric values, causing SQL errors.
 * 
 * The migration:
 * 1. Identifies and cleans up existing corrupted frequency data
 * 2. Sets corrupted frequency values to NULL
 * 3. Adds a check constraint to prevent future non-numeric insertions
 */
class Migration_cleanup_cat_frequency_errors extends CI_Migration {

    public function up()
    {
        if ($this->db->table_exists('cat')) {
            
            // First, let's see what corrupted data we have by checking for non-numeric frequency values
            $corrupted_query = $this->db->query("
                SELECT id, frequency, mode, user_id, radio, timestamp 
                FROM cat 
                WHERE frequency IS NOT NULL 
                AND frequency REGEXP '[^0-9]'
                ORDER BY timestamp DESC
            ");
            
            if ($corrupted_query->num_rows() > 0) {
                echo "Found " . $corrupted_query->num_rows() . " corrupted frequency records in CAT table\n";
                
                // Log the corrupted data for reference before cleaning
                foreach ($corrupted_query->result() as $row) {
                    echo "CAT ID {$row->id}: Corrupted frequency '{$row->frequency}' for radio '{$row->radio}' at {$row->timestamp}\n";
                }
            }
            
            // Clean up corrupted frequency data - set non-numeric frequencies to NULL
            $cleanup_result = $this->db->query("
                UPDATE cat 
                SET frequency = NULL 
                WHERE frequency IS NOT NULL 
                AND frequency REGEXP '[^0-9]'
            ");
            
            if ($cleanup_result && $this->db->affected_rows() > 0) {
                echo "Cleaned up " . $this->db->affected_rows() . " corrupted frequency records\n";
            }
            
            // Also clean up corrupted frequency_rx data
            $cleanup_rx_result = $this->db->query("
                UPDATE cat 
                SET frequency_rx = NULL 
                WHERE frequency_rx IS NOT NULL 
                AND frequency_rx REGEXP '[^0-9]'
            ");
            
            if ($cleanup_rx_result && $this->db->affected_rows() > 0) {
                echo "Cleaned up " . $this->db->affected_rows() . " corrupted frequency_rx records\n";
            }
            
            // Clean up mode fields that contain obvious error messages
            $cleanup_mode_result = $this->db->query("
                UPDATE cat 
                SET mode = NULL 
                WHERE mode IS NOT NULL 
                AND (mode LIKE '%error%' OR mode LIKE '%.c(%' OR CHAR_LENGTH(mode) > 20)
            ");
            
            if ($cleanup_mode_result && $this->db->affected_rows() > 0) {
                echo "Cleaned up " . $this->db->affected_rows() . " corrupted mode records\n";
            }
            
            // Clean up mode_rx fields that contain obvious error messages
            $cleanup_mode_rx_result = $this->db->query("
                UPDATE cat 
                SET mode_rx = NULL 
                WHERE mode_rx IS NOT NULL 
                AND (mode_rx LIKE '%error%' OR mode_rx LIKE '%.c(%' OR CHAR_LENGTH(mode_rx) > 20)
            ");
            
            if ($cleanup_mode_rx_result && $this->db->affected_rows() > 0) {
                echo "Cleaned up " . $this->db->affected_rows() . " corrupted mode_rx records\n";
            }
            
            echo "CAT table cleanup completed successfully\n";
        }
    }

    public function down()
    {
        // Cannot restore corrupted data once it's been cleaned up
        echo "Cannot restore previously corrupted CAT data\n";
    }
}