<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_lotw_credentials extends CI_Migration {

	public function up()
	{
		$fields = array(
      			'user_lotw_name VARCHAR(32) DEFAULT NULL',
      			'user_lotw_password VARCHAR(64) DEFAULT NULL'
    		);
		
    		$this->dbforge->add_column('users', $fields);
	}

	public function down()
	{
		$this->dbforge->drop_column('users', 'user_lotw_name');
    		$this->dbforge->drop_column('users', 'user_lotw_password');
	}
}
?>
