<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
*   Increase COL_CREDIT_SUBMITTED field size to 255
*/

class Migration_qso_table_credit_submitted_increase extends CI_Migration {

    public function up()
    {
        $fields = array(
            'COL_CREDIT_SUBMITTED' => array(
                    'name' => 'COL_CREDIT_SUBMITTED',
                    'type' => 'VARCHAR',
                    'constraint' => '255',
            )
        );
        $this->dbforge->modify_column($this->config->item('table_name'), $fields);
    }

    public function down()
    {
        echo "Not possible, sorry.";
    }
}