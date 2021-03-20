<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
*   This migration creates a table called options which will hold global options needed within cloudlog
*   removing the need for lots of configuration files.
*/

class Migration_add_user_hide_notes extends CI_Migration {

	public function up()
	{
		$fields = array(
			'user_show_notes integer DEFAULT 1',
		);

		$this->dbforge->add_column('users', $fields);
	}

	public function down()
	{
		$this->dbforge->drop_column('users', 'user_show_notes');
	}
}
