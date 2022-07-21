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
		
		// insert new DOM-M
		$data = array(
			array('mode' => "DOMINO", 'submode' => "DOM-M", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new VARA DOM4
		$data = array(
			array('mode' => "DOMINO", 'submode' => "DOM4", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new DOM5
		$data = array(
			array('mode' => "DOMINO", 'submode' => "DOM5", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new DOM8
		$data = array(
			array('mode' => "DOMINO", 'submode' => "DOM8", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new DOM11
		$data = array(
			array('mode' => "DOMINO", 'submode' => "DOM11", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new DOM16
		$data = array(
			array('mode' => "DOMINO", 'submode' => "VARA FM 9600", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new DOM22
		$data = array(
			array('mode' => "DOMINO", 'submode' => "DOM22", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new DOM44
		$data = array(
			array('mode' => "DOMINO", 'submode' => "DOM44", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new DOM88
		$data = array(
			array('mode' => "DOMINO", 'submode' => "DOM88", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new HELLX5
		$data = array(
			array('mode' => "HELL", 'submode' => "HELLX5", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new HELLX9
		$data = array(
			array('mode' => "HELL", 'submode' => "HELLX9", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new SLOWHELL
		$data = array(
			array('mode' => "HELL", 'submode' => "SLOWHELL", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new FST4
		$data = array(
			array('mode' => "MFSK", 'submode' => "FST4", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new FST4W
		$data = array(
			array('mode' => "MFSK", 'submode' => "FST4W", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new JTMS
		$data = array(
			array('mode' => "MFSK", 'submode' => "JTMS", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new Q65
		$data = array(
			array('mode' => "MFSK", 'submode' => "Q65", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new 8PSK125
		$data = array(
			array('mode' => "PSK", 'submode' => "8PSK125", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new 8PSK125F
		$data = array(
			array('mode' => "PSK", 'submode' => "8PSK125F", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new 8PSK125FL
		$data = array(
			array('mode' => "PSK", 'submode' => "8PSK125FL", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new 8PSK250
		$data = array(
			array('mode' => "PSK", 'submode' => "8PSK250", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new 8PSK250F
		$data = array(
			array('mode' => "PSK", 'submode' => "8PSK250F", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new 8PSK250FL
		$data = array(
			array('mode' => "PSK", 'submode' => "8PSK250FL", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new 8PSK500
		$data = array(
			array('mode' => "PSK", 'submode' => "8PSK500", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new 8PSK500F
		$data = array(
			array('mode' => "PSK", 'submode' => "8PSK500F", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new 8PSK1000
		$data = array(
			array('mode' => "PSK", 'submode' => "8PSK1000", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new 8PSK1000F
		$data = array(
			array('mode' => "PSK", 'submode' => "8PSK1000F", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new 8PSK1200F
		$data = array(
			array('mode' => "PSK", 'submode' => "8PSK1200F", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new PSK63F
		$data = array(
			array('mode' => "PSK", 'submode' => "PSK63F", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new PSK63RC4
		$data = array(
			array('mode' => "PSK", 'submode' => "PSK63RC4", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new PSK63RC5
		$data = array(
			array('mode' => "PSK", 'submode' => "PSK63RC5", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new PSK63RC10
		$data = array(
			array('mode' => "PSK", 'submode' => "PSK63RC10", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new PSK63RC20
		$data = array(
			array('mode' => "PSK", 'submode' => "PSK63RC20", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new PSK63RC32
		$data = array(
			array('mode' => "PSK", 'submode' => "PSK63RC32", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new PSK125C12
		$data = array(
			array('mode' => "PSK", 'submode' => "PSK125C12", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new PSK125R
		$data = array(
			array('mode' => "PSK", 'submode' => "PSK125R", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new PSK125RC10
		$data = array(
			array('mode' => "PSK", 'submode' => "PSK125RC10", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new PSK125RC12
		$data = array(
			array('mode' => "PSK", 'submode' => "PSK125RC12", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new PSK125RC16
		$data = array(
			array('mode' => "PSK", 'submode' => "PSK125RC16", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new PSK125RC4
		$data = array(
			array('mode' => "PSK", 'submode' => "PSK125RC4", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new PSK125RC5
		$data = array(
			array('mode' => "PSK", 'submode' => "PSK125RC5", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new PSK250C6
		$data = array(
			array('mode' => "PSK", 'submode' => "PSK250C6", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new PSK250R
		$data = array(
			array('mode' => "PSK", 'submode' => "PSK250R", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new PSK250RC2
		$data = array(
			array('mode' => "PSK", 'submode' => "PSK250RC2", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new PSK250RC3
		$data = array(
			array('mode' => "PSK", 'submode' => "PSK250RC3", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new PSK250RC5
		$data = array(
			array('mode' => "PSK", 'submode' => "PSK250RC5", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new PSK250RC6
		$data = array(
			array('mode' => "PSK", 'submode' => "PSK250RC6", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new PSK250RC7
		$data = array(
			array('mode' => "PSK", 'submode' => "PSK250RC7", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new PSK500C2
		$data = array(
			array('mode' => "PSK", 'submode' => "PSK500C2", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new PSK500C4
		$data = array(
			array('mode' => "PSK", 'submode' => "PSK500C4", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new PSK500R
		$data = array(
			array('mode' => "PSK", 'submode' => "PSK500R", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new PSK500RC2
		$data = array(
			array('mode' => "PSK", 'submode' => "PSK500RC2", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new PSK500RC3
		$data = array(
			array('mode' => "PSK", 'submode' => "PSK500RC3", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new PSK500RC4
		$data = array(
			array('mode' => "PSK", 'submode' => "PSK500RC4", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new PSK800C2
		$data = array(
			array('mode' => "PSK", 'submode' => "PSK800C2", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new PSK800RC2
		$data = array(
			array('mode' => "PSK", 'submode' => "PSK800RC2", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new PSK1000C2
		$data = array(
			array('mode' => "PSK", 'submode' => "PSK1000C2", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new PSK1000R
		$data = array(
			array('mode' => "PSK", 'submode' => "PSK1000R", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new PSK1000RC2
		$data = array(
			array('mode' => "PSK", 'submode' => "PSK1000RC2", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new THOR-M
		$data = array(
			array('mode' => "THOR", 'submode' => "THOR-M", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new THOR4
		$data = array(
			array('mode' => "THOR", 'submode' => "THOR4", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new THOR5
		$data = array(
			array('mode' => "THOR", 'submode' => "THOR5", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new THOR8
		$data = array(
			array('mode' => "THOR", 'submode' => "THOR8", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new THOR11
		$data = array(
			array('mode' => "THOR", 'submode' => "THOR11", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new THOR16
		$data = array(
			array('mode' => "THOR", 'submode' => "THOR16", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new THOR22
		$data = array(
			array('mode' => "THOR", 'submode' => "THOR22", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new THOR25X4
		$data = array(
			array('mode' => "THOR", 'submode' => "THOR25X4", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new THOR50X1
		$data = array(
			array('mode' => "THOR", 'submode' => "THOR50X1", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new THOR50X2
		$data = array(
			array('mode' => "THOR", 'submode' => "THOR50X2", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new THOR100
		$data = array(
			array('mode' => "THOR", 'submode' => "THOR100", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new THRBX1
		$data = array(
			array('mode' => "THRB", 'submode' => "THRBX1", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new THRBX2
		$data = array(
			array('mode' => "THRB", 'submode' => "THRBX2", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new THRBX4
		$data = array(
			array('mode' => "THRB", 'submode' => "THRBX4", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new THROB1
		$data = array(
			array('mode' => "THRB", 'submode' => "THROB1", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new THROB2
		$data = array(
			array('mode' => "THRB", 'submode' => "THROB2", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new THROB4
		$data = array(
			array('mode' => "THRB", 'submode' => "THROB4", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new NAVTEX
		$data = array(
			array('mode' => "TOR", 'submode' => "NAVTEX", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		// insert new SITORB
		$data = array(
			array('mode' => "TOR", 'submode' => "SITORB", 'qrgmode' => "DATA", 'active' => 1),
		);
		$this->db->insert_batch('adif_modes', $data);
		
		
	}

	public function down()
	{
		// Not Possible
	}
}