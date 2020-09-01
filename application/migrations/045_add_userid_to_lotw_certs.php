<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_userid_to_lotw_certs extends CI_Migration {

    public function up()
    {
        $fields = array(
            'user_id int(11) DEFAULT NULL',  
        );


        $this->dbforge->add_column('lotw_certs', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_column('lotw_certs', 'user_id');
    }
}