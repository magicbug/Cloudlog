<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
*   Tag Cloudlog as 2.4.11
*/

class Migration_tag_2_4_11 extends CI_Migration {

    public function up()
    {
    
        // Tag Cloudlog 2.4.11
        $this->db->where('option_name', 'version');
        $this->db->update('options', array('option_value' => '2.4.11'));
    }

    public function down()
    {
        $this->db->where('option_name', 'version');
        $this->db->update('options', array('option_value' => '2.4.10'));
    }
}