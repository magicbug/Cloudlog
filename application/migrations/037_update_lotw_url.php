<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_update_lotw_url extends CI_Migration {

	public function up()
	{
		$sql = "UPDATE config SET lotw_download_url = 'https://lotw.arrl.org/lotwuser/lotwreport.adi' WHERE id=1";
		$this->db->query($sql);

		$sql = "UPDATE config SET lotw_upload_url = 'https://lotw.arrl.org/lotwuser/upload' WHERE id=1";
		$this->db->query($sql);

		$sql = "UPDATE config SET lotw_login_url = 'https://lotw.arrl.org/lotwuser/default' WHERE id=1";
		$this->db->query($sql);
	}

	public function down()
	{

	}
}
?>
