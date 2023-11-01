<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 *   This adds an option to enable grid lookup
 *   by location entered
*/

class Migration_qso_end_times extends CI_Migration {

	public function up()
	{
		if (!$this->db->field_exists('user_qso_end_times', 'users')) {
			$fields = array(
				'user_qso_end_times integer DEFAULT 0 AFTER user_default_confirmation',
			);

			$this->dbforge->add_column('users', $fields);
		}
	}

	public function down()
	{
		if ($this->db->field_exists('user_qso_end_times', 'users')) {
			$this->dbforge->drop_column('users', 'user_qso_end_times');
		}
	}
}
