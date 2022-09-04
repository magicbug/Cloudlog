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
                )
            ));

            $this->dbforge->add_key('id', TRUE);

            $this->dbforge->create_table('bands');

            $this->db->query("INSERT INTO bands (band) values ('160m');");
            $this->db->query("INSERT INTO bands (band) values ('80m');");
            $this->db->query("INSERT INTO bands (band) values ('60m');");
            $this->db->query("INSERT INTO bands (band) values ('40m');");
            $this->db->query("INSERT INTO bands (band) values ('300m');");
            $this->db->query("INSERT INTO bands (band) values ('20m');");
            $this->db->query("INSERT INTO bands (band) values ('170m');");
            $this->db->query("INSERT INTO bands (band) values ('15m');");
            $this->db->query("INSERT INTO bands (band) values ('12m');");
            $this->db->query("INSERT INTO bands (band) values ('10m');");
            $this->db->query("INSERT INTO bands (band) values ('6m');");
            $this->db->query("INSERT INTO bands (band) values ('4m');");
            $this->db->query("INSERT INTO bands (band) values ('2m');");
            $this->db->query("INSERT INTO bands (band) values ('1.25m');");
            $this->db->query("INSERT INTO bands (band) values ('70cm');");
            $this->db->query("INSERT INTO bands (band) values ('33cm');");
            $this->db->query("INSERT INTO bands (band) values ('23cm');");
            $this->db->query("INSERT INTO bands (band) values ('13cm');");
            $this->db->query("INSERT INTO bands (band) values ('9cm');");
            $this->db->query("INSERT INTO bands (band) values ('6cm');");
            $this->db->query("INSERT INTO bands (band) values ('3cm');");
            $this->db->query("INSERT INTO bands (band) values ('1.25cm');");
            $this->db->query("INSERT INTO bands (band) values ('SAT');");
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
