<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
    Path: application\controllers\Welcome.php

    Displays the welcome to Cloudlog version 2.0 view to help users with migration from version 1.0
*/ 


class Welcome extends CI_Controller {

	public function index()
	{
        $data['page_title'] = "Welcome to Cloudlog Version 2.0";

        // load stations model 
        $this->load->model('stations');
        $data['CountAllStationLocations'] = $this->stations->CountAllStationLocations();

        // load logbooks model
        $this->load->model('logbooks_model');
        $data['NumberOfStationLogbooks'] = $this->logbooks_model->CountAllStationLogbooks();

        // load views
        $this->load->view('interface_assets/header', $data);
        $this->load->view('welcome/index');
        $this->load->view('interface_assets/footer');
    }

    public function locationsclaim() {
        try {
            // load model Stations and call function ClaimAllStationLocations
            $this->load->model('stations');
            $this->stations->ClaimAllStationLocations();

            echo "All Station Locations Claimed";
        } catch (Exception $e) {
            log_message('error', 'Error Claiming Station Locations during Migration. '.$e->getMessage());
            echo "Error Claiming Station Locations during Migration. See Logs for further information";
        }
    }

    public function defaultlogbook() {
        try {
            // load model Stations and call function ClaimAllStationLocations
            $this->load->model('logbooks_model');
            $this->logbooks_model->CreateDefaultLogbook();

            echo "Default Logbook Created";
        } catch (Exception $e) {
            log_message('error', 'Error Creating Default Logbook during Migration. '.$e->getMessage());
            echo "Error Creating Default Logbook during Migration. See Logs for further information";
        }
    }
}