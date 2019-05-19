<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_extenddxccprefix extends CI_Migration {

	public function up()
	{
		$this->db->query("ALTER TABLE dxcc CHANGE COLUMN `prefix` `prefix` varchar(32) NOT NULL; # was varchar(10) NOT NULL");
		$this->db->query("ALTER TABLE dxcc_entities CHANGE COLUMN `prefix` `prefix` varchar(32) NOT NULL; # was varchar(10) NOT NULL");
		$this->db->query("ALTER TABLE dxcc_exceptions CHANGE COLUMN `call` `call` varchar(32) NOT NULL; # was varchar(10) NOT NULL");
		$this->db->query("ALTER TABLE dxcc_prefixes CHANGE COLUMN `call` `call` varchar(32) NOT NULL; # was varchar(10) NOT NULL");
	}

	public function down(){
                $this->db->query("ALTER TABLE dxcc CHANGE COLUMN `prefix` `prefix` varchar(10) NOT NULL; # was varchar(10) NOT NULL");
                $this->db->query("ALTER TABLE dxcc_entities CHANGE COLUMN `prefix` `prefix` varchar(10) NOT NULL; # was varchar(10) NOT NULL");
                $this->db->query("ALTER TABLE dxcc_exceptions CHANGE COLUMN `call` `call` varchar(10) NOT NULL; # was varchar(10) NOT NULL");
                $this->db->query("ALTER TABLE dxcc_prefixes CHANGE COLUMN `call` `call` varchar(10) NOT NULL; # was varchar(10) NOT NULL");
	}
}
