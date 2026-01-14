<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_created_at_to_notes extends CI_Migration {

	public function up()
	{
		$fields = array(
			'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
		);

		if (!$this->db->field_exists('created_at', 'notes')) {
			$this->dbforge->add_column('notes', $fields);
		}
	}

	public function down()
	{
		$this->dbforge->drop_column('notes', 'created_at');
	}
}
