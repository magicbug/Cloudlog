<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_dateformat_to_users extends CI_Migration {

    public function up()
    {
        $fields = array(
            'user_date_format varchar(15) DEFAULT \'d/m/y\'',
        );

        $this->dbforge->add_column('users', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_column('users', 'user_date_format');
    }
}