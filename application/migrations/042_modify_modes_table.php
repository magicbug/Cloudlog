<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_modify_modes_table extends CI_Migration {

	public function up() {
		$fields = array(
				'submode' => array(
				'name' => 'submode',
				'type' => 'VARCHAR',
				'constraint' => '25',
			)
		);
		$this->dbforge->modify_column('adif_modes', $fields);
		
		$this->db->query("UPDATE `adif_modes` set submode = 'OLIVIA 16/500' where submode = 'OLIVIA 16/50';");
		$this->db->query("UPDATE `adif_modes` set submode = 'OLIVIA 16/1000' where submode = 'OLIVIA 16/10';");
		$this->db->query("UPDATE `adif_modes` set submode = 'OLIVIA 32/1000' where submode = 'OLIVIA 32/10';");
		
	}

	public function down(){
		echo "Not possible, sorry.";
	}
}
