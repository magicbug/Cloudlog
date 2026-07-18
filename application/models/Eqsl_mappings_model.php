<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Eqsl_mappings_model extends CI_Model
{
    private $table_name = 'eqsl_mappings';
    private $encrypted_prefix = 'enc:';
    private $last_failure_reason = '';
    private $last_db_error = array('code' => 0, 'message' => '');

    public function __construct()
    {
        $this->load->library('encryption');
    }

    public function table_exists()
    {
        return $this->db->table_exists($this->table_name);
    }

    public function get_active_mappings_for_sync()
    {
        if (!$this->table_exists()) {
            return array();
        }

        $this->db->select('eqsl_mappings.mapping_id, eqsl_mappings.user_id, eqsl_mappings.station_id, eqsl_mappings.eqsl_username, eqsl_mappings.eqsl_password, eqsl_mappings.eqsl_qth_nickname, eqsl_mappings.preferred_for_download, station_profile.station_callsign');
        $this->db->from($this->table_name);
        $this->db->join('station_profile', 'station_profile.station_id = eqsl_mappings.station_id', 'inner');
        $this->db->where('eqsl_mappings.enabled', 1);
        $this->db->where('coalesce(eqsl_mappings.eqsl_username, "") != ""');
        $this->db->where('coalesce(eqsl_mappings.eqsl_password, "") != ""');
        $this->db->where('coalesce(eqsl_mappings.eqsl_qth_nickname, "") != ""');
        $this->db->order_by('eqsl_mappings.station_id', 'asc');
        $this->db->order_by('eqsl_mappings.mapping_id', 'asc');

        $mappings = $this->db->get()->result_array();
        foreach ($mappings as &$mapping) {
            $mapping['eqsl_password'] = $this->decrypt_eqsl_password($mapping['eqsl_password']);
        }

        return $mappings;
    }

    public function has_mappings_for_user($user_id)
    {
        if (!$this->table_exists()) {
            return false;
        }

        $this->db->from($this->table_name);
        $this->db->where('user_id', (int) $user_id);
        return $this->db->count_all_results() > 0;
    }

    public function get_user_mappings($user_id)
    {
        if (!$this->table_exists()) {
            return array();
        }

        $this->db->select('eqsl_mappings.mapping_id, eqsl_mappings.user_id, eqsl_mappings.station_id, eqsl_mappings.eqsl_username, eqsl_mappings.eqsl_qth_nickname, eqsl_mappings.enabled, eqsl_mappings.preferred_for_download, eqsl_mappings.created_at, eqsl_mappings.modified, station_profile.station_callsign, station_profile.station_profile_name');
        $this->db->from($this->table_name);
        $this->db->join('station_profile', 'station_profile.station_id = eqsl_mappings.station_id', 'inner');
        $this->db->where('eqsl_mappings.user_id', (int) $user_id);
        $this->db->order_by('eqsl_mappings.station_id', 'asc');
        $this->db->order_by('eqsl_mappings.mapping_id', 'asc');

        return $this->db->get()->result_array();
    }

    public function get_active_mappings_for_user($user_id)
    {
        if (!$this->table_exists()) {
            return array();
        }

        $this->db->select('eqsl_mappings.*, station_profile.station_callsign, station_profile.station_profile_name');
        $this->db->from($this->table_name);
        $this->db->join('station_profile', 'station_profile.station_id = eqsl_mappings.station_id', 'inner');
        $this->db->where('eqsl_mappings.user_id', (int) $user_id);
        $this->db->where('eqsl_mappings.enabled', 1);
        $this->db->where('coalesce(eqsl_mappings.eqsl_username, "") != ""');
        $this->db->where('coalesce(eqsl_mappings.eqsl_password, "") != ""');
        $this->db->where('coalesce(eqsl_mappings.eqsl_qth_nickname, "") != ""');
        $this->db->order_by('eqsl_mappings.station_id', 'asc');
        $this->db->order_by('eqsl_mappings.mapping_id', 'asc');

        $mappings = $this->db->get()->result_array();
        foreach ($mappings as &$mapping) {
            $mapping['eqsl_password'] = $this->decrypt_eqsl_password($mapping['eqsl_password']);
        }

        return $mappings;
    }

    public function create_mapping($user_id, $station_id, $eqsl_username, $eqsl_password, $eqsl_qth_nickname, $enabled, $preferred_for_download)
    {
        $this->reset_last_failure();

        if (!$this->table_exists()) {
            $this->last_failure_reason = 'table_missing';
            return false;
        }

        $encrypted_password = $this->encrypt_eqsl_password($eqsl_password);
        if ($encrypted_password === null) {
            $this->last_failure_reason = 'encryption_failed';
            return false;
        }

        $data = array(
            'user_id' => (int) $user_id,
            'station_id' => (int) $station_id,
            'eqsl_username' => trim($eqsl_username),
            'eqsl_password' => $encrypted_password,
            'eqsl_qth_nickname' => trim($eqsl_qth_nickname),
            'enabled' => (int) $enabled,
            'preferred_for_download' => (int) $preferred_for_download,
            'created_at' => date('Y-m-d H:i:s'),
        );

        $inserted = $this->db->insert($this->table_name, $data);
        if ($inserted === false) {
            $this->last_failure_reason = 'insert_failed';
            $this->last_db_error = $this->db->error();
            return false;
        }

        if (!$this->db->affected_rows()) {
            $this->last_failure_reason = 'insert_no_rows';
            $this->last_db_error = $this->db->error();
            return false;
        }

        $mapping_id = (int) $this->db->insert_id();
        if ((int) $preferred_for_download === 1) {
            $this->clear_preferred_for_station($user_id, $station_id, $mapping_id);
        }

        return $mapping_id;
    }

    public function update_mapping($mapping_id, $user_id, $station_id, $eqsl_username, $eqsl_password, $eqsl_qth_nickname, $enabled, $preferred_for_download)
    {
        $this->reset_last_failure();

        if (!$this->table_exists()) {
            $this->last_failure_reason = 'table_missing';
            return false;
        }

        $encrypted_password = $this->encrypt_eqsl_password($eqsl_password);
        if ($encrypted_password === null) {
            $this->last_failure_reason = 'encryption_failed';
            return false;
        }

        $data = array(
            'station_id' => (int) $station_id,
            'eqsl_username' => trim($eqsl_username),
            'eqsl_password' => $encrypted_password,
            'eqsl_qth_nickname' => trim($eqsl_qth_nickname),
            'enabled' => (int) $enabled,
            'preferred_for_download' => (int) $preferred_for_download,
            'modified' => date('Y-m-d H:i:s'),
        );

        $this->db->where('mapping_id', (int) $mapping_id);
        $this->db->where('user_id', (int) $user_id);
        $updated = $this->db->update($this->table_name, $data);
        if ($updated === false) {
            $this->last_failure_reason = 'update_failed';
            $this->last_db_error = $this->db->error();
            return false;
        }

        if ((int) $preferred_for_download === 1) {
            $this->clear_preferred_for_station($user_id, $station_id, $mapping_id);
        }

        return true;
    }

    public function get_last_failure_reason()
    {
        return $this->last_failure_reason;
    }

    public function get_last_db_error()
    {
        return $this->last_db_error;
    }

    public function delete_mapping($mapping_id, $user_id)
    {
        if (!$this->table_exists()) {
            return false;
        }

        $this->db->where('mapping_id', (int) $mapping_id);
        $this->db->where('user_id', (int) $user_id);
        $this->db->delete($this->table_name);

        return $this->db->affected_rows() > 0;
    }

    public function get_mapping_by_id_for_user($mapping_id, $user_id)
    {
        if (!$this->table_exists()) {
            return null;
        }

        $this->db->from($this->table_name);
        $this->db->where('mapping_id', (int) $mapping_id);
        $this->db->where('user_id', (int) $user_id);
        $mapping = $this->db->get()->row_array();
        if ($mapping != null) {
            // Never expose stored password back into the form.
            $mapping['eqsl_password'] = '';
        }
        return $mapping;
    }

    public function find_duplicate_mapping($user_id, $station_id, $eqsl_username, $eqsl_qth_nickname)
    {
        if (!$this->table_exists()) {
            return null;
        }

        $this->db->from($this->table_name);
        $this->db->where('user_id', (int) $user_id);
        $this->db->where('station_id', (int) $station_id);
        $this->db->where('eqsl_username', trim($eqsl_username));
        $this->db->where('eqsl_qth_nickname', trim($eqsl_qth_nickname));
        return $this->db->get()->row_array();
    }

    public function find_duplicate_mapping_fuzzy($user_id, $station_id, $eqsl_username, $eqsl_qth_nickname)
    {
        if (!$this->table_exists()) {
            return null;
        }

        $sql = 'SELECT * FROM ' . $this->table_name
            . ' WHERE user_id = ?'
            . ' AND station_id = ?'
            . ' AND LOWER(TRIM(eqsl_username)) = LOWER(TRIM(?))'
            . ' AND LOWER(TRIM(eqsl_qth_nickname)) = LOWER(TRIM(?))'
            . ' LIMIT 1';

        $query = $this->db->query($sql, array(
            (int) $user_id,
            (int) $station_id,
            (string) $eqsl_username,
            (string) $eqsl_qth_nickname,
        ));

        return $query->row_array();
    }

    public function get_preferred_mapping_for_station($user_id, $station_id)
    {
        if (!$this->table_exists()) {
            return null;
        }

        $this->db->from($this->table_name);
        $this->db->where('user_id', (int) $user_id);
        $this->db->where('station_id', (int) $station_id);
        $this->db->where('enabled', 1);
        $this->db->where('coalesce(eqsl_password, "") != ""');
        $this->db->order_by('preferred_for_download', 'desc');
        $this->db->order_by('mapping_id', 'asc');
        $this->db->limit(1);

        $mapping = $this->db->get()->row_array();
        if ($mapping != null) {
            $mapping['eqsl_password'] = $this->decrypt_eqsl_password($mapping['eqsl_password']);
        }

        return $mapping;
    }

    public function get_password_for_user_and_username($user_id, $eqsl_username)
    {
        if (!$this->table_exists()) {
            return null;
        }

        $this->db->select('eqsl_password');
        $this->db->from($this->table_name);
        $this->db->where('user_id', (int) $user_id);
        $this->db->where('eqsl_username', trim($eqsl_username));
        $this->db->where('coalesce(eqsl_password, "") != ""');
        $this->db->order_by('mapping_id', 'desc');
        $this->db->limit(1);

        $row = $this->db->get()->row_array();
        if (!$row) {
            return null;
        }

        return $this->decrypt_eqsl_password($row['eqsl_password']);
    }

    private function encrypt_eqsl_password($password)
    {
        $password = trim((string) $password);
        if ($password === '') {
            return null;
        }

        $encrypted = $this->encryption->encrypt($password);
        if ($encrypted === false || $encrypted === null) {
            return null;
        }

        return $this->encrypted_prefix . $encrypted;
    }

    private function reset_last_failure()
    {
        $this->last_failure_reason = '';
        $this->last_db_error = array('code' => 0, 'message' => '');
    }

    private function decrypt_eqsl_password($stored_password)
    {
        $stored_password = (string) $stored_password;
        if ($stored_password === '') {
            return '';
        }

        if (strpos($stored_password, $this->encrypted_prefix) === 0) {
            $cipher = substr($stored_password, strlen($this->encrypted_prefix));
            $decrypted = $this->encryption->decrypt($cipher);
            if ($decrypted !== false && $decrypted !== null) {
                return $decrypted;
            }
            return '';
        }

        // Backward compatibility for legacy plaintext rows.
        return $stored_password;
    }

    private function clear_preferred_for_station($user_id, $station_id, $keep_mapping_id)
    {
        $this->db->set('preferred_for_download', 0);
        $this->db->where('user_id', (int) $user_id);
        $this->db->where('station_id', (int) $station_id);
        $this->db->where('mapping_id !=', (int) $keep_mapping_id);
        $this->db->update($this->table_name);
    }
}
