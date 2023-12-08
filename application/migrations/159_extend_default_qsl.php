<?php

// Create migration that makes the submode column in the logbook table an index
class Migration_extend_default_qsl  extends CI_Migration
{

    public function up()
    {
        $this->db->query("ALTER TABLE `users` CHANGE COLUMN `user_default_confirmation` `user_default_confirmation` VARCHAR(4) NULL DEFAULT NULL");
    }

    public function down()
    {
        //the down function can be empty here, but we need one. 
    }
}
