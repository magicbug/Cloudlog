<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Migration_add_m17_and_freedv
 *
 * Add M17 and FREEDV sub modes
 * See http://adif.org.uk/314/ADIF_314_annotated.htm
 */

class Migration_add_m17_and_freedv extends CI_Migration
{
	public function up()
	{
		// insert new FREEDV
		$query = $this->db->get_where('adif_modes', array('submode' => 'FREEDV'));
		if ($query->num_rows() == 0) {
			$data = array(
				array('mode' => "DIGITALVOICE", 'submode' => "FREEDV", 'qrgmode' => "DATA", 'active' => 1),
			);
			$this->db->insert_batch('adif_modes', $data);
		}
		
		// insert new M17
		$query = $this->db->get_where('adif_modes', array('submode' => 'M17'));
		if ($query->num_rows() == 0) {
			$data = array(
				array('mode' => "DIGITALVOICE", 'submode' => "M17", 'qrgmode' => "DATA", 'active' => 1),
			);
			$this->db->insert_batch('adif_modes', $data);
		}
	}

	public function down()
	{
		$query = $this->db->get_where('adif_modes', array('submode' => 'M17'));
		if ($query->num_rows() > 0) {
			$this->db->where('mode', 'DIGITALVOICE');
			$this->db->where('submode', 'M17');
			$this->db->delete('adif_modes');
		}
		$query = $this->db->get_where('adif_modes', array('submode' => 'FREEDV'));
		if ($query->num_rows() > 0) {
			$this->db->where('mode', 'DIGITALVOICE');
			$this->db->where('submode', 'FREEDV');
			$this->db->delete('adif_modes');
		}
	}
}
