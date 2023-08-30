<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
*   Tag Cloudlog as 2.4.8
*/

class Migration_tag_2_4_8 extends CI_Migration {

    public function up()
    {
    
        // Tag Cloudlog 2.4.7
        $this->db->where('option_name', 'version');
        $this->db->update('options', array('option_value' => '2.4.8'));
    }

    public function down()
    {
        $this->db->where('option_name', 'version');
        $this->db->update('options', array('option_value' => '2.4.7'));
    }
}