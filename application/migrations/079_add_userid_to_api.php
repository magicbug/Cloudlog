<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_add_userid_to_api extends CI_Migration
{
	public function up()
	{
		$fields = array(
			'user_id BIGINT(20) DEFAULT NULL',
		);

		if (!$this->db->field_exists('user_id', 'api')) {
			$this->dbforge->add_column('api', $fields);
		}
	}

	public function down()
	{
		$this->dbforge->drop_column('api', 'user_id');
	}
}
