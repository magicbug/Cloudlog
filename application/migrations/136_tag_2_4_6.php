<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
*   Tag Cloudlog as 2.4.6
*/

class Migration_tag_2_4_6 extends CI_Migration {

    public function up()
    {
    
        // Tag Cloudlog 2.4.6
        $this->db->where('option_name', 'version');
        $this->db->update('options', array('option_value' => '2.4.6'));
    }

    public function down()
    {
        $this->db->where('option_name', 'version');
        $this->db->update('options', array('option_value' => '2.4.5'));
    }
}