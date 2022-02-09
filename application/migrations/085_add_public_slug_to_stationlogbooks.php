<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
    Creates column public_slug in table station_logbooks
*/

class Migration_add_public_slug_to_stationlogbooks extends CI_Migration {

    public function up()
    {
        if ($this->db->table_exists('station_logbooks')) {

            $fields = array(
                'public_slug' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '191',
                    'null' => TRUE,
                    'unique' => TRUE
                )
            );

            if (!$this->db->field_exists('public_slug', 'station_logbooks')) {
                $this->dbforge->add_column('station_logbooks', $fields);

                $this->dbforge->add_key('public_slug');
            }
            
        }
    }

    public function down()
    {
        $this->dbforge->drop_column('station_logbooks', 'public_slug');
    }
}