<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_lotw_login_url extends CI_Migration {

	public function up()
	{
		$fields = array('lotw_login_url VARCHAR(244) DEFAULT NULL');
	
    		$this->dbforge->add_column('config', $fields);
		
		$sql = "UPDATE config SET lotw_login_url = 'https://p1k.arrl.org/lotwuser/default' WHERE id=1";

		$this->db->query($sql);
	}

	public function down()
	{
    		$this->dbforge->drop_column('config', 'lotw_login_url');
	}
}
?>
