<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Migration_add_jtty_submode
 *
 * Add JTTY submode under MFSK
 */

class Migration_add_jtty_submode extends CI_Migration
{
	public function up()
	{
		$query = $this->db->get_where('adif_modes', array('submode' => 'JTTY'));
		if ($query->num_rows() == 0) {
			$data = array(
				array('mode' => "MFSK", 'submode' => "JTTY", 'qrgmode' => "DATA", 'active' => 1),
			);
			$this->db->insert_batch('adif_modes', $data);
		}
	}

	public function down()
	{
		$query = $this->db->get_where('adif_modes', array('submode' => 'JTTY'));
		if ($query->num_rows() > 0) {
			$this->db->where('mode', 'MFSK');
			$this->db->where('submode', 'JTTY');
			$this->db->delete('adif_modes');
		}
	}
}
