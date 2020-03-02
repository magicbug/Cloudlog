<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_eqsl_images_table extends CI_Migration {

        public function up()
        {
                $this->dbforge->add_field(array(
                        'id' => array(
                                'type' => 'INT',
                                'constraint' => 5,
                                'unsigned' => TRUE,
                                'auto_increment' => TRUE
                        ),
                        'qso_id' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '250',
                        ),
                        'image_file' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '250',
                        ),
                        'modified' => array(
                                'type' => 'timestamp',
                                'null' => TRUE,
                        ),
                ));
                $this->dbforge->add_key('id', TRUE);
                $this->dbforge->create_table('eQSL_images');
        }

        public function down()
        {
                echo "not possible";
        }
}