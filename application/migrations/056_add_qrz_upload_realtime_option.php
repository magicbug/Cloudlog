<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_qrz_upload_realtime_option extends CI_Migration {

    public function up()
    {
        $fields = array(
            'qrzrealtime bool DEFAULT TRUE',
        );

        $this->dbforge->add_column('station_profile', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_column('station_profile', 'qrzrealtime');
    }
}