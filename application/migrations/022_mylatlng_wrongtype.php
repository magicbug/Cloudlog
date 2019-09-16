<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_mylatlng_wrongtype extends CI_Migration {

        public function up()
        {
                $fields = array(
                        'COL_MY_LAT' => array(
                                'name' => 'COL_MY_LAT',
                                'type' => 'VARCHAR',
                                'constraint' => '15',
                        ),

                        'COL_MY_LON' => array(
                                'name' => 'COL_MY_LON',
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