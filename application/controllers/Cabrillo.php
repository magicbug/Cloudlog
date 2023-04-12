<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/*
	This controller contains features for Cabrillo
*/

class Cabrillo extends CI_Controller {

	function __construct() {
		parent::__construct();

		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
	}

    public function index() {
        $data['page_title'] = "Export Cabrillo";

        $this->load->model('Contesting_model');
        $this->load->model('stations');

        $data['station_profile'] = $this->stations->all_of_user();
        $active_station_id = $this->stations->find_active();
        $station_profile = $this->stations->profile($active_station_id);

		$data['active_station_info'] = $station_profile->row();

		$footerData = [];
		$footerData['scripts'] = [
			'assets/js/sections/cabrillo.js'
		];
        
        $this->load->view('interface_assets/header', $data);
		$this->load->view('cabrillo/index');
		$this->load->view('interface_assets/footer', $footerData);
    }

    public function getContests() {
		$this->load->model('Contesting_model');

        $station_id = $this->security->xss_clean($this->input->post('station_id'));
        $year = $this->security->xss_clean($this->input->post('year'));
		$result = $this->Contesting_model->get_logged_contests($station_id, $year);

		header('Content-Type: application/json');
		echo json_encode($result);
    }

    public function getYears() {
		$this->load->model('Contesting_model');
        $station_id = $this->security->xss_clean($this->input->post('station_id'));

		$result = $this->Contesting_model->get_logged_years($station_id);

		header('Content-Type: application/json');
		echo json_encode($result);
    }

    public function getContestDates() {
        $this->load->model('Contesting_model');
        $station_id = $this->security->xss_clean($this->input->post('station_id'));
        $year = $this->security->xss_clean($this->input->post('year'));
        $contestid = $this->security->xss_clean($this->input->post('contestid'));

		$result = $this->Contesting_model->get_contest_dates($station_id, $year, $contestid);

		header('Content-Type: application/json');
		echo json_encode($result);
    }

    public function export() {
        // Set memory limit to unlimited to allow heavy usage
		ini_set('memory_limit', '-1');
        $this->load->model('Contesting_model');

        $this->load->model('stations');

        $station_id = $this->security->xss_clean($this->input->post('station_id'));
        $contest_id = $this->security->xss_clean($this->input->post('contestid'));

        $from = $this->security->xss_clean($this->input->post('contestdatesfrom'));
        $to = $this->security->xss_clean($this->input->post('contestdatesto'));

        $station = $this->stations->profile($station_id);

        $station = $station->row();

		$data['qsos'] = $this->Contesting_model->export_custom($from, $to, $contest_id, $station_id);

        $data['contest_id'] = $contest_id;
        $data['callsign'] = $station->station_callsign;
        $data['claimed_score'] = '';
        $data['operators'] = '';
        $data['club'] = '';
        $data['name'] = '';
        $data['address1'] = '';
        $data['address2'] = '';
        $data['address3'] = '';
        $data['soapbox'] = '';
        $data['gridlocator'] = $station->station_gridsquare;

		$this->load->view('cabrillo/export', $data);
    }
}