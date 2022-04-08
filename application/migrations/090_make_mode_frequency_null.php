<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_make_mode_frequency_null
 *
 * Modify frequency and mode column so that NULL is allowed
 * Basically for propmode != SAT
 * 
 */

class Migration_make_mode_frequency_null extends CI_Migration {

        public function up()
        {
                if ($this->db->field_exists('frequency', 'cat')) {
                        $fields = array(
                           'frequency' => array(
                              'type' => 'VARCHAR(10) NULL',
                           ),
                        );
                        $this->dbforge->modify_column('cat', $fields);
                }
                if ($this->db->field_exists('mode', 'cat')) {
                        $fields = array(
                           'mode' => array(
                              'type' => 'VARCHAR(10) NULL',
                           ),
                        );
                        $this->dbforge->modify_column('cat', $fields);
                }
        }

        public function down()
        {
                if ($this->db->field_exists('frequency', 'cat')) {
                        $fields = array(
                           'frequency' => array(
                              'type' => 'VARCHAR(10) NOT NULL',
                           ),
                        );
                        $this->dbforge->modify_column('cat', $fields);
                }
                if ($this->db->field_exists('mode', 'cat')) {
                        $fields = array(
                           'mode' => array(
                              'type' => 'VARCHAR(10) NOT NULL',
                           ),
                        );
                        $this->dbforge->modify_column('cat', $fields);
                }
        }
}
