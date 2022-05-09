<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 *   This migration adds a separate column which allows enabling 
 *   download of QSO details during LotW sync
*/

class Migration_add_lotw_download_qso_details extends CI_Migration {

	public function up()
	{
		$fields = array(
			'user_lotw_qso_details integer DEFAULT 0',
		);

		$this->dbforge->add_column('users', $fields, 'user_lotw_password');
	}

	public function down()
	{
		$this->dbforge->drop_column('users', 'user_lotw_qso_details');
	}
}
