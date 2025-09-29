<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Emeinitials extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        $this->load->model('user_model');
        if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
    }

    public function index()
    {
        // Render Page
        $data['page_title'] = "EME Initials";

        $this->load->model('Emeinitials_model');

        if ($this->input->post('band') != NULL) {   // Band is not set when page first loads.
            $band = $this->input->post('band');
        }
        else {
            $band = 'All';
        }

        if ($this->input->post('mode') != NULL) {
            $mode = $this->input->post('mode');
        }
        else {
            $mode = 'All';
        }

        $this->load->model('modes');
        $this->load->model('bands');

        $data['modes'] = $this->modes->active();

        $data['timeline_array'] = $this->Emeinitials_model->get_initials($band, $mode);
        $data['worked_bands'] = $this->bands->get_worked_bands();
        $data['bandselect'] = $band;
        $data['modeselect'] = $mode;

        $this->load->view('interface_assets/header', $data);
        $this->load->view('emeinitials/index');
        $this->load->view('interface_assets/footer');
    }
}
