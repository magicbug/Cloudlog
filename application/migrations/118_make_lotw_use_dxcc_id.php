<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_make_lotw_use_dxcc_id extends CI_Migration
{
	public function up()
	{
		$fields = array(
			'cert_dxcc_id SMALLINT(6) DEFAULT 0 NOT NULL AFTER `cert_dxcc`',
		);

		if (!$this->db->field_exists('cert_dxcc_id', 'lotw_certs')) {
			$this->dbforge->add_column('lotw_certs', $fields);
		}

		if ($this->db->field_exists('cert_dxcc', 'lotw_certs')) {
			$sql = 'UPDATE `lotw_certs` JOIN `dxcc_entities` ON `lotw_certs`.`cert_dxcc` = `dxcc_entities`.`name` SET `lotw_certs`.`cert_dxcc_id` = `dxcc_entities`.`adif`;';
			$this->db->query($sql);
			$this->dbforge->drop_column('lotw_certs', 'cert_dxcc');
		}	
	}

	public function down()
	{
		$fields = array(
			'cert_dxcc VARCHAR(255) NOT NULL AFTER `callsign`',
		);

		if (!$this->db->field_exists('cert_dxcc', 'lotw_certs')) {
			$this->dbforge->add_column('lotw_certs', $fields);
		}

		$sql = 'UPDATE `lotw_certs` JOIN `dxcc_entities` ON `lotw_certs`.`cert_dxcc_id` = `dxcc_entities`.`adif` SET `lotw_certs`.`cert_dxcc` = `dxcc_entities`.`name`;';
		$this->db->query($sql);

		$this->dbforge->drop_column('lotw_certs', 'cert_dxcc_id');
	}
}
