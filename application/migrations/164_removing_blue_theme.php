<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
*   Create a dxpedition table
*/

class Migration_removing_blue_theme extends CI_Migration
{
    public function up()
    {
		$this->db->query("DELETE FROM themes WHERE foldername = 'blue';");
        $this->db->query("DELETE FROM themes WHERE foldername = 'blue_wide';");
        $this->db->query("UPDATE users SET user_stylesheet = 'superhero' WHERE user_stylesheet = 'blue'");
        $this->db->query("UPDATE users SET user_stylesheet = 'superhero_wide' WHERE user_stylesheet = 'blue_wide'");
        $this->db->query("UPDATE options SET option_value = 'superhero' WHERE option_value = 'blue'");
        $this->db->query("UPDATE options SET option_value = 'superhero_wide' WHERE option_value = 'blue_wide'");
    }

    public function down()
    {
		$this->db->query("INSERT INTO themes (name, foldername) SELECT DISTINCT 'Blue','blue' FROM themes WHERE NOT EXISTS (SELECT 1 FROM themes WHERE foldername = 'blue');");
        $this->db->query("INSERT INTO themes (name, foldername) SELECT DISTINCT 'Blue wide','blue_wide' FROM themes WHERE NOT EXISTS (SELECT 1 FROM themes WHERE foldername = 'blue_wide');");
    }
}
