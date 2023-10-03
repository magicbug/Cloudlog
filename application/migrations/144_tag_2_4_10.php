<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
*   Tag Cloudlog as 2.4.10
*/

class Migration_tag_2_4_10 extends CI_Migration {

    public function up()
    {
    
        // Tag Cloudlog 2.4.10
        $this->db->where('option_name', 'version');
        $this->db->update('options', array('option_value' => '2.4.10'));
    }

    public function down()
    {
        $this->db->where('option_name', 'version');
        $this->db->update('options', array('option_value' => '2.4.9'));
    }
}