<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_signature_to_station_profile extends CI_Migration {

        public function up()
        {
                $fields = array(
                        'station_sig varchar(50) DEFAULT NULL',
                        'station_sig_info varchar(50) DEFAULT NULL',
                );

                $this->dbforge->add_column('station_profile', $fields);
        }

        public function down()
        {
                $this->dbforge->drop_column('station_profile', 'station_sig');
                $this->dbforge->drop_column('station_profile', 'station_sig_info');
        }
}