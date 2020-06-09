<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Timeline extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        $this->load->model('user_model');
        if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
    }

    public function index()
    {
        // Render Page
        $data['page_title'] = "DXCC Timeline";

        $this->load->model('Timeline_model');

        if ($this->input->post('band') != NULL) {   // Band is not set when page first loads.
            $band = $this->input->post('band');
        }
        else {
            $band = 'All';
        }

        $data['dxcc_timeline_array'] = $this->Timeline_model->get_dxcc_timeline($band);
        $data['worked_bands'] = $this->Timeline_model->get_worked_bands();
        $data['bandselect'] = $band;

        $this->load->view('interface_assets/header', $data);
        $this->load->view('timeline/index');
        $this->load->view('interface_assets/footer');
    }

    public function details() {
        $this->load->model('logbook_model');

        $adif = str_replace('"', "", $this->input->get("Adif"));
        $country = $this->logbook_model->get_entity($adif);
        $band = str_replace('"', "", $this->input->get("Band"));
        $data['results'] = $this->logbook_model->timeline_qso_details($adif, $band);

        // Render Page
        $data['page_title'] = "Log View - DXCC";
        $data['filter'] = "country ". $country['name'];

        if ($band != "All") {
            $data['filter'] .= " and " . $band;
        }

        $this->load->view('interface_assets/header', $data);
        $this->load->view('timeline/details');
        $this->load->view('interface_assets/footer');
    }

}