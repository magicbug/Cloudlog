<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_cota_to_tables extends CI_Migration {

        public function up()
        {
            $this->db->db_debug = false;
            $this->db->query("ALTER TABLE ".$this->config->item('table_name')." ADD COL_MY_COTA_REF varchar(50);");
            $this->db->query("ALTER TABLE ".$this->config->item('table_name')." ADD COL_COTA_REF varchar(50);");
            $this->db->query("ALTER TABLE `station_profile` ADD `station_cota` varchar(10) CHARACTER SET utf8mb4 NOT NULL;");
            $this->db->db_debug = true;
        }

        public function down()
        {
            echo "Not possible, sorry.";
        }
}
