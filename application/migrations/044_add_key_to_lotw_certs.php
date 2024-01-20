<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_key_to_lotw_certs extends CI_Migration {

    public function up()
    {
        $fields = array(
            'cert_key TEXT',
        );

        if (!$this->db->field_exists('cert_key', 'lotw_certs')) {
            $this->dbforge->add_column('lotw_certs', $fields);
        }
    }

    public function down()
    {
        if ($this->db->field_exists('cert_key', 'lotw_certs')) {
            $this->dbforge->drop_column('lotw_certs', 'cert_key');
        }
    }
}
