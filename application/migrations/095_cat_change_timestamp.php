<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_rename_profile_images
 *
 * Renames the qrz profile images column to also make it
 * suitable for hamqth.com
 * 
 */

class Migration_cat_change_timestamp extends CI_Migration {

        public function up()
        {
                if ($this->db->field_exists('timestamp', 'cat')) {
                    $this->db->query("ALTER TABLE `cat` CHANGE `timestamp` `timestamp` TIMESTAMP NULL DEFAULT NULL;");
                }
        }

        public function down()
        {

        }
}
