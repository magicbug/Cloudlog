<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_rsgb_award extends CI_Migration {

	public function up()
	{
		if (!$this->db->field_exists('rsgb', 'awardxuser')) {
			$this->dbforge->add_column('awardxuser', array(
				'rsgb' => array(
					'type' => 'TINYINT',
					'constraint' => 1,
					'default' => 1,
				),
			));
		}
	}

	public function down()
	{
		if ($this->db->field_exists('rsgb', 'awardxuser')) {
			$this->dbforge->drop_column('awardxuser', 'rsgb');
		}
	}
}
