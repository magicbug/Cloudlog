<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_logbook_table extends CI_Migration {

        public function up()
        {
                $this->dbforge->add_field(array(
                        'id' => array(
                                'type' => 'INT',
                                'constraint' => 5,
                                'unsigned' => TRUE,
                                'auto_increment' => TRUE
                        ),
                        'logbook_name' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '250',
                        ),
                        'modified' => array(
                                'type' => 'timestamp',
                                'null' => TRUE,
                        ),
                ));
                $this->dbforge->add_key('id', TRUE);
                $this->dbforge->create_table('logbooks');
        }

        public function down()
        {
                echo "not possible";
        }
}