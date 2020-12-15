<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_user_stylesheet extends CI_Migration {

    public function up()
    {
        $fields = array(
            'user_stylesheet varchar(255) default "bootstrap.min.css"',
        );

        $this->dbforge->add_column('users', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_column('users', 'user_stylesheet');
    }
}