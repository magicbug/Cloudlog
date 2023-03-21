<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_add_timestamp_to_api extends CI_Migration
{
	public function up()
	{
		$fields = array(
			'last_used TIMESTAMP DEFAULT NOW() NOT NULL AFTER `last_change`',
		);

		if (!$this->db->field_exists('last_used', 'api')) {
			$this->dbforge->add_column('api', $fields);
		}
	}

	public function down()
	{
		$this->dbforge->drop_column('api', 'last_used');
	}
}
