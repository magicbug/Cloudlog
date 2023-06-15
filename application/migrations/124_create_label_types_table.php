<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_label_types_table extends CI_Migration {

	public function up() {
        if (!$this->db->table_exists('label_types')) {
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

                'label_name' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '250',
                ),

                'paper_type' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '250',
                ),

                'metric' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '10',
                ),

                'marginleft' => array(
                    'type' => 'DECIMAL',
                    'constraint' => '6,3',
                    'null' => TRUE,
                ),

                'margintop' => array(
                    'type' => 'DECIMAL',
                    'constraint' => '6,3',
                    'null' => TRUE,
                ),

                'nx' => array(
                    'type' => 'DECIMAL',
                    'constraint' => '6,3',
                    'null' => TRUE,
                ),

                'ny' => array(
                    'type' => 'DECIMAL',
                    'constraint' => '6,3',
                    'null' => TRUE,
                ),

                'spacex' => array(
                    'type' => 'DECIMAL',
                    'constraint' => '6,3',
                    'null' => TRUE,
                ),

                'spacey' => array(
                    'type' => 'DECIMAL',
                    'constraint' => '6,3',
                    'null' => TRUE,
                ),

                'width' => array(
                    'type' => 'DECIMAL',
                    'constraint' => '6,3',
                    'null' => TRUE,
                ),
                
                'height' => array(
                    'type' => 'DECIMAL',
                    'constraint' => '6,3',
                    'null' => TRUE,
                ),

                'font_size' => array(
                    'type' => 'INT',
                    'constraint' => '5',
                    'null' => TRUE,
                ),

                'font' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '250',
                    'null' => TRUE,
                ),

                'qsos' => array(
                    'type' => 'INT',
                    'constraint' => '5',
                    'null' => TRUE,
                ),

                'useforprint' => array(
                    'type' => 'INT',
                    'constraint' => '5',
                    'null' => TRUE,
                ),

                'last_modified' => array(
                'type' => 'timestamp',
                'null' => TRUE,
                ),
            ));

            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->add_key('user_id', TRUE);
    
            $this->dbforge->create_table('label_types');
        }
	
	}

	public function down(){
        if ($this->db->table_exists('label_types')) {
		    $this->dbforge->drop_table('label_types');
        }
	}
}
