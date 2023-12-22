<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_dxcc_index extends CI_Migration {

	public function up() {

		// check if index idx_dxcc_prefixes_logic exists 
		// if not, add it
		$prefixes_index = $this->db->query("SHOW INDEX FROM dxcc_prefixes WHERE Key_name = 'idx_dxcc_prefixes_logic'")->num_rows();
		if ($prefixes_index == 0) {
			$this->db->query("ALTER TABLE `dxcc_prefixes` ADD INDEX `idx_dxcc_prefixes_logic` (`call`, `start`, `end`)");
		}


		// check if index dxcc_exceptions exists 
		// if not, add it
		$exceptions_index = $this->db->query("SHOW INDEX FROM dxcc_exceptions WHERE Key_name = 'idx_dxcc_exceptions_logic'")->num_rows();
		if ($exceptions_index == 0) {
			$this->db->query("ALTER TABLE `dxcc_exceptions` ADD INDEX `idx_dxcc_exceptions_logic` (`call`, `start`, `end`)");
		}
	}

	public function down(){

		// check if index idx_dxcc_prefixes_logic exists
		// if so, drop it
		$prefixes_index = $this->db->query("SHOW INDEX FROM dxcc_prefixes WHERE Key_name = 'idx_dxcc_prefixes_logic'")->num_rows();
		if ($prefixes_index == 1) {
			$this->db->query("ALTER TABLE dxcc_prefixes DROP INDEX idx_dxcc_prefixes_logic");
		}

		// check if index dxcc_exceptions exists
		// if so, drop it
		$exceptions_index = $this->db->query("SHOW INDEX FROM dxcc_exceptions WHERE Key_name = 'idx_dxcc_exceptions_logic'")->num_rows();
		if ($exceptions_index == 1) {
			$this->db->query("ALTER TABLE dxcc_exceptions DROP INDEX idx_dxcc_exceptions_logic");
		}
	}
}
