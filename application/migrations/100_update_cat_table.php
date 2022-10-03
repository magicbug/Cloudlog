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

                $fields = array(

                    'downlink_freq' => array(
                            'name' => 'frequency_rx',
                            'type' => 'BIGINT',
                            'constraint' => '13',
                    ),
                );
                $this->dbforge->modify_column('cat', $fields);

            }
            if ($this->db->field_exists('downlink_mode', 'cat')) {
                $fields = array(

                    'downlink_mode' => array(
                            'name' => 'mode_rx',
                            'type' => 'VARCHAR',
                            'constraint' => '10',
                    ),
                );
                $this->dbforge->modify_column('cat', $fields);
            }
        }
    }

    public function down()
    {
        if ($this->db->table_exists('cat')) {
            if ($this->db->field_exists('frequency_rx', 'cat')) {
                $fields = array(
                    'frequency_rx' => array(
                        'name'    => 'downlink_freq',
                        'type'    => 'BIGINT',
                        'null'    => TRUE,
                        'default' => NULL,
                    ),
                );
                $this->dbforge->modify_column('cat', $fields);
            }
            if ($this->db->field_exists('mode_rx', 'cat')) {
                $fields = array(
                    'mode_rx' => array(
                        'name'    => 'downlink_mode',
                        'type'    => 'VARCHAR(255)',
                        'null'    => TRUE,
                        'default' => NULL,
                    ),
                );
                $this->dbforge->modify_column('cat', $fields);
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
