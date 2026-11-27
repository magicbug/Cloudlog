<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_wae_award extends CI_Migration {

	public function up()
	{
		if (!$this->db->field_exists('wae', 'awardxuser')) {
			$this->dbforge->add_column('awardxuser', array(
				'wae' => array(
					'type' => 'TINYINT',
					'constraint' => 1,
					'default' => 1,
				),
			));
		}
	}

	public function down()
	{
		if ($this->db->field_exists('wae', 'awardxuser')) {
			$this->dbforge->drop_column('awardxuser', 'wae');
		}
	}
}
