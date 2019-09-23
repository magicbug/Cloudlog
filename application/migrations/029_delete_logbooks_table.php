<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_delete_logbooks_table extends CI_Migration {

        public function up()
        {
                $this->dbforge->drop_table('logbooks');
        }

        public function down()
        {
                echo "not possible";
        }
}