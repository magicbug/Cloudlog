<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_latlng_wrongtype extends CI_Migration {

        public function up()
        {
                $fields = array(
                        'COL_LAT' => array(
                                'name' => 'COL_LAT',
                                'type' => 'VARCHAR',
                                'constraint' => '15',
                        ),

                        'COL_LON' => array(
                                'name' => 'COL_LON',
                                'type' => 'VARCHAR',
                                'constraint' => '15',
                        ),
                );
                $this->dbforge->modify_column($this->config->item('table_name'), $fields);
        }

        public function down()
        {
                echo "Not possible, sorry.";
        }
}