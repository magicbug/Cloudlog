<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 *   This adds an option to enable grid and name lookup
 *   for WWFF references
*/

class Migration_add_user_language extends CI_Migration {

	public function up()
	{
		if (!$this->db->field_exists('language', 'users')) {
			$fields = array(
				'language varchar(32) default "english"',
			);

			$this->dbforge->add_column('users', $fields);
		}
	}

	public function down()
	{
		if ($this->db->field_exists('language', 'users')) {
			$this->dbforge->drop_column('users', 'language');
		}
	}
}
