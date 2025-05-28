<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 *   This adds a field to user-table to hold winkey websocket setting
*/

// Migration: 197_add_winkey_websocket
class Migration_add_winkey_websocket extends CI_Migration {

	public function up()
	{
        // Check if winkey_websocket exists in the user table if not create a boolean field
        if (!$this->db->field_exists('winkey_websocket', 'users')) {
            $fields = array(
                'winkey_websocket boolean default 0',
            );

            $this->dbforge->add_column('users', $fields);
        }
	}

	public function down()
	{
		if ($this->db->field_exists('winkey_websocket', 'users')) {
			$this->dbforge->drop_column('users', 'winkey_websocket');
		}
	}
}
