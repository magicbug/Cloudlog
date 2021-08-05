<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
*   This migration creates a table called options which will hold global options needed within cloudlog
*   removing the need for lots of configuration files.
*/

class Migration_add_index_cqz_prop extends CI_Migration {

	public function up()
	{
		$sql = "ALTER TABLE ".$this->config->item('table_name')." ADD INDEX `HRD_IDX_COL_CQZ` (`COL_CQZ`);";
		$this->db->query($sql);
		$sql = "ALTER TABLE ".$this->config->item('table_name')." ADD INDEX `HRD_IDX_COL_PROP_MODE` (`COL_PROP_MODE`);";
		$this->db->query($sql);
	}

	public function down()
	{

	}
}
