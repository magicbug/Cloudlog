<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_new_stylesheet_default extends CI_Migration {

    public function up()
    {
        $sql = "UPDATE users SET user_stylesheet = 'default'";
		$this->db->query($sql);
    }

    public function down()
    {
        $sql = "UPDATE users SET user_stylesheet = 'bootstrap.min.css'";
		$this->db->query($sql);
    }
}