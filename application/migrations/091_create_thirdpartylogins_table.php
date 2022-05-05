<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
*   This migration creates a table called thirdparty_logins
*   This table is used to store third party login details
*/

class Migration_create_thirdpartylogins_table extends CI_Migration {

    public function up()
    {
        if (!$this->db->table_exists('thirdparty_logins')) {
            $this->dbforge->add_field(array(
                'service_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE,
                    'unique' => TRUE
                ),

                'user_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => TRUE,
                    'auto_increment' => FALSE
                ),
                
                'service_name' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => TRUE
                ),

                'service_password' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => TRUE
                ),

                'active' => array(
                    'type' => 'BOOLEAN',
                    'null' => TRUE
                ),

                'modified' => array(
                    'type' => 'timestamp',
                    'null' => TRUE,
                )
            ));

            $this->dbforge->add_key('service_id', TRUE);
            $this->dbforge->add_key('user_id', TRUE);

            $this->dbforge->create_table('thirdparty_logins');
        }
    }

    public function down()
    {
        $this->dbforge->drop_table('thirdparty_logins');
    }
}