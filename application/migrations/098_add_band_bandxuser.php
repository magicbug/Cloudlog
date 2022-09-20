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
                ),
                'ssb' => array(
                    'type' => 'bigint',
                    'unsigned' => TRUE,
                    'auto_increment' => FALSE
                ),
                'data' => array(
                    'type' => 'bigint',
                    'unsigned' => TRUE,
                    'auto_increment' => FALSE
                ),
                'cw' => array(
                    'type' => 'bigint',
                    'unsigned' => TRUE,
                    'auto_increment' => FALSE
                )
            ));

            $this->dbforge->add_key('id', TRUE);

            $this->dbforge->create_table('bands');

            $this->db->query("INSERT INTO bands (band, bandgroup, ssb, data, cw) values ('160m', 'hf', '1900000', '1838000', '1830000');");
            $this->db->query("INSERT INTO bands (band, bandgroup, ssb, data, cw) values ('80m', 'hf', '3700000', '3583000', '3550000');");
            $this->db->query("INSERT INTO bands (band, bandgroup, ssb, data, cw) values ('60m', 'hf', '5330000', '5330000', '5260000');");
            $this->db->query("INSERT INTO bands (band, bandgroup, ssb, data, cw) values ('40m', 'hf', '7100000', '7040000', '7020000');");
            $this->db->query("INSERT INTO bands (band, bandgroup, ssb, data, cw) values ('30m', 'hf', '10120000', '10145000', '10120000');");
            $this->db->query("INSERT INTO bands (band, bandgroup, ssb, data, cw) values ('20m', 'hf', '14200000', '14080000', '14020000');");
            $this->db->query("INSERT INTO bands (band, bandgroup, ssb, data, cw) values ('17m', 'hf', '18130000', '18105000', '18080000');");
            $this->db->query("INSERT INTO bands (band, bandgroup, ssb, data, cw) values ('15m', 'hf', '21300000', '21080000', '21020000');");
            $this->db->query("INSERT INTO bands (band, bandgroup, ssb, data, cw) values ('12m', 'hf', '24950000', '24925000', '24900000');");
            $this->db->query("INSERT INTO bands (band, bandgroup, ssb, data, cw) values ('10m', 'hf', '28300000', '28120000', '28050000');");
            $this->db->query("INSERT INTO bands (band, bandgroup, ssb, data, cw) values ('6m', 'vhf', '50150000', '50230000', '50090000');");
            $this->db->query("INSERT INTO bands (band, bandgroup, ssb, data, cw) values ('4m', 'vhf', '70200000', '70200000', '70200000');");
            $this->db->query("INSERT INTO bands (band, bandgroup, ssb, data, cw) values ('2m', 'vhf', '144300000', '144370000', '144050000');");
            $this->db->query("INSERT INTO bands (band, bandgroup, ssb, data, cw) values ('1.25m', 'vhf', '222100000', '222100000', '222100000');");
            $this->db->query("INSERT INTO bands (band, bandgroup, ssb, data, cw) values ('70cm', 'uhf', '432200000', '432088000', '432050000');");
            $this->db->query("INSERT INTO bands (band, bandgroup, ssb, data, cw) values ('33cm', 'uhf', '902100000', '902100000', '902100000');");
            $this->db->query("INSERT INTO bands (band, bandgroup, ssb, data, cw) values ('23cm', 'shf', '1296000000', '1296138000', '129600000');");
            $this->db->query("INSERT INTO bands (band, bandgroup, ssb, data, cw) values ('13cm', 'shf', '2320800000', '2320800000', '2320800000');");
            $this->db->query("INSERT INTO bands (band, bandgroup, ssb, data, cw) values ('9cm', 'shf', '3410000000', '3410000000', '3400000000');");
            $this->db->query("INSERT INTO bands (band, bandgroup, ssb, data, cw) values ('6cm', 'shf', '5670000000', '5670000000', '5670000000');");
            $this->db->query("INSERT INTO bands (band, bandgroup, ssb, data, cw) values ('3cm', 'shf', '10225000000', '10225000000', '10225000000');");
            $this->db->query("INSERT INTO bands (band, bandgroup, ssb, data, cw) values ('1.25cm', 'shf', '24000000000', '24000000000', '240000000000');");
            $this->db->query("INSERT INTO bands (band, bandgroup, ssb, data, cw) values ('SAT', 'sat', 0, 0, 0);");
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
