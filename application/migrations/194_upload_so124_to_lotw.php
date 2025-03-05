<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_upload_so124_to_lotw extends CI_Migration
{
	public function up()
	{

        // update column COL_SAT_NAME to SO-124 if its HADES-R
        $this->db->set('COL_SAT_NAME', 'SO-124');
        $this->db->where('COL_SAT_NAME', 'HADES-R');
        $this->db->update($this->config->item('table_name'));
        log_message('info', 'Migration: Updated COL_SAT_NAME to SO-124 for HADES-R');

        // update column COL_LOTW_QSL_SENT to N if its SO-124
        $this->db->set('COL_LOTW_QSL_SENT', 'N');
        $this->db->where('COL_SAT_NAME', 'SO-124');
        $this->db->update($this->config->item('table_name'));
        log_message('info', 'Migration: Set COL_LOTW_QSL_SENT to N for SO-124');

	}

	public function down()
	{
        // Not Possible
	}
}
