<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_wpx_award extends CI_Migration {

	public function up()
	{
		if (!$this->db->field_exists('wpx', 'awardxuser')) {
			$this->dbforge->add_column('awardxuser', array(
				'wpx' => array(
					'type' => 'TINYINT',
					'constraint' => 1,
					'default' => 1,
				),
			));
		}
	}

	public function down()
	{
		if ($this->db->field_exists('wpx', 'awardxuser')) {
			$this->dbforge->drop_column('awardxuser', 'wpx');
		}
	}
}
