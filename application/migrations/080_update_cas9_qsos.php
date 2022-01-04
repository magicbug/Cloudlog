<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_update_cas9_qsos extends CI_Migration
{
	public function up()
	{
        $this->db->set('COL_SAT_NAME', 'HO-113');
        $this->db->set('COL_LOTW_QSL_SENT', 'N');
        $this->db->where('COL_SAT_NAME', 'CAS-9');
        $this->db->update($this->config->item('table_name'));
	}

	public function down()
	{
        // Not Possible
	}
}
