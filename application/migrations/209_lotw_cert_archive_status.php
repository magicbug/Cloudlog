<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_lotw_cert_archive_status
 *
 * Adds a simple boolean `archived` column to the `lotw_certs` table so
 * certificates can be marked as archived.
 */
class Migration_lotw_cert_archive_status extends CI_Migration {

	public function up()
	{
		if (! $this->db->field_exists('archived', 'lotw_certs')) {
			$fields = array(
				'archived BOOLEAN DEFAULT FALSE',
			);

			$this->dbforge->add_column('lotw_certs', $fields);
		}
	}

	public function down()
	{
		if ($this->db->field_exists('archived', 'lotw_certs')) {
			$this->dbforge->drop_column('lotw_certs', 'archived');
		}
	}
}
