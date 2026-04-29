<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Plugins_model extends CI_Model {

    public function table_exists()
    {
        return $this->db->table_exists('plugins');
    }

    public function get_all()
    {
        if (!$this->table_exists()) {
            return array();
        }

        $this->db->order_by('plugin_name', 'ASC');
        return $this->db->get('plugins')->result();
    }

    public function get_enabled()
    {
        if (!$this->table_exists()) {
            return array();
        }

        $this->db->where('plugin_status', 'enabled');
        return $this->db->get('plugins')->result();
    }

    public function get_by_slug($slug)
    {
        if (!$this->table_exists()) {
            return null;
        }

        $this->db->where('plugin_slug', $slug);
        $query = $this->db->get('plugins');

        return $query->num_rows() > 0 ? $query->row() : null;
    }

    public function upsert_plugin($slug, $manifest, $status = 'disabled')
    {
        if (!$this->table_exists()) {
            return false;
        }

        $now = date('Y-m-d H:i:s');
        $existing = $this->get_by_slug($slug);

        $data = array(
            'plugin_slug' => $slug,
            'plugin_name' => isset($manifest['name']) ? (string)$manifest['name'] : $slug,
            'plugin_version' => isset($manifest['version']) ? (string)$manifest['version'] : '1.0.0',
            'plugin_description' => isset($manifest['description']) ? (string)$manifest['description'] : null,
            'plugin_manifest' => json_encode($manifest),
            'updated_at' => $now,
        );

        if ($existing) {
            if ($existing->plugin_status === 'enabled') {
                $data['plugin_status'] = 'enabled';
            } else {
                $data['plugin_status'] = $status;
            }

            $this->db->where('plugin_slug', $slug);
            return $this->db->update('plugins', $data);
        }

        $data['plugin_status'] = $status;
        $data['installed_at'] = $now;

        return $this->db->insert('plugins', $data);
    }

    public function set_status($slug, $status)
    {
        if (!$this->table_exists()) {
            return false;
        }

        $this->db->where('plugin_slug', $slug);
        return $this->db->update('plugins', array(
            'plugin_status' => $status,
            'updated_at' => date('Y-m-d H:i:s'),
        ));
    }

    public function delete_by_slug($slug)
    {
        if (!$this->table_exists()) {
            return false;
        }

        $this->db->where('plugin_slug', $slug);
        return $this->db->delete('plugins');
    }
}
