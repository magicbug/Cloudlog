<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 *   This adds a field to station_profile table
*/

class Migration_add_eqsldefaultmsg extends CI_Migration {

	public function up()
	{
        if (!$this->db->field_exists('eqsl_defaultqslmsg', 'station_profile')) {
            $fields = array(
                'eqsl_defaultqslmsg VARCHAR(250) DEFAULT NULL',
            );

            $this->dbforge->add_column('station_profile', $fields);
        }
	}

	public function down()
	{
		if ($this->db->field_exists('eqsl_defaultqslmsg', 'station_profile')) {
			$this->dbforge->drop_column('station_profile', 'eqsl_defaultqslmsg');
		}
	}
}
