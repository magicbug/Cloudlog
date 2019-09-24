<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_log_setindex_station_id extends CI_Migration {

        public function up()
        {
            $this->db->db_debug = false;
            $this->db->query("ALTER TABLE ".$this->config->item('table_name')." ADD INDEX(`station_id`);");
            $this->db->db_debug = true;
        }

        public function down()
        {
            echo "Not possible, sorry.";
        }
}