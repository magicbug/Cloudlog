<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_lotwusers extends CI_Migration {

        public function up()
        {
                $this->dbforge->add_field(array(
                        'id' => array(
                                'type' => 'INT',
                                'constraint' => 5,
                                'unsigned' => TRUE,
                                'auto_increment' => TRUE
                        ),
                        'callsign' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '100',
                        ),
                        'upload_date' => array(
                                'type' => 'datetime',
                                'null' => TRUE,
                        ),
                ));
                $this->dbforge->add_key('id', TRUE);
                $this->dbforge->create_table('lotw_userlist');
        }

        public function down()
        {
                $this->dbforge->drop_table('lotw_userlist');
        }
}