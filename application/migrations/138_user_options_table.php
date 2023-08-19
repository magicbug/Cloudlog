<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_user_options_table extends CI_Migration {

	public function up() {
		if (!$this->db->table_exists('user_options')) {
			$this->db->query("CREATE TABLE `user_options` ( `user_id` int(11) NOT NULL, `option_type` varchar(45) NOT NULL, `option_name` varchar(45) NOT NULL, `option_key` varchar(45) NOT NULL, `option_value` varchar(45) DEFAULT NULL, PRIMARY KEY (`user_id`,`option_type`,`option_key`,`option_name`))");
		}

	}

	public function down(){
		if ($this->db->table_exists('user_options')) {
			$this->dbforge->drop_table('user_options');
		}
	}
}
