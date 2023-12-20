<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
*   Create a dxpedition table
*/

class Migration_convert_wide_theme_as_option extends CI_Migration
{
    public function up()
    {
      $this->db->query("DELETE FROM themes WHERE foldername = 'cosmo_wide';");
      $this->db->query("DELETE FROM themes WHERE foldername = 'cyborg_wide';");
      $this->db->query("DELETE FROM themes WHERE foldername = 'darkly_wide';");
      $this->db->query("DELETE FROM themes WHERE foldername = 'default_wide';");
      $this->db->query("DELETE FROM themes WHERE foldername = 'superhero_wide';");
      
      $this->db->query("UPDATE users SET user_stylesheet = '{\"style\":\"cosmo\", \"options\":\"0\"}' WHERE user_stylesheet = 'cosmo' ");
      $this->db->query("UPDATE users SET user_stylesheet = '{\"style\":\"cosmo\", \"options\":\"wide\"}' WHERE user_stylesheet = 'cosmo_wide' ");
      $this->db->query("UPDATE users SET user_stylesheet = '{\"style\":\"cyborg\", \"options\":\"0\"}' WHERE user_stylesheet = 'cyborg' ");
      $this->db->query("UPDATE users SET user_stylesheet = '{\"style\":\"cyborg\", \"options\":\"wide\"}' WHERE user_stylesheet = 'cyborg_wide' ");
      $this->db->query("UPDATE users SET user_stylesheet = '{\"style\":\"darkly\", \"options\":\"0\"}' WHERE user_stylesheet = 'darkly' ");
      $this->db->query("UPDATE users SET user_stylesheet = '{\"style\":\"darkly\", \"options\":\"wide\"}' WHERE user_stylesheet = 'darkly_wide' ");
      $this->db->query("UPDATE users SET user_stylesheet = '{\"style\":\"default\", \"options\":\"0\"}' WHERE user_stylesheet = 'default' ");
      $this->db->query("UPDATE users SET user_stylesheet = '{\"style\":\"default\", \"options\":\"wide\"}' WHERE user_stylesheet = 'default_wide' ");
      $this->db->query("UPDATE users SET user_stylesheet = '{\"style\":\"superhero\", \"options\":\"0\"}' WHERE user_stylesheet = 'superhero' ");
      $this->db->query("UPDATE users SET user_stylesheet = '{\"style\":\"superhero\", \"options\":\"wide\"}' WHERE user_stylesheet = 'superhero_wide'");

      $this->db->query("UPDATE options SET option_value = 'cosmo' WHERE option_value = 'cosmo_wide'");
      $this->db->query("UPDATE options SET option_value = 'cyborg' WHERE option_value = 'cyborg_wide'");
      $this->db->query("UPDATE options SET option_value = 'darkly' WHERE option_value = 'darkly_wide'");
      $this->db->query("UPDATE options SET option_value = 'default' WHERE option_value = 'default_wide'");
      $this->db->query("UPDATE options SET option_value = 'superhero' WHERE option_value = 'superhero_wide'");
    }

    public function down()
    {
      $this->db->query("INSERT INTO themes (name, foldername) SELECT DISTINCT 'Cosmo wide','cosmo_wide' FROM themes WHERE NOT EXISTS (SELECT 1 FROM themes WHERE foldername = 'cosmo_wide');");
      $this->db->query("INSERT INTO themes (name, foldername) SELECT DISTINCT 'Cyborg wide (Dark)','cyborg_wide' FROM themes WHERE NOT EXISTS (SELECT 1 FROM themes WHERE foldername = 'cyborg_wide');");
      $this->db->query("INSERT INTO themes (name, foldername) SELECT DISTINCT 'Darkly wide (Dark)','darkly_wide' FROM themes WHERE NOT EXISTS (SELECT 1 FROM themes WHERE foldername = 'darkly_wide');");
      $this->db->query("INSERT INTO themes (name, foldername) SELECT DISTINCT 'Default wide','default_wide' FROM themes WHERE NOT EXISTS (SELECT 1 FROM themes WHERE foldername = 'default_wide');");
      $this->db->query("INSERT INTO themes (name, foldername) SELECT DISTINCT 'Superhero wide (Dark)','superhero_wide' FROM themes WHERE NOT EXISTS (SELECT 1 FROM themes WHERE foldername = 'superhero_wide');");
      
      $this->db->query("UPDATE users SET user_stylesheet = 'cosmo' WHERE user_stylesheet = '{\"style\":\"cosmo\",\"options\":\"0\"}' ");
      $this->db->query("UPDATE users SET user_stylesheet = 'cosmo_wide' WHERE user_stylesheet = '{\"style\":\"cosmo\",\"options\":\"wide\"}' ");
      $this->db->query("UPDATE users SET user_stylesheet = 'cyborg' WHERE user_stylesheet = '{\"style\":\"cyborg\",\"options\":\"0\"}' ");
      $this->db->query("UPDATE users SET user_stylesheet = 'cyborg_wide' WHERE user_stylesheet = '{\"style\":\"cyborg\",\"options\":\"wide\"}' ");
      $this->db->query("UPDATE users SET user_stylesheet = 'darkly' WHERE user_stylesheet = '{\"style\":\"darkly\",\"options\":\"0\"}' ");
      $this->db->query("UPDATE users SET user_stylesheet = 'darkly_wide' WHERE user_stylesheet = '{\"style\":\"darkly\",\"options\":\"wide\"}' ");
      $this->db->query("UPDATE users SET user_stylesheet = 'default' WHERE user_stylesheet = '{\"style\":\"default\",\"options\":\"0\"}' ");
      $this->db->query("UPDATE users SET user_stylesheet = 'default_wide' WHERE user_stylesheet = '{\"style\":\"default\",\"options\":\"wide\"}' ");
      $this->db->query("UPDATE users SET user_stylesheet = 'superhero' WHERE user_stylesheet = '{\"style\":\"superhero\",\"options\":\"0\"}' ");
      $this->db->query("UPDATE users SET user_stylesheet = 'superhero_wide' WHERE user_stylesheet = '{\"style\":\"superhero\",\"options\":\"wide\"}' ");
    }
}
