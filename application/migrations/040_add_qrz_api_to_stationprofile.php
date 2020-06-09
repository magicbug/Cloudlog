<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_qrz_api_to_stationprofile extends CI_Migration {

    public function up()
    {
        $fields = array(
            'qrzapikey varchar(20) DEFAULT NULL',
        );

        $this->dbforge->add_column('station_profile', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_column('station_profile', 'qrzapikey');
    }
}