<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_rename_reupload_so121 extends CI_Migration
{
	public function up()
	{
        $this->db->set('COL_SAT_NAME', 'SO-121');
        $this->db->set('COL_LOTW_QSL_SENT', 'N');
        $this->db->where('COL_SAT_NAME', 'HADES-D');
        $this->db->update($this->config->item('table_name'));
	}

	public function down()
	{
        // Not Possible
	}
}
