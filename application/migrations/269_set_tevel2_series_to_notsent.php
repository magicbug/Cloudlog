<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_set_tevel2_series_to_notsent extends CI_Migration
{
	public function up()
	{
		$satellites = array(
			'TEVEL2-1',
			'TEVEL2-2',
			'TEVEL2-3',
			'TEVEL2-4',
			'TEVEL2-5',
			'TEVEL2-6',
			'TEVEL2-7',
			'TEVEL2-8',
			'TEVEL2-9',
		);

		$this->db->set('COL_LOTW_QSL_SENT', 'N');
		$this->db->where_in('COL_SAT_NAME', $satellites);
		$this->db->update($this->config->item('table_name'));

		log_message('info', 'Migration: Set COL_LOTW_QSL_SENT to N for TEVEL2-1 through TEVEL2-9');
	}

	public function down()
	{
		// Not possible to safely restore previous per-QSO sent state.
	}
}
