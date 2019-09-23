<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_station_id_to_logbook extends CI_Migration {

        public function up()
        {
                $fields = array(
                        'station_id int(11) DEFAULT NULL',
                        
                );

                $this->dbforge->add_column($this->config->item('table_name'), $fields);
        }

        public function down()
        {
                echo "not possible";
        }
}