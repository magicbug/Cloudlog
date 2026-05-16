<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_add_remote_operation_flag extends CI_Migration
{
	public function up()
	{
		$fields = array(
			'remote_operation' => array(
				'type' => 'TINYINT',
				'constraint' => 1,
				'null' => false,
				'default' => 0,
				'after' => 'winkey_websocket',
			),
		);

		$this->dbforge->add_column($this->config->item('auth_table'), $fields);
	}

	public function down()
	{
		$this->dbforge->drop_column($this->config->item('auth_table'), 'remote_operation');
	}
}