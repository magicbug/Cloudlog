<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_increase_qth_column_size extends CI_Migration
{
	public function up()
	{
        // In the table defined by varialble $this->config->item('table_name') change COL_QTH	to be varchar 255
        $this->dbforge->modify_column($this->config->item('table_name'), array(
            'COL_QTH' => array(
                'name' => 'COL_QTH',
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            )
        ));
	}

	public function down()
	{
        // Change it back to 64
        $this->dbforge->modify_column($this->config->item('table_name'), array(
            'COL_QTH' => array(
                'name' => 'COL_QTH',
                'type' => 'VARCHAR',
                'constraint' => '64',
                'null' => TRUE
            )
        ));
	}
}
