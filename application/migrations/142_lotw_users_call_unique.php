<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_lotw_users_call_unique extends CI_Migration {

	public function up() {
        // Update lotw_users and make the callsign column unique
        $this->db->query("ALTER TABLE lotw_users ADD UNIQUE `callsign_index` (`callsign`);");
    }

	public function down(){
         // Update lotw_users and make the callsign column not unique
        $this->db->query("ALTER TABLE lotw_users DROP UNIQUE `callsign_index`;");
	}
}
