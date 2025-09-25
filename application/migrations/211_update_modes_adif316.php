<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Migration to add new SCAMP modes to adif_modes table
// ADIF 3.1.6 modes: SCAMP_FAST, SCAMP_SLOW, SCAMP_VSLOW, SCAMP_OO, SCAMP_OO_SLW

class Migration_update_modes_adif316 extends CI_Migration
{
	public function up()
	{

		// insert new SCAMP_FAST mode
		$data = array(
			array('mode' => "FSK", 'submode' => "SCAMP_FAST", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new SCAMP_SLOW mode
		$data = array(
			array('mode' => "FSK", 'submode' => "SCAMP_SLOW", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);

		// insert new SCAMP_VSLOW mode
		$data = array(
			array('mode' => "FSK", 'submode' => "SCAMP_VSLOW", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);

		// insert new SCAMP_OO mode
		$data = array(
			array('mode' => "MTONE", 'submode' => "SCAMP_OO", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);

		// insert new SCAMP_OO_SLW mode
		$data = array(
			array('mode' => "MTONE", 'submode' => "SCAMP_OO_SLW", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
	}

	public function down()
	{
		// remove SCAMP modes
		$this->db->where('submode', 'SCAMP_FAST');
		$this->db->delete('adif_modes');

		$this->db->where('submode', 'SCAMP_SLOW');
		$this->db->delete('adif_modes');

		$this->db->where('submode', 'SCAMP_VSLOW');
		$this->db->delete('adif_modes');

		$this->db->where('submode', 'SCAMP_OO');
		$this->db->delete('adif_modes');

		$this->db->where('submode', 'SCAMP_OO_SLW');
		$this->db->delete('adif_modes');
	}
}