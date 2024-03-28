<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_add_sstv_images_table extends CI_Migration
{

        public function up()
        {
                // if sstv_images table doesn't exist
                if (!$this->db->table_exists('sstv_images')) {
                        $this->dbforge->add_field(array(
                                'id' => array(
                                        'type' => 'INT',
                                        'constraint' => 5,
                                        'unsigned' => TRUE,
                                        'auto_increment' => TRUE
                                ),
                                'qsoid' => array(
                                        'type' => 'VARCHAR',
                                        'constraint' => '250',
                                ),
                                'filename' => array(
                                        'type' => 'VARCHAR',
                                        'constraint' => '250',
                                ),
                                'modified' => array(
                                        'type' => 'timestamp',
                                        'null' => TRUE,
                                ),
                        ));
                        $this->dbforge->add_key('id', TRUE);
                        $this->dbforge->create_table('sstv_images');
                }
        }

        public function down()
        {
                echo "not possible";
        }
}
