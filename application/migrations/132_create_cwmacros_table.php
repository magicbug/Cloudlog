<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
*   This migration creates a table called thirdparty_logins
*   This table is used to store third party login details
*/

class Migration_create_cwmacros_table extends CI_Migration {

    public function up()
    {
        if (!$this->db->table_exists('cwmacros')) {
            $this->dbforge->add_field(array(
                'id' => array(
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

                'station_location_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => TRUE,
                    'auto_increment' => FALSE
                ),
                    
                'function1_name' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => TRUE
                ),

                'function1_macro' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => TRUE
                ),

                'function2_name' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => TRUE
                ),

                'function2_macro' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => TRUE
                ),

                'function3_name' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => TRUE
                ),

                'function3_macro' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => TRUE
                ),

                'function4_name' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => TRUE
                ),

                'function4_macro' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => TRUE
                ),

                'function5_name' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => TRUE
                ),

                'function5_macro' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => TRUE
                ),

                'modified' => array(
                    'type' => 'timestamp',
                    'null' => TRUE,
                )
            ));

            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->add_key('user_id', TRUE);
            $this->dbforge->add_key('station_location_id', TRUE);

            $this->dbforge->create_table('cwmacros');
        }
    }

    public function down()
    {
        $this->dbforge->drop_table('cwmacros');
    }
}