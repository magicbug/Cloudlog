<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_rename_cas5a extends CI_Migration
{
	public function up()
	{
        $this->db->set('COL_SAT_NAME', 'FO-118');
        $this->db->where('COL_SAT_NAME', 'CAS-5A');
        $this->db->update($this->config->item('table_name'));
	}

	public function down()
	{
        $this->db->set('COL_SAT_NAME', 'CAS-5A');
        $this->db->where('COL_SAT_NAME', 'FO-118');
        $this->db->update($this->config->item('table_name'));
	}
}
