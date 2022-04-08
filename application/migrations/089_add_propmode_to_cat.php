<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_add_propmode_to_cat
 *
 * Creates a varchar column in CAT table that holds value of propagation mode
 * 
 */

class Migration_add_propmode_to_cat extends CI_Migration {

        public function up()
        {
                if (!$this->db->field_exists('prop_mode', 'cat')) {
                        $fields = array(
                                'prop_mode VARCHAR(10) DEFAULT NULL',
                        );
                        $this->dbforge->add_column('cat', $fields, 'sat_name');
                }
        }

        public function down()
        {
                $this->dbforge->drop_column('cat', 'propmode');
        }
}
