<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_userid_to_station_table extends CI_Migration {

        public function up()
        {

                $fields = array(
                        'user_id int(11) DEFAULT NULL',
                        
                );

                $this->dbforge->add_column('station_profile', $fields);
        }

        public function down()
        {
                $this->dbforge->drop_column('station_profile', 'user_id');
        }
}