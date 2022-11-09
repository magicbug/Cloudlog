<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 *   This adds an option to enable grid lookup
 *   by location entered
*/

class Migration_user_auto_qth_option extends CI_Migration {

	public function up()
	{
		if (!$this->db->field_exists('user_qth_lookup', 'users')) {
			$fields = array(
				'user_qth_lookup integer DEFAULT 0 AFTER user_wwff_lookup',
			);

			$this->dbforge->add_column('users', $fields);
		}
	}

	public function down()
	{
		if ($this->db->field_exists('user_qth_lookup', 'users')) {
			$this->dbforge->drop_column('users', 'user_qth_lookup');
		}
	}
}
