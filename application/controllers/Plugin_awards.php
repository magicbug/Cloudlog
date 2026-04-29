<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Plugin_awards extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->model('user_model');
        if (!$this->user_model->authorize(2)) {
            $this->session->set_flashdata('notice', 'You\'re not allowed to do that!');
            redirect('dashboard');
        }

        $this->load->library('plugin_manager');
    }

    public function view($slug = '')
    {
        $slug = strtolower(trim((string)$slug));
        $result = $this->plugin_manager->render_award_page($slug);

        if (!isset($result['ok']) || $result['ok'] !== true) {
            $message = isset($result['message']) ? $result['message'] : 'Unable to load plugin award page.';
            $this->session->set_flashdata('notice', $message);
            redirect('awards');
            return;
        }

        $data['page_title'] = $result['page_title'];
        $data['plugin_award_title'] = $result['page_title'];
        $data['plugin_award_content'] = $result['content'];
        $data['plugin_award_slug'] = $result['plugin_slug'];

        $this->load->view('interface_assets/header', $data);
        $this->load->view('plugins/award_page', $data);
        $this->load->view('interface_assets/footer');
    }
}
