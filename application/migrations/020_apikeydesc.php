<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_apikeydesc extends CI_Migration {

        public function up()
        {
                $user_fields = array(
                        'description VARCHAR(255) DEFAULT NULL'
                );
                $this->dbforge->add_column('api', $user_fields);
        }

        public function down()
        {
                $this->dbforge->drop_column('api', 'description');
        }
}