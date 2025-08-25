<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
 *   This adds an option to enable/disable Radio Status panel on public logbook displays
 */

class Migration_add_radio_panel_option_to_logbook extends CI_Migration
{

    public function up()
    {
        if (!$this->db->field_exists('public_radio_status', 'station_logbooks')) {
            $fields = array(
                'public_radio_status integer DEFAULT 0 AFTER public_search',
            );

            $this->dbforge->add_column('station_logbooks', $fields);
        }
    }

    public function down()
    {
        if ($this->db->field_exists('public_radio_status', 'station_logbooks')) {
            $this->dbforge->drop_column('station_logbooks', 'public_radio_status');
        }
    }
}
