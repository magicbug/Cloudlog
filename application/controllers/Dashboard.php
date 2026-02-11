<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		
		// Load common models that are used across multiple methods
		$this->load->model('user_model');
		$this->load->model('logbook_model');
		$this->load->model('logbooks_model');
	}

	public function index()
	{
		// If environment is set to development then show the debug toolbar
		if (ENVIRONMENT == 'development') {
			$this->output->enable_profiler(TRUE);
		}

		// Load language files
		$this->lang->load('lotw');

		// LoTW infos
		$this->load->model('LotwCert');

		if ($this->optionslib->get_option('version2_trigger') == "false") {
			redirect('welcome');
		}

		// Check if users logged in

		if ($this->user_model->validate_session() == 0) {
			// user is not logged in
			redirect('user/login');
		}

		$logbooks_locations_array = $this->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		/*
			Setup Code
		// Check if the user has any logbook locations if not its setup time
		if (empty($logbooks_locations_array)) {
			// user has no locations
			$this->session->set_flashdata('notice', 'You have no locations, please add one to continue.');
			redirect('information/welcome');
		}
		*/

		// Calculate Lat/Lng from Locator to use on Maps
		if ($this->session->userdata('user_locator')) {
			$this->load->library('qra');

			$qra_position = $this->qra->qra2latlong($this->session->userdata('user_locator'));
			if ($qra_position) {
				$data['qra'] = "set";
				$data['qra_lat'] = $qra_position[0];
				$data['qra_lng'] = $qra_position[1];
			} else {
				$data['qra'] = "none";
			}
		} else {
			$data['qra'] = "none";
		}

		$this->load->model('stations');
		$this->load->model('setup_model');

		// Use consolidated setup counts instead of 3 separate queries
		$setup_counts = $this->setup_model->getAllSetupCounts();
		$data['countryCount'] = $setup_counts['country_count'];
		$data['logbookCount'] = $setup_counts['logbook_count'];
		$data['locationCount'] = $setup_counts['location_count'];

		$data['current_active'] = $this->stations->find_active();

		$setup_required = false;

		if ($setup_required) {
			$data['page_title'] = "Cloudlog Setup Checklist";

			$this->load->view('interface_assets/header', $data);
			$this->load->view('setup/check_list');
			$this->load->view('interface_assets/footer');
		} else {

			//
			$this->load->model('cat');
			$this->load->model('vucc');
			
			// Load cache driver for dashboard statistics caching (15 minutes)
			$this->load->driver('cache', array('adapter' => 'file'));

			$data['radio_status'] = $this->cat->recent_status();

			// Create cache key based on user and active logbook
			$cache_key = 'dashboard_stats_' . $this->session->userdata('user_id') . '_' . $this->session->userdata('active_station_logbook');
			$cache_ttl = 900; // 15 minutes
			
			// Try to get QSO statistics from cache
			$qso_stats = $this->cache->get($cache_key . '_qso');
			if (!$qso_stats) {
				// Cache miss - query database
				$qso_stats = $this->logbook_model->get_qso_statistics_consolidated($logbooks_locations_array);
				$this->cache->save($cache_key . '_qso', $qso_stats, $cache_ttl);
			}
			$data['todays_qsos'] = $qso_stats['todays_qsos'];
			$data['total_qsos'] = $qso_stats['total_qsos'];
			$data['month_qsos'] = $qso_stats['month_qsos'];
			$data['year_qsos'] = $qso_stats['year_qsos'];

			// Try to get countries statistics from cache
			$countries_stats = $this->cache->get($cache_key . '_countries');
			if (!$countries_stats) {
				// Cache miss - query database
				$countries_stats = $this->logbook_model->get_countries_statistics_consolidated($logbooks_locations_array);
				$this->cache->save($cache_key . '_countries', $countries_stats, $cache_ttl);
			}
			
			$data['total_countries'] = $countries_stats['Countries_Worked'];
			$data['total_countries_confirmed_paper'] = $countries_stats['Countries_Worked_QSL'];
			$data['total_countries_confirmed_eqsl'] = $countries_stats['Countries_Worked_EQSL'];
			$data['total_countries_confirmed_lotw'] = $countries_stats['Countries_Worked_LOTW'];
			$current_countries = $countries_stats['Countries_Current'];

			$data['dashboard_upcoming_dx_card'] = false;
			$data['dashboard_qslcard_card'] = false;
			$data['dashboard_eqslcard_card'] = false;
			$data['dashboard_lotw_card'] = false;
			$data['dashboard_vuccgrids_card'] = false;

			$dashboard_options = $this->user_options_model->get_options('dashboard')->result();

			// Optimize options processing - convert to associative array for O(1) lookup
			$options_map = array();
			foreach ($dashboard_options as $item) {
				$options_map[$item->option_name][$item->option_key] = $item->option_value;
			}

			// Quick lookups instead of nested loops
			$data['dashboard_upcoming_dx_card'] = isset($options_map['dashboard_upcoming_dx_card']['enabled']) && $options_map['dashboard_upcoming_dx_card']['enabled'] == 'true';
			$data['dashboard_qslcard_card'] = isset($options_map['dashboard_qslcards_card']['enabled']) && $options_map['dashboard_qslcards_card']['enabled'] == 'true';
			$data['dashboard_eqslcard_card'] = isset($options_map['dashboard_eqslcards_card']['enabled']) && $options_map['dashboard_eqslcards_card']['enabled'] == 'true';
			$data['dashboard_lotw_card'] = isset($options_map['dashboard_lotw_card']['enabled']) && $options_map['dashboard_lotw_card']['enabled'] == 'true';
			$data['dashboard_vuccgrids_card'] = isset($options_map['dashboard_vuccgrids_card']['enabled']) && $options_map['dashboard_vuccgrids_card']['enabled'] == 'true';

			// Only load VUCC data if the card is actually enabled
			if ($data['dashboard_vuccgrids_card']) {
				// Try to get VUCC data from cache
				$vucc_data = $this->cache->get($cache_key . '_vucc');
				if (!$vucc_data) {
					// Cache miss - query database
					$vucc_data = array(
						'vucc' => $this->vucc->fetchVuccSummary(),
						'vuccSAT' => $this->vucc->fetchVuccSummary('SAT')
					);
					$this->cache->save($cache_key . '_vucc', $vucc_data, $cache_ttl);
				}
				$data['vucc'] = $vucc_data['vucc'];
				$data['vuccSAT'] = $vucc_data['vuccSAT'];
			}

			// Try to get QSL statistics from cache
			$QSLStatsBreakdownArray = $this->cache->get($cache_key . '_qsl');
			if (!$QSLStatsBreakdownArray) {
				// Cache miss - query database
				$QSLStatsBreakdownArray = $this->logbook_model->get_QSLStats($logbooks_locations_array);
				$this->cache->save($cache_key . '_qsl', $QSLStatsBreakdownArray, $cache_ttl);
			}

			$data['total_qsl_sent'] = $QSLStatsBreakdownArray['QSL_Sent'];
			$data['total_qsl_rcvd'] = $QSLStatsBreakdownArray['QSL_Received'];
			$data['total_qsl_requested'] = $QSLStatsBreakdownArray['QSL_Requested'];
			$data['qsl_sent_today'] = $QSLStatsBreakdownArray['QSL_Sent_today'];
			$data['qsl_rcvd_today'] = $QSLStatsBreakdownArray['QSL_Received_today'];
			$data['qsl_requested_today'] = $QSLStatsBreakdownArray['QSL_Requested_today'];

			$data['total_eqsl_sent'] = $QSLStatsBreakdownArray['eQSL_Sent'];
			$data['total_eqsl_rcvd'] = $QSLStatsBreakdownArray['eQSL_Received'];
			$data['eqsl_sent_today'] = $QSLStatsBreakdownArray['eQSL_Sent_today'];
			$data['eqsl_rcvd_today'] = $QSLStatsBreakdownArray['eQSL_Received_today'];

			$data['total_lotw_sent'] = $QSLStatsBreakdownArray['LoTW_Sent'];
			$data['total_lotw_rcvd'] = $QSLStatsBreakdownArray['LoTW_Received'];
			$data['lotw_sent_today'] = $QSLStatsBreakdownArray['LoTW_Sent_today'];
			$data['lotw_rcvd_today'] = $QSLStatsBreakdownArray['LoTW_Received_today'];

			$data['total_qrz_sent'] = $QSLStatsBreakdownArray['QRZ_Sent'];
			$data['total_qrz_rcvd'] = $QSLStatsBreakdownArray['QRZ_Received'];
			$data['qrz_sent_today'] = $QSLStatsBreakdownArray['QRZ_Sent_today'];
			$data['qrz_rcvd_today'] = $QSLStatsBreakdownArray['QRZ_Received_today'];

			$data['last_five_qsos'] = $this->logbook_model->get_last_qsos('18', $logbooks_locations_array);

			$data['page_title'] = "Dashboard";

			// Optimize DXCC calculation - get count directly instead of loading all records
			$this->load->model('dxcc');
			$total_dxcc_count = $this->dxcc->get_total_dxcc_count();
			$data['total_countries_needed'] = $total_dxcc_count - $current_countries;

			$this->load->view('interface_assets/header', $data);
			$this->load->view('dashboard/index');
			$this->load->view('interface_assets/footer');
		}
	}

	public function todays_qso_component() {
		if ($this->user_model->validate_session() == 0) {
			// User is not logged in
			return;
		}

		$logbooks_locations_array = $this->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		// Use consolidated query instead of individual todays_qsos call
		$qso_stats = $this->logbook_model->get_qso_statistics_consolidated($logbooks_locations_array);
		$data['todays_qsos'] = $qso_stats['todays_qsos'];
		$this->load->view('components/dashboard_todays_qsos', $data);
	}

	public function logbook_display_component() {
		if ($this->user_model->validate_session() == 0) {
			// User is not logged in
			return;
		}

		// Get Logbook Locations
		$logbooks_locations_array = $this->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		// Get the last 20 QSOs
		$data['last_five_qsos'] = $this->logbook_model->get_last_qsos('20', $logbooks_locations_array);
		$this->load->view('components/dashboard_logbook_table', $data);
	}

	function radio_display_component()
	{
		$this->load->model('cat');

		$data['radio_status'] = $this->cat->recent_status();
		$this->load->view('components/radio_display_table', $data);
	}

	function upcoming_dxcc_component()
	{

		$this->load->model('Workabledxcc_model');

		$this->load->driver('cache', array('adapter' => 'file', 'backup' => 'file'));

		// Get the user ID from the session data
		$userID = $this->session->userdata('user_id');


		$thisWeekRecords = $this->Workabledxcc_model->GetThisWeek();


		$data['thisWeekRecords'] = $thisWeekRecords;

		// Only sort if we have valid records (not an error)
		if (!isset($thisWeekRecords['error']) && !empty($thisWeekRecords)) {
			usort($data['thisWeekRecords'], function ($a, $b) {
				$dateA = new DateTime($a['1']);
				$dateB = new DateTime($b['1']);
				return $dateA <=> $dateB;
			});
		}

		$this->load->view('components/upcoming_dxccs', $data);
	}
}
