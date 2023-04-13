<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_add_contest_session_table extends CI_Migration
{
	public function up()
	{
		if (!$this->db->table_exists('contest_session')) {
            $this->dbforge->add_field(array(
                'id' => array(
                    'type' => 'INT',
                    'constraint' => 20,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE,
                    'unique' => TRUE
                ),
                'contestid' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                    'unsigned' => TRUE,
                    'auto_increment' => FALSE
                ),
                'exchangetype' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 20,
                    'unsigned' => TRUE,
                    'auto_increment' => FALSE
                ),
                'exchangesent' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 20,
                    'unsigned' => TRUE,
                    'auto_increment' => FALSE
                ),
                'serialsent' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 20,
                    'unsigned' => TRUE,
                    'auto_increment' => FALSE
                ),
                'copytodok' => array(
                    'type' => 'bigint',
                    'unsigned' => TRUE,
                    'auto_increment' => FALSE
                ),
                'qso' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                    'unsigned' => TRUE,
                    'auto_increment' => FALSE
                ),
                'station_id' => array(
                    'type' => 'bigint',
                    'unsigned' => TRUE,
                    'auto_increment' => FALSE
                ),
            ));

            $this->dbforge->add_key('id', TRUE);

            $this->dbforge->create_table('contest_session');
        }
	}

	public function down()
	{
		$this->dbforge->drop_table('contest_session');
	}
}