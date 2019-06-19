<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_clubloguserfields extends CI_Migration {

        public function up()
        {
                $user_fields = array(
                        'user_clublog_name VARCHAR(255) DEFAULT NULL',
                        'user_clublog_password VARCHAR(255) DEFAULT NULL',
                        'user_clublog_callsign VARCHAR(255) DEFAULT NULL'
                );
                $this->dbforge->add_column('users', $user_fields);
        }

        public function down()
        {
                $this->dbforge->drop_column('users', 'user_clublog_name');
                $this->dbforge->drop_column('users', 'user_clublog_password');
                $this->dbforge->drop_column('users', 'user_clublog_callsign');
        }
}