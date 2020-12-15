<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_delete_lotw_userlist_table extends CI_Migration {

        public function up()
        {
                $this->dbforge->drop_table('lotw_userlist');
        }

        public function down()
        {
                echo "not possible";
        }
}