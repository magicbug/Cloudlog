<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/*
	This controller will contain features for contesting
*/

class Contesting extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->lang->load('contesting');
		
		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
	}

    public function index()
    {

        $this->load->model('cat');
        $this->load->model('stations');
        $this->load->model('logbook_model');
        $this->load->model('user_model');
        $this->load->model('modes');

        $data['active_station_profile'] = $this->stations->find_active();
        $data['notice'] = false;
        $data['stations'] = $this->stations->all();
        $data['radios'] = $this->cat->radios();
        $data['dxcc'] = $this->logbook_model->fetchDxcc();
        $data['modes'] = $this->modes->active();


        $this->load->library('form_validation');

        $this->form_validation->set_rules('start_date', 'Date', 'required');
        $this->form_validation->set_rules('start_time', 'Time', 'required');
        $this->form_validation->set_rules('callsign', 'Callsign', 'required');

            $data['page_title'] = "Contest Logging";

            $this->load->view('interface_assets/header', $data);
            $this->load->view('contesting/index');
            $this->load->view('interface_assets/footer');


            //setcookie("radio", $qso_data['radio'], time()+3600*24*99);
            //setcookie("station_profile_id", $qso_data['station_profile_id'], time()+3600*24*99);

            //$this->session->set_userdata($qso_data);

            // If SAT name is set make it session set to sat
            if($this->input->post('sat_name')) {
                $this->session->set_userdata('prop_mode', 'SAT');
            }

    }

    public function getSessionQsos() {
        //load model
        $this->load->model('Contesting_model');

        $qso = $this->input->post('qso');

        // get QSOs to fill the table
        $data = $this->Contesting_model->getSessionQsos($qso);

        return json_encode($data);
    }
}