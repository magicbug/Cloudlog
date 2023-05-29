<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_reupload_tevel extends CI_Migration
{
	public function up()
	{
        $this->db->set('COL_LOTW_QSL_SENT', 'N');
        $this->db->like('COL_SAT_NAME', 'TEVEL', 'after');
        $this->db->update($this->config->item('table_name'));
	}

	public function down()
	{
        // Not Possible
	}
}
