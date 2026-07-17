<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Activators extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        $this->load->model('user_model');
        if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
    }

    public function index()
    {
        // Render Page
        $data['page_title'] = "Gridsquare Activators";

        $this->load->model('Activators_model');

        if ($this->input->post('band') != NULL) {   // Band is not set when page first loads.
            $band = $this->input->post('band');
        }
        else {
            $band = 'All';
        }

        $this->load->model('bands');

        $data['worked_bands'] = $this->bands->get_worked_bands();
        $data['maxactivatedgrids'] = $this->Activators_model->get_max_activated_grids();
        $data['activators_array'] = $this->Activators_model->get_activators($band, $this->input->post('mincount'), $this->input->post('leogeo'));
        $data['activators_vucc_array'] = $this->Activators_model->get_activators_vucc($band, $this->input->post('leogeo'));
        $data['bandselect'] = $band;

        $this->load->view('interface_assets/header', $data);
        $this->load->view('activators/index');
        $this->load->view('interface_assets/footer');
    }

    public function details() {
        $this->load->model('logbook_model');
        $this->load->model('Activators_model');

        $call = str_replace('"', "", $this->input->post("Callsign"));
        $band = str_replace('"', "", $this->input->post("Band"));
        $leogeo = str_replace('"', "", $this->input->post("LeoGeo"));
        $canonical_call = $this->Activators_model->canonicalize_callsign($call);
        $matched_calls = $this->logbook_model->get_callsigns($canonical_call);
        $call_variants = array();
        if ($matched_calls) {
            foreach ($matched_calls->result() as $row) {
                $call_variants[] = $row->COL_CALL;
            }
        }
        if (empty($call_variants)) {
            $call_variants[] = $canonical_call;
        }

        $data['results'] = $this->logbook_model->activator_details($call_variants, $band, $leogeo);
        $data['filter'] = "Call ".$canonical_call;
        switch($band) {
        case 'All':     $data['page_title'] = "Log View All Bands";
                        $data['filter'] .= " and Band All";
                        break;
        case 'SAT':     $data['page_title'] = "Log View SAT";
                        $data['filter'] .= " and Band SAT";
                        break;
        default:        $data['page_title'] = "Log View Band";
                        $data['filter'] .= " and Band ".$band;
                        break;
        }
        if ($band == "SAT") {
            switch($leogeo) {
            case 'both':    $data['filter'] .= " and GEO/LEO";
                            break;
            case 'leo':     $data['filter'] .= " and LEO";
                            break;
            case 'geo':     $data['filter'] .= " and GEO";
                            break;
            }
        }


        $this->load->view('activators/details', $data);
    }

    public function component_activators() {
        // HTMX endpoint for activators table
        $this->load->model('Activators_model');
        $this->load->model('bands');

        $band = $this->input->post('band') ?: 'All';
        $mincount = $this->input->post('mincount') ?: 2;
        $leogeo = $this->input->post('leogeo') ?: 'both';

        $activators_array = $this->Activators_model->get_activators($band, $mincount, $leogeo);
        $activators_vucc_array = $this->Activators_model->get_activators_vucc($band, $leogeo);

        // Get Date format
        if($this->session->userdata('user_date_format')) {
            $custom_date_format = $this->session->userdata('user_date_format');
        } else {
            $custom_date_format = $this->config->item('qso_date_format');
        }

        $vucc_grids = array();
        if ($activators_vucc_array) {
            foreach ($activators_vucc_array as $line) {
                $vucc_grids[$line->call] = $line->vucc_grids;
            }
        }

        if ($activators_array) {
            $this->load->view('activators/component_table', array(
                'activators_array' => $activators_array,
                'vucc_grids' => $vucc_grids,
                'custom_date_format' => $custom_date_format,
                'band' => $band,
                'leogeo' => $leogeo
            ));
        } else {
            echo '<div class="alert alert-info" role="alert">No activators found for the selected filters.</div>';
        }
    }

}
