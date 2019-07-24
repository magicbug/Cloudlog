<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_forceint_wrongtype extends CI_Migration {

        public function up()
        {
                $fields = array(
                        'COL_FORCE_INIT' => array(
                                'name' => 'COL_FORCE_INIT',
                                'type' => 'VARCHAR',
                                'constraint' => '2',
                        ),

                        'COL_SWL' => array(
                                'name' => 'COL_SWL',
                                'type' => 'VARCHAR',
                                'constraint' => '2',
                        ),
                );
                $this->dbforge->modify_column($this->config->item('table_name'), $fields);
        }

        public function down()
        {
                echo "Not possible, sorry.";
        }
}