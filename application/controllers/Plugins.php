<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Plugins extends CI_Controller {

    private const PLUGINS_CSRF_SESSION_KEY = 'plugins_csrf_token';

    public function __construct()
    {
        parent::__construct();

        $this->load->model('user_model');
        if (!$this->user_model->authorize(99)) {
            $this->session->set_flashdata('notice', 'You\'re not allowed to do that!');
            redirect('dashboard');
        }

        $this->load->library('plugin_manager');
    }

    public function index()
    {
        $data['page_title'] = 'Plugin Manager';
        $data['plugins'] = $this->plugin_manager->list_plugins();
        $data['plugins_csrf_token'] = $this->get_plugins_csrf_token();

        $this->load->view('interface_assets/header', $data);
        $this->load->view('plugins/index', $data);
        $this->load->view('interface_assets/footer');
    }

    public function upload()
    {
        if (strtolower($this->input->method()) !== 'post') {
            redirect('plugins');
            return;
        }

        if (!$this->validate_plugins_csrf_token((string)$this->input->post('plugins_csrf_token', true))) {
            $this->session->set_flashdata('notice', 'Security validation failed. Please retry from the Plugin Manager page.');
            redirect('plugins');
            return;
        }

        $upload_dir = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR;
        if (!is_dir($upload_dir) && !@mkdir($upload_dir, 0755, true)) {
            $this->session->set_flashdata('notice', 'Unable to create plugin upload directory.');
            redirect('plugins');
            return;
        }

        $config = array(
            'upload_path' => $upload_dir,
            'allowed_types' => 'zip|ZIP',
            'max_size' => 20480,
            'encrypt_name' => true,
            'detect_mime' => true,
            'mod_mime_fix' => true,
        );

        $this->load->library('upload');
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('plugin_zip')) {
            $this->session->set_flashdata('notice', trim(strip_tags($this->upload->display_errors('', ''))));
            redirect('plugins');
            return;
        }

        $upload_data = $this->upload->data();
        $result = $this->plugin_manager->install_from_zip($upload_data['full_path']);
        @unlink($upload_data['full_path']);

        $this->session->set_flashdata('notice', $result['message']);
        redirect('plugins');
    }

    public function enable()
    {
        if (strtolower($this->input->method()) !== 'post') {
            redirect('plugins');
            return;
        }

        if (!$this->validate_plugins_csrf_token((string)$this->input->post('plugins_csrf_token', true))) {
            $this->session->set_flashdata('notice', 'Security validation failed. Please retry from the Plugin Manager page.');
            redirect('plugins');
            return;
        }

        $slug = strtolower(trim((string)$this->input->post('plugin_slug', true)));
        $result = $this->plugin_manager->set_enabled($slug, true);
        $this->session->set_flashdata('notice', $result['message']);

        redirect('plugins');
    }

    public function disable()
    {
        if (strtolower($this->input->method()) !== 'post') {
            redirect('plugins');
            return;
        }

        if (!$this->validate_plugins_csrf_token((string)$this->input->post('plugins_csrf_token', true))) {
            $this->session->set_flashdata('notice', 'Security validation failed. Please retry from the Plugin Manager page.');
            redirect('plugins');
            return;
        }

        $slug = strtolower(trim((string)$this->input->post('plugin_slug', true)));
        $result = $this->plugin_manager->set_enabled($slug, false);
        $this->session->set_flashdata('notice', $result['message']);

        redirect('plugins');
    }

    public function delete()
    {
        if (strtolower($this->input->method()) !== 'post') {
            redirect('plugins');
            return;
        }

        if (!$this->validate_plugins_csrf_token((string)$this->input->post('plugins_csrf_token', true))) {
            $this->session->set_flashdata('notice', 'Security validation failed. Please retry from the Plugin Manager page.');
            redirect('plugins');
            return;
        }

        $slug = strtolower(trim((string)$this->input->post('plugin_slug', true)));
        $result = $this->plugin_manager->delete_plugin($slug);
        $this->session->set_flashdata('notice', $result['message']);

        redirect('plugins');
    }

    private function get_plugins_csrf_token()
    {
        $token = (string)$this->session->userdata(self::PLUGINS_CSRF_SESSION_KEY);

        if ($token === '') {
            $token = bin2hex(random_bytes(32));
            $this->session->set_userdata(self::PLUGINS_CSRF_SESSION_KEY, $token);
        }

        return $token;
    }

    private function validate_plugins_csrf_token($posted_token)
    {
        $session_token = (string)$this->session->userdata(self::PLUGINS_CSRF_SESSION_KEY);

        if ($session_token === '' || $posted_token === '') {
            return false;
        }

        return hash_equals($session_token, $posted_token);
    }
}
