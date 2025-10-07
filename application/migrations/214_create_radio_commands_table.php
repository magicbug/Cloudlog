<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_radio_commands_table extends CI_Migration {

    public function up()
    {
        // Drop table if it exists (for rerunning migration)
        if ($this->db->table_exists('radio_commands')) {
            $this->dbforge->drop_table('radio_commands');
        }

        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'radio_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => FALSE,
            ),
            'radio_name' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => TRUE,
            ),
            'user_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => FALSE,
            ),
            'station_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => TRUE,
            ),
            'command_type' => array(
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => FALSE,
            ),
            'frequency' => array(
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => TRUE,
                'null' => TRUE,
            ),
            'mode' => array(
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => TRUE,
            ),
            'bandwidth' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => TRUE,
            ),
            'vfo' => array(
                'type' => 'VARCHAR',
                'constraint' => 5,
                'null' => TRUE,
            ),
            'status' => array(
                'type' => 'ENUM',
                'constraint' => array('PENDING', 'PROCESSING', 'COMPLETED', 'FAILED'),
                'default' => 'PENDING',
                'null' => FALSE,
            ),
            'error_message' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'created_at' => array(
                'type' => 'TIMESTAMP',
                'null' => FALSE,
            ),
            'processed_at' => array(
                'type' => 'TIMESTAMP',
                'null' => TRUE,
            ),
            'expires_at' => array(
                'type' => 'TIMESTAMP',
                'null' => FALSE,
            ),
        ));

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key(array('radio_id', 'status'));
        $this->dbforge->add_key(array('created_at', 'status'));
        $this->dbforge->add_key('user_id');
        $this->dbforge->add_key('expires_at');

        $this->dbforge->create_table('radio_commands');

        // Note: Foreign key constraints removed for compatibility
        // The application will handle referential integrity through the model layer
        // This ensures the migration works regardless of database engine or existing table structure

        // Set default timestamp for created_at
        if ($this->db->dbdriver == 'mysql' || $this->db->dbdriver == 'mysqli') {
            $this->db->query('ALTER TABLE `' . $this->db->dbprefix . 'radio_commands` 
                MODIFY `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP');
        }
    }

    public function down()
    {
        $this->dbforge->drop_table('radio_commands');
    }
}