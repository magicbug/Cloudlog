<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 *   Change Greencube to Oscar number
*/

class Migration_change_greencube_to_oscar extends CI_Migration {

	public function up()
	{
		$this->db->set('COL_SAT_NAME', 'IO-117');
        $this->db->set('COL_SAT_MODE', 'U');
		$this->db->where('COL_SAT_NAME', 'GREENCUBE');
		$this->db->update($this->config->item('table_name'));
	}

	public function down()
	{
		$this->db->set('COL_SAT_NAME', 'GREENCUBE');
		$this->db->where('COL_SAT_NAME', 'IO-117');
		$this->db->update($this->config->item('table_name'));
	}
}