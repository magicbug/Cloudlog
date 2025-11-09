<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_clubloguserfields extends CI_Migration {

        public function up()
        {
                // Check and add each field only if it does not exist
                $fields_to_add = array(
                        'user_clublog_name' => array(
                                'type' => 'VARCHAR',
                                'constraint' => 255,
                                'null' => TRUE,
                        ),
                        'user_clublog_password' => array(
                                'type' => 'VARCHAR',
                                'constraint' => 255,
                                'null' => TRUE,
                        ),
                        'user_clublog_callsign' => array(
                                'type' => 'VARCHAR',
                                'constraint' => 255,
                                'null' => TRUE,
                        ),
                );

                // Get current columns in 'users' table
                $fields = $this->db->list_fields('users');

                foreach ($fields_to_add as $field => $definition) {
                        if (!in_array($field, $fields)) {
                                $this->dbforge->add_column('users', array($field => $definition));
                        }
                }
        }

        public function down()
        {
                $this->dbforge->drop_column('users', 'user_clublog_name');
                $this->dbforge->drop_column('users', 'user_clublog_password');
                $this->dbforge->drop_column('users', 'user_clublog_callsign');
        }
}