<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cloudlog_hooks {

    private $CI;
    private $plugin_instances = array();

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('plugins_model');
    }

    public function apply_filters($hook_name, $payload, $context = array())
    {
        $handlers = $this->get_handlers_for_hook($hook_name);
        if (empty($handlers)) {
            return $payload;
        }

        $current = $payload;
        foreach ($handlers as $handler) {
            $plugin_instance = $this->load_plugin_instance($handler['plugin'], $handler['manifest']);
            if (!$plugin_instance) {
                continue;
            }

            $method = $handler['method'];
            if (!method_exists($plugin_instance, $method)) {
                continue;
            }

            try {
                $returned = $plugin_instance->$method($current, $context);
                if ($returned !== null) {
                    $current = $returned;
                }
            } catch (Throwable $e) {
                log_message('error', 'Plugin filter failed [' . $hook_name . '] plugin=' . $handler['plugin']->plugin_slug . ' error=' . $e->getMessage());
            }
        }

        return $current;
    }

    public function do_action($hook_name, $payload = array(), $context = array())
    {
        $handlers = $this->get_handlers_for_hook($hook_name);
        if (empty($handlers)) {
            return;
        }

        foreach ($handlers as $handler) {
            $plugin_instance = $this->load_plugin_instance($handler['plugin'], $handler['manifest']);
            if (!$plugin_instance) {
                continue;
            }

            $method = $handler['method'];
            if (!method_exists($plugin_instance, $method)) {
                continue;
            }

            try {
                $plugin_instance->$method($payload, $context);
            } catch (Throwable $e) {
                log_message('error', 'Plugin action failed [' . $hook_name . '] plugin=' . $handler['plugin']->plugin_slug . ' error=' . $e->getMessage());
            }
        }
    }

    private function get_handlers_for_hook($hook_name)
    {
        if (!$this->CI->plugins_model->table_exists()) {
            return array();
        }

        $enabled_plugins = $this->CI->plugins_model->get_enabled();
        if (empty($enabled_plugins)) {
            return array();
        }

        $handlers = array();

        foreach ($enabled_plugins as $plugin) {
            $manifest = json_decode((string)$plugin->plugin_manifest, true);
            if (!is_array($manifest) || !isset($manifest['hooks']) || !is_array($manifest['hooks'])) {
                continue;
            }

            if (!isset($manifest['hooks'][$hook_name])) {
                continue;
            }

            $method = $manifest['hooks'][$hook_name];
            if (!is_string($method) || $method === '') {
                continue;
            }

            $handlers[] = array(
                'plugin' => $plugin,
                'manifest' => $manifest,
                'method' => $method,
            );
        }

        return $handlers;
    }

    private function load_plugin_instance($plugin, $manifest)
    {
        $slug = $plugin->plugin_slug;
        if (isset($this->plugin_instances[$slug])) {
            return $this->plugin_instances[$slug];
        }

        $entry_file = isset($manifest['entry']) ? trim((string)$manifest['entry']) : 'Plugin.php';
        $class_name = isset($manifest['class']) ? trim((string)$manifest['class']) : 'Plugin';

        if (!preg_match('/^[A-Za-z0-9_\/.-]+$/', $entry_file)) {
            log_message('error', 'Plugin entry path invalid for ' . $slug);
            return null;
        }

        if (!preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $class_name)) {
            log_message('error', 'Plugin class invalid for ' . $slug);
            return null;
        }

        $plugin_path = APPPATH . 'plugins' . DIRECTORY_SEPARATOR . $slug . DIRECTORY_SEPARATOR;
        $entry_path = realpath($plugin_path . $entry_file);
        $plugin_root = realpath($plugin_path);

        if ($plugin_root === false || $entry_path === false || strpos($entry_path, $plugin_root) !== 0) {
            log_message('error', 'Plugin entry file missing or outside plugin root for ' . $slug);
            return null;
        }

        require_once $entry_path;

        if (!class_exists($class_name)) {
            log_message('error', 'Plugin class not found: ' . $class_name . ' (' . $slug . ')');
            return null;
        }

        try {
            $instance = new $class_name($this->CI);
        } catch (Throwable $e) {
            log_message('error', 'Plugin construction failed (' . $slug . '): ' . $e->getMessage());
            return null;
        }

        $this->plugin_instances[$slug] = $instance;

        return $instance;
    }
}
