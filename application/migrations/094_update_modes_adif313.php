<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_update_modes_adif313 extends CI_Migration
{
	public function up()
	{
		// deactivate C4FM => Import only
		$this->db->set('active', 0);
		$this->db->where('mode', 'C4FM');
		$this->db->update('adif_modes');
		
		// deactivate DSTAR => Import only
		$this->db->set('active', 0);
		$this->db->where('mode', 'DSTAR');
		$this->db->update('adif_modes');
		
		// insert new C4FM
		$data = array(
			array('mode' => "DIGITALVOICE", 'submode' => "C4FM", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new DMR
		$data = array(
			array('mode' => "DIGITALVOICE", 'submode' => "DMR", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new DSTAR
		$data = array(
			array('mode' => "DIGITALVOICE", 'submode' => "DSTAR", 'qrgmode' => "DATA", 'active' => 1),
		);
		
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new DYNAMIC
		$data = array(
			array('mode' => "DYNAMIC", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new VARA HF
		$data = array(
			array('mode' => "DYNAMIC", 'submode' => "VARA HF", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new VARA SATELLITE
		$data = array(
			array('mode' => "DYNAMIC", 'submode' => "VARA SATELLITE", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new VARA FM 1200
		$data = array(
			array('mode' => "DYNAMIC", 'submode' => "VARA FM 1200", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new VARA FM 9600
		$data = array(
			array('mode' => "DYNAMIC", 'submode' => "VARA FM 9600", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
	}

	public function down()
	{
		// Not Possible
	}
}