<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_alter_lotw_user_table extends CI_Migration {

	public function up() {
		$this->db->query("ALTER TABLE lotw_users ADD UNIQUE INDEX callsign_UNIQUE (callsign ASC)");
		$this->db->query("ALTER TABLE lotw_users DROP INDEX callsign");
	}

	public function down(){
		$this->db->query("ALTER TABLE lotw_users DROP INDEX callsign_UNIQUE");
		$this->db->query("ALTER TABLE lotw_users ADD INDEX callsign (callsign)");
	}
}
