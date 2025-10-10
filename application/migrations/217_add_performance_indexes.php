<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_add_performance_indexes extends CI_Migration
{

    public function up()
    {
        $this->db->db_debug = false;
        
        // Add index on station_profile.user_id for better user filtering performance
        $user_id_index_exists = $this->db->query("SHOW INDEX FROM station_profile WHERE Key_name = 'idx_user_id'")->num_rows();
        
        if ($user_id_index_exists == 0) {
            $sql = "ALTER TABLE station_profile ADD INDEX `idx_user_id` (`user_id`)";
            $this->db->query($sql);
        }
        
        // Add index on qsl_images.qsoid for better QSL image JOIN performance
        $qsoid_index_exists = $this->db->query("SHOW INDEX FROM qsl_images WHERE Key_name = 'idx_qsoid'")->num_rows();
        
        if ($qsoid_index_exists == 0) {
            $sql = "ALTER TABLE qsl_images ADD INDEX `idx_qsoid` (`qsoid`)";
            $this->db->query($sql);
        }
        
        // Add index on lotw_users.callsign for better JOIN performance (in addition to unique constraint)
        $callsign_index_exists = $this->db->query("SHOW INDEX FROM lotw_users WHERE Key_name = 'idx_callsign'")->num_rows();
        
        if ($callsign_index_exists == 0) {
            $sql = "ALTER TABLE lotw_users ADD INDEX `idx_callsign` (`callsign`)";
            $this->db->query($sql);
        }
        
        $this->db->db_debug = true;
    }

    public function down()
    {
        $this->db->db_debug = false;
        
        // Drop the user_id index if it exists
        $user_id_index_exists = $this->db->query("SHOW INDEX FROM station_profile WHERE Key_name = 'idx_user_id'")->num_rows();
        
        if ($user_id_index_exists > 0) {
            $sql = "ALTER TABLE station_profile DROP INDEX `idx_user_id`";
            $this->db->query($sql);
        }
        
        // Drop the qsoid index if it exists
        $qsoid_index_exists = $this->db->query("SHOW INDEX FROM qsl_images WHERE Key_name = 'idx_qsoid'")->num_rows();
        
        if ($qsoid_index_exists > 0) {
            $sql = "ALTER TABLE qsl_images DROP INDEX `idx_qsoid`";
            $this->db->query($sql);
        }
        
        // Drop the lotw_users callsign index if it exists
        $callsign_index_exists = $this->db->query("SHOW INDEX FROM lotw_users WHERE Key_name = 'idx_callsign'")->num_rows();
        
        if ($callsign_index_exists > 0) {
            $sql = "ALTER TABLE lotw_users DROP INDEX `idx_callsign`";
            $this->db->query($sql);
        }
        
        $this->db->db_debug = true;
    }
}