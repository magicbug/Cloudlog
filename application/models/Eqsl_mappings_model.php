<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Eqsl_mappings_model extends CI_Model
{
    private $table_name = 'eqsl_mappings';

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

        return $this->db->get()->result_array();
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

        $this->db->select('eqsl_mappings.*, station_profile.station_callsign, station_profile.station_profile_name');
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

        return $this->db->get()->result_array();
    }

    public function create_mapping($user_id, $station_id, $eqsl_username, $eqsl_password, $eqsl_qth_nickname, $enabled, $preferred_for_download)
    {
        if (!$this->table_exists()) {
            return false;
        }

        $data = array(
            'user_id' => (int) $user_id,
            'station_id' => (int) $station_id,
            'eqsl_username' => trim($eqsl_username),
            'eqsl_password' => trim($eqsl_password),
            'eqsl_qth_nickname' => trim($eqsl_qth_nickname),
            'enabled' => (int) $enabled,
            'preferred_for_download' => (int) $preferred_for_download,
            'created_at' => date('Y-m-d H:i:s'),
        );

        $this->db->insert($this->table_name, $data);
        if (!$this->db->affected_rows()) {
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
        if (!$this->table_exists()) {
            return false;
        }

        $data = array(
            'station_id' => (int) $station_id,
            'eqsl_username' => trim($eqsl_username),
            'eqsl_password' => trim($eqsl_password),
            'eqsl_qth_nickname' => trim($eqsl_qth_nickname),
            'enabled' => (int) $enabled,
            'preferred_for_download' => (int) $preferred_for_download,
            'modified' => date('Y-m-d H:i:s'),
        );

        $this->db->where('mapping_id', (int) $mapping_id);
        $this->db->where('user_id', (int) $user_id);
        $this->db->update($this->table_name, $data);

        if ((int) $preferred_for_download === 1) {
            $this->clear_preferred_for_station($user_id, $station_id, $mapping_id);
        }

        return true;
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
        return $this->db->get()->row_array();
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

        return $this->db->get()->row_array();
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
