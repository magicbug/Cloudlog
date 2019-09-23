<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_station_active extends CI_Migration {

        public function up()
        {
                $fields = array(
                        'station_active tinyint(1) DEFAULT NULL',
                );

                $this->dbforge->add_column('station_profile', $fields);
        }

        public function down()
        {
                $this->dbforge->drop_column('station_profile', 'station_active');
        }
}