<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 *   This adds an option to enable the 
 *   QUICKLOG Feature
*/

class Migration_user_quicklog extends CI_Migration {

	public function up()
	{
		if (!$this->db->field_exists('user_quicklog', 'users')) {
			$fields = array(
				'user_quicklog integer DEFAULT 0 AFTER user_default_confirmation',
			);

			$this->dbforge->add_column('users', $fields);
		}
		if (!$this->db->field_exists('user_quicklog_enter', 'users')) {
			$fields = array(
				'user_quicklog_enter integer DEFAULT 0 AFTER user_default_confirmation',
			);

			$this->dbforge->add_column('users', $fields);
		}
	}

	public function down()
	{
		if ($this->db->field_exists('user_quicklog', 'users')) {
			$this->dbforge->drop_column('users', 'user_quicklog');
		}
		if ($this->db->field_exists('user_quicklog_enter', 'users')) {
			$this->dbforge->drop_column('users', 'user_quicklog_enter');
		}
	}
}