<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_encrypt_eqsl_passwords extends CI_Migration
{
    public function up()
    {
        $this->load->library('encryption');
        $this->db->db_debug = false;

        // Encrypted values are larger than legacy varchar(64).
        $this->db->query('ALTER TABLE users MODIFY COLUMN user_eqsl_password TEXT DEFAULT NULL');

        if ($this->db->table_exists('eqsl_mappings')) {
            $this->db->query('ALTER TABLE eqsl_mappings MODIFY COLUMN eqsl_password TEXT NOT NULL');
        }

        $this->encrypt_existing_user_passwords();
        $this->encrypt_existing_mapping_passwords();

        $this->db->db_debug = true;
    }

    private function encrypt_existing_user_passwords()
    {
        $query = $this->db->query('SELECT user_id, user_eqsl_password FROM users WHERE COALESCE(user_eqsl_password, "") <> ""');
        foreach ($query->result() as $row) {
            $stored = (string) $row->user_eqsl_password;
            if (strpos($stored, 'enc:') === 0) {
                continue;
            }

            $encrypted = $this->encryption->encrypt($stored);
            if ($encrypted === false || $encrypted === null) {
                continue;
            }

            $this->db->where('user_id', (int) $row->user_id);
            $this->db->update('users', array('user_eqsl_password' => 'enc:' . $encrypted));
        }
    }

    private function encrypt_existing_mapping_passwords()
    {
        if (!$this->db->table_exists('eqsl_mappings')) {
            return;
        }

        $query = $this->db->query('SELECT mapping_id, eqsl_password FROM eqsl_mappings WHERE COALESCE(eqsl_password, "") <> ""');
        foreach ($query->result() as $row) {
            $stored = (string) $row->eqsl_password;
            if (strpos($stored, 'enc:') === 0) {
                continue;
            }

            $encrypted = $this->encryption->encrypt($stored);
            if ($encrypted === false || $encrypted === null) {
                continue;
            }

            $this->db->where('mapping_id', (int) $row->mapping_id);
            $this->db->update('eqsl_mappings', array('eqsl_password' => 'enc:' . $encrypted));
        }
    }

    public function down()
    {
        $this->db->db_debug = false;

        // Keep TEXT sizes on rollback to avoid accidental truncation.
        // Password values remain encrypted and backward-compatible with runtime decrypt helpers.

        $this->db->db_debug = true;
    }
}
