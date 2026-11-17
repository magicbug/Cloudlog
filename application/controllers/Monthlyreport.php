<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
	Controller for generating monthly reports of amateur radio activity
*/

class Monthlyreport extends CI_Controller {

	const EMAIL_SEND_LAST_DAY = 7;
	const EMAIL_BATCH_SIZE = 20;

	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));

		// Cron entry point: runs without a session and only emails opted-in users.
		if ($this->router->method === 'send_scheduled') {
			return;
		}

		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { 
			$this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); 
			redirect('dashboard'); 
		}
	}

	/**
	 * Index page - display report selection form
	 */
	public function index() 
	{
		$this->load->model('logbooks_model');
		$this->load->model('monthlyreport_email_model');
		
		$data['logbooks'] = $this->logbooks_model->show_all();
		$data['page_title'] = "Monthly Report";
		$data['email_configured'] = $this->monthlyreport_email_model->email_configured();
		$data['email_pref'] = $this->monthlyreport_email_model->get_preference($this->session->userdata('user_id'));
		$data['user_email'] = $this->user_model->get_user_email_by_id($this->session->userdata('user_id'));
		$last_month = $this->previous_month();
		$data['last_month_label'] = date('F Y', mktime(0, 0, 0, $last_month['month'], 1, $last_month['year']));
		
		$this->load->view('interface_assets/header', $data);
		$this->load->view('monthlyreport/index');
		$this->load->view('interface_assets/footer');
	}

	/**
	 * Generate report for selected logbook and month
	 */
	public function generate()
	{
		$this->load->model('monthlyreport_model');
		$this->load->model('logbooks_model');
		
		// Get parameters from POST
		$logbook_id = $this->input->post('logbook_id');
		$year = $this->input->post('year');
		$month = $this->input->post('month');
		
		// Validate inputs
		if (empty($logbook_id) || empty($year) || empty($month)) {
			$this->session->set_flashdata('notice', 'Please select a logbook and month');
			redirect('monthlyreport');
		}
		
		// Get logbook relationships (station locations)
		$logbooks_locations_array = $this->logbooks_model->list_logbook_relationships($logbook_id);
		
		if (!$logbooks_locations_array) {
			$this->session->set_flashdata('notice', 'No station locations found for this logbook');
			redirect('monthlyreport');
		}
		
		// Generate report data
		$data['report'] = $this->monthlyreport_model->generate_report($logbooks_locations_array, $year, $month);
		$data['logbook_name'] = $this->logbooks_model->find_name($logbook_id);
		$data['year'] = $year;
		$data['month'] = $month;
		$data['month_name'] = date('F', mktime(0, 0, 0, $month, 1));
		$data['page_title'] = "Monthly Report - " . $data['month_name'] . " " . $year;
		
		$this->load->view('interface_assets/header', $data);
		$this->load->view('monthlyreport/report');
		$this->load->view('interface_assets/footer');
	}

	/**
	 * Save the monthly report email opt-in and logbook
	 */
	public function save_email_preference()
	{
		$this->load->model('monthlyreport_email_model');

		$user_id = $this->session->userdata('user_id');
		$enabled = $this->input->post('email_enabled') == '1';
		$logbook_id = (int) $this->input->post('email_logbook_id');

		if (!$this->monthlyreport_email_model->user_can_access_logbook($user_id, $logbook_id)) {
			$this->session->set_flashdata('notice', 'Please choose one of your logbooks for the monthly email');
			redirect('monthlyreport');
		}

		$this->monthlyreport_email_model->save_preference($user_id, $enabled, $logbook_id);
		$this->session->set_flashdata('success', $enabled
			? 'Monthly report email turned on for ' . htmlspecialchars($this->monthlyreport_email_model->logbook_name($logbook_id))
			: 'Monthly report email turned off');
		redirect('monthlyreport');
	}

	/**
	 * Email last month's report now; not recorded in the send log
	 */
	public function send_preview()
	{
		$this->load->model('monthlyreport_email_model');

		$user_id = $this->session->userdata('user_id');
		$logbook_id = (int) $this->input->post('email_logbook_id');

		if (!$this->monthlyreport_email_model->email_configured()) {
			$this->session->set_flashdata('notice', 'Outbound email is not configured on this Cloudlog');
			redirect('monthlyreport');
		}

		if (!$this->monthlyreport_email_model->user_can_access_logbook($user_id, $logbook_id)) {
			$this->session->set_flashdata('notice', 'Please choose one of your logbooks for the monthly email');
			redirect('monthlyreport');
		}

		$user = $this->user_model->get_by_id($user_id)->row();
		if (!$user || $user->user_email == '') {
			$this->session->set_flashdata('notice', 'Your account has no email address');
			redirect('monthlyreport');
		}

		$period = $this->previous_month();
		$label = date('F Y', mktime(0, 0, 0, $period['month'], 1, $period['year']));
		$result = $this->monthlyreport_email_model->send_report($user, $logbook_id, $period['year'], $period['month']);

		if ($result['status'] === 'sent') {
			$this->session->set_flashdata('success', 'The ' . $label . ' report was emailed to ' . $user->user_email);
		} elseif ($result['status'] === 'skipped_empty') {
			$this->session->set_flashdata('notice', 'No QSOs in ' . $label . ' for that logbook, so there was nothing to email. The scheduled email skips empty months too.');
		} else {
			$this->session->set_flashdata('notice', 'The email could not be sent. Check Admin > Options > Email, or the Cloudlog log for details.');
		}
		redirect('monthlyreport');
	}

	/**
	 * Daily cron job: in the first week of each month (UTC), email last month's report
	 * to opted-in users who have not had it yet.
	 */
	public function send_scheduled()
	{
		$this->load->library('cron_runner');
		$this->cron_runner->run_guarded('monthly_report_email', function () {
			$this->load->model('monthlyreport_email_model');

			if ((int) gmdate('j') > self::EMAIL_SEND_LAST_DAY) {
				echo 'Monthly report emails are only sent on days 1-' . self::EMAIL_SEND_LAST_DAY . ' of the month.' . "\n";
				return;
			}

			if (!$this->monthlyreport_email_model->email_configured()) {
				echo 'Email is not configured; no monthly reports sent.' . "\n";
				log_message('info', 'Monthly report email: email not configured');
				return;
			}

			$period = $this->previous_month();
			$period_key = sprintf('%04d-%02d', $period['year'], $period['month']);
			$users = $this->monthlyreport_email_model->get_pending_users($period_key, self::EMAIL_BATCH_SIZE);

			$counts = array('sent' => 0, 'skipped_empty' => 0, 'skipped_inaccessible' => 0, 'error' => 0);
			foreach ($users as $user) {
				$logbook_id = $user->logbook_id !== null && $user->logbook_id !== '' ? (int) $user->logbook_id : null;

				if ($logbook_id === null || !$this->monthlyreport_email_model->user_can_access_logbook($user->user_id, $logbook_id)) {
					$result = array('status' => 'skipped_inaccessible', 'error' => null);
				} else {
					try {
						$result = $this->monthlyreport_email_model->send_report($user, $logbook_id, $period['year'], $period['month']);
					} catch (Throwable $e) {
						log_message('error', 'Monthly report email to user ' . $user->user_id . ': ' . $e->getMessage());
						$result = array('status' => 'error', 'error' => $e->getMessage());
					}
				}

				$this->monthlyreport_email_model->log_result($user->user_id, $period_key, $logbook_id, $result['status'], $result['error']);
				$counts[$result['status']]++;
			}

			$summary = 'Monthly report emails for ' . $period_key . ': sent ' . $counts['sent']
				. ', empty ' . $counts['skipped_empty']
				. ', no logbook ' . $counts['skipped_inaccessible']
				. ', failed ' . $counts['error'];
			echo $summary . "\n";
			log_message('info', $summary);
		});
	}

	/**
	 * @return array year, month of the previous calendar month (UTC)
	 */
	private function previous_month()
	{
		$first_of_this_month = gmmktime(0, 0, 0, (int) gmdate('n'), 1, (int) gmdate('Y'));
		$last_month = $first_of_this_month - 86400;
		return array('year' => (int) gmdate('Y', $last_month), 'month' => (int) gmdate('n', $last_month));
	}

	/**
	 * Export report in AI-friendly format (JSON)
	 */
	public function export_json()
	{
		$this->load->model('monthlyreport_model');
		$this->load->model('logbooks_model');
		
		// Get parameters from POST
		$logbook_id = $this->input->post('logbook_id');
		$year = $this->input->post('year');
		$month = $this->input->post('month');
		
		// Get logbook relationships
		$logbooks_locations_array = $this->logbooks_model->list_logbook_relationships($logbook_id);
		
		// Generate report data
		$report = $this->monthlyreport_model->generate_report($logbooks_locations_array, $year, $month);
		$logbook_name = $this->logbooks_model->find_name($logbook_id);
		$month_name = date('F', mktime(0, 0, 0, $month, 1));
		
		// Create AI-friendly export
		$export = array(
			'metadata' => array(
				'logbook' => $logbook_name,
				'period' => $month_name . ' ' . $year,
				'generated_at' => date('Y-m-d H:i:s'),
				'report_type' => 'monthly_amateur_radio_activity'
			),
			'summary' => array(
				'total_qsos' => $report['total_qsos'],
				'new_dxcc_entities' => count($report['new_dxcc']),
				'new_dxcc_by_band_count' => count($report['new_dxcc_by_band']),
				'new_dxcc_satellite' => count($report['new_dxcc_satellite']),
				'new_dxcc_eme' => count($report['new_dxcc_eme']),
				'new_gridsquares' => count($report['new_grids']),
				'new_gridsquares_hf' => count($report['new_grids_hf']),
				'new_gridsquares_satellite' => count($report['new_grids_satellite']),
				'new_gridsquares_eme' => count($report['new_grids_eme']),
				'unique_callsigns' => $report['unique_callsigns'],
				'countries_worked' => count($report['countries_worked'])
			),
			'details' => array(
				'new_countries' => $report['new_dxcc'],
				'new_countries_by_band' => $report['new_dxcc_by_band'],
				'new_countries_satellite' => $report['new_dxcc_satellite'],
				'new_countries_eme' => $report['new_dxcc_eme'],
				'new_gridsquares' => $report['new_grids'],
				'new_gridsquares_by_band' => $report['new_grids_by_band'],
				'new_gridsquares_hf' => $report['new_grids_hf'],
				'new_gridsquares_satellite' => $report['new_grids_satellite'],
				'new_gridsquares_eme' => $report['new_grids_eme'],
				'callsigns_worked' => $report['callsign_list'],
				'modes_used' => $report['modes'],
				'bands_used' => $report['bands'],
				'continents_worked' => $report['continents'],
				'satellite_qsos' => $report['satellite_qsos'],
				'eme_qsos' => $report['eme_qsos'],
				'satellite_breakdown' => $report['satellite_breakdown']
			),
			'statistics' => array(
				'qsos_per_day' => round($report['total_qsos'] / date('t', strtotime("$year-$month-01")), 2),
				'most_active_band' => $report['top_band'],
				'most_used_mode' => $report['top_mode']
			),
			'ai_prompt_suggestion' => "Write an engaging amateur radio blog post about my " . $month_name . " " . $year . " operating activity. I made " . $report['total_qsos'] . " contacts" . 
				(count($report['new_dxcc']) > 0 ? ", worked " . count($report['new_dxcc']) . " new countries" : "") .
				(count($report['new_grids']) > 0 ? ", and confirmed " . count($report['new_grids']) . " new gridsquares" : "") . 
				". Focus on the highlights and make it sound exciting for fellow radio enthusiasts."
		);
		
		// Set headers for JSON download
		$filename = "monthly_report_{$year}_{$month}.json";
		$this->output
			->set_content_type('application/json')
			->set_header('Content-Disposition: attachment; filename="' . $filename . '"')
			->set_output(json_encode($export, JSON_PRETTY_PRINT));
	}

	/**
	 * Export report as plain text for AI consumption
	 */
	public function export_text()
	{
		$this->load->model('monthlyreport_model');
		$this->load->model('logbooks_model');
		
		// Get parameters from POST
		$logbook_id = $this->input->post('logbook_id');
		$year = $this->input->post('year');
		$month = $this->input->post('month');
		
		// Get logbook relationships
		$logbooks_locations_array = $this->logbooks_model->list_logbook_relationships($logbook_id);
		
		// Generate report data
		$report = $this->monthlyreport_model->generate_report($logbooks_locations_array, $year, $month);
		$logbook_name = $this->logbooks_model->find_name($logbook_id);
		$month_name = date('F', mktime(0, 0, 0, $month, 1));
		
		// Create plain text report
		$text = "AMATEUR RADIO MONTHLY ACTIVITY REPORT\n";
		$text .= "========================================\n\n";
		$text .= "Logbook: {$logbook_name}\n";
		$text .= "Period: {$month_name} {$year}\n";
		$text .= "Generated: " . date('Y-m-d H:i:s') . "\n\n";
		
		$text .= "SUMMARY\n";
		$text .= "-------\n";
		$text .= "Total QSOs: {$report['total_qsos']}\n";
		$text .= "Unique Callsigns Worked: {$report['unique_callsigns']}\n";
		$text .= "New DXCC Entities: " . count($report['new_dxcc']) . "\n";
		$text .= "New Gridsquares: " . count($report['new_grids']) . "\n";
		$text .= "Countries Worked: " . count($report['countries_worked']) . "\n\n";
		
		if (count($report['new_dxcc']) > 0) {
			$text .= "NEW COUNTRIES THIS MONTH - OVERALL\n";
			$text .= "----------------------------------\n";
			foreach ($report['new_dxcc'] as $dxcc) {
				$text .= "- {$dxcc['name']}\n";
			}
			$text .= "\n";
		}
		
		if (!empty($report['new_dxcc_by_band'])) {
			$text .= "NEW COUNTRIES BY BAND\n";
			$text .= "---------------------\n";
			// Bands already sorted in model
			foreach ($report['new_dxcc_by_band'] as $band => $dxcc_list) {
				$text .= "\n{$band} (" . count($dxcc_list) . " new):\n";
				foreach ($dxcc_list as $dxcc) {
					$text .= "  - {$dxcc['name']}";
					if (!empty($dxcc['callsign'])) {
						$text .= " (worked {$dxcc['callsign']}";
						if (!empty($dxcc['mode'])) {
							$text .= " on {$dxcc['mode']}";
						}
						$text .= ")";
					}
					$text .= "\n";
				}
			}
			$text .= "\n";
		}
		
		if (count($report['new_dxcc_satellite']) > 0) {
			$text .= "NEW COUNTRIES VIA SATELLITE\n";
			$text .= "---------------------------\n";
			foreach ($report['new_dxcc_satellite'] as $dxcc) {
				$text .= "- {$dxcc['name']}";
				if (!empty($dxcc['callsign'])) {
					$text .= " (worked {$dxcc['callsign']}";
					if (!empty($dxcc['mode'])) {
						$text .= " on {$dxcc['mode']}";
					}
					$text .= ")";
				}
				$text .= "\n";
			}
			$text .= "\n";
		}
		
		if (count($report['new_dxcc_eme']) > 0) {
			$text .= "NEW COUNTRIES VIA EME (MOONBOUNCE)\n";
			$text .= "----------------------------------\n";
			foreach ($report['new_dxcc_eme'] as $dxcc) {
				$text .= "- {$dxcc['name']}";
				if (!empty($dxcc['callsign'])) {
					$text .= " (worked {$dxcc['callsign']}";
					if (!empty($dxcc['mode'])) {
						$text .= " on {$dxcc['mode']}";
					}
					$text .= ")";
				}
				$text .= "\n";
			}
			$text .= "\n";
		}
		
		if (count($report['new_grids']) > 0) {
			$text .= "NEW GRIDSQUARES THIS MONTH\n";
			$text .= "--------------------------\n";
			
			if (!empty($report['new_grids_by_band'])) {
				foreach ($report['new_grids_by_band'] as $band => $grids_list) {
					$text .= "\n{$band} (" . count($grids_list) . " new):\n";
					foreach ($grids_list as $grid) {
						$text .= "  {$grid['grid']}";
						if (!empty($grid['callsign'])) {
							$text .= " ({$grid['callsign']}";
							if (!empty($grid['satellite'])) {
								$text .= " via {$grid['satellite']}";
							}
							if (!empty($grid['mode'])) {
								$text .= " - {$grid['mode']}";
							}
							$text .= ")";
						}
						$text .= "\n";
					}
				}
			}
			$text .= "\n";
		}		$text .= "MODES USED\n";
		$text .= "----------\n";
		foreach ($report['modes'] as $mode => $count) {
			$text .= sprintf("%-10s %d QSOs\n", $mode, $count);
		}
		$text .= "\n";
		
		$text .= "BANDS USED\n";
		$text .= "----------\n";
		foreach ($report['bands'] as $band => $count) {
			$text .= sprintf("%-10s %d QSOs\n", $band, $count);
		}
		$text .= "\n";
		
		if (!empty($report['continents'])) {
			$text .= "CONTINENTAL DISTRIBUTION\n";
			$text .= "------------------------\n";
			foreach ($report['continents'] as $continent => $count) {
				$text .= sprintf("%-15s %d QSOs\n", $continent, $count);
			}
			$text .= "\n";
		}
		
		if (!empty($report['callsign_list'])) {
			$text .= "UNIQUE CALLSIGNS WORKED (" . count($report['callsign_list']) . ")\n";
			$text .= "----------------------------\n";
			foreach ($report['callsign_list'] as $callsign) {
				$text .= sprintf("%-15s %d QSO%s\n", $callsign['callsign'], $callsign['qso_count'], $callsign['qso_count'] > 1 ? 's' : '');
			}
			$text .= "\n";
		}
		
		if ($report['satellite_qsos'] > 0) {
			$text .= "SATELLITE ACTIVITY\n";
			$text .= "------------------\n";
			$text .= "Total Satellite QSOs: {$report['satellite_qsos']}\n";
			
			if (!empty($report['satellite_breakdown'])) {
				$text .= "\nQSOs per Satellite:\n";
				foreach ($report['satellite_breakdown'] as $sat) {
					$text .= sprintf("  %-20s %3d QSOs (%s%%)\n", $sat['satellite'], $sat['qso_count'], $sat['percentage']);
				}
			}
			$text .= "\n";
		}
		
		if ($report['eme_qsos'] > 0) {
			$text .= "EME (MOONBOUNCE) ACTIVITY\n";
			$text .= "-------------------------\n";
			$text .= "Total EME QSOs: {$report['eme_qsos']}\n\n";
		}
		
		$text .= "STATISTICS\n";
		$text .= "----------\n";
		$days_in_month = date('t', strtotime("$year-$month-01"));
		$text .= "Average QSOs per day: " . round($report['total_qsos'] / $days_in_month, 2) . "\n";
		$text .= "Most active band: {$report['top_band']}\n";
		$text .= "Most used mode: {$report['top_mode']}\n\n";
		
		$text .= "---\n";
		$text .= "This report can be used to generate an article about your amateur radio activity.\n";
		$text .= "Consider using an AI tool like ChatGPT or Claude to create an engaging blog post from this data.\n";
		
		// Set headers for text download
		$filename = "monthly_report_{$year}_{$month}.txt";
		$this->output
			->set_content_type('text/plain')
			->set_header('Content-Disposition: attachment; filename="' . $filename . '"')
			->set_output($text);
	}
}
