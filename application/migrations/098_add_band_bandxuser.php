<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_band_bandxuser extends CI_Migration {
    public function up()
    {
        if (!$this->db->table_exists('bands')) {
            $this->dbforge->add_field(array(
                'id' => array(
                    'type' => 'INT',
                    'constraint' => 20,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE,
                    'unique' => TRUE
                ),

                'band' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 20,
                    'unsigned' => TRUE,
                    'auto_increment' => FALSE
                ),
                'bandgroup' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 20,
                    'unsigned' => TRUE,
                    'auto_increment' => FALSE
                )
            ));

            $this->dbforge->add_key('id', TRUE);

            $this->dbforge->create_table('bands');

            $this->db->query("INSERT INTO bands (band, bandgroup) values ('160m', 'hf');");
            $this->db->query("INSERT INTO bands (band, bandgroup) values ('80m', 'hf');");
            $this->db->query("INSERT INTO bands (band, bandgroup) values ('60m', 'hf');");
            $this->db->query("INSERT INTO bands (band, bandgroup) values ('40m', 'hf');");
            $this->db->query("INSERT INTO bands (band, bandgroup) values ('30m', 'hf');");
            $this->db->query("INSERT INTO bands (band, bandgroup) values ('20m', 'hf');");
            $this->db->query("INSERT INTO bands (band, bandgroup) values ('17m', 'hf');");
            $this->db->query("INSERT INTO bands (band, bandgroup) values ('15m', 'hf');");
            $this->db->query("INSERT INTO bands (band, bandgroup) values ('12m', 'hf');");
            $this->db->query("INSERT INTO bands (band, bandgroup) values ('10m', 'hf');");
            $this->db->query("INSERT INTO bands (band, bandgroup) values ('6m', 'vhf');");
            $this->db->query("INSERT INTO bands (band, bandgroup) values ('4m', 'vhf');");
            $this->db->query("INSERT INTO bands (band, bandgroup) values ('2m', 'vhf');");
            $this->db->query("INSERT INTO bands (band, bandgroup) values ('1.25m', 'vhf');");
            $this->db->query("INSERT INTO bands (band, bandgroup) values ('70cm', 'uhf');");
            $this->db->query("INSERT INTO bands (band, bandgroup) values ('33cm', 'uhf');");
            $this->db->query("INSERT INTO bands (band, bandgroup) values ('23cm', 'shf');");
            $this->db->query("INSERT INTO bands (band, bandgroup) values ('13cm', 'shf');");
            $this->db->query("INSERT INTO bands (band, bandgroup) values ('9cm', 'shf');");
            $this->db->query("INSERT INTO bands (band, bandgroup) values ('6cm', 'shf');");
            $this->db->query("INSERT INTO bands (band, bandgroup) values ('3cm', 'shf');");
            $this->db->query("INSERT INTO bands (band, bandgroup) values ('1.25cm', 'shf');");
            $this->db->query("INSERT INTO bands (band, bandgroup) values ('SAT', 'sat');");
        }

        if (!$this->db->table_exists('bandxuser')) {
            $this->dbforge->add_field(array(
                'id' => array(
                    'type' => 'INT',
                    'constraint' => 20,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE,
                    'unique' => TRUE
                ),

                'bandid' => array(
                    'type' => 'INT',
                    'constraint' => 20,
                    'unsigned' => TRUE,
                    'auto_increment' => FALSE
                ),
                
                'userid' => array(
                    'type' => 'INT',
                    'constraint' => 20,
                    'unsigned' => TRUE,
                    'auto_increment' => FALSE
                ),

                'active' => array(
                    'type' => 'INT',
                    'constraint' => 20,
                    'unsigned' => TRUE,
                    'auto_increment' => FALSE
                ),
                
                'cq' => array(
                    'type' => 'INT',
                    'constraint' => 20,
                    'unsigned' => TRUE,
                    'auto_increment' => FALSE
                ),

                'dok' => array(
                    'type' => 'INT',
                    'constraint' => 20,
                    'unsigned' => TRUE,
                    'auto_increment' => FALSE
                ),

                'dxcc' => array(
                    'type' => 'INT',
                    'constraint' => 20,
                    'unsigned' => TRUE,
                    'auto_increment' => FALSE
                ),

                'iota' => array(
                    'type' => 'INT',
                    'constraint' => 20,
                    'unsigned' => TRUE,
                    'auto_increment' => FALSE
                ),

                'sig' => array(
                    'type' => 'INT',
                    'constraint' => 20,
                    'unsigned' => TRUE,
                    'auto_increment' => FALSE
                ),

                'sota' => array(
                    'type' => 'INT',
                    'constraint' => 20,
                    'unsigned' => TRUE,
                    'auto_increment' => FALSE
                ),

                'uscounties' => array(
                    'type' => 'INT',
                    'constraint' => 20,
                    'unsigned' => TRUE,
                    'auto_increment' => FALSE
                ),

                'was' => array(
                    'type' => 'INT',
                    'constraint' => 20,
                    'unsigned' => TRUE,
                    'auto_increment' => FALSE
                ),

                'vucc' => array(
                    'type' => 'INT',
                    'constraint' => 20,
                    'unsigned' => TRUE,
                    'auto_increment' => FALSE
                ),
            ));

            $this->dbforge->add_key('id', TRUE);

            $this->dbforge->create_table('bandxuser');

            $this->db->query("insert into bandxuser (bandid, userid, active, cq, dok, dxcc, iota, sig, sota, uscounties, was, vucc) select bands.id, users.user_id, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1 from bands join users on 1 = 1;");
        }
    }

    public function down()
    {
        $this->dbforge->drop_table('bandxuser');
        $this->dbforge->drop_table('bands');
    }
}
