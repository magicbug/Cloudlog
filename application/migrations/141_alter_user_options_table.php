<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_alter_user_options_table extends CI_Migration {

	public function up() {
		$this->db->query("ALTER TABLE user_options CHANGE COLUMN option_value option_value text DEFAULT NULL;");
	}

	public function down(){
	}
}
