<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_eQSL_login_and_url extends CI_Migration {

	public function up()
	{
		$user_fields = array(
      			'user_eqsl_name VARCHAR(32) DEFAULT NULL',
      			'user_eqsl_password VARCHAR(64) DEFAULT NULL'
    		);
		$this->dbforge->add_column('users', $user_fields);
		
		
		$config_fields = array('eqsl_download_url VARCHAR(244) DEFAULT NULL','eqsl_rcvd_mark VARCHAR(1) DEFAULT NULL');
		$this->dbforge->add_column('config', $config_fields);
		
		$sql = "UPDATE config SET eqsl_download_url = 'http://www.eqsl.cc/qslcard/DownloadInBox.cfm' WHERE id=1";
		$this->db->query($sql);
		
		$sql = "UPDATE config SET eqsl_rcvd_mark = 'Y' WHERE id=1";
		$this->db->query($sql);
	}

	public function down()
	{
			$this->dbforge->drop_column('users', 'user_eqsl_name');
    		$this->dbforge->drop_column('users', 'user_eqsl_password');
    		$this->dbforge->drop_column('config', 'eqsl_download_url');
    		$this->dbforge->drop_column('config', 'eqsl_rcvd_mark');
	}
}
?>
