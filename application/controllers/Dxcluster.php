<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dxcluster extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        $this->load->model('user_model');
        if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
    }

    function index()
    {
        $data['page_title'] = "DX Cluster Spots";

        $this->load->view('dxcluster/index', $data);
    }

    function bandmap()
    {
        $data['page_title'] = "DX Cluster Bandmap";

        $this->load->view('dxcluster/bandmap');

    }
}