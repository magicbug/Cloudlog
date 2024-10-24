<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_set_SONATE_lotw_upload extends CI_Migration
{
	public function up()
	{
        $this->db->set('COL_LOTW_QSL_SENT', 'N');
        $this->db->where('COL_SAT_NAME', 'SONATE-2');
        $this->db->update($this->config->item('table_name'));
	}

	public function down()
	{
        // Not Possible
	}
}
