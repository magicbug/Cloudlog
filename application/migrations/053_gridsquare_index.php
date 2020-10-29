<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_gridsquare_index extends CI_Migration {

    public function up()
    {
        $sql = "ALTER TABLE ".$this->config->item('table_name')." ADD INDEX `gridsquares` (`COL_GRIDSQUARE`);";
        $this->db->query($sql);
    }

    public function down()
    {

    }
}