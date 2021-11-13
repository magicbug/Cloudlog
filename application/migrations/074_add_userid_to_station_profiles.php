<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_userid_to_station_profiles extends CI_Migration {

    public function up()
    {
        $fields = array(
            'user_id BIGINT(20) DEFAULT NULL',
        );

        if (!$this->db->field_exists('user_id', 'station_profile')) {
            $this->dbforge->add_column('station_profile', $fields);
        }
    }

    public function down()
    {
        $this->dbforge->drop_column('station_profile', 'user_id');
    }
}