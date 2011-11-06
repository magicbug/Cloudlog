<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	/*
		TODO
			- DXCLuster Spots
			- Breakdown of QSOs per band/mode
			- Countries worked
	*/

	public function index()
	{
	
		// Database connections
		$this->load->model('logbook_model');
		$this->load->model('user_model');
		if(!$this->user_model->authorize($this->config->item('auth_mode'))) {
			if($this->user_model->validate_session()) {
				$this->user_model->clear_session();
				show_error('Access denied<p>Click <a href="'.site_url('user/login').'">here</a> to log in as another user', 403);
			} else {
				redirect('user/login');
			}
		}
		
		// Store info
		$data['todays_qsos'] = $this->logbook_model->todays_qsos();
		$data['total_qsos'] = $this->logbook_model->total_qsos();
		$data['month_qsos'] = $this->logbook_model->month_qsos();
		$data['year_qsos'] = $this->logbook_model->year_qsos();
		
		$data['total_ssb'] = $this->logbook_model->total_ssb();
		$data['total_cw'] = $this->logbook_model->total_cw();
		$data['total_fm'] = $this->logbook_model->total_fm();
		$data['total_digi'] = $this->logbook_model->total_digi();
		
		$data['total_countrys'] = $this->logbook_model->total_countrys();
		
		$data['total_qsl_sent'] = $this->logbook_model->total_qsl_sent();
		$data['total_qsl_recv'] = $this->logbook_model->total_qsl_recv();
		$data['total_qsl_requested'] = $this->logbook_model->total_qsl_requested();
		
		$data['total_bands'] = $this->logbook_model->total_bands();
		
		$data['last_five_qsos'] = $this->logbook_model->get_last_qsos('11');

		$data['page_title'] = "Dashboard";

		$this->load->view('layout/header', $data);
		$this->load->view('dashboard/index');
		$this->load->view('layout/footer');
	}
	
	function map() {
		$this->load->model('logbook_model');
		
		$this->load->library('qra');

		//echo date('Y-m-d')
		$raw = strtotime('Monday last week');
		
		$mon = date('Y-m-d', $raw);
		$sun = date('Y-m-d', strtotime('Sunday this week'));

		$qsos = $this->logbook_model->map_week_qsos($mon, $sun);

		echo "{\"markers\": [";
		$count = 1;
		foreach ($qsos->result() as $row) {
			//print_r($row);
			if($row->COL_GRIDSQUARE != null) {
				$stn_loc = $this->qra->qra2latlong($row->COL_GRIDSQUARE);
				if($count != 1) {
					echo ",";
				}

				echo "{\"lat\":\"".$stn_loc[0]."\",\"lng\":\"".$stn_loc[1]."\", \"html\":\"Callsign: ".$row->COL_CALL."<br />Date/Time: ".$row->COL_TIME_ON."<br />Band: ".$row->COL_BAND."<br />Mode: ".$row->COL_MODE."\",\"label\":\"".$row->COL_CALL."\"}";

				$count++;

			} else {
				$query = $this->db->query('
					SELECT *
					FROM dxcc
					WHERE prefix = SUBSTRING( \''.$row->COL_CALL.'\', 1, LENGTH( prefix ) )
					ORDER BY LENGTH( prefix ) DESC
					LIMIT 1 
				');

				foreach ($query->result() as $dxcc) {
					if($count != 1) {
					echo ",";
						}
					echo "{\"lat\":\"".$dxcc->lat."\",\"lng\":\"".$dxcc->long."\", \"html\":\"Callsign: ".$row->COL_CALL."<br />Date/Time: ".$row->COL_TIME_ON."<br />Band: ".$row->COL_BAND."<br />Mode: ".$row->COL_MODE."\",\"label\":\"".$row->COL_CALL."\"}";
					$count++;
				}
			}

		}
		echo "]";
		echo "}";

	}
	
	
	function todays_map() {
		$this->load->library('qra');
		$this->load->model('logbook_model');
		// TODO: Auth
		$qsos = $this->logbook_model->get_todays_qsos('');

	
		echo "{\"markers\": [";

		foreach ($qsos->result() as $row) {
			//print_r($row);
			if($row->COL_GRIDSQUARE != null) {
				$stn_loc = $this->qra->qra2latlong($row->COL_GRIDSQUARE);
				echo "{\"point\":new GLatLng(".$stn_loc[0].",".$stn_loc[1]."), \"html\":\"Callsign: ".$row->COL_CALL."<br />Date/Time: ".$row->COL_TIME_ON."<br />Band: ".$row->COL_BAND."<br />Mode: ".$row->COL_MODE."\",\"label\":\"".$row->COL_CALL."\"},";
			} else {
				$query = $this->db->query('
					SELECT *
					FROM dxcc
					WHERE prefix = SUBSTRING( \''.$row->COL_CALL.'\', 1, LENGTH( prefix ) )
					ORDER BY LENGTH( prefix ) DESC
					LIMIT 1 
				');
				
				foreach ($query->result() as $dxcc) {
					echo "{\"point\":new GLatLng(".$dxcc->lat.",".$dxcc->long."), \"html\":\"Callsign: ".$row->COL_CALL."<br />Date/Time: ".$row->COL_TIME_ON."<br />Band: ".$row->COL_BAND."<br />Mode: ".$row->COL_MODE."\",\"label\":\"".$row->COL_CALL."\"},";
				}
			}
			
		}
		echo "]";
		echo "}";

	}
	
}