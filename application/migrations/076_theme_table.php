<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Theme_table extends CI_Migration {

	public function up()
	{
		// create themes table
		if (!$this->db->table_exists('themes')) {
			$this->db->query("create table themes (id integer not null auto_increment, name varchar(256) not null, foldername varchar(256) not null, primary key (id)) ENGINE=myisam DEFAULT CHARSET=utf8;");
			$this->db->query("INSERT INTO themes (name, foldername) values ('Blue','blue');");
			$this->db->query("INSERT INTO themes (name, foldername) values ('Cosmo','cosmo');");
			$this->db->query("INSERT INTO themes (name, foldername) values ('Cyborg (Dark)','cyborg');");
			$this->db->query("INSERT INTO themes (name, foldername) values ('Darkly (Dark)','darkly');");
			$this->db->query("INSERT INTO themes (name, foldername) values ('Default','default');");
			$this->db->query("INSERT INTO themes (name, foldername) values ('Superhero (Dark)','superhero');");
        }
	}

	public function down(){
		
	}
}
