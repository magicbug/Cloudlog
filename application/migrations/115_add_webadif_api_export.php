<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_webadif_api_export extends CI_Migration {

    public function up()
    {
		if (!$this->db->field_exists('webadifapikey', 'station_profile')) {
			$fields = array(
				'webadifapikey varchar(50) DEFAULT NULL'
			);
			$this->dbforge->add_column('station_profile', $fields);
		}
		if (!$this->db->field_exists('webadifapiurl', 'station_profile')) {
			$fields = array(
				'webadifapiurl varchar(256) DEFAULT NULL'
			);
			$this->dbforge->add_column('station_profile', $fields);
		}
		if (!$this->db->field_exists('webadifrealtime', 'station_profile')) {
			$fields = array(
				'webadifrealtime bool DEFAULT FALSE'
			);
			$this->dbforge->add_column('station_profile', $fields);
		}


		if (!$this->db->table_exists('webadif')) {
			$this->dbforge->add_field(array(
				'id' => array(
					'type' => 'INT',
					'auto_increment' => TRUE
				),
				'qso_id' => array(
					'type' => 'int',
				),
				'upload_date' => array(
					'type' => 'datetime',
				),
			));

			$this->dbforge->add_key('id', TRUE);
			$this->dbforge->add_key(array('qso_id','upload_date'), FALSE);

			$this->dbforge->create_table('webadif');
		}

	}

    public function down()
    {
        $this->dbforge->drop_column('station_profile', 'webadifapikey');
		$this->dbforge->drop_column('station_profile', 'webadifapiurl');
		$this->dbforge->drop_column('station_profile', 'webadifrealtime');
		$this->dbforge->drop_table('webadif');
    }
}
