<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_eqslqthnickname_to_stationprofile extends CI_Migration {

        public function up()
        {
                $fields = array(
                        'eqslqthnickname varchar(255) DEFAULT NULL',
                );

                $this->dbforge->add_column('station_profile', $fields);
        }

        public function down()
        {
                $this->dbforge->drop_column('station_profile', 'eqslqthnickname');
        }
}