<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
*   Create a dxpedition table
*/

class Migration_create_dxpedition_table extends CI_Migration
{

    public function up()
    {
        /* check if the dxpedition table exists if not create dxpedition table */
        if (!$this->db->table_exists('dxpedition')) {

            $this->dbforge->add_field(array(
                'id' => array(
                    'type' => 'INT',
                    'constraint' => 6,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE,
                    'null' => FALSE
                ),
                'start_date' => array(
                    'type' => 'DATE',
                    'null' => TRUE,
                ),
                'end_date' => array(
                    'type' => 'DATE',
                    'null' => TRUE,
                ),
                'callsign' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '50',
                    'null' => FALSE,
                ),
                'country' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => TRUE,
                ),
                'notes' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => TRUE,
                ),
            ));
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->add_key('callsign', TRUE);

            $this->dbforge->create_table('dxpedition');
        }
    }

    public function down()
    {
        $this->dbforge->drop_table('dxpedition');
    }
}
