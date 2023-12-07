<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
*   Create a dxpedition table
*/

class Migration_wide_themes extends CI_Migration
{
    public function up()
    {
		$this->db->query("INSERT INTO themes (name, foldername) SELECT DISTINCT 'Blue wide','blue_wide' FROM themes WHERE NOT EXISTS (SELECT 1 FROM themes WHERE foldername = 'blue_wide');");
		$this->db->query("INSERT INTO themes (name, foldername) SELECT DISTINCT 'Cosmo wide','cosmo_wide' FROM themes WHERE NOT EXISTS (SELECT 1 FROM themes WHERE foldername = 'cosmo_wide');");
		$this->db->query("INSERT INTO themes (name, foldername) SELECT DISTINCT 'Cyborg wide (Dark)','cyborg_wide' FROM themes WHERE NOT EXISTS (SELECT 1 FROM themes WHERE foldername = 'cyborg_wide');");
		$this->db->query("INSERT INTO themes (name, foldername) SELECT DISTINCT 'Darkly wide (Dark)','darkly_wide' FROM themes WHERE NOT EXISTS (SELECT 1 FROM themes WHERE foldername = 'darkly_wide');");
		$this->db->query("INSERT INTO themes (name, foldername) SELECT DISTINCT 'Default wide','default_wide' FROM themes WHERE NOT EXISTS (SELECT 1 FROM themes WHERE foldername = 'default_wide');");
		$this->db->query("INSERT INTO themes (name, foldername) SELECT DISTINCT 'Superhero wide (Dark)','superhero_wide' FROM themes WHERE NOT EXISTS (SELECT 1 FROM themes WHERE foldername = 'superhero_wide');");
    }

    public function down()
    {
		$this->db->query("DELETE FROM themes WHERE foldername = 'blue_wide';");
		$this->db->query("UPDATE users SET user_stylesheet = 'blue' WHERE user_stylesheet = 'blue_wide'");
		$this->db->query("DELETE FROM themes WHERE foldername = 'cosmo_wide';");
		$this->db->query("UPDATE users SET user_stylesheet = 'cosmo' WHERE user_stylesheet = 'cosmo_wide'");
		$this->db->query("DELETE FROM themes WHERE foldername = 'cyborg_wide';");
		$this->db->query("UPDATE users SET user_stylesheet = 'cyborg' WHERE user_stylesheet = 'cyborg_wide'");
		$this->db->query("DELETE FROM themes WHERE foldername = 'darkly_wide';");
		$this->db->query("UPDATE users SET user_stylesheet = 'darkly' WHERE user_stylesheet = 'darkly_wide'");
		$this->db->query("DELETE FROM themes WHERE foldername = 'default_wide';");
		$this->db->query("UPDATE users SET user_stylesheet = 'default' WHERE user_stylesheet = 'default_wide'");
		$this->db->query("DELETE FROM themes WHERE foldername = 'superhero_wide';");
		$this->db->query("UPDATE users SET user_stylesheet = 'superhero' WHERE user_stylesheet = 'default_wide'");
    }
}
