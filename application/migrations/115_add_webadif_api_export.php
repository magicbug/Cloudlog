<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_webadif_api_export extends CI_Migration {

    public function up()
    {
        $fields = array(
            'webadifapikey varchar(50) DEFAULT NULL',
			'webadifapiurl varchar(256) DEFAULT NULL',
			'webadifrealtime bool DEFAULT FALSE',
        );
        $this->dbforge->add_column('station_profile', $fields);

		$fields = array(
			"webadif_upload_date datetime DEFAULT NULL",
			"webadif_upload_status varchar(1) DEFAULT 'N'",
		);
		$this->dbforge->add_column($this->config->item('table_name'), $fields);

    }

    public function down()
    {
        $this->dbforge->drop_column('station_profile', 'webadifapikey');
		$this->dbforge->drop_column('station_profile', 'webadifapiurl');
		$this->dbforge->drop_column('station_profile', 'webadifrealtime');
		$this->dbforge->drop_column($this->config->item('table_name'), 'webadif_upload_date');
		$this->dbforge->drop_column($this->config->item('table_name'), 'webadif_upload_status');
    }
}
