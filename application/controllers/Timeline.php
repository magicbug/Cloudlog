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
        $data['page_title'] = "Timeline";

        $this->load->model('Timeline_model');

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

        if ($this->input->post('award') != NULL) {
            $award = $this->input->post('award');
        }
        else {
            $award = 'dxcc';
        }

        if ($this->input->post('qsl') != NULL) {
            $qsl = $this->input->post('qsl');
        }
        else {
            $qsl = '0';
        }

        if ($this->input->post('lotw') != NULL) {
            $lotw = $this->input->post('lotw');
        }
        else {
            $lotw = '0';
        }

        if ($this->input->post('eqsl') != NULL) {
            $eqsl = $this->input->post('eqsl');
        }
        else {
            $eqsl = '0';
        }

        $this->load->model('modes');
        $this->load->model('bands');

        $data['modes'] = $this->modes->active();

        $data['timeline_array'] = $this->Timeline_model->get_timeline($band, $mode, $award, $qsl, $lotw, $eqsl);
        $data['worked_bands'] = $this->bands->get_worked_bands();
        $data['bandselect'] = $band;
        $data['modeselect'] = $mode;

        $this->load->view('interface_assets/header', $data);
        $this->load->view('timeline/index');
        $this->load->view('interface_assets/footer');
    }

    public function details() {
        $this->load->model('logbook_model');
        $this->load->model('timeline_model');

        $querystring = str_replace('"', "", $this->input->post("Querystring"));

        $band = str_replace('"', "", $this->input->post("Band"));
        $mode = str_replace('"', "", $this->input->post("Mode"));
        $type = str_replace('"', "", $this->input->post("Type"));
        $data['results'] = $this->timeline_model->timeline_qso_details($querystring, $band, $mode, $type);


        switch($type) {
            case 'dxcc':    $country = $this->logbook_model->get_entity($querystring);
                            $data['page_title'] = "Log View - DXCC";
                            $data['filter'] = "country ". $country['name'];
                            break;
            case 'was' :    $data['page_title'] = "Log View - WAS";
                            $data['filter'] = "state ". $querystring;
                            break;
            case 'iota':    $data['page_title'] = "Log View - IOTA";
                            $data['filter'] = "iota ". $querystring;
                            break;
            case 'waz' :    $data['page_title'] = "Log View - WAZ";
                            $data['filter'] = "CQ zone ". $querystring;
                            break;
            case 'vucc' :   $data['page_title'] = "Log View - VUCC";
                            $data['filter'] = "Gridsquare ". $querystring;
                            break;
        }

        if ($band != "All") {
            $data['filter'] .= " and " . $band;
        }

        $this->load->view('timeline/details', $data);
    }

}