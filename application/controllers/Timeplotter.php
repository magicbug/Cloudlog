<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Timeplotter extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        $this->load->model('user_model');
        if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
    }

    public function index()
    {
        // Render Page
        $data['page_title'] = "Timeplotter";

        $this->load->model('bands');

        $data['worked_bands'] = $this->bands->get_worked_bands();

        $this->load->model('dxcc');
        $data['dxcc_list'] = $this->dxcc->list();

        $this->load->model('modes');

        $data['modes'] = $this->modes->active();

        $this->load->view('interface_assets/header', $data);
        $this->load->view('timeplotter/index');
        $this->load->view('interface_assets/footer');
    }

    public function getTimes() {
        // POST data
        $postData = $this->input->post();

        //load model
        $this->load->model('Timeplotter_model');

        // get data
        $data = $this->Timeplotter_model->getTimes($postData);

        return json_encode($data);

    }

}