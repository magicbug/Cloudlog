<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_add_power_to_cat
 *
 * Creates an int column in CAT table that holds value of radio output power
 * 
 */

class Migration_add_power_to_cat extends CI_Migration {

        public function up()
        {
                if (!$this->db->field_exists('power', 'cat')) {
                        $fields = array(
                                'power INT NULL DEFAULT 0',
                        );
                        $this->dbforge->add_column('cat', $fields, 'sat_name');
                }
        }

        public function down()
        {
                $this->dbforge->drop_column('cat', 'power');
        }
}
