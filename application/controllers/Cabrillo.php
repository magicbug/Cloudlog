<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/*
	This controller contains features for Cabrillo
*/

class Cabrillo extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
	}

    public function index()
    {
        $data['page_title'] = "Export Cabrillo";

        $this->load->model('Contesting_model');

        $data['contestyears'] = $this->Contesting_model->get_logged_years();

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
		$result = $this->Contesting_model->get_logged_contests($this->input->post('year'));

		header('Content-Type: application/json');
		echo json_encode($result);
    }

    public function getContestDates() {
        $this->load->model('Contesting_model');
		$result = $this->Contesting_model->get_contest_dates($this->input->post('year'), $this->input->post('contestid'));

		header('Content-Type: application/json');
		echo json_encode($result);
    }

    public function export() {
        // Set memory limit to unlimited to allow heavy usage
		ini_set('memory_limit', '-1');
        $this->load->model('Contesting_model');

        $contest_id = $this->security->xss_clean($this->input->post('contestid'));
        $fromto = $this->security->xss_clean($this->input->post('contestdates'));

        $fromto = explode(',', $fromto);

        $from = $fromto[0];
        $to = $fromto[1];

		$data['qsos'] = $this->Contesting_model->export_custom($from, $to, $contest_id);

        $data['contest_id'] = $contest_id;
        $data['callsign'] = '';
        $data['claimed_score'] = '';
        $data['operators'] = '';
        $data['club'] = '';
        $data['name'] = '';
        $data['address1'] = '';
        $data['address2'] = '';
        $data['address3'] = '';
        $data['soapbox'] = '';

		$this->load->view('cabrillo/export', $data);
    }
}