<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_stationprofile_measurement_base extends CI_Migration {

    public function up()
    {
        $fields = array(
            'measurement_base varchar(1) DEFAULT "K"',
        );

        $this->dbforge->add_column('station_profile', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_column('station_profile', 'measurement_base');
    }
}