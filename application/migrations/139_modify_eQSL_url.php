<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_modify_eQSL_url extends CI_Migration {

	public function up()
	{
		$sql = "UPDATE config SET eqsl_download_url = 'https://www.eqsl.cc/qslcard/DownloadInBox.cfm' WHERE id=1";
		$this->db->query($sql);
	}

	public function down()
	{
		// Will not go back to insecure connections
	}
}
?>
