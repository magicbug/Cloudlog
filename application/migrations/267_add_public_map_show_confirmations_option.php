<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_public_map_show_confirmations_option extends CI_Migration {

    public function up()
    {
        $this->db->where('option_name', 'public_map_show_confirmations');
        $query = $this->db->get('options');

        if ($query->num_rows() == 0) {
            $data = array(
                'option_name' => 'public_map_show_confirmations',
                'option_value' => '0',
                'autoload' => 'yes'
            );
            $this->db->insert('options', $data);
        }
    }

    public function down()
    {
        $this->db->where('option_name', 'public_map_show_confirmations');
        $this->db->delete('options');
    }

}
