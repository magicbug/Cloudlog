<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_create_eqsl_images_table
 *
 * Creates columnns reset_password_code and reset_password_time in the users table
 * 
 */

class Migration_add_reset_pass_to_users extends CI_Migration {

        public function up()
        {
                if (!$this->db->field_exists('reset_password_code', 'users')) {
                        $fields = array(
                                'reset_password_code varchar(50) DEFAULT NULL',
                        );
                        $this->dbforge->add_column('users', $fields);
                }

                if (!$this->db->field_exists('reset_password_date', 'users')) {
                        $fields = array(
                                'reset_password_date TIMESTAMP NULL DEFAULT NULL',
                        );
                        $this->dbforge->add_column('users', $fields);
                }
        }

        public function down()
        {
                $this->dbforge->drop_column('users', 'reset_password_code');
                $this->dbforge->drop_column('users', 'reset_password_date');
        }
}
