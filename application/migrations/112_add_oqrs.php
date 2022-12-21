<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_oqrs extends CI_Migration {

	public function up()
	{
		if (!$this->db->field_exists('oqrs', 'station_profile')) {
			$fields = array(
				'oqrs int DEFAULT 0',
			);

			$this->dbforge->add_column('station_profile', $fields);
		}

		if (!$this->db->field_exists('oqrs_text', 'station_profile')) {
			$fields = array(
				'oqrs_text text',
			);

			$this->dbforge->add_column('station_profile', $fields);
		}

        if (!$this->db->field_exists('oqrs_email', 'station_profile')) {
			$fields = array(
				'oqrs_email int DEFAULT 0',
			);

			$this->dbforge->add_column('station_profile', $fields);
		}

		if (!$this->db->table_exists('oqrs')) {
            $this->dbforge->add_field(array(
                'id' => array(
                    'type' => 'INT',
                    'constraint' => 20,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE,
                    'unique' => TRUE
                ),
                'requesttime' => array(
                    'type' => 'timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
                ),
                'date' => array(
                    'type' => 'date',
                ),
                'time' => array(
                    'type' => 'time',
                ),
				'band' => array(
                    'type' => 'VARCHAR',
					'constraint' => 10,
                ),
                'mode' => array(
                    'type' => 'VARCHAR',
					'constraint' => 12,
                ),
                'requestcallsign' => array(
                    'type' => 'VARCHAR',
					'constraint' => 32,
				),
				'station_id' => array(
                    'type' => 'int',
                ),
				'note' => array(
                    'type' => 'TEXT',
                ),
				'email' => array(
                    'type' => 'TEXT',
                ),
				'qslroute' => array(
                    'type' => 'VARCHAR',
					'constraint' => 50,
                ),
				'status' => array(
                    'type' => 'int',
                ),
				'qsoid' => array(
                    'type' => 'int',
                )
            ));

			$this->dbforge->add_key('id', TRUE);

            $this->dbforge->create_table('oqrs');
		}
	}

	public function down()
	{
		if ($this->db->field_exists('oqrs', 'station_profile')) {
			$this->dbforge->drop_column('station_profile', 'oqrs');
		}
		if ($this->db->field_exists('oqrs_text', 'station_profile')) {
			$this->dbforge->drop_column('station_profile', 'oqrs_text');
		}

        if ($this->db->field_exists('oqrs_email', 'station_profile')) {
			$this->dbforge->drop_column('station_profile', 'oqrs_email');
		}

		$this->dbforge->drop_table('oqrs');
	}
}