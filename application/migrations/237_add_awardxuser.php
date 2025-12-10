<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_awardxuser extends CI_Migration {
    public function up()
    {
        if (!$this->db->table_exists('awardxuser')) {
            $this->dbforge->add_field(array(
                'id' => array(
                    'type' => 'INT',
                    'constraint' => 20,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE,
                    'unique' => TRUE
                ),
                
                'userid' => array(
                    'type' => 'INT',
                    'constraint' => 20,
                    'unsigned' => TRUE,
                    'auto_increment' => FALSE
                ),

                'cq' => array(
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 1
                ),

                'dok' => array(
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 1
                ),

                'dxcc' => array(
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 1
                ),

                'ffma' => array(
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 1
                ),

                'iota' => array(
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 1
                ),

                'gridmaster_dl' => array(
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 1
                ),

                'gridmaster_lx' => array(
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 1
                ),

                'gridmaster_ja' => array(
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 1
                ),

                'gridmaster_us' => array(
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 1
                ),

                'gridmaster_uk' => array(
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 1
                ),

                'gmdxsummer' => array(
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 1
                ),

                'pota' => array(
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 1
                ),

                'sig' => array(
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 1
                ),

                'sota' => array(
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 1
                ),

                'uscounties' => array(
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 1
                ),

                'vucc' => array(
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 1
                ),

                'wab' => array(
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 1
                ),

                'waja' => array(
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 1
                ),

                'was' => array(
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 1
                ),

                'wwff' => array(
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 1
                ),
            ));

            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->add_key('userid');

            $this->dbforge->create_table('awardxuser');

            // Insert default records for all existing users (all awards enabled by default)
            $this->db->query("INSERT INTO awardxuser (userid, cq, dok, dxcc, ffma, iota, gridmaster_dl, gridmaster_lx, gridmaster_ja, gridmaster_us, gridmaster_uk, gmdxsummer, pota, sig, sota, uscounties, vucc, wab, waja, was, wwff) 
                              SELECT user_id, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1 FROM users;");
        }
    }

    public function down()
    {
        $this->dbforge->drop_table('awardxuser');
    }
}
