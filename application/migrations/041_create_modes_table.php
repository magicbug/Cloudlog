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
				'qrgmode' => array(
						'type' => 'VARCHAR',
						'constraint' => 4,
				),
				'active' => array(
						'type' => 'INT',
				),
		));
		$this->dbforge->create_table('adif_modes');
	

		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('AM', 'SSB', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('AMTORFEC', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('ARDOP', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('ASCI', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('ATV', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('C4FM', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('CHIP', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('CHIP128', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('CHIP64', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('CLO', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('CONTESTI', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('CW', 'CW', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('DIGITALVOICE', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('DOMINO', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('DOMINOEX', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('DOMINOF', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('DSTAR', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('FAX', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('FM', 'SSB', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('FMHELL', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('FSK31', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('FSK441', 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('FSKHELL', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('FSQCALL', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('FT4', 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('FT8', 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('GTOR', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('HELL', 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('HELL80', 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('HFSK', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('ISCAT', 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('ISCAT-A', 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('ISCAT-B', 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('JS8', 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('JT4', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('JT44', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('JT4A', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('JT4B', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('JT4C', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('JT4D', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('JT4E', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('JT4F', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('JT4G', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('JT65', 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('JT65A', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('JT65B', 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('JT65B2', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('JT65C', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('JT65C2', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('JT6C', 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('JT6M', 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('JT9', 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('JT9-1', 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('JT9-10', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('JT9-2', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('JT9-30', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('JT9-5', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('JT9A', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('JT9B', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('JT9C', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('JT9D', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('JT9E', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('JT9E FAST', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('JT9F', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('JT9F FAST', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('JT9G', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('JT9G FAST', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('JT9H', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('JT9H FAST', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('JTMS', 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('JTMSK', 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('LSB', 'SSB', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('MFSK', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('MFSK11', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('MFSK128', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('MFSK16', 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('MFSK22', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('MFSK31', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('MFSK32', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('MFSK4', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('MFSK64', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('MFSK8', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('MSK144', 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('MT63', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('OLIVIA', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('OLIVIA 16/1000', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('OLIVIA 16/500', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('OLIVIA 32/1000', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('OLIVIA 4/125', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('OLIVIA 4/250', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('OLIVIA 8/250', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('OLIVIA 8/500', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('OPERA', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('OPERA-BEACON', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('OPERA-QSO', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('PAC', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('PAC2', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('PAC3', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('PAC4', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('PAX', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('PAX2', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('PCW', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('PKT', 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('PSK', 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('PSK10', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('PSK1000', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('PSK125', 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('PSK250', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('PSK2K', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('PSK31', 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('PSK500', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('PSK63', 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('PSK63F', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('PSKAM10', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('PSKAM31', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('PSKAM50', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('PSKFEC31', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('PSKHELL', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('Q15', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('QPSK125', 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('QPSK250', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('QPSK31', 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('QPSK500', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('QPSK63', 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('QRA64', 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('QRA64A', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('QRA64B', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('QRA64C', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('QRA64D', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('QRA64E', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('ROS', 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('ROS-EME', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('ROS-HF', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('ROS-MF', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('RTTY', 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('RTTYM', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('SIM31', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('SSB', 'SSB', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('SSTV', 'DATA', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('T10', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('THOR', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('THRB', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('THRBX', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('TOR', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('USB', 'SSB', 1);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('V4', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('VOI', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('WINMOR', 'DATA', 0);");
		$this->db->query("INSERT INTO `adif_modes` (`mode`, `qrgmode`, `active`) VALUES ('WSPR', 'DATA', 0);");
	}

	public function down(){
		$this->dbforge->drop_table('config');
	}
}
