<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Plugin_manager {

    private $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('plugins_model');
    }

    public function list_plugins()
    {
        $plugins = $this->CI->plugins_model->get_all();
        $result = array();

        foreach ($plugins as $plugin) {
            $manifest = json_decode((string)$plugin->plugin_manifest, true);
            if (!is_array($manifest)) {
                $manifest = array();
            }

            $plugin_dir = $this->get_plugin_root() . $plugin->plugin_slug . DIRECTORY_SEPARATOR;
            $manifest_path = $plugin_dir . 'manifest.json';

            if (is_file($manifest_path)) {
                $disk_manifest = $this->read_manifest($manifest_path);
                if ($disk_manifest) {
                    $manifest = $disk_manifest;
                }
            }

            $result[] = (object)array(
                'plugin_slug' => $plugin->plugin_slug,
                'plugin_name' => isset($manifest['name']) ? $manifest['name'] : $plugin->plugin_name,
                'plugin_version' => isset($manifest['version']) ? $manifest['version'] : $plugin->plugin_version,
                'plugin_description' => isset($manifest['description']) ? $manifest['description'] : $plugin->plugin_description,
                'plugin_status' => $plugin->plugin_status,
                'installed_at' => $plugin->installed_at,
                'updated_at' => $plugin->updated_at,
                'path_exists' => is_dir($plugin_dir),
            );
        }

        return $result;
    }

    public function install_from_zip($zip_file)
    {
        if (!$this->CI->plugins_model->table_exists()) {
            return array('ok' => false, 'message' => 'Plugins table does not exist. Run migrations first.');
        }

        if (!class_exists('ZipArchive')) {
            return array('ok' => false, 'message' => 'ZipArchive extension is not available on this server.');
        }

        $this->ensure_plugin_root();

        $zip = new ZipArchive();
        $open_result = $zip->open($zip_file);
        if ($open_result !== true) {
            return array('ok' => false, 'message' => 'Could not open plugin zip file.');
        }

        $invalid_path = $this->find_invalid_archive_path($zip);
        if ($invalid_path !== null) {
            $zip->close();
            return array('ok' => false, 'message' => 'Plugin archive contains an invalid path: ' . $invalid_path);
        }

        $temp_base = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'plugins_tmp' . DIRECTORY_SEPARATOR;
        if (!is_dir($temp_base) && !@mkdir($temp_base, 0755, true)) {
            $zip->close();
            return array('ok' => false, 'message' => 'Unable to create plugin temp directory.');
        }

        $extract_dir = $temp_base . uniqid('plugin_', true) . DIRECTORY_SEPARATOR;
        if (!@mkdir($extract_dir, 0755, true)) {
            $zip->close();
            return array('ok' => false, 'message' => 'Unable to create plugin extraction directory.');
        }

        if (!$zip->extractTo($extract_dir)) {
            $zip->close();
            $this->recursive_delete($extract_dir);
            return array('ok' => false, 'message' => 'Failed to extract plugin archive.');
        }
        $zip->close();

        $plugin_root = $this->resolve_extracted_plugin_root($extract_dir);
        if ($plugin_root === null) {
            $this->recursive_delete($extract_dir);
            return array('ok' => false, 'message' => 'manifest.json was not found in the uploaded plugin.');
        }

        $manifest_path = $plugin_root . 'manifest.json';
        $manifest = $this->read_manifest($manifest_path);
        if (!$manifest) {
            $this->recursive_delete($extract_dir);
            return array('ok' => false, 'message' => 'manifest.json is invalid JSON.');
        }

        $award_menu_warning = $this->validate_award_menu_definition($plugin_root, $manifest);

        $slug = $this->resolve_slug($manifest);
        if ($slug === null) {
            $this->recursive_delete($extract_dir);
            return array('ok' => false, 'message' => 'manifest.json must include a valid slug (letters, numbers, _ or -).');
        }

        $dest_dir = $this->get_plugin_root() . $slug . DIRECTORY_SEPARATOR;

        $is_upgrade = false;
        $previous_version = null;
        $backup_dir = null;

        if (is_dir($dest_dir)) {
            $is_upgrade = true;

            $existing_manifest_path = $dest_dir . 'manifest.json';
            if (is_file($existing_manifest_path)) {
                $existing_manifest = $this->read_manifest($existing_manifest_path);
                if (is_array($existing_manifest) && isset($existing_manifest['version'])) {
                    $previous_version = (string)$existing_manifest['version'];
                }
            }

            $backup_base = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'plugins_backup' . DIRECTORY_SEPARATOR;
            if (!is_dir($backup_base) && !@mkdir($backup_base, 0755, true)) {
                $this->recursive_delete($extract_dir);
                return array('ok' => false, 'message' => 'Unable to create plugin backup directory.');
            }

            $backup_dir = $backup_base . $slug . '_' . date('Ymd_His') . '_' . substr(sha1(uniqid('', true)), 0, 8) . DIRECTORY_SEPARATOR;
            if (!@rename($dest_dir, $backup_dir)) {
                $this->recursive_delete($extract_dir);
                return array('ok' => false, 'message' => 'Unable to backup existing plugin before upgrade.');
            }
        }

        if (!$this->recursive_copy($plugin_root, $dest_dir)) {
            $this->recursive_delete($extract_dir);
            if ($is_upgrade && $backup_dir !== null && is_dir($backup_dir)) {
                @rename($backup_dir, $dest_dir);
            }
            return array('ok' => false, 'message' => 'Failed to copy plugin files into application/plugins.');
        }

        $this->recursive_delete($extract_dir);

        if (!$this->CI->plugins_model->upsert_plugin($slug, $manifest, 'disabled')) {
            $this->recursive_delete($dest_dir);
            if ($is_upgrade && $backup_dir !== null && is_dir($backup_dir)) {
                @rename($backup_dir, $dest_dir);
            }
            return array('ok' => false, 'message' => 'Failed to persist plugin metadata.');
        }

        if ($is_upgrade && $backup_dir !== null && is_dir($backup_dir)) {
            $this->recursive_delete($backup_dir);
        }

        $plugin_name = isset($manifest['name']) ? $manifest['name'] : $slug;
        $new_version = isset($manifest['version']) ? (string)$manifest['version'] : null;

        if ($is_upgrade) {
            if ($previous_version !== null && $new_version !== null) {
                $message = 'Plugin upgraded: ' . $plugin_name . ' (' . $previous_version . ' -> ' . $new_version . ')';
            } else {
                $message = 'Plugin upgraded: ' . $plugin_name;
            }
        } else {
            $message = 'Plugin installed: ' . $plugin_name;
        }

        if ($award_menu_warning !== null) {
            $message .= ' Warning: ' . $award_menu_warning;
        }

        return array('ok' => true, 'message' => $message);
    }

    public function set_enabled($slug, $enabled)
    {
        if (!preg_match('/^[a-z0-9_-]+$/', $slug)) {
            return array('ok' => false, 'message' => 'Invalid plugin slug.');
        }

        $plugin = $this->CI->plugins_model->get_by_slug($slug);
        if (!$plugin) {
            return array('ok' => false, 'message' => 'Plugin not found.');
        }

        $plugin_dir = $this->get_plugin_root() . $slug . DIRECTORY_SEPARATOR;
        if (!is_dir($plugin_dir)) {
            return array('ok' => false, 'message' => 'Plugin files are missing on disk.');
        }

        $status = $enabled ? 'enabled' : 'disabled';
        if (!$this->CI->plugins_model->set_status($slug, $status)) {
            return array('ok' => false, 'message' => 'Failed to update plugin status.');
        }

        return array('ok' => true, 'message' => 'Plugin ' . $status . ': ' . $slug);
    }

    public function delete_plugin($slug)
    {
        if (!preg_match('/^[a-z0-9_-]+$/', $slug)) {
            return array('ok' => false, 'message' => 'Invalid plugin slug.');
        }

        if (!$this->CI->plugins_model->table_exists()) {
            return array('ok' => false, 'message' => 'Plugins table does not exist.');
        }

        $plugin = $this->CI->plugins_model->get_by_slug($slug);
        if (!$plugin) {
            return array('ok' => false, 'message' => 'Plugin not found.');
        }

        $plugin_dir = $this->get_plugin_root() . $slug . DIRECTORY_SEPARATOR;
        if (is_dir($plugin_dir)) {
            $this->recursive_delete($plugin_dir);
            if (is_dir($plugin_dir)) {
                return array('ok' => false, 'message' => 'Failed to remove plugin files from disk.');
            }
        }

        if (!$this->CI->plugins_model->delete_by_slug($slug)) {
            return array('ok' => false, 'message' => 'Failed to remove plugin metadata.');
        }

        return array('ok' => true, 'message' => 'Plugin deleted: ' . $slug);
    }

    public function get_award_menu_entries()
    {
        if (!$this->CI->plugins_model->table_exists()) {
            return array();
        }

        $enabled_plugins = $this->CI->plugins_model->get_enabled();
        $entries = array();

        foreach ($enabled_plugins as $plugin) {
            $manifest = $this->get_manifest_for_plugin($plugin);
            if (!is_array($manifest) || !isset($manifest['award_menu']) || !is_array($manifest['award_menu'])) {
                continue;
            }

            $award_menu = $manifest['award_menu'];
            $title = isset($award_menu['title']) ? trim((string)$award_menu['title']) : '';
            if ($title === '') {
                continue;
            }

            $route = isset($award_menu['route']) ? trim((string)$award_menu['route']) : 'plugin_awards/view/' . $plugin->plugin_slug;
            $route = ltrim($route, '/');
            if (!preg_match('/^[A-Za-z0-9_\/-]+$/', $route)) {
                continue;
            }

            $icon = isset($award_menu['icon']) ? trim((string)$award_menu['icon']) : 'fas fa-award';
            if ($icon === '') {
                $icon = 'fas fa-award';
            }

            $order = isset($award_menu['order']) ? (int)$award_menu['order'] : 100;

            $entries[] = array(
                'slug' => $plugin->plugin_slug,
                'title' => $title,
                'icon' => $icon,
                'route' => $route,
                'order' => $order,
            );
        }

        usort($entries, static function ($a, $b) {
            if ($a['order'] === $b['order']) {
                return strcmp($a['title'], $b['title']);
            }
            return $a['order'] <=> $b['order'];
        });

        return $entries;
    }

    public function render_award_page($slug)
    {
        if (!preg_match('/^[a-z0-9_-]+$/', $slug)) {
            return array('ok' => false, 'message' => 'Invalid plugin slug.');
        }

        if (!$this->CI->plugins_model->table_exists()) {
            return array('ok' => false, 'message' => 'Plugins table does not exist.');
        }

        $plugin = $this->CI->plugins_model->get_by_slug($slug);
        if (!$plugin || $plugin->plugin_status !== 'enabled') {
            return array('ok' => false, 'message' => 'Award plugin is not enabled.');
        }

        $manifest = $this->get_manifest_for_plugin($plugin);
        if (!is_array($manifest) || !isset($manifest['award_menu']) || !is_array($manifest['award_menu'])) {
            return array('ok' => false, 'message' => 'Plugin does not declare an award menu entry.');
        }

        $award_menu = $manifest['award_menu'];
        $method = isset($award_menu['method']) ? trim((string)$award_menu['method']) : 'renderAwardPage';
        if (!preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $method)) {
            return array('ok' => false, 'message' => 'Invalid plugin award method.');
        }

        $instance = $this->instantiate_plugin($plugin, $manifest);
        if (!$instance) {
            return array('ok' => false, 'message' => 'Unable to load plugin class.');
        }

        if (!method_exists($instance, $method)) {
            return array('ok' => false, 'message' => 'Plugin award method not found: ' . $method);
        }

        try {
            $result = $instance->$method(array(
                'slug' => $slug,
                'manifest' => $manifest,
                'user_id' => (int)$this->CI->session->userdata('user_id'),
            ));
        } catch (Throwable $e) {
            log_message('error', 'Plugin award render failed (' . $slug . '): ' . $e->getMessage());
            return array('ok' => false, 'message' => 'Plugin award rendering failed.');
        }

        $page_title = isset($award_menu['title']) ? (string)$award_menu['title'] : $plugin->plugin_name;
        $content = '';

        if (is_string($result)) {
            $content = $result;
        } elseif (is_array($result)) {
            if (isset($result['page_title'])) {
                $page_title = (string)$result['page_title'];
            }
            if (isset($result['content'])) {
                $content = (string)$result['content'];
            }
        }

        if (trim($content) === '') {
            return array('ok' => false, 'message' => 'Plugin award page returned empty content.');
        }

        return array(
            'ok' => true,
            'page_title' => $page_title,
            'content' => $content,
            'plugin_slug' => $slug,
        );
    }

    public function get_plugin_root()
    {
        return APPPATH . 'plugins' . DIRECTORY_SEPARATOR;
    }

    private function get_manifest_for_plugin($plugin)
    {
        $manifest = json_decode((string)$plugin->plugin_manifest, true);
        if (!is_array($manifest)) {
            $manifest = array();
        }

        $plugin_dir = $this->get_plugin_root() . $plugin->plugin_slug . DIRECTORY_SEPARATOR;
        $manifest_path = $plugin_dir . 'manifest.json';
        if (is_file($manifest_path)) {
            $disk_manifest = $this->read_manifest($manifest_path);
            if ($disk_manifest) {
                $manifest = $disk_manifest;
            }
        }

        return $manifest;
    }

    private function instantiate_plugin($plugin, $manifest)
    {
        $entry_file = isset($manifest['entry']) ? trim((string)$manifest['entry']) : 'Plugin.php';
        $class_name = isset($manifest['class']) ? trim((string)$manifest['class']) : 'Plugin';

        if (!preg_match('/^[A-Za-z0-9_\/.-]+$/', $entry_file)) {
            log_message('error', 'Plugin entry path invalid for ' . $plugin->plugin_slug);
            return null;
        }

        if (!preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $class_name)) {
            log_message('error', 'Plugin class invalid for ' . $plugin->plugin_slug);
            return null;
        }

        $plugin_path = $this->get_plugin_root() . $plugin->plugin_slug . DIRECTORY_SEPARATOR;
        $entry_path = realpath($plugin_path . $entry_file);
        $plugin_root = realpath($plugin_path);

        if ($plugin_root === false || $entry_path === false || strpos($entry_path, $plugin_root) !== 0) {
            log_message('error', 'Plugin entry file missing or outside plugin root for ' . $plugin->plugin_slug);
            return null;
        }

        require_once $entry_path;

        if (!class_exists($class_name)) {
            log_message('error', 'Plugin class not found: ' . $class_name . ' (' . $plugin->plugin_slug . ')');
            return null;
        }

        try {
            return new $class_name($this->CI);
        } catch (Throwable $e) {
            log_message('error', 'Plugin construction failed (' . $plugin->plugin_slug . '): ' . $e->getMessage());
            return null;
        }
    }

    private function validate_award_menu_definition($plugin_root, $manifest)
    {
        if (!isset($manifest['award_menu']) || !is_array($manifest['award_menu'])) {
            return null;
        }

        $award_menu = $manifest['award_menu'];
        $method = isset($award_menu['method']) ? trim((string)$award_menu['method']) : 'renderAwardPage';
        if (!preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $method)) {
            return 'award_menu.method is invalid and may prevent Awards dropdown rendering.';
        }

        $entry_file = isset($manifest['entry']) ? trim((string)$manifest['entry']) : 'Plugin.php';
        if (!preg_match('/^[A-Za-z0-9_\/.-]+$/', $entry_file)) {
            return 'award_menu is declared but entry path is invalid.';
        }

        $entry_path = realpath($plugin_root . $entry_file);
        $plugin_root_real = realpath($plugin_root);
        if ($entry_path === false || $plugin_root_real === false || strpos($entry_path, $plugin_root_real) !== 0 || !is_file($entry_path)) {
            return 'award_menu is declared but plugin entry file was not found.';
        }

        $entry_content = @file_get_contents($entry_path);
        if ($entry_content === false) {
            return 'award_menu is declared but plugin entry file could not be read.';
        }

        $method_regex = '/function\s+' . preg_quote($method, '/') . '\s*\(/i';
        if (!preg_match($method_regex, $entry_content)) {
            return 'award_menu is declared but method ' . $method . ' was not found in ' . $entry_file . '.';
        }

        return null;
    }

    private function ensure_plugin_root()
    {
        $plugin_root = $this->get_plugin_root();
        if (!is_dir($plugin_root)) {
            @mkdir($plugin_root, 0755, true);
            @file_put_contents($plugin_root . 'index.html', '<!DOCTYPE html><html><head><title></title></head><body></body></html>');
        }
    }

    private function find_invalid_archive_path(ZipArchive $zip)
    {
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $name = $zip->getNameIndex($i);
            if ($name === false) {
                return 'unknown';
            }

            $normalized = str_replace('\\', '/', $name);
            if (strpos($normalized, '../') !== false || strpos($normalized, '..\\') !== false) {
                return $name;
            }

            if (preg_match('/^[a-zA-Z]:\//', $normalized) || strpos($normalized, '/') === 0) {
                return $name;
            }
        }

        return null;
    }

    private function resolve_extracted_plugin_root($extract_dir)
    {
        if (is_file($extract_dir . 'manifest.json')) {
            return $extract_dir;
        }

        $entries = @scandir($extract_dir);
        if (!is_array($entries)) {
            return null;
        }

        foreach ($entries as $entry) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }

            $path = $extract_dir . $entry;
            if (is_dir($path) && is_file($path . DIRECTORY_SEPARATOR . 'manifest.json')) {
                return $path . DIRECTORY_SEPARATOR;
            }
        }

        return null;
    }

    private function read_manifest($manifest_path)
    {
        $content = @file_get_contents($manifest_path);
        if ($content === false) {
            return null;
        }

        $manifest = json_decode($content, true);
        if (!is_array($manifest)) {
            return null;
        }

        return $manifest;
    }

    private function resolve_slug($manifest)
    {
        if (!isset($manifest['slug'])) {
            return null;
        }

        $slug = strtolower(trim((string)$manifest['slug']));
        if (!preg_match('/^[a-z0-9_-]+$/', $slug)) {
            return null;
        }

        return $slug;
    }

    private function recursive_copy($src, $dst)
    {
        if (!is_dir($src)) {
            return false;
        }

        if (!is_dir($dst) && !@mkdir($dst, 0755, true)) {
            return false;
        }

        $items = @scandir($src);
        if (!is_array($items)) {
            return false;
        }

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $from = $src . DIRECTORY_SEPARATOR . $item;
            $to = $dst . DIRECTORY_SEPARATOR . $item;

            if (is_dir($from)) {
                if (!$this->recursive_copy($from, $to)) {
                    return false;
                }
            } else {
                if (!@copy($from, $to)) {
                    return false;
                }
            }
        }

        return true;
    }

    private function recursive_delete($path)
    {
        if (!file_exists($path)) {
            return;
        }

        if (is_file($path) || is_link($path)) {
            @unlink($path);
            return;
        }

        $items = @scandir($path);
        if (is_array($items)) {
            foreach ($items as $item) {
                if ($item === '.' || $item === '..') {
                    continue;
                }
                $this->recursive_delete($path . DIRECTORY_SEPARATOR . $item);
            }
        }

        @rmdir($path);
    }
}
