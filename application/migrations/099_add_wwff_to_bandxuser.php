<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_wwff_to_bandxuser extends CI_Migration {
    public function up()
    {
        if (!$this->db->field_exists('wwff', 'bandxuser')) {
            $fields = array(
                    'wwff' => array(
                    'type' => 'INT',
                    'constraint' => 20,
                    'unsigned' => TRUE,
                ),
            );
            $this->dbforge->add_column('bandxuser', $fields);

            $this->db->query("update bandxuser set wwff = 1");
        }
    }

    public function down()
    {
        $this->dbforge->drop_column('bandxuser', 'wwff');
    }
}
