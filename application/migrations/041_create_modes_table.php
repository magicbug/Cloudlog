<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_modes_table extends CI_Migration {

	public function up() {
		$this->dbforge->add_field('id');

		$this->dbforge->add_field(array(
				'mode' => array(
						'type' => 'VARCHAR',
						'constraint' => 12,
				),
				'submode' => array(
						'type' => 'VARCHAR',
						'constraint' => 12,
						'null' => TRUE,
				),
				'qrgmode' => array(
						'type' => 'VARCHAR',
						'constraint' => 4,
				),
				'active' => array(
						'type' => 'INT',
				),
		));
		$this->dbforge->create_table('adif_modes');
	

		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('AM', NULL, 'SSB', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('ARDOP', NULL, 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('ATV', NULL, 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('C4FM', NULL, 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('CHIP', NULL, 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('CHIP', 'CHIP128', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('CHIP', 'CHIP64', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('CLO', NULL, 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('CONTESTI', NULL, 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('CW', NULL, 'CW', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('CW', 'PCW', 'CW', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('DIGITALVOICE', NULL, 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('DOMINO', NULL, 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('DOMINO', 'DOMINOEX', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('DOMINO', 'DOMINOF', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('DSTAR', NULL, 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('FAX', NULL, 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('FM', NULL, 'SSB', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('FSK441', NULL, 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('FT8', NULL, 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('HELL', NULL, 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('HELL', 'FMHELL', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('HELL', 'FSKHELL', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('HELL', 'HELL80', 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('HELL', 'HFSK', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('HELL', 'PSKHELL', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('ISCAT', NULL, 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('ISCAT', 'ISCAT-A', 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('ISCAT', 'ISCAT-B', 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('JT4', NULL, 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('JT4', 'JT4A', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('JT4', 'JT4B', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('JT4', 'JT4C', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('JT4', 'JT4D', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('JT4', 'JT4E', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('JT4', 'JT4F', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('JT4', 'JT4G', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('JT44', NULL, 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('JT65', NULL, 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('JT65', 'JT65A', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('JT65', 'JT65B', 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('JT65', 'JT65B2', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('JT65', 'JT65C', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('JT65', 'JT65C2', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('JT6C', NULL, 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('JT6M', NULL, 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('JT9', NULL, 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('JT9', 'JT9-1', 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('JT9', 'JT9-10', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('JT9', 'JT9-2', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('JT9', 'JT9-30', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('JT9', 'JT9-5', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('JT9', 'JT9A', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('JT9', 'JT9B', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('JT9', 'JT9C', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('JT9', 'JT9D', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('JT9', 'JT9E', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('JT9', 'JT9E FAST', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('JT9', 'JT9F', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('JT9', 'JT9F FAST', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('JT9', 'JT9G', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('JT9', 'JT9G FAST', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('JT9', 'JT9H', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('JT9', 'JT9H FAST', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('JTMS', NULL, 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('JTMSK', NULL, 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('MFSK', NULL, 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('MFSK', 'FSQCALL', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('MFSK', 'FT4', 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('MFSK', 'JS8', 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('MFSK', 'MFSK11', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('MFSK', 'MFSK128', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('MFSK', 'MFSK16', 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('MFSK', 'MFSK22', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('MFSK', 'MFSK31', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('MFSK', 'MFSK32', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('MFSK', 'MFSK4', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('MFSK', 'MFSK64', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('MFSK', 'MFSK8', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('MSK144', NULL, 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('MT63', NULL, 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('OLIVIA', NULL, 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('OLIVIA', 'OLIVIA 16/10', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('OLIVIA', 'OLIVIA 16/50', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('OLIVIA', 'OLIVIA 32/10', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('OLIVIA', 'OLIVIA 4/125', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('OLIVIA', 'OLIVIA 4/250', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('OLIVIA', 'OLIVIA 8/250', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('OLIVIA', 'OLIVIA 8/500', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('OPERA', NULL, 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('OPERA', 'OPERA-BEACON', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('OPERA', 'OPERA-QSO', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('PAC', NULL, 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('PAC', 'PAC2', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('PAC', 'PAC3', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('PAC', 'PAC4', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('PAX', NULL, 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('PAX', 'PAX2', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('PKT', NULL, 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('PSK', NULL, 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('PSK', 'FSK31', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('PSK', 'PSK10', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('PSK', 'PSK1000', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('PSK', 'PSK125', 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('PSK', 'PSK250', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('PSK', 'PSK31', 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('PSK', 'PSK500', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('PSK', 'PSK63', 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('PSK', 'PSK63F', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('PSK', 'PSKAM10', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('PSK', 'PSKAM31', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('PSK', 'PSKAM50', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('PSK', 'PSKFEC31', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('PSK', 'QPSK125', 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('PSK', 'QPSK250', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('PSK', 'QPSK31', 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('PSK', 'QPSK500', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('PSK', 'QPSK63', 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('PSK', 'SIM31', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('PSK2K', NULL, 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('Q15', NULL, 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('QRA64', NULL, 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('QRA64', 'QRA64A', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('QRA64', 'QRA64B', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('QRA64', 'QRA64C', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('QRA64', 'QRA64D', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('QRA64', 'QRA64E', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('ROS', NULL, 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('ROS', 'ROS-EME', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('ROS', 'ROS-HF', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('ROS', 'ROS-MF', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('RTTY', NULL, 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('RTTY', 'ASCI', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('RTTYM', NULL, 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('SSB', NULL, 'SSB', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('SSB', 'LSB', 'SSB', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('SSB', 'USB', 'SSB', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('SSTV', NULL, 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('T10', NULL, 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('THOR', NULL, 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('THRB', NULL, 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('THRB', 'THRBX', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('TOR', NULL, 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('TOR', 'AMTORFEC', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('TOR', 'GTOR', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('V4', NULL, 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('VOI', NULL, 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('WINMOR', NULL, 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `submode`, `qrgmode`, `active`) VALUES('WSPR', NULL, 'DATA', 0);");
	}

	public function down(){
		$this->dbforge->drop_table('config');
	}
}
