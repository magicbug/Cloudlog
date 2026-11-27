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
		$this->load->helper('dashboard');
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
		$data['active_station_profile'] = ($data['current_active'] != 0) ? $this->stations->profile_clean($data['current_active']) : null;
		$active_logbook_id = $this->session->userdata('active_station_logbook');
		$data['active_logbook_name'] = !empty($active_logbook_id) ? $this->logbooks_model->find_name($active_logbook_id) : '';
		$data['clublog_cron_station_warning'] = false;
		$this->load->model('clublog_model');
		$user_id = $this->session->userdata('user_id');
		if (!empty($user_id)) {
			$data['clublog_cron_station_warning'] = $this->clublog_model->user_needs_clublogcron_station_warning($user_id);
		}

		$setup_required = false;

		if ($setup_required) {
			$data['page_title'] = "Cloudlog Setup Checklist";

			$this->load->view('interface_assets/header', $data);
			$this->load->view('setup/check_list');
			$this->load->view('interface_assets/footer');
		} else {

			$this->load->model('vucc');

			// Load cache driver for dashboard statistics caching (15 minutes)
			$this->load->driver('cache', array('adapter' => 'file'));

			// Create cache key based on user and active logbook
			$cache_key = 'dashboard_stats_' . $this->session->userdata('user_id') . '_' . $this->session->userdata('active_station_logbook');
			$cache_ttl = 900; // 15 minutes
			
			// Try to get QSO statistics from cache
			$qso_stats = $this->cache->get($cache_key . '_qso');
			if (!$qso_stats || !isset($qso_stats['prev_month_mtd_qsos'])) {
				// Cache miss - query database
				$qso_stats = $this->logbook_model->get_qso_statistics_consolidated($logbooks_locations_array);
				$this->cache->save($cache_key . '_qso', $qso_stats, $cache_ttl);
			}
			$data['todays_qsos'] = $qso_stats['todays_qsos'];
			$data['total_qsos'] = $qso_stats['total_qsos'];
			$data['month_qsos'] = $qso_stats['month_qsos'];
			$data['prev_month_mtd_qsos'] = $qso_stats['prev_month_mtd_qsos'];
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
			$data['dashboard_hamsat_card'] = false;
			$data['dashboard_map_greyline'] = true;

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
			$data['dashboard_hamsat_card'] = isset($options_map['dashboard_hamsat_card']['enabled']) && $options_map['dashboard_hamsat_card']['enabled'] == 'true';
			$data['dashboard_map_greyline'] = !isset($options_map['dashboard_map_greyline']['enabled']) || $options_map['dashboard_map_greyline']['enabled'] == 'true';

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

			$data['page_title'] = "Dashboard";

			// Optimize DXCC calculation - get count directly instead of loading all records
			$this->load->model('dxcc');
			$total_dxcc_count = $this->dxcc->get_total_dxcc_count();
			$data['total_dxcc_count'] = $total_dxcc_count;
			$data['current_countries'] = $current_countries;
			$data['total_countries_needed'] = max(0, $total_dxcc_count - $current_countries);

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

		$this->load->library('user_agent');

		// Get Logbook Locations
		$logbooks_locations_array = $this->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		// Show fewer rows on mobile to reduce dashboard noise.
		$recent_qso_limit = $this->agent->is_mobile() ? '10' : '20';
		$data['last_five_qsos'] = $this->logbook_model->get_last_qsos($recent_qso_limit, $logbooks_locations_array);
		$this->load->view('components/dashboard_logbook_table', $data);
	}

	function radio_display_component()
	{
		if ($this->user_model->validate_session() == 0) {
			return;
		}

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

	function hamsat_component()
	{
		if ($this->user_model->validate_session() == 0) {
			return;
		}

		$this->load->model('user_options_model');
		$this->load->model('logbooks_model');
		$this->load->model('logbook_model');
		$this->load->driver('cache', array('adapter' => 'file', 'backup' => 'file'));

		$userID = (int) $this->session->userdata('user_id');

		$hkey_opt = $this->user_options_model->get_options('hamsat', array('option_name' => 'hamsat_key', 'option_key' => 'api'))->result();
		$user_hamsat_key = (count($hkey_opt) > 0) ? trim((string) $hkey_opt[0]->option_value) : '';

		$workable_opt = $this->user_options_model->get_options('hamsat', array('option_name' => 'hamsat_key', 'option_key' => 'workable'))->result();
		$user_hamsat_workable_only = (count($workable_opt) > 0) ? (int) $workable_opt[0]->option_value : 0;

		$cache_key = 'dashboard_hamsat_' . $userID . '_' . md5($user_hamsat_key);
		$rovedata = $this->cache->get($cache_key);
		$error = false;
		$error_message = '';

		if ($rovedata === false) {
			$url = 'https://hams.at/api/alerts/upcoming';
			$http_opts = array(
				'method' => 'GET',
				'timeout' => 6
			);
			if ($user_hamsat_key !== '') {
				$http_opts['header'] = "Authorization: Bearer " . $user_hamsat_key . "\r\n";
			}
			$json = @file_get_contents($url, false, stream_context_create(array('http' => $http_opts)));

			if ($json === false) {
				$error = true;
				$error_message = 'Unable to connect to hams.at right now.';
				$rovedata = array('data' => array());
			} else {
				$decoded = json_decode($json, true);
				if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
					$error = true;
					$error_message = 'Invalid response from hams.at.';
					$rovedata = array('data' => array());
				} else {
					$rovedata = $decoded;
					$this->cache->save($cache_key, $rovedata, 120);
				}
			}
		}

		$logbooks_locations_array = $this->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));
		$grid_cache = array();
		$passes = array();
		$now_ts = time();

		if (!$error && isset($rovedata['data']) && is_array($rovedata['data'])) {
			foreach ($rovedata['data'] as $rove) {
				$aos_ts = !empty($rove['aos_at']) ? strtotime($rove['aos_at']) : 0;
				$los_ts = !empty($rove['los_at']) ? strtotime($rove['los_at']) : 0;
				if ($los_ts > 0 && $los_ts < $now_ts) {
					continue;
				}

				$grids_out = array();
				$has_needed = false;
				$raw_grids = (isset($rove['grids']) && is_array($rove['grids'])) ? $rove['grids'] : array();
				foreach ($raw_grids as $g) {
					$g4 = strtoupper(substr(trim((string) $g), 0, 4));
					if ($g4 === '') continue;
					if (!array_key_exists($g4, $grid_cache)) {
						$grid_cache[$g4] = ($this->logbook_model->check_if_grid_worked_in_logbook($g4, $logbooks_locations_array, "SAT") != 0);
					}
					$worked = $grid_cache[$g4];
					if (!$worked) {
						$has_needed = true;
					}
					$grids_out[] = array('grid' => $g4, 'worked' => $worked);
				}

				$is_live = ($aos_ts > 0 && $los_ts > 0 && $now_ts >= $aos_ts && $now_ts <= $los_ts);
				if ($is_live) {
					$mins_left = max(1, (int) ceil(($los_ts - $now_ts) / 60));
					$time_label = 'LIVE · ' . $mins_left . 'm';
				} elseif ($aos_ts > $now_ts) {
					$diff = $aos_ts - $now_ts;
					if ($diff < 3600) {
						$time_label = 'In ' . max(1, (int) floor($diff / 60)) . 'm';
					} elseif ($diff < 86400) {
						$time_label = 'In ' . (int) floor($diff / 3600) . 'h';
					} else {
						$time_label = date('M j', $aos_ts);
					}
				} else {
					$time_label = !empty($rove['aos_at']) ? date('H:i', $aos_ts) : '—';
				}

				$passes[] = array(
					'callsign' => isset($rove['callsign']) ? $rove['callsign'] : '',
					'url' => isset($rove['url']) ? $rove['url'] : 'https://hams.at/',
					'sat' => isset($rove['satellite']['name']) ? $rove['satellite']['name'] : '',
					'mode' => isset($rove['mode']) ? $rove['mode'] : '',
					'comment' => isset($rove['comment']) ? $rove['comment'] : '',
					'grids' => $grids_out,
					'has_needed' => $has_needed,
					'is_live' => $is_live,
					'is_workable' => !empty($rove['is_workable']),
					'time_label' => $time_label,
					'aos_utc' => $aos_ts ? date('H:i', $aos_ts) . ' UTC' : ''
				);
			}
		}

		$data['error'] = $error;
		$data['error_message'] = $error_message;
		$data['passes'] = $passes;
		$data['has_key'] = ($user_hamsat_key !== '');
		$data['workable_only_default'] = ($user_hamsat_workable_only == 1 && $user_hamsat_key !== '');

		$this->load->view('components/hamsat_dashboard', $data);
	}
}
