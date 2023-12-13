<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
*   Tag Cloudlog as 2.5.0
*/

class Migration_tag_2_5_0 extends CI_Migration {

    public function up()
    {
    
        // Tag Cloudlog 2.5.0
        $this->db->where('option_name', 'version');
        $this->db->update('options', array('option_value' => '2.5.0'));
    }

    public function down()
    {
        $this->db->where('option_name', 'version');
        $this->db->update('options', array('option_value' => '2.4.11'));
    }
}