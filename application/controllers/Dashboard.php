<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function index()
	{
		// If environment is set to development then show the debug toolbar
		if(ENVIRONMENT == 'development') {
            $this->output->enable_profiler(TRUE);
        }

		// Load language files
		$this->lang->load('lotw');

		// Database connections
		$this->load->model('logbook_model');
		$this->load->model('user_model');

		// LoTW infos
		$this->load->model('LotwCert');

		if($this->optionslib->get_option('version2_trigger') == "false") {
			redirect('welcome');
		}

		// Check if users logged in

		if($this->user_model->validate_session() == 0) {
			// user is not logged in
			redirect('user/login');
		}

		$this->load->model('logbooks_model');
		$logbooks_locations_array = $this->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		// Calculate Lat/Lng from Locator to use on Maps
		if($this->session->userdata('user_locator')) {
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

		$data['countryCount'] = $this->setup_model->getCountryCount();
		$data['logbookCount'] = $this->setup_model->getLogbookCount();
		$data['locationCount'] = $this->setup_model->getLocationCount();

		$data['current_active'] = $this->stations->find_active();

		$setup_required = false;

		if($setup_required) {
			$data['page_title'] = "Cloudlog Setup Checklist";

			$this->load->view('interface_assets/header', $data);
			$this->load->view('setup/check_list');
			$this->load->view('interface_assets/footer');
		} else {

			//
			$this->load->model('cat');
			$this->load->model('vucc');

			$data['radio_status'] = $this->cat->recent_status();

			// Store info
			$data['todays_qsos'] = $this->logbook_model->todays_qsos($logbooks_locations_array);
			$data['total_qsos'] = $this->logbook_model->total_qsos($logbooks_locations_array);
			$data['month_qsos'] = $this->logbook_model->month_qsos($logbooks_locations_array);
			$data['year_qsos'] = $this->logbook_model->year_qsos($logbooks_locations_array);

			// Load  Countries Breakdown data into array
			$CountriesBreakdown = $this->logbook_model->total_countries_confirmed($logbooks_locations_array);

			$data['total_countries'] = $CountriesBreakdown['Countries_Worked'];
			$data['total_countries_confirmed_paper'] = $CountriesBreakdown['Countries_Worked_QSL'];
			$data['total_countries_confirmed_eqsl'] = $CountriesBreakdown['Countries_Worked_EQSL'];
			$data['total_countries_confirmed_lotw'] = $CountriesBreakdown['Countries_Worked_LOTW'];

			$QSLStatsBreakdownArray =$this->logbook_model->get_QSLStats($logbooks_locations_array);

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

			$data['vucc'] = $this->vucc->fetchVuccSummary();
			$data['vuccSAT'] = $this->vucc->fetchVuccSummary('SAT');

			$data['page_title'] = "Dashboard";

			$this->load->model('dxcc');
			$dxcc = $this->dxcc->list_current();

			$current = $this->logbook_model->total_countries_current($logbooks_locations_array);

			$data['total_countries_needed'] = count($dxcc->result()) - $current;

			$this->load->view('interface_assets/header', $data);
			$this->load->view('dashboard/index');
			$this->load->view('interface_assets/footer');
		}

	}

	function radio_display_component() {
		$this->load->model('cat');

		$data['radio_status'] = $this->cat->recent_status();
		$this->load->view('components/radio_display_table', $data);
	}


	}
