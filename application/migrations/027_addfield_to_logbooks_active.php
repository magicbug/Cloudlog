<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_addfield_to_logbooks_active extends CI_Migration {

        public function up()
        {
                $fields = array(
                        'active_logbook tinyint(1) DEFAULT NULL',
                );

                $this->dbforge->add_column('logbooks', $fields);
        }

        public function down()
        {
                $this->dbforge->drop_column('active_logbook', 'logbooks');
        }
}