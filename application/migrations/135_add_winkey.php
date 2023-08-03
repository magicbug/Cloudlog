<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 *   This adds a field to user-table to hold/persist language-setting per user
*/

class Migration_add_winkey extends CI_Migration {

	public function up()
	{
        // Check if winkey exists in the user table if not create a boolean field
        if (!$this->db->field_exists('winkey', 'users')) {
            $fields = array(
                'winkey boolean default 0',
            );

            $this->dbforge->add_column('users', $fields);
        }
	}

	public function down()
	{
		if ($this->db->field_exists('winkey', 'users')) {
			$this->dbforge->drop_column('users', 'winkey');
		}
	}
}
