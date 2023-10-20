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
		$this->load->model('user_options_model');

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

		$userOptions = $this->user_options_model->get_options('LogbookAdvanced')->result();
		if (isset($userOptions[0])) {
			$data['options'] = $userOptions[0]->option_value;
		}

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
			'qslSentMethod' => xss_clean($this->input->post('qslSentMethod')),
			'qslReceivedMethod' => xss_clean($this->input->post('qslReceivedMethod')),
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
			'qslimages' => xss_clean($this->input->post('qslimages')),
			'dupes' => xss_clean($this->input->post('dupes')),
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
		$qso = $this->qso_info($qsoID)->row_array();
		if ($qso === null) {
			header("Content-Type: application/json");
			echo json_encode([]);
			return;
		}

		$callbook = $this->logbook_model->loadCallBook($qso['COL_CALL'], $this->config->item('use_fullname'));

		if ($callbook['callsign'] ?? "" !== "") {
			$this->logbookadvanced_model->updateQsoWithCallbookInfo($qsoID, $qso, $callbook);
			$qso = $this->qso_info($qsoID)->row_array();
		}

		$qsoObj = new QSO($qso);

		header("Content-Type: application/json");
		echo json_encode($qsoObj->toArray());
	}

	  /* Return QSO Info */
	  function qso_info($id) {
		$this->load->model('logbook_model');
		if ($this->logbook_model->check_qso_is_accessible($id)) {
			$this->db->where('COL_PRIMARY_KEY', $id);
			$this->db->join('station_profile', 'station_profile.station_id = '.$this->config->item('table_name').'.station_id');
    		$this->db->join('dxcc_entities', $this->config->item('table_name').'.col_dxcc = dxcc_entities.adif', 'left');
    		$this->db->join('lotw_users', 'lotw_users.callsign = '.$this->config->item('table_name').'.col_call', 'left outer');

			return $this->db->get($this->config->item('table_name'));
		} else {
			return;
		}
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

	public function startAtLabel() {
		$this->load->view('logbookadvanced/startatform');
	}

	public function qslSlideshow() {
		$cleanids = $this->security->xss_clean($this->input->post('ids'));
        $this->load->model('logbookadvanced_model');
        $data['qslimages'] = $this->logbookadvanced_model->getQslsForQsoIds($cleanids);
        $this->load->view('logbookadvanced/qslcarousel', $data);
	}

	public function mapSelectedQsos() {
		$this->load->model('logbookadvanced_model');

		$searchCriteria = array(
			'user_id' => (int)$this->session->userdata('user_id'),
			'dateFrom' => '',
			'dateTo' => '',
			'de' => '',
			'dx' => '',
			'mode' => '',
			'band' => '',
			'qslSent' => '',
			'qslReceived' => '',
			'qslSentMethod' => '',
			'qslReceivedMethod' => '',
			'iota' => '',
			'dxcc' => '',
			'propmode' => '',
			'gridsquare' => '',
			'state' => '',
			'cqzone' => '',
			'qsoresults' => count($this->input->post('ids')),
			'sats' => '',
			'lotwSent' => '',
			'lotwReceived' => '',
			'eqslSent' => '',
			'eqslReceived' => '',
			'qslvia' => '',
			'sota' => '',
			'pota' => '',
			'wwff' => '',
			'qslimages' => '',
			'ids' => xss_clean($this->input->post('ids'))
		);

		$result = $this->logbookadvanced_model->searchDb($searchCriteria);
		$this->prepareMappedQSos($result);
	}

	public function mapQsos() {
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
			'qslSentMethod' => xss_clean($this->input->post('qslSentMethod')),
			'qslReceivedMethod' => xss_clean($this->input->post('qslReceivedMethod')),
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
			'qslimages' => xss_clean($this->input->post('qslimages')),
		);

		$result = $this->logbookadvanced_model->searchDb($searchCriteria);
		$this->prepareMappedQSos($result);
	}

	public function prepareMappedQSos($qsos) {
		if ($this->session->userdata('user_measurement_base') == NULL) {
			$measurement_base = $this->config->item('measurement_base');
		}
		else {
			$measurement_base = $this->session->userdata('user_measurement_base');
		}

		$CI =& get_instance();
		// Get Date format
		if($CI->session->userdata('user_date_format')) {
			// If Logged in and session exists
			$custom_date_format = $CI->session->userdata('user_date_format');
		} else {
			// Get Default date format from /config/cloudlog.php
			$custom_date_format = $CI->config->item('qso_date_format');
		}

		switch ($measurement_base) {
			case 'M':
				$var_dist = " miles";
				break;
			case 'N':
				$var_dist = " nautic miles";
				break;
			case 'K':
				$var_dist = " kilometers";
				break;
		}

		$mappedcoordinates = array();
		foreach ($qsos as $qso) {
			if (!empty($qso['COL_MY_GRIDSQUARE']) || !empty($qso['COL_MY_VUCC_GRIDS'])) {
				if (!empty($qso['COL_GRIDSQUARE'])  || !empty($qso['COL_VUCC_GRIDS'])) {
					$mappedcoordinates[] = $this->calculate($qso, ($qso['COL_MY_GRIDSQUARE'] ?? '') == '' ? $qso['COL_MY_VUCC_GRIDS'] : $qso['COL_MY_GRIDSQUARE'], ($qso['COL_GRIDSQUARE'] ?? '') == '' ? $qso['COL_VUCC_GRIDS'] : $qso['COL_GRIDSQUARE'], $measurement_base, $var_dist, $custom_date_format);
				} else {
					if (!empty($qso['lat'])  || !empty($qso['long'])) {
						$mappedcoordinates[] = $this->calculateCoordinates($qso, $qso['lat'], $qso['long'], ($qso['COL_MY_GRIDSQUARE'] ?? '') == '' ? $qso['COL_MY_VUCC_GRIDS'] : $qso['COL_MY_GRIDSQUARE'], $measurement_base, $var_dist, $custom_date_format);
					}
				}
			}
		}

		header("Content-Type: application/json");
		print json_encode($mappedcoordinates);
	}

	public function calculate($qso, $locator1, $locator2, $measurement_base, $var_dist, $custom_date_format) {
		$this->load->library('Qra');

		$data['distance'] = $this->qra->distance($locator1, $locator2, $measurement_base) . $var_dist;
		$data['bearing'] = $this->qra->get_bearing($locator1, $locator2) . "&#186;";
		$latlng1 = $this->qra->qra2latlong($locator1);
		$latlng2 = $this->qra->qra2latlong($locator2);
		$latlng1[0] = number_format((float)$latlng1[0], 3, '.', '');;
		$latlng1[1] = number_format((float)$latlng1[1], 3, '.', '');;
		$latlng2[0] = number_format((float)$latlng2[0], 3, '.', '');;
		$latlng2[1] = number_format((float)$latlng2[1], 3, '.', '');;

		$data['latlng1'] = $latlng1;
		$data['latlng2'] = $latlng2;

		$data['callsign'] = $qso['COL_CALL'];
		$data['band'] = $qso['COL_BAND'];
		$data['mode'] = $qso['COL_MODE'];
		$data['gridsquare'] = $locator2;
		$data['mygridsquare'] = $locator1;
		$data['mycallsign'] = $qso['station_callsign'];
		$data['datetime'] = date($custom_date_format, strtotime($qso['COL_TIME_ON'])). date(' H:i',strtotime($qso['COL_TIME_ON']));
		$data['satname'] = $qso['COL_SAT_NAME'];

		return $data;
	}

	public function calculateCoordinates($qso, $lat, $long, $mygrid, $measurement_base, $var_dist, $custom_date_format) {
		$this->load->library('Qra');

		$latlng1 = $this->qra->qra2latlong($mygrid);
		$latlng2[0] = $lat;
		$latlng2[1] = $long;
		$latlng1[0] = number_format((float)$latlng1[0], 3, '.', '');;
		$latlng1[1] = number_format((float)$latlng1[1], 3, '.', '');;
		$latlng2[0] = number_format((float)$latlng2[0], 3, '.', '');;
		$latlng2[1] = number_format((float)$latlng2[1], 3, '.', '');;

		$data['latlng1'] = $latlng1;
		$data['latlng2'] = $latlng2;
		$data['callsign'] = $qso['COL_CALL'];
		$data['band'] = $qso['COL_BAND'];
		$data['mode'] = $qso['COL_MODE'];
		$data['mygridsquare'] = $mygrid;
		$data['mycallsign'] = $qso['station_callsign'];
		$data['datetime'] = date($custom_date_format, strtotime($qso['COL_TIME_ON'])). date(' H:i',strtotime($qso['COL_TIME_ON']));
		$data['satname'] = $qso['COL_SAT_NAME'];

		return $data;
	}

	public function userOptions() {
		$this->load->model('user_options_model');
		$userOptions = $this->user_options_model->get_options('LogbookAdvanced')->result();
		if (isset($userOptions[0])) {
			$data['options'] = $options = json_decode($userOptions[0]->option_value);
		} else {
			$data['options'] = null;
		}
		$this->load->view('logbookadvanced/useroptions', $data);
	}

	public function setUserOptions() {
		$json_string['datetime']['show'] = $this->input->post('datetime');
		$json_string['de']['show'] = $this->input->post('de');
		$json_string['dx']['show'] = $this->input->post('dx');
		$json_string['mode']['show'] = $this->input->post('mode');
		$json_string['rstr']['show'] = $this->input->post('rstr');
		$json_string['rsts']['show'] = $this->input->post('rsts');
		$json_string['band']['show'] = $this->input->post('band');
		$json_string['myrefs']['show'] = $this->input->post('myrefs');
		$json_string['refs']['show'] = $this->input->post('refs');
		$json_string['name']['show'] = $this->input->post('name');
		$json_string['qslvia']['show'] = $this->input->post('qslvia');
		$json_string['qsl']['show'] = $this->input->post('qsl');
		$json_string['lotw']['show'] = $this->input->post('lotw');
		$json_string['eqsl']['show'] = $this->input->post('eqsl');
		$json_string['qslmsg']['show'] = $this->input->post('qslmsg');
		$json_string['dxcc']['show'] = $this->input->post('dxcc');
		$json_string['state']['show'] = $this->input->post('state');
		$json_string['cqzone']['show'] = $this->input->post('cqzone');
		$json_string['iota']['show'] = $this->input->post('iota');

		$obj['column_settings']= json_encode($json_string);

		$this->load->model('user_options_model');
		$this->user_options_model->set_option('LogbookAdvanced', 'LogbookAdvanced', $obj);
	}
}
