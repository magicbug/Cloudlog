<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_label_paper_types_table extends CI_Migration {

	public function up() {
        if (!$this->db->table_exists('paper_types')) {
            $this->dbforge->add_field(array(
                'id' => array(
                    'type' => 'INT',
                    'constraint' => 5,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE
                ),

                'user_id' => array(
                    'type' => 'INT',
                    'constraint' => 5,
                ),

                'paper_name' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '250',
                ),

				'metric' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '10',
                ),

                'width' => array(
                    'type' => 'DECIMAL',
                    'constraint' => '6,3',
                    'null' => TRUE,
                ),

                'orientation' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '1',
                    'null' => TRUE,
                ),

                'height' => array(
                    'type' => 'DECIMAL',
                    'constraint' => '6,3',
                    'null' => TRUE,
                ),

                'last_modified' => array(
                'type' => 'timestamp',
                'null' => TRUE,
                ),
            ));

            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->add_key('user_id', TRUE);

            $this->dbforge->create_table('paper_types');
        }

	}

	public function down(){
        if ($this->db->table_exists('paper_types')) {
		    $this->dbforge->drop_table('paper_types');
        }
	}
}
