<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Add indexes to CAT table for improved performance
 * 
 * This migration adds indexes to the CAT table based on common query patterns:
 * 1. user_id - used in almost every query for multi-user filtering
 * 2. radio, user_id - composite index for radio lookups by specific users
 * 3. timestamp - used for recent_status() queries and CAT timeout checks
 * 4. user_id, timestamp - composite index for user-specific recent queries
 */
class Migration_add_cat_table_indexes extends CI_Migration {

    public function up()
    {
        if ($this->db->table_exists('cat')) {
            
            // Add index on user_id (most frequent filter condition)
            $this->db->query("ALTER TABLE `cat` ADD INDEX `idx_user_id` (`user_id`)");
            
            // Add composite index on radio and user_id (used in update() method for finding existing radio records)
            $this->db->query("ALTER TABLE `cat` ADD INDEX `idx_radio_user` (`radio`, `user_id`)");
            
            // Add index on timestamp (used in recent_status() for filtering recent data)
            $this->db->query("ALTER TABLE `cat` ADD INDEX `idx_timestamp` (`timestamp`)");
            
            // Add composite index on user_id and timestamp (optimizes recent_status() queries)
            $this->db->query("ALTER TABLE `cat` ADD INDEX `idx_user_timestamp` (`user_id`, `timestamp`)");
        }
    }

    public function down()
    {
        if ($this->db->table_exists('cat')) {
            // Remove the indexes in reverse order
            $this->db->query("ALTER TABLE `cat` DROP INDEX `idx_user_timestamp`");
            $this->db->query("ALTER TABLE `cat` DROP INDEX `idx_timestamp`");
            $this->db->query("ALTER TABLE `cat` DROP INDEX `idx_radio_user`");
            $this->db->query("ALTER TABLE `cat` DROP INDEX `idx_user_id`");
        }
    }
}