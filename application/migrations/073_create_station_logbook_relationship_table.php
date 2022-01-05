<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
*   This migration creates a table called options which will hold global options needed within cloudlog
*   removing the need for lots of configuration files.
*/

class Migration_create_station_logbook_relationship_table extends CI_Migration {

    public function up()
    {

        if (!$this->db->table_exists('station_logbooks')) {
            $this->dbforge->add_field(array(
                'logbook_id' => array(
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
                
                'logbook_name' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '191',
                    'null' => TRUE
                ),

                'modified' => array(
                    'type' => 'timestamp',
                    'null' => TRUE,
                )
            ));

            $this->dbforge->add_key('logbook_id', TRUE);
            $this->dbforge->add_key('user_id', TRUE);

            $this->dbforge->create_table('station_logbooks');
        }

        if (!$this->db->table_exists('station_logbooks_relationship')) {
            $this->dbforge->add_field(array(
                'logbook_relation_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE,
                    'unique' => TRUE
                ),

                'station_logbook_id' => array(
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

                'modified' => array(
                    'type' => 'timestamp',
                    'null' => TRUE,
                )
            ));

            $this->dbforge->add_key('logbook_relation_id', TRUE);
            $this->dbforge->add_key('station_logbook_id', TRUE);
            $this->dbforge->add_key('station_location_id', TRUE);

            $this->dbforge->create_table('station_logbooks_relationship');
        }
    }

    public function down()
    {
        $this->dbforge->drop_table('station_logbooks_relationship');
    }
}