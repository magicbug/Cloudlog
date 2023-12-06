<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
*   Create a dxpedition table
*/

class Migration_wide_themes extends CI_Migration
{
    public function up()
    {
		$this->db->query("INSERT IGNORE INTO themes (name, foldername) values ('Blue wide','blue_wide');");
		$this->db->query("INSERT IGNORE INTO themes (name, foldername) values ('Cosmo wide','cosmo_wide');");
		$this->db->query("INSERT IGNORE INTO themes (name, foldername) values ('Cyborg wide (Dark)','cyborg_wide');");
		$this->db->query("INSERT IGNORE INTO themes (name, foldername) values ('Darkly wide (Dark)','darkly_wide');");
		$this->db->query("INSERT IGNORE INTO themes (name, foldername) values ('Default wide','default_wide');");
		$this->db->query("INSERT IGNORE INTO themes (name, foldername) values ('Superhero wide (Dark)','superhero_wide');");
    }

    public function down()
    {
		$this->db->query("delete from themes where foldername = 'blue_wide';");
		$this->db->query("delete from themes where foldername = 'cosmo_wide';");
		$this->db->query("delete from themes where foldername = 'cyborg_wide';");
		$this->db->query("delete from themes where foldername = 'darkly_wide';");
		$this->db->query("delete from themes where foldername = 'default_wide';");
		$this->db->query("delete from themes where foldername = 'superhero_wide';");
    }
}
