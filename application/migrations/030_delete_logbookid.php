<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_delete_logbookid extends CI_Migration {

        public function up()
        {
            $this->dbforge->drop_column($this->config->item('table_name'), 'logbook_id');

        }

        public function down()
        {
            echo "not possible";
        }
}