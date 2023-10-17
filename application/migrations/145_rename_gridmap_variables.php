<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_rename_gridmap_variables
 *
 * Renames the default band and confirmation variables
 * in order to use them more generally
 * 
 */

class Migration_rename_gridmap_variables extends CI_Migration {

        public function up()
        {
                if ($this->db->field_exists('user_gridmap_default_band', 'users')) {
                        $this->db->query("ALTER TABLE `users` CHANGE `user_gridmap_default_band` `user_default_band` VARCHAR(10) DEFAULT NULL;");
                }
                if ($this->db->field_exists('user_gridmap_confirmation', 'users')) {
                        $this->db->query("ALTER TABLE `users` CHANGE `user_gridmap_confirmation` `user_default_confirmation` VARCHAR(3) DEFAULT NULL;");
                }
        }

        public function down()
        {
                if ($this->db->field_exists('user_default_band', 'users')) {
                        $this->db->query("ALTER TABLE `users` CHANGE `user_default_band` `user_gridmap_default_band` VARCHAR(10) DEFAULT NULL;");
                }
                if ($this->db->field_exists('user_default_confirmation', 'users')) {
                        $this->db->query("ALTER TABLE `users` CHANGE `user_default_confirmation` `user_gridmap_confirmation` VARCHAR(3) DEFAULT NULL;");
                }
        }
}
