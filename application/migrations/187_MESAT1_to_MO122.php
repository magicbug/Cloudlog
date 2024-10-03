<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
*   Change sat name from MESAT1 or MESAT-1 to MO-122
*/

class Migration_MESAT1_to_MO122 extends CI_Migration {
    
    public function up()
    {

        $this->db->set('COL_SAT_NAME', 'MESAT1');
        $this->db->where('COL_SAT_NAME', 'MO-122');
        $this->db->update($this->config->item('table_name'));

        $this->db->set('COL_SAT_NAME', 'MESAT-1');
        $this->db->where('COL_SAT_NAME', 'MO-122');
        $this->db->update($this->config->item('table_name'));
    }

    public function down()
    {
        // Not Possible
    }
    
}