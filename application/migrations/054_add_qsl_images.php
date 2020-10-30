<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_qsl_images extends CI_Migration {

    public function up()
    {
        // create qsl images table
        $this->db->query("CREATE TABLE IF NOT EXISTS `qsl_images` 
                    (`id` integer NOT NULL auto_increment, `qsoid` int, `filename` text, primary key (id)) 
                    ENGINE=myisam DEFAULT CHARSET=utf8;");
    }

    public function down()
    {
        $this->db->query("");
    }
}