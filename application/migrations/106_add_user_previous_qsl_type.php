<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 *   This adds an option to select the QSL type for previous QSOs
*/

class Migration_add_user_previous_qsl_type extends CI_Migration {

	public function up()
	{
		if (!$this->db->field_exists('user_previous_qsl_type', 'users')) {
			$fields = array(
				'user_previous_qsl_type integer DEFAULT 0',
			);

			$this->dbforge->add_column('users', $fields);
		}
	}

	public function down()
	{
		if ($this->db->field_exists('user_previous_qsl_type', 'users')) {
			$this->dbforge->drop_column('users', 'user_previous_qsl_type');
		}
	}
}
