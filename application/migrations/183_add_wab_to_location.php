<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
*   Add WAB Field to Station Location
*/

class Migration_add_wab_to_location extends CI_Migration
{

    public function up()
    {
        if (!$this->db->field_exists('station_wab', 'station_profile')) {
            // Add WAB Ref to station profile
            $fields = array(
                'station_wab varchar(10) DEFAULT NULL',
            );
            $this->dbforge->add_column('station_profile', $fields);
        }
    }

    public function down()
    {
        if ($this->db->field_exists('station_wab', 'station_profile')) {
            $this->dbforge->drop_column('station_profile', 'station_wab');
        }
    }
}
