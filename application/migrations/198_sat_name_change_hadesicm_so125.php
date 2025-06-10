<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_sat_name_change_hadesicm_so125 extends CI_Migration
{
	public function up()
	{
        // update column COL_SAT_NAME to SO-125 if its HADES-ICM
        $this->db->set('COL_SAT_NAME', 'SO-125');
        $this->db->where('COL_SAT_NAME', 'HADES-ICM');
        $this->db->update($this->config->item('table_name'));
        log_message('info', 'Migration: Updated COL_SAT_NAME to SO-125 for HADES-ICM');

        // update column COL_LOTW_QSL_SENT to N if its SO-125
        $this->db->set('COL_LOTW_QSL_SENT', 'N');
        $this->db->where('COL_SAT_NAME', 'SO-125');
        $this->db->update($this->config->item('table_name'));
        log_message('info', 'Migration: Set COL_LOTW_QSL_SENT to N for SO-125');

	}

	public function down()
	{
       //Change back to HADES-ICM
        $this->db->set('COL_SAT_NAME', 'HADES-ICM');
        $this->db->where('COL_SAT_NAME', 'SO-125');
        $this->db->update($this->config->item('table_name'));
        log_message('info', 'Migration: Reverted COL_SAT_NAME back to HADES-ICM');

        // Set COL_LOTW_QSL_SENT back to N for HADES-ICM
        $this->db->set('COL_LOTW_QSL_SENT', 'N');
        $this->db->where('COL_SAT_NAME', 'HADES-ICM');
        $this->db->update($this->config->item('table_name'));
        log_message('info', 'Migration: Reverted COL_LOTW_QSL_SENT back to N for HADES-ICM');
	}
}
