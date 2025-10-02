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

        /// Load layout

        $this->load->view('interface_assets/header', $data);
		$this->load->view('dxcluster/index', $data);
		$this->load->view('interface_assets/footer');
    }

    function bandmap()
    {
        $this->load->model('bands');
        
        $data['page_title'] = "DX Cluster Bandmap";
        $data['bands'] = $this->bands->get_user_bands_for_bandmap();

        $this->load->view('dxcluster/bandmap', $data);

    }
}