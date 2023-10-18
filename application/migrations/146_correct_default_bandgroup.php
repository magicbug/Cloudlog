<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 *
 * Correction of the default bandgroup of 160m, 23cm and 13cm
 * 
 */

 class Migration_correct_default_bandgroup extends CI_Migration {

    public function up()
    {
        $this->db->query("UPDATE bands SET bandgroup = 'mf' WHERE band = '160m' AND bandgroup = 'hf';");
        $this->db->query("UPDATE bands SET bandgroup = 'uhf' WHERE band = '23cm' AND bandgroup = 'shf';");
        $this->db->query("UPDATE bands SET bandgroup = 'uhf' WHERE band = '13cm' AND bandgroup = 'shf';");
    }

    public function down()
    {
        $this->db->query("UPDATE bands SET bandgroup = 'hf' WHERE band = '160m' AND bandgroup = 'mf';");
        $this->db->query("UPDATE bands SET bandgroup = 'shf' WHERE band = '23cm' AND bandgroup = 'uhf';");
        $this->db->query("UPDATE bands SET bandgroup = 'shf' WHERE band = '13cm' AND bandgroup = 'uhf';");
    }
}