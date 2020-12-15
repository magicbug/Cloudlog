<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
*   This migration creates a table called options which will hold global options needed within cloudlog
*   removing the need for lots of configuration files.
*/

class Migration_add_default_theme_to_options_table extends CI_Migration {

    public function up()
    {
        $data = array(
            array('option_name' => "theme", 'option_value' => "default", 'autoload' => "yes")
         );

         $this->db->insert_batch('options', $data);
    }

    public function down()
    {
        // No option to down
    }
}