<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_migration extends CI_Migration {

	public function up()
	{
		$this->db->db_debug = false;

		$this->db->query("ALTER TABLE cat CHANGE COLUMN frequency frequency bigint(13) NOT NULL; # was int(11) NOT NULL");
		$this->db->query("ALTER TABLE cat CHANGE COLUMN uplink_freq uplink_freq bigint(13) DEFAULT NULL; # was int(11) NOT NULL");
		$this->db->query("ALTER TABLE cat CHANGE COLUMN downlink_freq downlink_freq bigint(13) DEFAULT NULL; # was int(11) NOT NULL");
		$this->db->query("ALTER TABLE cat CHANGE COLUMN downlink_mode downlink_mode varchar(255) DEFAULT NULL; # was varchar(255) NOT NULL");
		$this->db->query("ALTER TABLE cat CHANGE COLUMN sat_name sat_name varchar(255) DEFAULT NULL; # was varchar(255) NOT NULL");
		$this->db->query("ALTER TABLE cat CHANGE COLUMN uplink_mode uplink_mode varchar(255) DEFAULT NULL; # was varchar(255) NOT NULL");
		$this->db->query("ALTER TABLE cat ENGINE=InnoDB DEFAULT CHARSET=utf8mb4; # was ENGINE=InnoDB DEFAULT CHARSET=latin1");
		$this->db->query("ALTER TABLE config ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4; # was ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8");
		$this->db->query("ALTER TABLE dxcc ENGINE=MyISAM DEFAULT CHARSET=utf8mb4; # was ENGINE=MyISAM DEFAULT CHARSET=utf8");
		$this->db->query("ALTER TABLE dxccexceptions ENGINE=InnoDB AUTO_INCREMENT=166 DEFAULT CHARSET=utf8mb4; # was ENGINE=InnoDB AUTO_INCREMENT=166 DEFAULT CHARSET=utf8");
		$this->db->query("ALTER TABLE notes ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4; # was ENGINE=InnoDB DEFAULT CHARSET=latin1");

		$this->db->query( "ALTER TABLE `station_profile` MODIFY COLUMN `station_id`  int(11) NOT NULL AUTO_INCREMENT FIRST , ADD PRIMARY KEY (`station_id`); # was int(11) NOT NULL" );


		$this->db->query("ALTER TABLE station_profile ENGINE=InnoDB DEFAULT CHARSET=utf8mb4; # was ENGINE=InnoDB DEFAULT CHARSET=latin1");
		$this->db->query("ALTER TABLE timezones CHANGE COLUMN name name varchar(120) COLLATE utf8mb4_bin NOT NULL; # was varchar(120) COLLATE utf8_bin NOT NULL");
		$this->db->query("ALTER TABLE timezones ENGINE=MyISAM AUTO_INCREMENT=151 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin; # was ENGINE=MyISAM AUTO_INCREMENT=151 DEFAULT CHARSET=utf8 COLLATE=utf8_bin");
		$this->db->query("ALTER TABLE users CHANGE COLUMN user_callsign user_callsign varchar(32) NOT NULL COMMENT 'User''s callsign'; # was varchar(255) NOT NULL");
		$this->db->query("ALTER TABLE users CHANGE COLUMN user_firstname user_firstname varchar(32) NOT NULL COMMENT 'User''s first name'; # was varchar(255) NOT NULL");
		$this->db->query("ALTER TABLE users CHANGE COLUMN user_locator user_locator varchar(16) NOT NULL COMMENT 'User''s locator'; # was varchar(255) NOT NULL");
		$this->db->query("ALTER TABLE users CHANGE COLUMN user_timezone user_timezone int(3) NOT NULL DEFAULT 0; # was char(255) NOT NULL");
		$this->db->query("ALTER TABLE users CHANGE COLUMN user_lastname user_lastname varchar(32) NOT NULL COMMENT 'User''s last name'; # was varchar(255) NOT NULL");

		$this->db->query("ALTER TABLE users ADD COLUMN user_eqsl_qth_nickname varchar(32) DEFAULT NULL;");

		$this->db->query("ALTER TABLE users ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4; # was ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1");
		$this->db->query("CREATE TABLE IF NOT EXISTS contest_template (
		  id int(11) NOT NULL AUTO_INCREMENT,
		  name varchar(255) NOT NULL,
		  band_160 varchar(20) NOT NULL,
		  band_80 varchar(20) NOT NULL,
		  band_40 varchar(20) NOT NULL,
		  band_20 varchar(20) NOT NULL,
		  band_15 varchar(20) NOT NULL,
		  band_10 varchar(20) NOT NULL,
		  band_6m varchar(20) NOT NULL,
		  band_4m varchar(20) NOT NULL,
		  band_2m varchar(20) NOT NULL,
		  band_70cm varchar(20) NOT NULL,
		  band_23cm varchar(20) NOT NULL,
		  mode_ssb varchar(20) NOT NULL,
		  mode_cw varchar(20) NOT NULL,
		  serial varchar(20) NOT NULL,
		  point_per_km int(20) NOT NULL,
		  qra varchar(20) NOT NULL,
		  other_exch varchar(255) NOT NULL,
		  scoring varchar(255) NOT NULL,
		  PRIMARY KEY (id),
		  KEY name (name)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

			$this->db->query("CREATE TABLE IF NOT EXISTS contests (
		  id int(11) NOT NULL AUTO_INCREMENT,
		  name varchar(255) NOT NULL,
		  start datetime NOT NULL,
		  end datetime NOT NULL,
		  template int(11) NOT NULL,
		  serial_num tinyint(11) NOT NULL,
		  PRIMARY KEY (id)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

		$this->db->query("ALTER TABLE ".$this->config->item('table_name')." CHANGE COLUMN COL_FREQ_RX COL_FREQ_RX bigint(13) DEFAULT NULL; # was int(11) DEFAULT NULL");
		$this->db->query("ALTER TABLE ".$this->config->item('table_name')." CHANGE COLUMN COL_FREQ COL_FREQ bigint(13) DEFAULT NULL; # was int(11) DEFAULT NULL");
		$this->db->query("ALTER TABLE ".$this->config->item('table_name')." ENGINE=MyISAM DEFAULT CHARSET=utf8mb4; # was ENGINE=MyISAM DEFAULT CHARSET=latin1");

		$this->db->db_debug = true;
	}

	public function down(){
		echo "Not possible, sorry.";
	}
}
