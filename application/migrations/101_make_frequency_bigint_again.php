<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
   Restore initial field settings for frequency as it
   broke with commit f6feea5
*/

class Migration_make_frequency_bigint_again extends CI_Migration {

    public function up()
    {
        if ($this->db->table_exists('cat')) {
            if ($this->db->field_exists('frequency', 'cat')) {
                $fields = array(
                    'frequency' => array(
                        'name' => 'frequency',
                        'type' => 'BIGINT',
                        'null' => TRUE,
                        'default' => NULL,
                    ),
                );
                $this->dbforge->modify_column('cat', $fields);
            }
        }
    }

    public function down()
    {
        if ($this->db->table_exists('cat')) {
            if ($this->db->field_exists('frequency', 'cat')) {
                $fields = array(
                    'frequency' => array(
                        'name' => 'frequency',
                        'type' => 'VARCHAR(10)',
                        'null' => TRUE,
                        'default' => NULL,
                    ),
                );
                $this->dbforge->modify_column('cat', $fields);
            }
        }
    }
}
