<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_lotw_certs_table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'lotw_cert_id' => array(
                'type' => 'INT',
                'constraint' => 1,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            
            'callsign' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
            ),
            
            'cert_dxcc' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),

            'date_created' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),

            'date_expires' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
        ));

        $this->dbforge->add_key('lotw_cert_id', TRUE);
        $this->dbforge->create_table('lotw_certs');
    }

    public function down()
    {
        $this->dbforge->drop_table('lotw_certs');
    }
}