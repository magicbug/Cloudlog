<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_lotw_enddates extends CI_Migration
{
	public function up()
	{
		if ($this->db->table_exists('lotw_certs')) {
			$sql = 'UPDATE lotw_certs SET qso_end_date = DATE_ADD(qso_end_date, INTERVAL 24*60*60 -1 SECOND) WHERE TIME(qso_end_date) = "00:00:00";';
			$this->db->query($sql);
		}
	}

	public function down()
	{
		if ($this->db->table_exists('lotw_certs')) {
			$sql = 'UPDATE lotw_certs SET qso_end_date = DATE_SUB(qso_end_date, INTERVAL 24*60*60 -1 SECOND) WHERE TIME(qso_end_date) = "23:59:59";';
			$this->db->query($sql);
		}
	}
}
