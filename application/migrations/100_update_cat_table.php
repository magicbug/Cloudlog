<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
   Update CAT table
*/

class Migration_update_cat_table extends CI_Migration {

    public function up()
    {
        if ($this->db->table_exists('cat')) {
            if ($this->db->field_exists('uplink_freq', 'cat')) {
                $this->dbforge->drop_column('cat', 'uplink_freq');
            }
            if ($this->db->field_exists('uplink_mode', 'cat')) {
                $this->dbforge->drop_column('cat', 'uplink_mode');
            }
            if ($this->db->field_exists('downlink_freq', 'cat')) {
                $this->db->query("ALTER TABLE cat CHANGE COLUMN downlink_freq TO frequency_rx");
            }
            if ($this->db->field_exists('downlink_mode', 'cat')) {
                $this->db->query("ALTER TABLE cat CHANGE COLUMN downlink_mode TO mode_rx");
            }
        }
    }

    public function down()
    {
        if ($this->db->table_exists('cat')) {
            if ($this->db->field_exists('frequency_rx', 'cat')) {
                $this->db->query("ALTER TABLE cat CHANGE COLUMN frequency_rx TO downlink_freq");
            }
            if ($this->db->field_exists('mode_rx', 'cat')) {
                $this->db->query("ALTER TABLE cat CHANGE COLUMN mode_rx TO downlink_mode");
            }
            if (!$this->db->field_exists('uplink_freq', 'cat')) {
                $fields = array(
                    'uplink_freq bigint(13) DEFAULT NULL AFTER `downlink_freq`',
                );
                $this->dbforge->add_column('cat', $fields);
            }
            if (!$this->db->field_exists('uplink_mode', 'cat')) {
                $fields = array(
                    'uplink_mode varchar(255) DEFAULT NULL AFTER `downlink_mode`',
                );
                $this->dbforge->add_column('cat', $fields);
            }
        }
    }
}
