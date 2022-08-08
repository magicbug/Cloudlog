<?php

use Cloudlog\QSLManager\SearchCriteria;
use Cloudlog\QSLManager\QSO;

if (!defined('BASEPATH')) exit('No direct script access allowed');


/*
	This controller will contain features for managing QSL cards
*/

class Qslmanager extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url', 'psr4_autoloader'));

		$this->load->model('user_model');
		if (!$this->user_model->authorize(2)) {
			$this->session->set_flashdata('notice', 'You\'re not allowed to do that!');
			redirect('dashboard');
		}
	}

	public function index()
	{
		$this->load->model('stations');
		$this->load->model('logbook_model');
		$this->load->model('bands');

		$stationIds = [];

		$deOptions = [];
		foreach ($this->stations->all_of_user()->result() as $station) {
			$deOptions[$station->station_callsign] = 1;
			$stationIds[] = $station->station_id;
		}
		ksort($deOptions);
		$deOptions = array_keys($deOptions);

		$modes = [];
		if ($stationIds !== []) {
			foreach ($this->logbook_model->get_worked_modes($stationIds) as $mode) {
				$key = $mode['mode'];
				if ($mode['submode'] !== null) {
					$key .= "|" . $mode['submode'];
				}
				if ($mode['submode'] == null) {
					$modes[$key] = $mode['mode'];
				} else {
					$modes[$key] = $mode['submode'];
				}
			}
		}

		$data = [];
		$data['page_title'] = "QSL Manager";
		$data['hasDatePicker'] = true;

		$pageData = [];
		$pageData['datePlaceholder'] = 'YYYY-MM-DD';
		$pageData['deOptions'] = $deOptions;
		$pageData['modes'] = $modes;
		$pageData['bands'] = $this->bands->get_worked_bands_for_user($this->session->userdata('user_id'));

		$footerData = [];
		$footerData['scripts'] = [
			'assets/js/moment.min.js',
			'assets/js/tempusdominus-bootstrap-4.min.js',
			'assets/js/qslmanager.js?' . filemtime(realpath(__DIR__ . "/../../assets/js/qslmanager.js"))
		];

		$this->load->view('interface_assets/header', $data);
		$this->load->view('qslmanager/index', $pageData);
		$this->load->view('interface_assets/footer', $footerData);
	}

	public function search()
	{
		$this->load->model('logbook_model');

		$searchCriteria = new SearchCriteria(
			(int)$this->session->userdata('user_id'),
			xss_clean($this->input->post('dateFrom')),
			xss_clean($this->input->post('dateTo')),
			xss_clean($this->input->post('de')),
			xss_clean($this->input->post('dx')),
			xss_clean($this->input->post('mode')),
			xss_clean($this->input->post('band')),
			xss_clean($this->input->post('qslSent')),
			xss_clean($this->input->post('qslReceived'))
		);

		$qsos = [];
		foreach ($this->logbook_model->doSearchForQSLManager($searchCriteria) as $qso) {
			$qsos[] = $qso->toArray();
		}

		header("Content-Type: application/json");
		print json_encode($qsos);

	}

	public function test()
	{
		$this->load->model('logbook_model');
		$callbook = $this->logbook_model->loadCallBook("CT7AUS", $this->config->item('use_fullname'));

	}

	public function updateFromCallbook()
	{
		$this->load->model('logbook_model');

		$qsoID = xss_clean($this->input->post('qsoID'));
		$qso = $this->logbook_model->qso_info($qsoID)->row_array();
		if ($qso === null) {
			header("Content-Type: application/json");
			echo json_encode([]);
			return;
		}

		$callbook = $this->logbook_model->loadCallBook($qso['COL_CALL'], $this->config->item('use_fullname'));

		if ($callbook['callsign'] !== "") {
			$qso['COL_NAME'] = trim($callbook['name']);
			$qso['COL_QSL_VIA'] = trim($callbook['qslmgr']);
		}

		$qsoObj = new QSO($qso);

		header("Content-Type: application/json");
		echo json_encode($qsoObj->toArray());
	}

}
