<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
*   Tag Cloudlog as 2.6.9
*/

class Migration_tag_2_6_9 extends CI_Migration {

    public function up()
    {
    
        // Tag Cloudlog 2.6.3
        $this->db->where('option_name', 'version');
        $this->db->update('options', array('option_value' => '2.6.9'));

        // Trigger Version Info Dialog
        $this->db->where('option_type', 'version_dialog');
        $this->db->where('option_name', 'confirmed');
        $this->db->update('user_options', array('option_value' => 'false'));

    }

    public function down()
    {
        $this->db->where('option_name', 'version');
        $this->db->update('options', array('option_value' => '2.6.8'));
    }
}