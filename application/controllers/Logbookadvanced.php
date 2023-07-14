<?php

use Cloudlog\QSLManager\QSO;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logbookadvanced extends CI_Controller {

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

	function index() {
		$this->load->model('stations');
		$this->load->model('logbookadvanced_model');
		$this->load->model('logbook_model');
		$this->load->model('bands');
		$this->load->model('iota');
		$this->load->model('dxcc');

		$stationIds = [];

		$deOptions = [];
		foreach ($this->stations->all_of_user()->result() as $station) {
			$deOptions[$station->station_callsign] = 1;
			$stationIds[] = $station->station_id;
		}
		ksort($deOptions);
		$deOptions = array_keys($deOptions);

		$data = [];
		$data['page_title'] = "Advanced logbook";
		$data['hasDatePicker'] = true;

		$pageData = [];
		$pageData['datePlaceholder'] = 'DD/MM/YYYY';
		$pageData['deOptions'] = $deOptions;
		$pageData['modes'] = $this->logbookadvanced_model->get_modes();
		$pageData['dxccarray'] = $this->logbook_model->fetchDxcc();
		$pageData['iotaarray'] = $this->logbook_model->fetchIota();
		$pageData['sats'] = $this->bands->get_worked_sats();

		$pageData['bands'] = $this->bands->get_worked_bands();

		$CI =& get_instance();
		// Get Date format
		if($CI->session->userdata('user_date_format')) {
			// If Logged in and session exists
			$pageData['custom_date_format'] = $CI->session->userdata('user_date_format');
		} else {
			// Get Default date format from /config/cloudlog.php
			$pageData['custom_date_format'] = $CI->config->item('qso_date_format');
		}

		switch ($pageData['custom_date_format']) {
			case "d/m/y": $pageData['custom_date_format'] = 'DD/MM/YY'; break;
			case "d/m/Y": $pageData['custom_date_format'] = 'DD/MM/YYYY'; break;
			case "m/d/y": $pageData['custom_date_format'] = 'MM/DD/YY'; break;
			case "m/d/Y": $pageData['custom_date_format'] = 'MM/DD/YYYY'; break;
			case "d.m.Y": $pageData['custom_date_format'] = 'DD.MM.YYYY'; break;
			case "y/m/d": $pageData['custom_date_format'] = 'YY/MM/DD'; break;
			case "Y-m-d": $pageData['custom_date_format'] = 'YYYY-MM-DD'; break;
			case "M d, Y": $pageData['custom_date_format'] = 'MMM DD, YYYY'; break;
			case "M d, y": $pageData['custom_date_format'] = 'MMM DD, YY'; break;
			default: $pageData['custom_date_format'] = 'DD/MM/YYYY';
		}

		$footerData = [];
		$footerData['scripts'] = [
			'assets/js/moment.min.js',
			'assets/js/tempusdominus-bootstrap-4.min.js',
			'assets/js/datetime-moment.js',
			'assets/js/sections/logbookadvanced.js?' . filemtime(realpath(__DIR__ . "/../../assets/js/sections/logbookadvanced.js"))
		];

		$this->load->view('interface_assets/header', $data);
		$this->load->view('logbookadvanced/index', $pageData);
		$this->load->view('interface_assets/footer', $footerData);
	}

	public function search() {
		$this->load->model('logbookadvanced_model');

		$searchCriteria = array(
			'user_id' => (int)$this->session->userdata('user_id'),
			'dateFrom' => xss_clean($this->input->post('dateFrom')),
			'dateTo' => xss_clean($this->input->post('dateTo')),
			'de' => xss_clean($this->input->post('de')),
			'dx' => xss_clean($this->input->post('dx')),
			'mode' => xss_clean($this->input->post('mode')),
			'band' => xss_clean($this->input->post('band')),
			'qslSent' => xss_clean($this->input->post('qslSent')),
			'qslReceived' => xss_clean($this->input->post('qslReceived')),
			'iota' => xss_clean($this->input->post('iota')),
			'dxcc' => xss_clean($this->input->post('dxcc')),
			'propmode' => xss_clean($this->input->post('propmode')),
			'gridsquare' => xss_clean($this->input->post('gridsquare')),
			'state' => xss_clean($this->input->post('state')),
			'cqzone' => xss_clean($this->input->post('cqzone')),
			'qsoresults' => xss_clean($this->input->post('qsoresults')),
			'sats' => xss_clean($this->input->post('sats')),
			'lotwSent' => xss_clean($this->input->post('lotwSent')),
			'lotwReceived' => xss_clean($this->input->post('lotwReceived')),
			'eqslSent' => xss_clean($this->input->post('eqslSent')),
			'eqslReceived' => xss_clean($this->input->post('eqslReceived')),
			'qslvia' => xss_clean($this->input->post('qslvia')),
			'sota' => xss_clean($this->input->post('sota')),
			'pota' => xss_clean($this->input->post('pota')),
			'wwff' => xss_clean($this->input->post('wwff')),
		);

		$qsos = [];
		foreach ($this->logbookadvanced_model->searchQsos($searchCriteria) as $qso) {
			$qsos[] = $qso->toArray();
		}

		header("Content-Type: application/json");
		print json_encode($qsos);
	}

	public function updateFromCallbook() {
		$this->load->model('logbook_model');
		$this->load->model('logbookadvanced_model');

		$qsoID = xss_clean($this->input->post('qsoID'));
		$qso = $this->logbook_model->qso_info($qsoID)->row_array();
		if ($qso === null) {
			header("Content-Type: application/json");
			echo json_encode([]);
			return;
		}

		$callbook = $this->logbook_model->loadCallBook($qso['COL_CALL'], $this->config->item('use_fullname'));

		if ($callbook['callsign'] !== "") {
			$this->logbookadvanced_model->updateQsoWithCallbookInfo($qsoID, $qso, $callbook);
			$qso['COL_NAME'] = trim($callbook['name']);
			if (isset($callbook['qslmgr'])) {
				$qso['COL_QSL_VIA'] = trim($callbook['qslmgr']);
			}
		}

		$qsoObj = new QSO($qso);

		header("Content-Type: application/json");
		echo json_encode($qsoObj->toArray());
	}

	function export_to_adif() {
		$this->load->model('logbookadvanced_model');

		$ids = xss_clean($this->input->post('id'));
		$sortorder = xss_clean($this->input->post('sortorder'));
		$user_id = (int)$this->session->userdata('user_id');

		$data['qsos'] = $this->logbookadvanced_model->getQsosForAdif($ids, $user_id, $sortorder);

		$this->load->view('adif/data/exportall', $data);
	}

	function update_qsl() {
		$this->load->model('logbookadvanced_model');

		$ids = xss_clean($this->input->post('id'));
		$user_id = (int)$this->session->userdata('user_id');
		$method = xss_clean($this->input->post('method'));
		$sent = xss_clean($this->input->post('sent'));

		$status = $this->logbookadvanced_model->updateQsl($ids, $user_id, $method, $sent);

		$data = $this->logbookadvanced_model->getQsosForAdif($ids, $user_id);

		$results = $data->result('array');

        $qsos = [];
        foreach ($results as $data) {
            $qsos[] = new QSO($data);
        }

		$q = [];
		foreach ($qsos as $qso) {
			$q[] = $qso->toArray();
		}

		header("Content-Type: application/json");
		print json_encode($q);
	}

	function update_qsl_received() {
		$this->load->model('logbookadvanced_model');

		$ids = xss_clean($this->input->post('id'));
		$user_id = (int)$this->session->userdata('user_id');
		$method = xss_clean($this->input->post('method'));
		$sent = xss_clean($this->input->post('sent'));

		$status = $this->logbookadvanced_model->updateQslReceived($ids, $user_id, $method, $sent);

		$data = $this->logbookadvanced_model->getQsosForAdif($ids, $user_id);

		$results = $data->result('array');

        $qsos = [];
        foreach ($results as $data) {
            $qsos[] = new QSO($data);
        }

		$q = [];
		foreach ($qsos as $qso) {
			$q[] = $qso->toArray();
		}

		header("Content-Type: application/json");
		print json_encode($q);
	}
}
