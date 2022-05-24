<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
    Creates column service_username in table thirdparty_logins
*/

class Migration_add_service_username extends CI_Migration {

    public function up()
    {
        if ($this->db->table_exists('thirdparty_logins')) {

            $fields = array(
                'service_username' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => TRUE
                )
            );

            if (!$this->db->field_exists('service_username', 'thirdparty_logins')) {
                $this->dbforge->add_column('thirdparty_logins', $fields);
            }
            
        }
    }

    public function down()
    {
        $this->dbforge->drop_column('thirdparty_logins', 'service_username');
    }
}