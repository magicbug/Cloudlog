<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
*   This migration creates a table called options which will hold global options needed within cloudlog
*   removing the need for lots of configuration files.
*/

class Migration_add_user_definable_columns extends CI_Migration {

	public function up()
	{
		$fields = array(
			'user_column1 varchar(32) default "Mode"',
		);

		$this->dbforge->add_column('users', $fields);

		$fields = array(
			'user_column2 varchar(32) default "RSTS"',
		);

		$this->dbforge->add_column('users', $fields);

		$fields = array(
			'user_column3 varchar(32) default "RSTR"',
		);

		$this->dbforge->add_column('users', $fields);

		$fields = array(
			'user_column4 varchar(32) default "Band"',
		);

		$this->dbforge->add_column('users', $fields);
	}

	public function down()
	{
		$this->dbforge->drop_column('users', 'user_column1');
		$this->dbforge->drop_column('users', 'user_column2');
		$this->dbforge->drop_column('users', 'user_column3');
		$this->dbforge->drop_column('users', 'user_column4');
	}
}
