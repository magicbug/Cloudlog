<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
    Path: application\controllers\Welcome.php

    Displays the welcome to Cloudlog version 2.0 view to help users with migration from version 1.0
*/ 


class Welcome extends CI_Controller {

	public function index()
	{
        if($this->optionslib->get_option('version2_trigger') == "false") {
            $data['page_title'] = "Welcome to Cloudlog Version 2.0";

            // load stations model 
            $this->load->model('stations');
            $data['CountAllStationLocations'] = $this->stations->CountAllStationLocations();

            // load logbooks model
            $this->load->model('logbooks_model');
            $data['NumberOfStationLogbooks'] = $this->logbooks_model->CountAllStationLogbooks();
    
            // load api model
            $this->load->model('api_model');
            $data['NumberOfAPIKeys'] = $this->api_model->CountKeysWithNoUserID();

            // load note model
            $this->load->model('note');
            $data['NumberOfNotes'] = $this->note->CountAllNotes();

            if($data['CountAllStationLocations'] > 0 || $data['NumberOfStationLogbooks'] > 0 || $data['NumberOfAPIKeys'] > 0  || $data['NumberOfNotes'] > 0) {
                // load views
                $this->load->view('interface_assets/mini_header', $data);
                $this->load->view('welcome/index');
                $this->load->view('interface_assets/footer');
            } else {
                $data['NoMigrationRequired'] = false;
                $this->optionslib->update('version2_trigger', "true", "yes");
                redirect('dashboard');
            }
        } else {
            redirect('dashboard');
        }
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

    public function claimnotes() {
        try {
            // load model Stations and call function ClaimAllStationLocations
            $this->load->model('note');
            $this->note->ClaimAllNotes();

            echo "Notes all claimed";
        } catch (Exception $e) {
            log_message('error', 'Error claiming notes during Migration. '.$e->getMessage());
            echo "Error claiming notes during Migration. See Logs for further information";
        }
    }

    public function claimapikeys() {
        try {
            // load model Stations and call function ClaimAllStationLocations
            $this->load->model('api_model');
            $this->api_model->ClaimAllAPIKeys();

            echo "All API Keys claimed";
        } catch (Exception $e) {
            log_message('error', 'Error claiming API Keys during Migration. '.$e->getMessage());
            echo "Error claiming API Keys during Migration. See Logs for further information";
        }
    }
}