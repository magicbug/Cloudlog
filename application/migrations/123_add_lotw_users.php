<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_add_lotw_users extends CI_Migration
{
	public function up()
	{
		if (!$this->db->table_exists('lotw_users')) {
            $this->dbforge->add_field(array(
                'id' => array(
                    'type' => 'INT',
                    'constraint' => 20,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE,
                    'unique' => TRUE
                ),
                'callsign' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 32,
                    'unsigned' => TRUE,
                ),
                'lastupload' => array(
                    'type' => 'datetime',
                )
            ));

            $this->dbforge->add_key('id', TRUE);

            $this->dbforge->create_table('lotw_users');

            $this->db->query("ALTER TABLE lotw_users ADD INDEX `callsign` (`callsign`)");
        }

	}

	public function down()
	{
      if ($this->db->table_exists('lotw_users')) {
        $this->dbforge->drop_table('lotw_users');
      }
	}
}
