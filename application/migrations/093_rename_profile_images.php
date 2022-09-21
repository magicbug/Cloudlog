<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_rename_profile_images
 *
 * Renames the qrz profile images column to also make it
 * suitable for hamqth.com
 * 
 */

class Migration_rename_profile_images extends CI_Migration {

        public function up()
        {
                if ($this->db->field_exists('user_show_qrz_image', 'users') && !$this->db->field_exists('user_show_profile_image', 'users')) {
                        $fields = array(
                                'user_show_qrz_image' => [ 'name' => 'user_show_profile_image', 'type' => ' BOOLEAN DEFAULT FALSE', ]
                        );
                        $this->dbforge->modify_column('users', $fields);
                }
        }

        public function down()
        {
                if ($this->db->field_exists('user_show_profile_image', 'users')) {
                        $fields = array(
                                'user_show_profile_image' => [ 'name' => 'user_show_qrz_image', 'type' => ' BOOLEAN DEFAULT FALSE', ]
                        );
                        $this->dbforge->modify_column('users', $fields);
                }
        }
}
