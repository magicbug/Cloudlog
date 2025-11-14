<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
	Controller for generating monthly reports of amateur radio activity
*/

class Monthlyreport extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));

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
		
		$data['logbooks'] = $this->logbooks_model->show_all();
		$data['page_title'] = "Monthly Report";
		
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
				'new_gridsquares_hf' => $report['new_grids_hf'],
				'new_gridsquares_satellite' => $report['new_grids_satellite'],
				'new_gridsquares_eme' => $report['new_grids_eme'],
				'callsigns_worked' => $report['callsign_list'],
				'modes_used' => $report['modes'],
				'bands_used' => $report['bands'],
				'continents_worked' => $report['continents'],
				'satellite_qsos' => $report['satellite_qsos'],
				'eme_qsos' => $report['eme_qsos']
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
		$this->output
			->set_content_type('application/json')
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
					$text .= "  - {$dxcc['name']}\n";
				}
			}
			$text .= "\n";
		}
		
		if (count($report['new_dxcc_satellite']) > 0) {
			$text .= "NEW COUNTRIES VIA SATELLITE\n";
			$text .= "---------------------------\n";
			foreach ($report['new_dxcc_satellite'] as $dxcc) {
				$text .= "- {$dxcc['name']}\n";
			}
			$text .= "\n";
		}
		
		if (count($report['new_dxcc_eme']) > 0) {
			$text .= "NEW COUNTRIES VIA EME (MOONBOUNCE)\n";
			$text .= "----------------------------------\n";
			foreach ($report['new_dxcc_eme'] as $dxcc) {
				$text .= "- {$dxcc['name']}\n";
			}
			$text .= "\n";
		}
		
		if (count($report['new_grids']) > 0) {
			$text .= "NEW GRIDSQUARES THIS MONTH\n";
			$text .= "--------------------------\n";
			
			if (count($report['new_grids_hf']) > 0) {
				$text .= "HF/VHF Terrestrial (" . count($report['new_grids_hf']) . "):\n";
				$grids_per_line = 10;
				$grid_list = array_column($report['new_grids_hf'], 'grid');
				for ($i = 0; $i < count($grid_list); $i += $grids_per_line) {
					$text .= implode(', ', array_slice($grid_list, $i, $grids_per_line)) . "\n";
				}
				$text .= "\n";
			}
			
			if (count($report['new_grids_satellite']) > 0) {
				$text .= "Satellite (" . count($report['new_grids_satellite']) . "):\n";
				$grids_per_line = 10;
				$grid_list = array_column($report['new_grids_satellite'], 'grid');
				for ($i = 0; $i < count($grid_list); $i += $grids_per_line) {
					$text .= implode(', ', array_slice($grid_list, $i, $grids_per_line)) . "\n";
				}
				$text .= "\n";
			}
			
			if (count($report['new_grids_eme']) > 0) {
				$text .= "EME (Moonbounce) (" . count($report['new_grids_eme']) . "):\n";
				$grids_per_line = 10;
				$grid_list = array_column($report['new_grids_eme'], 'grid');
				for ($i = 0; $i < count($grid_list); $i += $grids_per_line) {
					$text .= implode(', ', array_slice($grid_list, $i, $grids_per_line)) . "\n";
				}
			}
		}
		
		$text .= "MODES USED\n";
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
			$text .= "Total Satellite QSOs: {$report['satellite_qsos']}\n\n";
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
