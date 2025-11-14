<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Statistics extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		// Load language files
		$this->lang->load(array(
			'statistics',
		));
	}


	public function index()
	{
        $this->load->model('user_model');
        $this->load->model('bands');

        if(!$this->user_model->authorize($this->config->item('auth_mode'))) {
            if($this->user_model->validate_session()) {
                $this->user_model->clear_session();
                show_error('Access denied<p>Click <a href="'.site_url('user/login').'">here</a> to log in as another user', 403);
            } else {
                redirect('user/login');
            }
        }	
		// Render User Interface

		// Set Page Title
		$data['page_title'] = $this->lang->line('statistics_statistics');
		$data['sat_active'] = array_search("SAT", $this->bands->get_user_bands(), true);
		
		// Load Views
		$this->load->view('interface_assets/header', $data);
		$this->load->view('statistics/index');
		$this->load->view('interface_assets/footer');
	}
	
	function custom() {
	
	    $this->load->model('user_model');
		if(!$this->user_model->authorize($this->config->item('auth_mode'))) {
			if($this->user_model->validate_session()) {
				$this->user_model->clear_session();
				show_error('Access denied<p>Click <a href="'.site_url('user/login').'">here</a> to log in as another user', 403);
			} else {
				redirect('user/login');
			}
		}
	
	    $this->load->model('logbook_model');

		$data['page_title'] = "Custom Statistics";
		$data['modes'] = $this->logbook_model->get_modes();
	
		$this->load->helper(array('form', 'url'));

		$this->load->library('form_validation');

		$this->form_validation->set_rules('start_date', 'Start Date', 'required');
		$this->form_validation->set_rules('end_date', 'End Date', 'required');

		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('interface_assets/header', $data);
			$this->load->view('statistics/custom', $data);
			$this->load->view('interface_assets/footer');
		}
		else
		{
		
			$this->load->model('stats');
	
			$data['result'] = $this->stats->result();
		
			$this->load->view('interface_assets/header', $data);
			$this->load->view('statistics/custom_result');
			$this->load->view('interface_assets/footer');
		}
	
	}

	public function get_year() {
		$this->load->model('logbook_model');

		// Check for date filters
		$start_date = $this->input->post('start_date');
		$end_date = $this->input->post('end_date');

		// get data
		if ($start_date && $end_date) {
			$this->db->select('YEAR(COL_TIME_ON) as year, COUNT(*) as total');
			$this->db->from($this->config->item('table_name'));
			$this->db->where('COL_TIME_ON >=', $start_date);
			$this->db->where('COL_TIME_ON <=', $end_date . ' 23:59:59');
			$this->db->group_by('year');
			$this->db->order_by('year', 'ASC');
			$totals_year = $this->db->get();
		} else {
			$totals_year = $this->logbook_model->totals_year();
		}

		$yearstats = array();
		
		$i = 0;
		if ($totals_year) {
			foreach($totals_year->result() as $qso_numbers) {
				$yearstats[$i]['year'] = $qso_numbers->year;
				$yearstats[$i++]['total'] = $qso_numbers->total;
			}
		}
		
		header('Content-Type: application/json');
		echo json_encode($yearstats);
	}

	public function get_mode() {
		$this->load->model('logbook_model');

		$modestats = array();
		
		$i = 0;
		$modestats[$i]['mode'] = 'ssb';
		$modestats[$i++]['total'] = $this->logbook_model->total_ssb();
		$modestats[$i]['mode'] = 'cw';
		$modestats[$i++]['total'] = $this->logbook_model->total_cw();
		$modestats[$i]['mode'] = 'fm';
		$modestats[$i++]['total'] = $this->logbook_model->total_fm();
		$modestats[$i]['mode'] = 'digi';
		$modestats[$i]['total'] = $this->logbook_model->total_digi();
		usort($modestats, fn($a, $b) => $b['total'] <=> $a['total']);
		
		header('Content-Type: application/json');

		echo json_encode($modestats);
	}

	public function get_band() {
		$this->load->model('logbook_model');

		$bandstats = array();

		$total_bands = $this->logbook_model->total_bands();
		
		$i = 0;
		
		if ($total_bands) {
			foreach($total_bands->result() as $qso_numbers) {
				$bandstats[$i]['band'] = $qso_numbers->band;
				$bandstats[$i++]['count'] = $qso_numbers->count;
			}
		}

		header('Content-Type: application/json');
		echo json_encode($bandstats);
	}

	public function get_sat() {
		$this->load->model('logbook_model');

		$satstats = array();

		$total_sat = $this->logbook_model->total_sat();
		$i = 0;
		
		if ($total_sat) {
			foreach($total_sat->result() as $qso_numbers) {
				$satstats[$i]['sat'] = $qso_numbers->COL_SAT_NAME;
				$satstats[$i++]['count'] = $qso_numbers->count;
			}
		}

		header('Content-Type: application/json');
		echo json_encode($satstats);
	}

	public function get_unique_callsigns() {
		$this->load->model('stats');

		$result = $this->stats->unique_callsigns();
		$total_qsos['qsoarray'] = $result['qsoView'];
		$total_qsos['bandunique'] = $result['bandunique'];
		$total_qsos['modeunique'] = $result['modeunique'];
		$total_qsos['total'] = $result['total'];
		$total_qsos['bands'] = $this->stats->get_bands();

		$this->load->view('statistics/uniquetable', $total_qsos);
	}

	public function get_total_qsos() {
		$this->load->model('stats');

		$totalqsos = array();

		$result = $this->stats->total_qsos();
		$total_qsos['qsoarray'] = $result['qsoView'];
		$total_qsos['bandtotal'] = $result['bandtotal'];
		$total_qsos['modetotal'] = $result['modetotal'];
		$total_qsos['bands'] = $this->stats->get_bands();

		$this->load->view('statistics/qsotable', $total_qsos);
	}

	public function get_summary_stats() {
		$this->load->model('logbook_model');
		$this->load->model('stats');

		$data = array();
		
		// Check for date filters
		$start_date = $this->input->post('start_date');
		$end_date = $this->input->post('end_date');
		
		$where_clause = '';
		if ($start_date && $end_date) {
			$where_clause = "WHERE COL_TIME_ON >= '$start_date' AND COL_TIME_ON <= '$end_date 23:59:59'";
		}
		
		// Total QSOs
		if ($where_clause) {
			$this->db->where('COL_TIME_ON >=', $start_date);
			$this->db->where('COL_TIME_ON <=', $end_date . ' 23:59:59');
			$data['total_qsos'] = $this->db->count_all_results($this->config->item('table_name'));
		} else {
			$data['total_qsos'] = $this->logbook_model->total_qsos();
		}
		
		// Unique callsigns
		if ($where_clause) {
			$this->db->select('COUNT(DISTINCT COL_CALL) as count');
			$this->db->from($this->config->item('table_name'));
			$this->db->where('COL_TIME_ON >=', $start_date);
			$this->db->where('COL_TIME_ON <=', $end_date . ' 23:59:59');
			$query = $this->db->get();
			$data['unique_callsigns'] = $query->row()->count;
		} else {
			$unique_result = $this->stats->unique_callsigns();
			$unique_total = $unique_result['total'];
			$data['unique_callsigns'] = is_object($unique_total) ? $unique_total->calls : $unique_total;
		}
		
		// Total bands worked
		if ($where_clause) {
			$this->db->select('DISTINCT COL_BAND');
			$this->db->from($this->config->item('table_name'));
			$this->db->where('COL_TIME_ON >=', $start_date);
			$this->db->where('COL_TIME_ON <=', $end_date . ' 23:59:59');
			$this->db->where('COL_BAND IS NOT NULL');
			$bands = $this->db->get();
			$data['total_bands'] = $bands ? $bands->num_rows() : 0;
		} else {
			$bands = $this->logbook_model->total_bands();
			$data['total_bands'] = $bands ? $bands->num_rows() : 0;
		}
		
		// Total modes
		$data['total_modes'] = 0;
		if ($where_clause) {
			// Count distinct mode categories with date filter
			$this->db->select('COL_MODE');
			$this->db->from($this->config->item('table_name'));
			$this->db->where('COL_TIME_ON >=', $start_date);
			$this->db->where('COL_TIME_ON <=', $end_date . ' 23:59:59');
			$this->db->where_in('COL_MODE', ['SSB', 'USB', 'LSB']);
			$data['total_modes'] += ($this->db->count_all_results() > 0) ? 1 : 0;
			
			$this->db->select('COL_MODE');
			$this->db->from($this->config->item('table_name'));
			$this->db->where('COL_TIME_ON >=', $start_date);
			$this->db->where('COL_TIME_ON <=', $end_date . ' 23:59:59');
			$this->db->where('COL_MODE', 'CW');
			$data['total_modes'] += ($this->db->count_all_results() > 0) ? 1 : 0;
			
			$this->db->select('COL_MODE');
			$this->db->from($this->config->item('table_name'));
			$this->db->where('COL_TIME_ON >=', $start_date);
			$this->db->where('COL_TIME_ON <=', $end_date . ' 23:59:59');
			$this->db->where('COL_MODE', 'FM');
			$data['total_modes'] += ($this->db->count_all_results() > 0) ? 1 : 0;
			
			// Digital modes
			$this->db->select('COL_MODE');
			$this->db->from($this->config->item('table_name'));
			$this->db->where('COL_TIME_ON >=', $start_date);
			$this->db->where('COL_TIME_ON <=', $end_date . ' 23:59:59');
			$this->db->where_not_in('COL_MODE', ['SSB', 'USB', 'LSB', 'CW', 'FM', 'AM']);
			$data['total_modes'] += ($this->db->count_all_results() > 0) ? 1 : 0;
		} else {
			$data['total_modes'] += ($this->logbook_model->total_ssb() > 0) ? 1 : 0;
			$data['total_modes'] += ($this->logbook_model->total_cw() > 0) ? 1 : 0;
			$data['total_modes'] += ($this->logbook_model->total_fm() > 0) ? 1 : 0;
			$data['total_modes'] += ($this->logbook_model->total_digi() > 0) ? 1 : 0;
		}
		
		// Countries worked
		$data['total_countries'] = $this->get_total_countries($start_date, $end_date);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function get_trends() {
		$this->load->model('logbook_model');
		$this->load->model('logbooks_model');
		
		$logbooks_locations_array = $this->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));
		
		if (!$logbooks_locations_array) {
			header('Content-Type: application/json');
			echo json_encode(array('error' => 'No logbook relationships found'));
			return;
		}
		
		$trends = array();
		
		// QSOs this month
		$this->db->select('COUNT(*) as count');
		$this->db->where('MONTH(COL_TIME_ON)', date('m'));
		$this->db->where('YEAR(COL_TIME_ON)', date('Y'));
		$this->db->where_in('station_id', $logbooks_locations_array);
		$this->db->from($this->config->item('table_name'));
		$query = $this->db->get();
		$result = $query->row();
		$trends['this_month'] = $result ? $result->count : 0;
		
		// QSOs this year
		$this->db->select('COUNT(*) as count');
		$this->db->where('YEAR(COL_TIME_ON)', date('Y'));
		$this->db->where_in('station_id', $logbooks_locations_array);
		$this->db->from($this->config->item('table_name'));
		$query = $this->db->get();
		$result = $query->row();
		$trends['this_year'] = $result ? $result->count : 0;
		
		// QSOs last 30 days
		$this->db->select('COUNT(*) as count');
		$this->db->where('COL_TIME_ON >=', date('Y-m-d', strtotime('-30 days')));
		$this->db->where_in('station_id', $logbooks_locations_array);
		$this->db->from($this->config->item('table_name'));
		$query = $this->db->get();
		$result = $query->row();
		$trends['last_30_days'] = $result ? $result->count : 0;
		
		// Monthly trend (last 12 months)
		$monthly = array();
		for ($i = 11; $i >= 0; $i--) {
			$month = date('Y-m', strtotime("-$i months"));
			$year = substr($month, 0, 4);
			$mon = substr($month, 5, 2);
			
			$this->db->select('COUNT(*) as count');
			$this->db->where('YEAR(COL_TIME_ON)', $year);
			$this->db->where('MONTH(COL_TIME_ON)', $mon);
			$this->db->where_in('station_id', $logbooks_locations_array);
			$this->db->from($this->config->item('table_name'));
			$query = $this->db->get();
			$result = $query->row();
			
			$monthly[] = array(
				'month' => date('M Y', strtotime($month . '-01')),
				'count' => $result ? $result->count : 0
			);
		}
		$trends['monthly'] = $monthly;
		
		header('Content-Type: application/json');
		echo json_encode($trends);
	}

	public function get_continents() {
		$this->load->model('logbook_model');
		$this->load->model('logbooks_model');
		
		$logbooks_locations_array = $this->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));
		
		if (!$logbooks_locations_array) {
			header('Content-Type: application/json');
			echo json_encode(array());
			return;
		}
		
		$this->db->select('COL_CONT, COUNT(*) as count');
		$this->db->from($this->config->item('table_name'));
		$this->db->where('COL_CONT IS NOT NULL');
		$this->db->where('COL_CONT !=', '');
		$this->db->where_in('station_id', $logbooks_locations_array);
		$this->db->group_by('COL_CONT');
		$this->db->order_by('count', 'DESC');
		$query = $this->db->get();
		
		$continents = array();
		if ($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$continents[] = array(
					'continent' => $row->COL_CONT,
					'count' => $row->count
				);
			}
		}
		
		header('Content-Type: application/json');
		echo json_encode($continents);
	}

	public function get_most_worked() {
		$this->load->model('logbook_model');
		$this->load->model('logbooks_model');
		
		$logbooks_locations_array = $this->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));
		
		if (!$logbooks_locations_array) {
			header('Content-Type: application/json');
			echo json_encode(array('callsigns' => array(), 'countries' => array()));
			return;
		}
		
		// Most worked callsigns
		$this->db->select('COL_CALL, COUNT(*) as count, COL_COUNTRY, MAX(COL_TIME_ON) as last_qso');
		$this->db->from($this->config->item('table_name'));
		$this->db->where_in('station_id', $logbooks_locations_array);
		$this->db->group_by('COL_CALL');
		$this->db->order_by('count', 'DESC');
		$this->db->limit(10);
		$query = $this->db->get();
		
		$callsigns = array();
		if ($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$callsigns[] = array(
					'callsign' => $row->COL_CALL,
					'count' => $row->count,
					'country' => $row->COL_COUNTRY,
					'last_qso' => $row->last_qso
				);
			}
		}
		
		// Most worked countries
		$this->db->select('COL_COUNTRY, COUNT(*) as count');
		$this->db->from($this->config->item('table_name'));
		$this->db->where('COL_COUNTRY IS NOT NULL');
		$this->db->where('COL_COUNTRY !=', '');
		$this->db->where_in('station_id', $logbooks_locations_array);
		$this->db->group_by('COL_COUNTRY');
		$this->db->order_by('count', 'DESC');
		$this->db->limit(10);
		$query = $this->db->get();
		
		$countries = array();
		if ($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$countries[] = array(
					'country' => $row->COL_COUNTRY,
					'count' => $row->count
				);
			}
		}
		
		$result = array(
			'callsigns' => $callsigns,
			'countries' => $countries
		);
		
		header('Content-Type: application/json');
		echo json_encode($result);
	}

	private function get_total_countries($start_date = null, $end_date = null) {
		$this->db->select('COUNT(DISTINCT COL_COUNTRY) as count');
		$this->db->from($this->config->item('table_name'));
		$this->db->where('COL_COUNTRY IS NOT NULL');
		$this->db->where('COL_COUNTRY !=', '');
		
		if ($start_date && $end_date) {
			$this->db->where('COL_TIME_ON >=', $start_date);
			$this->db->where('COL_TIME_ON <=', $end_date . ' 23:59:59');
		}
		
		$query = $this->db->get();
		return $query->row()->count;
	}
}
