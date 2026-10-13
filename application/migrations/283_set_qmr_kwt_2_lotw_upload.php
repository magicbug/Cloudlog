<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_set_qmr_kwt_2_lotw_upload extends CI_Migration
{
	public function up()
	{
		$this->db->set('COL_LOTW_QSL_SENT', 'N');
		$this->db->where('COL_SAT_NAME', 'QMR-KWT-2');
		$this->db->update($this->config->item('table_name'));

		log_message('info', 'Migration: Set COL_LOTW_QSL_SENT to N for QMR-KWT-2');
	}

	public function down()
	{
		// Not possible to safely restore previous per-QSO sent state.
	}
}
