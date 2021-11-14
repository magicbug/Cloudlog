<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_userid_to_hardware extends CI_Migration {

	public function up()
	{
		$fields = array(
			'user_id BIGINT(20) DEFAULT NULL',
		);

		if (!$this->db->field_exists('user_id', 'cat')) {
			$this->dbforge->add_column('cat', $fields);
		}
	}

	public function down()
	{
		$this->dbforge->drop_column('cat', 'user_id');
	}
}
