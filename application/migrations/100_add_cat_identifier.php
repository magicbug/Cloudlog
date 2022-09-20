<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
   Creates column CAT identifier to distinguish multiple istances 
   of same CAT software
*/

class Migration_add_cat_identifier extends CI_Migration {

    public function up()
    {
        if ($this->db->table_exists('cat')) {
            if (!$this->db->field_exists('identifier', 'cat')) {
                $fields = array(
                    'identifier VARCHAR(250) NULL AFTER `radio`',
                );
                $this->dbforge->add_column('cat', $fields);
            }
        }
    }

    public function down()
    {
        $this->dbforge->drop_column('cat', 'identifier');
    }
}
