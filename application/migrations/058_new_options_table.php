<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
*   This migration creates a table called options which will hold global options needed within cloudlog
*   removing the need for lots of configuration files.
*/

class Migration_new_options_table extends CI_Migration {

    public function up()
    {
        // if table options doesn't exist
        if (!$this->db->table_exists('options')) {
            $this->dbforge->add_field(array(
                'option_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE
                ),
                
                'option_name' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '191',
                    'null' => TRUE,
                    'unique' => TRUE,
                ),
                
                'option_value' => array(
                    'type' => 'longtext',
                ),
    
                'autoload' => array(
                    'type' => 'varchar',
                    'constraint' => '20',
                    'null' => TRUE,
                )
            ));
    
            $this->dbforge->add_key('option_id', TRUE);
    
            $this->dbforge->create_table('options');
        }
    }

    public function down()
    {
        $this->dbforge->drop_table('options');
    }
}