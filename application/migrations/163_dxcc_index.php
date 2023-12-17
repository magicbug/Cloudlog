<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_dxcc_index extends CI_Migration {

	public function up() {
		$this->db->query("ALTER TABLE `dxcc_prefixes` ADD INDEX `idx_dxcc_prefixes_logic` (`call`, `start`, `end`)");
		$this->db->query("ALTER TABLE `dxcc_exceptions` ADD INDEX `idx_dxcc_exceptions_logic` (`call`, `start`, `end`)");
	}

	public function down(){
		$this->db->query("ALTER TABLE dxcc_prefixes DROP INDEX idx_dxcc_prefixes_logic");
		$this->db->query("ALTER TABLE dxcc_exceptions DROP INDEX idx_dxcc_exceptions_logic");
	}
}
