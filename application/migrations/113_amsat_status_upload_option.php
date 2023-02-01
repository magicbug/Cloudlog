<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_amsat_status_upload_option
 *
 * Creates a boolean column with option to allow for activating uploading
 * a status info to https://amsat.org/status
 */

class Migration_amsat_status_upload_option extends CI_Migration {

        public function up()
        {
                if (!$this->db->field_exists('user_amsat_status_upload', 'users')) {
                        $fields = array(
                                'user_amsat_status_upload BOOLEAN DEFAULT FALSE',
                        );
                        $this->dbforge->add_column('users', $fields, 'user_column5');
                }
        }

        public function down()
        {
                $this->dbforge->drop_column('users', 'user_amsat_status_upload');
        }
}
