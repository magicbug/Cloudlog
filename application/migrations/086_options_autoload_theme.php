<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
    Creates column public_slug in table station_logbooks
*/

class Migration_options_autoload_theme extends CI_Migration {

    public function up()
    {
        $this->db->set('autoload', 'yes');
        $this->db->where('option_name', "theme");
        $this->db->update('options');
    }

    public function down()
    {
        $this->db->set('autoload', 'no');
        $this->db->where('option_name', "theme");
        $this->db->update('options');
    }
}