<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Statistics extends CI_Controller {

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
		$data['page_title'] = "Statistics";
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

		// get data
		$totals_year = $this->logbook_model->totals_year();

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
}
