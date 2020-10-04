<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accumulated extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        $this->load->model('user_model');
        if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
    }

    public function index()
    {
        // Render Page
        $data['page_title'] = "Accumulated statistics";

        $this->load->model('Accumulate_model');

        if ($this->input->post('band') != NULL) {   // Band is not set when page first loads.
            $band = $this->input->post('band');
        }
        else {
            $band = 'All';
        }

        $data['accumulated_dxcc_array'] = $this->Accumulate_model->get_accumulated_dxcc($band);
        $data['worked_bands'] = $this->Accumulate_model->get_worked_bands();
        $data['bandselect'] = $band;

        $this->load->view('interface_assets/header', $data);
        $this->load->view('accumulate/index');
        $this->load->view('interface_assets/footer');
    }

    public function details() {
        $this->load->model('logbook_model');

        $adif = str_replace('"', "", $this->input->post("Adif"));
        $country = $this->logbook_model->get_entity($adif);
        $band = str_replace('"', "", $this->input->post("Band"));
        $data['results'] = $this->logbook_model->timeline_qso_details($adif, $band);

        // Render Page
        $data['page_title'] = "Log View - DXCC";
        $data['filter'] = "country ". $country['name'];

        if ($band != "All") {
            $data['filter'] .= " and " . $band;
        }

        $this->load->view('timeline/details', $data);
    }

}