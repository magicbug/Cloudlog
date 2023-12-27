<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
*   Create a dxpedition table
*/

class Migration_set_oqrs_stationname_option extends CI_Migration
{
    public function up()
    {
		$this->db->query("INSERT INTO options (option_name, option_value, autoload) SELECT DISTINCT 'groupedSearchShowStationName', 'on', NULL FROM options WHERE NOT EXISTS (SELECT 1 FROM options WHERE option_name = 'groupedSearchShowStationName');");
    }

    public function down()
    {
		$this->db->query("DELETE FROM options WHERE option_name = 'groupedSearchShowStationName';");
    }
}
