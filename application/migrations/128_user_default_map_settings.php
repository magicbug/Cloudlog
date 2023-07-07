<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_user_default_map_settings
 *
 * Creates user account settings for choosing default map 
 * and QSL type for Gridmap view
*/

class Migration_user_default_map_settings extends CI_Migration {

        public function up()
        {
                if (!$this->db->field_exists('user_gridmap_default_band', 'users')) {
                        $fields = array(
                                'user_gridmap_default_band varchar(10) default NULL',
                        );
                        $this->dbforge->add_column('users', $fields);
                }
                if (!$this->db->field_exists('user_gridmap_confirmation', 'users')) {
                        $fields = array(
                                'user_gridmap_confirmation varchar(3) default NULL',
                        );
                        $this->dbforge->add_column('users', $fields);
                }
                $data = array(
                   'user_gridmap_default_band' => 'All',
                   'user_gridmap_confirmation' => 'QL',
                );
                $this->db->update('users', $data);
        }

        public function down()
        {
                $this->dbforge->drop_column('users', 'user_gridmap_default_band');
                $this->dbforge->drop_column('users', 'user_gridmap_confirmation');
        }
}


 
