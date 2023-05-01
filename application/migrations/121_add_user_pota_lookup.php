<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 *   This adds an option to enable grid and name lookup
 *   for WWFF references
*/

class Migration_add_user_pota_lookup extends CI_Migration {

	public function up()
	{
		if (!$this->db->field_exists('user_pota_lookup', 'users')) {
			$fields = array(
				'user_pota_lookup integer DEFAULT 0 AFTER user_wwff_lookup',
			);

			$this->dbforge->add_column('users', $fields);
		}
	}

	public function down()
	{
		if ($this->db->field_exists('user_pota_lookup', 'users')) {
			$this->dbforge->drop_column('users', 'user_pota_lookup');
		}
	}
}
