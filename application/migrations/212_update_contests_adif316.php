<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Migration_update_contests_adif316 extends CI_Migration
{
	public function up()
	{
		// Add new contests from ADIF 3.1.6 specification
		$data = array(
			array('name' => 'DARC CW Trainee Contest', 'adifname' => 'DARC-CWA', 'active' => 1),
			array('name' => 'DARC 10m Contest', 'adifname' => 'DARC-10', 'active' => 1),
			array('name' => 'DARC Trainee Contest', 'adifname' => 'DARC-TRAINEE', 'active' => 1),
			array('name' => 'DARC Hell Contest', 'adifname' => 'DARC-HELL', 'active' => 1),
			array('name' => 'DARC Microwave Contest', 'adifname' => 'DARC-MICROWAVE', 'active' => 1),
			array('name' => 'DARC RTTY Short Contest', 'adifname' => 'ShortRY', 'active' => 1),
			array('name' => 'DARC UKW Spring Contest', 'adifname' => 'DARC-UKW-SPRING', 'active' => 1),
			array('name' => 'DARC UKW Field Day Contests', 'adifname' => 'DARC-UKW-FIELD-DAY', 'active' => 1),
			array('name' => 'DARC VHF-, UHF-, Microwave Contest', 'adifname' => 'DARC-VHF-UHF-MICROWAVE', 'active' => 1),
			array('name' => 'DARC Easter Contest', 'adifname' => 'EASTER', 'active' => 1),
			array('name' => 'International Naval Contest (INC)', 'adifname' => 'NAVAL', 'active' => 1),
			array('name' => 'ORARI Banggai DX Contest', 'adifname' => 'BANGGAI-DX', 'active' => 1),
			array('name' => 'ORARI Bekasi Merdeka Contest', 'adifname' => 'BEKASI-MERDEKA-CONTEST', 'active' => 1),
			array('name' => 'ORARI DX Contest', 'adifname' => 'ORARI-DX', 'active' => 1)
		);
		
		$this->db->insert_batch('contest', $data);
	}

	public function down()
	{
		// Remove the contests added in this migration
		$contest_names = array(
			'DARC-CWA',
			'DARC-10',
			'DARC-TRAINEE', 
			'DARC-HELL',
			'DARC-MICROWAVE',
			'ShortRY',
			'DARC-UKW-SPRING',
			'DARC-UKW-FIELD-DAY',
			'DARC-VHF-UHF-MICROWAVE',
			'EASTER',
			'NAVAL',
			'BANGGAI-DX',
			'BEKASI-MERDEKA-CONTEST',
			'ORARI-DX'
		);
		
		$this->db->where_in('adifname', $contest_names);
		$this->db->delete('contest');
	}
}