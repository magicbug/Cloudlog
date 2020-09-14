<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_stationprofile_measurement_base extends CI_Migration {

    public function up()
    {
        $fields = array(
            'user_measurement_base varchar(1)',
        );

        $this->dbforge->add_column('users', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_column('users', 'user_measurement_base');
    }
}