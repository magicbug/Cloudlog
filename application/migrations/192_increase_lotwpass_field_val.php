<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_increase_lotwpass_field_val extends CI_Migration
{
	public function up()
	{
        // In the user table increase the length of the user_lotw_password field to 255
        $this->dbforge->modify_column('users', array(
            'user_lotw_password' => array(
                'name' => 'user_lotw_password',
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            )
        ));
	}

	public function down()
	{
        // Reset it back to 64
        $this->dbforge->modify_column('users', array(
            'user_lotw_password' => array(
                'name' => 'user_lotw_password',
                'type' => 'VARCHAR',
                'constraint' => '64',
                'null' => TRUE
            )
        ));
	}
}
