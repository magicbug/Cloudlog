<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_column_logbookid extends CI_Migration {

        public function up()
        {
                 $fields = array(
                        'logbook_id int(11) DEFAULT NULL',
                );

                $this->dbforge->add_column($this->config->item('table_name'), $fields);
        }

        public function down()
        {
                echo "not possible";
        }
}