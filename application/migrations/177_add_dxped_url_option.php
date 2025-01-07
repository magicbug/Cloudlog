<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 *  This migration adds a dxped_url-key to the options table, to configure
 *  the endpoint, from where the dxpedition-data is being loaded.
 */

class Migration_add_dxped_url_option extends CI_Migration {

    public function up()
    {
        // Check if dxped_url is already in the options table
        if ($this->db->where('option_name', 'dxped_url')->count_all_results('options') == 0) {
            // Insert dxped_url option
            $data = array(
            'option_name' => "dxped_url",
            'option_value' => "https://cdn.cloudlog.org/read_ng3k_dxped_list.php",
            'autoload' => "yes"
            );
            $this->db->insert('options', $data);
        }

    }

    public function down()
    {
        // No option to down
    }
}
