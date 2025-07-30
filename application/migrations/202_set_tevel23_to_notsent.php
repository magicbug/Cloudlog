<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_set_tevel23_to_notsent extends CI_Migration
{
	public function up()
	{
        // update column COL_LOTW_QSL_SENT to N if its TEVEL2-3
        $this->db->set('COL_LOTW_QSL_SENT', 'N');
        $this->db->where('COL_SAT_NAME', 'TEVEL2-3');
        $this->db->update($this->config->item('table_name'));
        log_message('info', 'Migration: Set COL_LOTW_QSL_SENT to N for TEVEL2-3');

	}

	public function down()
	{
        // Set COL_LOTW_QSL_SENT back to N for TEVEL2-3
        $this->db->set('COL_LOTW_QSL_SENT', 'N');
        $this->db->where('COL_SAT_NAME', 'TEVEL2-3');
        $this->db->update($this->config->item('table_name'));
        log_message('info', 'Migration: Reverted COL_LOTW_QSL_SENT back to N for TEVEL2-3');
	}
}
