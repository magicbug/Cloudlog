<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_col_client_ref extends CI_Migration {

	public function up()
	{
		$this->load->dbforge();
		$table_name = $this->config->item('table_name');
		$this->db->db_debug = false;

		if (!$this->db->field_exists('COL_CLIENT_REF', $table_name)) {
			$fields = array(
				'COL_CLIENT_REF' => array(
					'type' => 'VARCHAR',
					'constraint' => 36,
					'null' => TRUE,
				),
			);
			$this->dbforge->add_column($table_name, $fields);
		}

		$index_exists = $this->db->query(
			"SHOW INDEX FROM {$table_name} WHERE Key_name = 'idx_station_client_ref'"
		)->num_rows();

		if ($index_exists === 0) {
			$this->db->query(
				"ALTER TABLE {$table_name} ADD UNIQUE INDEX idx_station_client_ref (station_id, COL_CLIENT_REF)"
			);
		}

		$this->db->db_debug = true;
	}

	public function down()
	{
		$this->load->dbforge();
		$table_name = $this->config->item('table_name');
		$this->db->db_debug = false;

		$index_exists = $this->db->query(
			"SHOW INDEX FROM {$table_name} WHERE Key_name = 'idx_station_client_ref'"
		)->num_rows();

		if ($index_exists > 0) {
			$this->db->query("ALTER TABLE {$table_name} DROP INDEX idx_station_client_ref");
		}

		if ($this->db->field_exists('COL_CLIENT_REF', $table_name)) {
			$this->dbforge->drop_column($table_name, 'COL_CLIENT_REF');
		}

		$this->db->db_debug = true;
	}
}
