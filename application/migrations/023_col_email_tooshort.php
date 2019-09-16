<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_col_email_tooshort extends CI_Migration {

        public function up()
        {
                $fields = array(
                        'COL_EMAIL' => array(
                                'name' => 'COL_EMAIL',
                                'type' => 'VARCHAR',
                                'constraint' => '255',
                        )
                );
                $this->dbforge->modify_column($this->config->item('table_name'), $fields);
        }

        public function down()
        {
                echo "Not possible, sorry.";
        }
}