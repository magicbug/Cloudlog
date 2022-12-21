<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_reupload_io117_and_fo118 extends CI_Migration
{
	public function up()
	{
        $this->db->set('COL_LOTW_QSL_SENT', 'N');
        $this->db->where('COL_SAT_NAME', 'IO-117');
        $this->db->update($this->config->item('table_name'));

        $this->db->set('COL_LOTW_QSL_SENT', 'N');
        $this->db->where('COL_SAT_NAME', 'FO-118');
        $this->db->update($this->config->item('table_name'));
	}

	public function down()
	{
        // Not Possible
	}
}
