<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_plugins_table extends CI_Migration {

    public function up()
    {
        if (!$this->db->table_exists('plugins')) {
            $this->dbforge->add_field(array(
                'id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE,
                ),
                'plugin_slug' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 120,
                    'null' => FALSE,
                ),
                'plugin_name' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => FALSE,
                ),
                'plugin_version' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 60,
                    'null' => FALSE,
                    'default' => '1.0.0',
                ),
                'plugin_description' => array(
                    'type' => 'TEXT',
                    'null' => TRUE,
                ),
                'plugin_status' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 16,
                    'null' => FALSE,
                    'default' => 'disabled',
                ),
                'plugin_manifest' => array(
                    'type' => 'MEDIUMTEXT',
                    'null' => TRUE,
                ),
                'installed_at' => array(
                    'type' => 'DATETIME',
                    'null' => FALSE,
                ),
                'updated_at' => array(
                    'type' => 'DATETIME',
                    'null' => FALSE,
                ),
            ));

            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->add_key('plugin_slug', TRUE);
            $this->dbforge->create_table('plugins');
        }
    }

    public function down()
    {
        if ($this->db->table_exists('plugins')) {
            $this->dbforge->drop_table('plugins');
        }
    }
}
