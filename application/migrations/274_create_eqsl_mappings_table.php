<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_create_eqsl_mappings_table extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('eqsl_mappings')) {
            $this->dbforge->add_field(array(
                'mapping_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true,
                ),
                'user_id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => false,
                ),
                'station_id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => false,
                ),
                'eqsl_username' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 32,
                    'null' => false,
                ),
                'eqsl_password' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 64,
                    'null' => false,
                ),
                'eqsl_qth_nickname' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => false,
                ),
                'enabled' => array(
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 1,
                ),
                'preferred_for_download' => array(
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 1,
                ),
                'created_at' => array(
                    'type' => 'DATETIME',
                    'null' => false,
                ),
                'modified' => array(
                    'type' => 'DATETIME',
                    'null' => true,
                ),
            ));

            $this->dbforge->add_key('mapping_id', true);
            $this->dbforge->create_table('eqsl_mappings');

            $this->db->query('CREATE UNIQUE INDEX idx_eqsl_mapping_unique ON eqsl_mappings (user_id, station_id, eqsl_username, eqsl_qth_nickname)');
            $this->db->query('CREATE INDEX idx_eqsl_mapping_station ON eqsl_mappings (station_id, enabled)');
            $this->db->query('CREATE INDEX idx_eqsl_mapping_user ON eqsl_mappings (user_id, enabled)');
        }

        $this->seed_from_legacy_fields();
    }

    private function seed_from_legacy_fields()
    {
        if (!$this->db->table_exists('eqsl_mappings')) {
            return;
        }

        // Backfill mappings from existing user-level credentials and station-level nicknames.
        $sql = "INSERT IGNORE INTO eqsl_mappings (user_id, station_id, eqsl_username, eqsl_password, eqsl_qth_nickname, enabled, preferred_for_download, created_at)\n"
            . "SELECT station_profile.user_id, station_profile.station_id, users.user_eqsl_name, users.user_eqsl_password, station_profile.eqslqthnickname, 1, 1, NOW()\n"
            . "FROM station_profile\n"
            . "INNER JOIN users ON users.user_id = station_profile.user_id\n"
            . "WHERE COALESCE(station_profile.eqslqthnickname, '') <> ''\n"
            . "AND COALESCE(users.user_eqsl_name, '') <> ''\n"
            . "AND COALESCE(users.user_eqsl_password, '') <> ''";

        $this->db->query($sql);
    }

    public function down()
    {
        if ($this->db->table_exists('eqsl_mappings')) {
            $this->dbforge->drop_table('eqsl_mappings');
        }
    }
}
