<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
*   Create station_logbooks_permissions table for multi-user logbook sharing
*/

class Migration_create_logbook_permissions extends CI_Migration {

    public function up()
    {
        // Check if table already exists
        if (!$this->db->table_exists('station_logbooks_permissions')) {
            // Create station_logbooks_permissions table
            $this->dbforge->add_field(array(
            'permission_id' => array(
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'logbook_id' => array(
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => TRUE,
            ),
            'user_id' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'permission_level' => array(
                'type' => 'ENUM',
                'constraint' => array('read', 'write', 'admin'),
                'default' => 'read',
            ),
            'created_at' => array(
                'type' => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP',
            ),
            'modified' => array(
                'type' => 'TIMESTAMP',
                'null' => TRUE,
            ),
        ));

            $this->dbforge->add_key('permission_id', TRUE);
            $this->dbforge->create_table('station_logbooks_permissions');

            // Add unique constraint on logbook_id + user_id combination
            $this->db->query('CREATE UNIQUE INDEX idx_logbook_user ON station_logbooks_permissions (logbook_id, user_id)');
            
            // Add indexes for performance
            $this->db->query('CREATE INDEX idx_logbook_id ON station_logbooks_permissions (logbook_id)');
            $this->db->query('CREATE INDEX idx_user_id ON station_logbooks_permissions (user_id)');

            // Add foreign key for logbook_id only (station_logbooks uses InnoDB)
            // Note: Cannot add foreign key for user_id because users table uses MyISAM engine
            $this->db->query('ALTER TABLE station_logbooks_permissions 
                ADD CONSTRAINT fk_slp_logbook 
                FOREIGN KEY (logbook_id) 
                REFERENCES station_logbooks(logbook_id) 
                ON DELETE CASCADE');
        }
    }

    public function down()
    {
        $this->dbforge->drop_table('station_logbooks_permissions');
    }
}
