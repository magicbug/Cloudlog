<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_qslviavarchar extends CI_Migration {

        public function up()
        {
                $this->db->db_debug = false;
                $this->db->query("ALTER TABLE ".$this->config->item('table_name')." CHANGE COLUMN COL_QSL_VIA COL_QSL_VIA varchar(255) DEFAULT NULL;");
                $this->db->db_debug = true;
        }

        public function down()
        {
                echo "Not possible, sorry.";
        }
}