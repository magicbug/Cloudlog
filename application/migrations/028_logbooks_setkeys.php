<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_logbooks_setkeys extends CI_Migration {

        public function up()
        {
            $this->db->db_debug = false;
            $this->db->query("ALTER TABLE `logbooks` ADD UNIQUE(`id`);");
            $this->db->query("ALTER TABLE `logbooks` ADD INDEX(`id`);");
            $this->db->query("ALTER TABLE `logbooks` ADD INDEX(`logbook_name`);");
            $this->db->db_debug = true;
        }

        public function down()
        {
            echo "Not possible, sorry.";
        }
}