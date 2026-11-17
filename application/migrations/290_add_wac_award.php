<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_wac_award extends CI_Migration {

	public function up()
	{
		if (!$this->db->field_exists('wac', 'awardxuser')) {
			$this->dbforge->add_column('awardxuser', array(
				'wac' => array(
					'type' => 'TINYINT',
					'constraint' => 1,
					'default' => 1,
				),
			));
		}
	}

	public function down()
	{
		if ($this->db->field_exists('wac', 'awardxuser')) {
			$this->dbforge->drop_column('awardxuser', 'wac');
		}
	}
}
