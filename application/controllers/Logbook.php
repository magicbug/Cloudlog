<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Logbook extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		// Load language files
		$this->lang->load(array(
			'contesting',
			'qslcard',
			'lotw',
			'eqsl',
			'qso'
		));
	}

	function index()
	{

		// Check if users logged in
		$this->load->model('user_model');
		if($this->user_model->validate_session() == 0) {
			// user is not logged in
			redirect('user/login');
		}
		$this->load->model('stations');

		// If environment is set to development then show the debug toolbar
		if(ENVIRONMENT == 'development') {
            $this->output->enable_profiler(TRUE);
        }

		$this->load->model('logbook_model');

		$this->load->library('pagination');
		$config['base_url'] = base_url().'index.php/logbook/index/';
		$config['total_rows'] = $this->logbook_model->total_qsos();
		$config['per_page'] = '25';
		$config['num_links'] = 6;
		$config['full_tag_open'] = '';
		$config['full_tag_close'] = '';
		$config['cur_tag_open'] = '<strong class="active"><a href="">';
		$config['cur_tag_close'] = '</a></strong>';

		$this->pagination->initialize($config);

		//load the model and get results
		$data['results'] = $this->logbook_model->get_qsos($config['per_page'],$this->uri->segment(3));

		if(!$data['results']) {
			$this->session->set_flashdata('notice', lang('error_no_logbook_found') . ' <a href="' . site_url('logbooks') . '" title="Station Logbooks">Station Logbooks</a>');
		}

		// Calculate Lat/Lng from Locator to use on Maps
		if($this->session->userdata('user_locator')) {
				$this->load->library('qra');
				$qra_position = $this->qra->qra2latlong($this->session->userdata('user_locator'));
				if (isset($qra_position[0]) and isset($qra_position[1])) {
					$data['qra'] = "set";
					$data['qra_lat'] = $qra_position[0];
					$data['qra_lng'] = $qra_position[1];
				} else {
					$data['qra'] = "none";
				}
		} else {
				$data['qra'] = "none";
		}



		// load the view
		$data['page_title'] = "Logbook";

		$this->load->view('interface_assets/header', $data);
		$this->load->view('view_log/index');
		$this->load->view('interface_assets/footer');

	}

	function jsonentity($adif) {
        $this->load->model('user_model');
        if(!$this->user_model->authorize($this->config->item('auth_mode'))) { return; }

        $return['dxcc'] = $this->getentity($adif);
        header('Content-Type: application/json');
        echo json_encode($return, JSON_PRETTY_PRINT);
    }

	function json($tempcallsign, $temptype, $tempband, $tempmode, $tempstation_id = null)
	{
		// Cleaning for security purposes
		$callsign = $this->security->xss_clean($tempcallsign);
		$type = $this->security->xss_clean($temptype);
		$band = $this->security->xss_clean($tempband);
		$mode = $this->security->xss_clean($tempmode);
		$station_id = $this->security->xss_clean($tempstation_id);

		$this->load->model('user_model');
		if(!$this->user_model->authorize($this->config->item('auth_mode'))) { return; }

		// Convert - in Callsign to / Used for URL processing
		$callsign = str_replace("-","/",$callsign);
		$callsign = str_replace("Ø","0",$callsign);

		// Check if callsign is an LoTW User
		// Check Database for all other data
		$this->load->model('logbook_model');

		$lotw_days=$this->logbook_model->check_last_lotw($callsign);
		if ($lotw_days != null) {
			$lotw_member="active";
		} else {
			$lotw_member="not found";
		}

		$return = [
			"callsign" => strtoupper($callsign),
			"dxcc" => false,
			"callsign_name" => "",
			"callsign_qra"  => "",
			"callsign_distance"  => 0,
			"callsign_qth"  => "",
			"callsign_iota" => "",
			"callsign_state" => "",
			"callsign_us_county" => "",
			"qsl_manager" => "",
			"bearing" 		=> "",
			"workedBefore" => false,
			"lotw_member" => $lotw_member,
			"lotw_days" => $lotw_days,
			"image" => "",
		];

		$return['dxcc'] = $this->dxcheck($callsign);
		$split_callsign=explode('/',$callsign);
		if (isset($split_callsign[1]) && ($split_callsign[1] != "")) {	// Do we have "/" in Call?
			if (strlen($split_callsign[1])>3) {			// Last Element longer than 3 chars? Take that as call
				$lookupcall = $split_callsign[1];
			} else {						// Last Element up to 3 Chars? Take first element as Call
				$lookupcall = $split_callsign[0];
			}
		} else {
			$lookupcall=$callsign;
		}

		$return['partial'] = $this->partial($lookupcall);

		$callbook = $this->logbook_model->loadCallBook($callsign, $this->config->item('use_fullname'));

		if ($this->session->userdata('user_measurement_base') == NULL) {
			$measurement_base = $this->config->item('measurement_base');
		} else {
			$measurement_base = $this->session->userdata('user_measurement_base');
		}

		$return['callsign_name'] 		= $this->nval($callbook['name'] ?? '', $this->logbook_model->call_name($callsign));
		$return['callsign_qra'] 		= $this->nval($callbook['gridsquare'] ?? '',  $this->logbook_model->call_qra($callsign));
		$return['callsign_distance'] 	= $this->distance($return['callsign_qra']);
		$return['callsign_qth'] 		= $this->nval($callbook['city'] ?? '', $this->logbook_model->call_qth($callsign));
		$return['callsign_iota'] 		= $this->nval($callbook['iota'] ?? '', $this->logbook_model->call_iota($callsign));
		$return['qsl_manager'] 			= $this->nval($callbook['qslmgr'] ?? '', $this->logbook_model->call_qslvia($callsign));
		$return['callsign_state'] 		= $this->nval($callbook['state'] ?? '', $this->logbook_model->call_state($callsign));
		$return['callsign_us_county'] 	= $this->nval($callbook['us_county'] ?? '', $this->logbook_model->call_us_county($callsign));
		$return['workedBefore'] 		= $this->worked_grid_before($return['callsign_qra'], $type, $band, $mode);
		$return['confirmed'] 		= $this->confirmed_grid_before($return['callsign_qra'], $type, $band, $mode);

		if ($this->session->userdata('user_show_profile_image')) {
			if (isset($callbook) && isset($callbook['image'])) {
				if ($callbook['image'] == "") {
					$return['image'] = "n/a";
				} else {
					$return['image'] = $callbook['image'];
				}
			} else {
				$return['image'] = "n/a";
			}
		}

		if ($return['callsign_qra'] != "") {
			$return['latlng'] = $this->qralatlng($return['callsign_qra']);
			$return['bearing'] = $this->bearing($return['callsign_qra'], $measurement_base, $station_id);
		}

		echo json_encode($return, JSON_PRETTY_PRINT);

		return;
	}

	// Returns $val2 first if it has value, even if it is null or empty string, if not return $val1.
	function nval($val1, $val2) {
		return (($val2 ?? "") === "" ? ($val1 ?? "") : ($val2 ?? ""));
	}

	function confirmed_grid_before($gridsquare, $type, $band, $mode) {
		if (strlen($gridsquare) < 4)
			return false;

		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));
		$user_default_confirmation = $this->session->userdata('user_default_confirmation');

		if(!empty($logbooks_locations_array)) {
			$extrawhere='';
			if (isset($user_default_confirmation) && strpos($user_default_confirmation, 'Q') !== false) {
				$extrawhere="COL_QSL_RCVD='Y'"; 
			}
			if (isset($user_default_confirmation) && strpos($user_default_confirmation, 'L') !== false) {
				if ($extrawhere!='') {
					$extrawhere.=" OR";
				}
				$extrawhere.=" COL_LOTW_QSL_RCVD='Y'";
			}
			if (isset($user_default_confirmation) && strpos($user_default_confirmation, 'E') !== false) {
				if ($extrawhere!='') {
					$extrawhere.=" OR";
				}
				$extrawhere.=" COL_EQSL_QSL_RCVD='Y'";
			}


			if($type == "SAT") {
				$this->db->where('COL_PROP_MODE', 'SAT');
				if ($extrawhere != '') {
					$this->db->where('('.$extrawhere.')');
				} else {
					$this->db->where("1=0");
				}
			} else {
				$CI->load->model('logbook_model');
				$this->db->where('COL_MODE', $CI->logbook_model->get_main_mode_from_mode($mode));
				$this->db->where('COL_BAND', $band);
				$this->db->where('COL_PROP_MODE !=','SAT');
				if ($extrawhere != '') {
					$this->db->where('('.$extrawhere.')');
				} else {
					$this->db->where("1=0");
				}
			}

			$this->db->where_in('station_id', $logbooks_locations_array);
			$this->db->like('SUBSTRING(COL_GRIDSQUARE, 1, 4)', substr($gridsquare, 0, 4));
			$this->db->order_by($this->config->item('table_name').".COL_TIME_ON", "desc");
			$this->db->limit(1);


			$query = $this->db->get($this->config->item('table_name'));


			foreach ($query->result() as $workedBeforeRow) {
				return true;
			}
		}
		return false;
	}

function worked_grid_before($gridsquare, $type, $band, $mode)
	{
		if (strlen($gridsquare) < 4)
			return false;

		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		if(!empty($logbooks_locations_array)) {
			if($type == "SAT") {
				$this->db->where('COL_PROP_MODE', 'SAT');
			} else {
				$this->db->where('COL_MODE', $this->logbook_model->get_main_mode_from_mode($mode));
				$this->db->where('COL_BAND', $band);
				$this->db->where('COL_PROP_MODE !=','SAT');

			}
			$this->db->where_in('station_id', $logbooks_locations_array);
			$this->db->like('SUBSTRING(COL_GRIDSQUARE, 1, 4)', substr($gridsquare, 0, 4));
			$this->db->order_by($this->config->item('table_name').".COL_TIME_ON", "desc");
			$this->db->limit(1);


			$query = $this->db->get($this->config->item('table_name'));


			foreach ($query->result() as $workedBeforeRow)
			{
				return true;
			}
		}
		return false;
	}

	/*
	*	Function: jsonlookupgrid
	*
	* 	Usage: Used to look up gridsquares when creating a QSO to check whether its needed or not
	*	the $type variable is only used for satellites, set this to SAT.
	*
	*/
	function jsonlookupgrid($gridsquare, $type, $band, $mode) {
		$return = [
			"workedBefore" => false,
			"confirmed" => false,
		];
		$user_default_confirmation = $this->session->userdata('user_default_confirmation');
		$CI =& get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		if($type == "SAT") {
			$this->db->where('COL_PROP_MODE', 'SAT');
		} else {
			$CI->load->model('logbook_model');
			$this->db->where('COL_MODE', $CI->logbook_model->get_main_mode_from_mode($mode));
			$this->db->where('COL_BAND', $band);
			$this->db->where('COL_PROP_MODE !=','SAT');

		}

		$this->db->where_in('station_id', $logbooks_locations_array);

		$this->db->like('SUBSTRING(COL_GRIDSQUARE, 1, 4)', substr($gridsquare, 0, 4));
		$query = $this->db->get($this->config->item('table_name'), 1, 0);
		foreach ($query->result() as $workedBeforeRow)
		{
			$return['workedBefore'] = true;
		}

		
		$extrawhere='';
		if (isset($user_default_confirmation) && strpos($user_default_confirmation, 'Q') !== false) {
			$extrawhere="COL_QSL_RCVD='Y'"; 
		}
		if (isset($user_default_confirmation) && strpos($user_default_confirmation, 'L') !== false) {
			if ($extrawhere!='') {
				$extrawhere.=" OR";
			}
			$extrawhere.=" COL_LOTW_QSL_RCVD='Y'";
		}
		if (isset($user_default_confirmation) && strpos($user_default_confirmation, 'E') !== false) {
			if ($extrawhere!='') {
				$extrawhere.=" OR";
			}
			$extrawhere.=" COL_EQSL_QSL_RCVD='Y'";
		}

		if($type == "SAT") {
			$this->db->where('COL_PROP_MODE', 'SAT');
			if ($extrawhere != '') {
				$this->db->where('('.$extrawhere.')');
			} else {
				$this->db->where("1=0");
			}
		} else {
			$CI->load->model('logbook_model');
			$this->db->where('COL_MODE', $CI->logbook_model->get_main_mode_from_mode($mode));
			$this->db->where('COL_BAND', $band);
			$this->db->where('COL_PROP_MODE !=','SAT');
			if ($extrawhere != '') {
				$this->db->where('('.$extrawhere.')');
			} else {
				$this->db->where("1=0");
			}
		}

		$this->db->where_in('station_id', $logbooks_locations_array);

		$this->db->like('SUBSTRING(COL_GRIDSQUARE, 1, 4)', substr($gridsquare, 0, 4));
		$query = $this->db->get($this->config->item('table_name'), 1, 0);
		foreach ($query->result() as $workedBeforeRow) {
			$return['confirmed']=true;
		}

		header('Content-Type: application/json');
		echo json_encode($return, JSON_PRETTY_PRINT);

		return;
	}

	function jsonlookupdxcc($country, $type, $band, $mode) {

		$return = [
			"workedBefore" => false,
			"confirmed" => false,
		];

		$user_default_confirmation = $this->session->userdata('user_default_confirmation');
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));
		$CI->load->model('logbook_model');

		if(!empty($logbooks_locations_array)) {
			if($type == "SAT") {
				$this->db->where('COL_PROP_MODE', 'SAT');
			} else {
				$this->db->where('COL_MODE', $this->logbook_model->get_main_mode_from_mode($mode));
				$this->db->where('COL_BAND', $band);
				$this->db->where('COL_PROP_MODE !=','SAT');

			}

			$this->db->where_in('station_id', $logbooks_locations_array);
			$this->db->where('COL_COUNTRY', urldecode($country));

			$query = $this->db->get($this->config->item('table_name'), 1, 0);
			foreach ($query->result() as $workedBeforeRow)
			{
				$return['workedBefore'] = true;
			}

			$extrawhere='';
			if (isset($user_default_confirmation) && strpos($user_default_confirmation, 'Q') !== false) {
				$extrawhere="COL_QSL_RCVD='Y'"; 
			}
			if (isset($user_default_confirmation) && strpos($user_default_confirmation, 'L') !== false) {
				if ($extrawhere!='') {
					$extrawhere.=" OR";
				}
				$extrawhere.=" COL_LOTW_QSL_RCVD='Y'";
			}
			if (isset($user_default_confirmation) && strpos($user_default_confirmation, 'E') !== false) {
				if ($extrawhere!='') {
					$extrawhere.=" OR";
				}
				$extrawhere.=" COL_EQSL_QSL_RCVD='Y'";
			}


			if($type == "SAT") {
				$this->db->where('COL_PROP_MODE', 'SAT');
				if ($extrawhere != '') {
					$this->db->where('('.$extrawhere.')');
				} else {
					$this->db->where("1=0");
				}
			} else {
				$CI->load->model('logbook_model');
				$this->db->where('COL_MODE', $CI->logbook_model->get_main_mode_from_mode($mode));
				$this->db->where('COL_BAND', $band);
				$this->db->where('COL_PROP_MODE !=','SAT');
				if ($extrawhere != '') {
					$this->db->where('('.$extrawhere.')');
				} else {
					$this->db->where("1=0");
				}
			}

			$this->db->where_in('station_id', $logbooks_locations_array);
			$this->db->where('COL_COUNTRY', urldecode($country));

			$query = $this->db->get($this->config->item('table_name'), 1, 0);
			foreach ($query->result() as $workedBeforeRow) {
				$return['confirmed']=true;
			}


			header('Content-Type: application/json');
			echo json_encode($return, JSON_PRETTY_PRINT);

			return;
		} else {
			$return['workedBefore'] = false;
			$return['confirmed'] = false;

			header('Content-Type: application/json');
			echo json_encode($return, JSON_PRETTY_PRINT);
			return;
		}
	}

	function jsonlookupcallsign($callsign, $type, $band, $mode) {

		// Convert - in Callsign to / Used for URL processing
		$callsign = str_replace("-","/",$callsign);

		$return = [
			"workedBefore" => false,
			"confirmed" => false,
		];

		$user_default_confirmation = $this->session->userdata('user_default_confirmation');
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));
		$CI->load->model('logbook_model');

		if(!empty($logbooks_locations_array)) {
			if($type == "SAT") {
				$this->db->where('COL_PROP_MODE', 'SAT');
			} else {
				$this->db->where('COL_MODE', $this->logbook_model->get_main_mode_from_mode($mode));
				$this->db->where('COL_BAND', $band);
				$this->db->where('COL_PROP_MODE !=','SAT');

			}

			$this->db->where_in('station_id', $logbooks_locations_array);
			$this->db->where('COL_CALL', strtoupper($callsign));

			$query = $this->db->get($this->config->item('table_name'), 1, 0);
			foreach ($query->result() as $workedBeforeRow)
			{
				$return['workedBefore'] = true;
			}

			$extrawhere='';
			if (isset($user_default_confirmation) && strpos($user_default_confirmation, 'Q') !== false) {
				$extrawhere="COL_QSL_RCVD='Y'"; 
			}
			if (isset($user_default_confirmation) && strpos($user_default_confirmation, 'L') !== false) {
				if ($extrawhere!='') {
					$extrawhere.=" OR";
				}
				$extrawhere.=" COL_LOTW_QSL_RCVD='Y'";
			}
			if (isset($user_default_confirmation) && strpos($user_default_confirmation, 'E') !== false) {
				if ($extrawhere!='') {
					$extrawhere.=" OR";
				}
				$extrawhere.=" COL_EQSL_QSL_RCVD='Y'";
			}


			if($type == "SAT") {
				$this->db->where('COL_PROP_MODE', 'SAT');
				if ($extrawhere != '') {
					$this->db->where('('.$extrawhere.')');
				} else {
					$this->db->where("1=0");
				}
			} else {
				$CI->load->model('logbook_model');
				$this->db->where('COL_MODE', $CI->logbook_model->get_main_mode_from_mode($mode));
				$this->db->where('COL_BAND', $band);
				$this->db->where('COL_PROP_MODE !=','SAT');
				if ($extrawhere != '') {
					$this->db->where('('.$extrawhere.')');
				} else {
					$this->db->where("1=0");
				}
			}
			$this->db->where_in('station_id', $logbooks_locations_array);
			$this->db->where('COL_CALL', strtoupper($callsign));

			$query = $this->db->get($this->config->item('table_name'), 1, 0);
			foreach ($query->result() as $workedBeforeRow) {
				$return['confirmed'] = true;
			}

			header('Content-Type: application/json');
			echo json_encode($return, JSON_PRETTY_PRINT);
			return;
		} else {
			$return['workedBefore'] = false;
			$return['confirmed'] = false;
			header('Content-Type: application/json');
			echo json_encode($return, JSON_PRETTY_PRINT);
			return;
		}
	}


	/* Used to generate maps for displaying on /logbook/ */
	function qso_map() {
		header('Content-Type: application/json; charset=utf-8');
		$this->load->model('logbook_model');

		$this->load->library('qra');

		$data['qsos'] = $this->logbook_model->get_qsos($this->uri->segment(3),$this->uri->segment(4));

		echo "{\"markers\": [";
		$count = 1;
		foreach ($data['qsos']->result() as $row) {
			if($row->COL_GRIDSQUARE != null) {
				$stn_loc = $this->qra->qra2latlong($row->COL_GRIDSQUARE);
				if($count != 1) {
					echo ",";
				}

				if($row->COL_SAT_NAME != null) {
						echo "{\"lat\":\"".$stn_loc[0]."\",\"lng\":\"".$stn_loc[1]."\", \"html\":\"Callsign: ".$row->COL_CALL."<br />Date/Time: ".$row->COL_TIME_ON."<br />SAT: ".$row->COL_SAT_NAME."<br />Mode: ";
						echo $row->COL_SUBMODE==null?$row->COL_MODE:$row->COL_SUBMODE;
						echo "\",\"label\":\"".$row->COL_CALL."\"}";
				} else {
						echo "{\"lat\":\"".$stn_loc[0]."\",\"lng\":\"".$stn_loc[1]."\", \"html\":\"Callsign: ".$row->COL_CALL."<br />Date/Time: ".$row->COL_TIME_ON."<br />Band: ".$row->COL_BAND."<br />Mode: ";
						echo $row->COL_SUBMODE==null?$row->COL_MODE:$row->COL_SUBMODE;
						echo "\",\"label\":\"".$row->COL_CALL."\"}";
				}

				$count++;
			}elseif($row->COL_VUCC_GRIDS != null) {

				$grids = explode(",", $row->COL_VUCC_GRIDS);
				if (count($grids) == 2) {
					$grid1 = $this->qra->qra2latlong(trim($grids[0]));
					$grid2 = $this->qra->qra2latlong(trim($grids[1]));

					$coords[]=array('lat' => $grid1[0],'lng'=> $grid1[1]);
					$coords[]=array('lat' => $grid2[0],'lng'=> $grid2[1]);

					$stn_loc = $this->qra->get_midpoint($coords);
				}
				if (count($grids) == 4) {
					$grid1 = $this->qra->qra2latlong(trim($grids[0]));
					$grid2 = $this->qra->qra2latlong(trim($grids[1]));
					$grid3 = $this->qra->qra2latlong(trim($grids[2]));
					$grid4 = $this->qra->qra2latlong(trim($grids[3]));

					$coords[]=array('lat' => $grid1[0],'lng'=> $grid1[1]);
					$coords[]=array('lat' => $grid2[0],'lng'=> $grid2[1]);
					$coords[]=array('lat' => $grid3[0],'lng'=> $grid3[1]);
					$coords[]=array('lat' => $grid4[0],'lng'=> $grid4[1]);

					$stn_loc = $this->qra->get_midpoint($coords);
				}

				if($count != 1) {
					echo ",";
				}

				if($row->COL_SAT_NAME != null) {
					echo "{\"lat\":\"".$stn_loc[0]."\",\"lng\":\"".$stn_loc[1]."\", \"html\":\"Callsign: ".$row->COL_CALL."<br />Date/Time: ".$row->COL_TIME_ON."<br />SAT: ".$row->COL_SAT_NAME."<br />Mode: ";
					echo $row->COL_SUBMODE==null?$row->COL_MODE:$row->COL_SUBMODE;
					echo "\",\"label\":\"".$row->COL_CALL."\"}";
				} else {
					echo "{\"lat\":\"".$stn_loc[0]."\",\"lng\":\"".$stn_loc[1]."\", \"html\":\"Callsign: ".$row->COL_CALL."<br />Date/Time: ".$row->COL_TIME_ON."<br />Band: ".$row->COL_BAND."<br />Mode: ";
					echo $row->COL_SUBMODE==null?$row->COL_MODE:$row->COL_SUBMODE;
					echo "\",\"label\":\"".$row->COL_CALL."\"}";
				}

				$count++;

			} else {
				if($count != 1) {
					echo ",";
				}

				$result = $this->logbook_model->dxcc_lookup($row->COL_CALL, $row->COL_TIME_ON);

				if(isset($result)) {
					$lat = $result['lat'];
					$lng = $result['long'];
				}
				echo "{\"lat\":\"".$lat."\",\"lng\":\"".$lng."\", \"html\":\"Callsign: ".$row->COL_CALL."<br />Date/Time: ".$row->COL_TIME_ON."<br />Band: ".$row->COL_BAND."<br />Mode: ";
				echo $row->COL_SUBMODE==null?$row->COL_MODE:$row->COL_SUBMODE;
				echo "\",\"label\":\"".$row->COL_CALL."\"}";
				$count++;
			}

		}
		echo "]";
		echo "}";
	}

	function view($id) {
		$this->load->model('user_model');
		if(!$this->user_model->authorize($this->config->item('auth_mode'))) { return; }

		$this->load->library('qra');

		$this->load->model('logbook_model');
		$data['query'] = $this->logbook_model->get_qso($id);

        if ($this->session->userdata('user_measurement_base') == NULL) {
            $data['measurement_base'] = $this->config->item('measurement_base');
        }
        else {
            $data['measurement_base'] = $this->session->userdata('user_measurement_base');
        }

        $this->load->model('Qsl_model');
        $data['qslimages'] = $this->Qsl_model->getQslForQsoId($id);
		$data['max_upload'] = ini_get('upload_max_filesize');
		$this->load->view('interface_assets/mini_header', $data);
		$this->load->view('view_log/qso');
		$this->load->view('interface_assets/footer');
	}

	function partial($id) {
		$this->load->model('user_model');
		if(!$this->user_model->authorize($this->config->item('auth_mode'))) { return; }

		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		$html = "";

		if(!empty($logbooks_locations_array)) {
			$this->db->select(''.$this->config->item('table_name').'.COL_CALL, '.$this->config->item('table_name').'.COL_BAND, '.$this->config->item('table_name').'.COL_FREQ, '.$this->config->item('table_name').'.COL_TIME_ON, '.$this->config->item('table_name').'.COL_RST_RCVD, '.$this->config->item('table_name').'.COL_RST_SENT, '.$this->config->item('table_name').'.COL_MODE, '.$this->config->item('table_name').'.COL_SUBMODE, '.$this->config->item('table_name').'.COL_PRIMARY_KEY, '.$this->config->item('table_name').'.COL_SAT_NAME, '.$this->config->item('table_name').'.COL_GRIDSQUARE, '.$this->config->item('table_name').'.COL_QSL_RCVD, '.$this->config->item('table_name').'.COL_EQSL_QSL_RCVD, '.$this->config->item('table_name').'.COL_EQSL_QSL_SENT, '.$this->config->item('table_name').'.COL_QSL_SENT, '.$this->config->item('table_name').'.COL_STX, '.$this->config->item('table_name').'.COL_STX_STRING, '.$this->config->item('table_name').'.COL_SRX, '.$this->config->item('table_name').'.COL_SRX_STRING, '.$this->config->item('table_name').'.COL_LOTW_QSL_SENT, '.$this->config->item('table_name').'.COL_LOTW_QSL_RCVD, '.$this->config->item('table_name').'.COL_VUCC_GRIDS, '.$this->config->item('table_name').'.COL_MY_GRIDSQUARE, '.$this->config->item('table_name').'.COL_CONTEST_ID, '.$this->config->item('table_name').'.COL_STATE, station_profile.*');
			$this->db->from($this->config->item('table_name'));

			$this->db->join('station_profile', 'station_profile.station_id = '.$this->config->item('table_name').'.station_id');
			$this->db->where_in('station_profile.station_id', $logbooks_locations_array);
			$this->db->order_by(''.$this->config->item('table_name').'.COL_TIME_ON', "desc");

			$this->db->like($this->config->item('table_name').'.COL_CALL', $id);
			$this->db->order_by($this->config->item('table_name').".COL_TIME_ON", "desc");
			$this->db->limit(5);

			$query = $this->db->get();
		}

		if (!empty($logbooks_locations_array) && $query->num_rows() > 0)
		{
			$html .= "<div class=\"table-responsive\">";
			$html .= "<table class=\"table\">";
				$html .= "<tr>";
					$html .= "<th>Date</th>";
					$html .= "<th>Callsign</th>";
					$html .= $this->part_table_header_col($this, $this->session->userdata('user_column1')==""?'Mode':$this->session->userdata('user_column1'));
					$html .= $this->part_table_header_col($this, $this->session->userdata('user_column2')==""?'RSTS':$this->session->userdata('user_column2'));
					$html .= $this->part_table_header_col($this, $this->session->userdata('user_column3')==""?'RSTR':$this->session->userdata('user_column3'));
					$html .= $this->part_table_header_col($this, $this->session->userdata('user_column4')==""?'Band':$this->session->userdata('user_column4'));
					switch($this->session->userdata('user_previous_qsl_type')) {
						case 0:
							$html .= "<th>".lang('gen_hamradio_qsl')."</th>";
							break;
						case 1:
							$html .= "<th>".lang('lotw_short')."</th>";
							break;
						case 2:
							$html .= "<th>".lang('eqsl_short')."</th>";
							break;
						default:
							$html .= "<th>".lang('gen_hamradio_qsl')."</th>";
							break;
					}
					$html .= "<th></th>";
				$html .= "</tr>";

			// Get Date format
			if($this->session->userdata('user_date_format')) {
				// If Logged in and session exists
				$custom_date_format = $this->session->userdata('user_date_format');
			} else {
				// Get Default date format from /config/cloudlog.php
				$custom_date_format = $this->config->item('qso_date_format');
			}

			foreach ($query->result() as $row)
			{

				$timestamp = strtotime($row->COL_TIME_ON);

				$html .= "<tr>";
					$html .= "<td>".date($custom_date_format, $timestamp). date(' H:i',strtotime($row->COL_TIME_ON)) . "</td>";
					$html .= "<td><a id='edit_qso' href='javascript:displayQso(" . $row->COL_PRIMARY_KEY . ");'>" . str_replace('0','&Oslash;',strtoupper($row->COL_CALL)) . "</a></td>";
					$html .= $this->part_table_col($row, $this->session->userdata('user_column1')==""?'Mode':$this->session->userdata('user_column1'));
					$html .= $this->part_table_col($row, $this->session->userdata('user_column2')==""?'RSTS':$this->session->userdata('user_column2'));
					$html .= $this->part_table_col($row, $this->session->userdata('user_column3')==""?'RSTR':$this->session->userdata('user_column3'));
					$html .= $this->part_table_col($row, $this->session->userdata('user_column4')==""?'Band':$this->session->userdata('user_column4'));
					if ($this->session->userdata('user_previous_qsl_type') == 1) {
						$html .= "<td class=\"lotw\">";
						$html .= "<span class=\"qsl-";
						switch ($row->COL_LOTW_QSL_SENT) {
							case "Y":
								$html .= "green";
								break;
							default:
								$html .= "red";
						}
						$html .= "\">&#9650;</span>";
						$html .= "<span class=\"qsl-";
						switch ($row->COL_LOTW_QSL_RCVD) {
							case "Y":
								$html .= "green";
								break;
							default:
								$html .= "red";
						}
						$html .= "\">&#9660;</span>";
						$html .= "</td>";
					} else if ($this->session->userdata('user_previous_qsl_type') == 2) {
						$html .= "<td class=\"eqsl\">";
						$html .= "<span class=\"qsl-";
						switch ($row->COL_EQSL_QSL_SENT) {
							case "Y":
								$html .= "green";
								break;
							default:
								$html .= "red";
						}
						$html .= "\">&#9650;</span>";
						$html .= "<span class=\"qsl-";
						switch ($row->COL_EQSL_QSL_RCVD) {
							case "Y":
								$html .= "green";
								break;
							default:
								$html .= "red";
						}
						$html .= "\">&#9660;</span>";
						$html .= "</td>";
					} else {
						$html .= "<td class=\"qsl\">";
						$html .= "<span class=\"qsl-";
						switch ($row->COL_QSL_SENT) {
							case "Y":
								$html .= "green";
								break;
							case "Q":
								$html .= "yellow";
								break;
							case "R":
								$html .= "yellow";
								break;
							case "I":
								$html .= "grey";
								break;
							default:
								$html .= "red";
						}
						$html .= "\">&#9650;</span>";
						$html .= "<span class=\"qsl-";
						switch ($row->COL_QSL_RCVD) {
							case "Y":
								$html .= "green";
								break;
							case "Q":
								$html .= "yellow";
								break;
							case "R":
								$html .= "yellow";
								break;
							case "I":
								$html .= "grey";
								break;
							default:
								$html .= "red";
						}
						$html .= "\">&#9660;</span>";
						$html .= "</td>";
					}
					$html .= "<td><span class=\"badge badge-info\">".$row->station_callsign."</span></td>";
				$html .= "</tr>";
			}
			$html .= "</table>";
			$html .= "</div>";
			return $html;
		} else {
				if ($this->config->item('callbook') == "qrz" && $this->config->item('qrz_username') != null && $this->config->item('qrz_password') != null) {
					// Lookup using QRZ
					$this->load->library('qrz');

					if(!$this->session->userdata('qrz_session_key')) {
						$qrz_session_key = $this->qrz->session($this->config->item('qrz_username'), $this->config->item('qrz_password'));
						$this->session->set_userdata('qrz_session_key', $qrz_session_key);
					}
					$callsign['callsign'] = $this->qrz->search($id, $this->session->userdata('qrz_session_key'), $this->config->item('use_fullname'));

					if (empty($callsign['callsign']['callsign'])) {
						$qrz_session_key = $this->qrz->session($this->config->item('qrz_username'), $this->config->item('qrz_password'));
						$this->session->set_userdata('qrz_session_key', $qrz_session_key);
						$callsign['callsign'] = $this->qrz->search($id, $this->session->userdata('qrz_session_key'), $this->config->item('use_fullname'));
					}
				} else if ($this->config->item('callbook') == "hamqth" && $this->config->item('hamqth_username') != null && $this->config->item('hamqth_password') != null) {
					// Load the HamQTH library
					$this->load->library('hamqth');

					if(!$this->session->userdata('hamqth_session_key')) {
						$hamqth_session_key = $this->hamqth->session($this->config->item('hamqth_username'), $this->config->item('hamqth_password'));
						$this->session->set_userdata('hamqth_session_key', $hamqth_session_key);
					}

					$callsign['callsign'] = $this->hamqth->search($id, $this->session->userdata('hamqth_session_key'));

					// If HamQTH session has expired, start a new session and retry the search.
					if($callsign['callsign']['error'] == "Session does not exist or expired") {
						$hamqth_session_key = $this->hamqth->session($this->config->item('hamqth_username'), $this->config->item('hamqth_password'));
						$this->session->set_userdata('hamqth_session_key', $hamqth_session_key);
						$callsign['callsign'] = $this->hamqth->search($callsign, $this->session->userdata('hamqth_session_key'));
					}
					if (isset($data['callsign']['gridsquare'])) {
						$CI = &get_instance();
						$CI->load->model('logbook_model');
						$callsign['grid_worked'] = $CI->logbook_model->check_if_grid_worked_in_logbook(strtoupper(substr($data['callsign']['gridsquare'],0,4)), 0, $this->session->userdata('user_default_band'));
					}
					if (isset($callsign['callsign']['error'])) {
						$callsign['error'] = $callsign['callsign']['error'];
					}
				} else {
					$callsign['error'] = 'Lookup not configured. Please review configuration.';
				}

				// There's no hamli integration? Disabled for now.
				/*else {
					// Lookup using hamli
					$this->load->library('hamli');

					$callsign['callsign'] = $this->hamli->callsign($id);
				}*/

				if (isset($callsign['callsign']['gridsquare'])) {
					$CI = &get_instance();
					$CI->load->model('logbook_model');
					$callsign['grid_worked'] = $CI->logbook_model->check_if_grid_worked_in_logbook(strtoupper(substr($callsign['callsign']['gridsquare'],0,4)), 0, $this->session->userdata('user_default_band'));
				}
				if (isset($callsign['callsign']['error'])) {
					$callsign['error'] = $callsign['callsign']['error'];
				}
				$callsign['id'] = strtoupper($id);

				return $this->load->view('search/result', $callsign, true);
		}
	}

	function search_result($id="", $id2="") {
		$this->load->model('user_model');

		if(!$this->user_model->authorize($this->config->item('auth_mode'))) { return; }

		$fixedid = $id;

		if ($id2 != "") {
			if (strlen($id2)>3) {	// Last Element longer than 3 chars? Take that as call
				$fixedid = $id2;
			} else {		// Last Element up to 3 Chars? Take first element as Call
				$fixedid = $id;
			}
		}

		$query = $this->querydb($fixedid);

		if ($query->num_rows() == 0) {
			$query = $this->querydb($id);

			if ($query->num_rows() > 0) {
				$data['results'] = $query;
				$this->load->view('view_log/partial/log_ajax.php', $data);
			}
			else {
				$this->load->model('search');

				$iota_search = $this->search->callsign_iota($id);

				if ($iota_search->num_rows() > 0)
				{
					$data['results'] = $iota_search;
					$this->load->view('view_log/partial/log_ajax.php', $data);
				} else {
					if ($this->config->item('callbook') == "qrz" && $this->config->item('qrz_username') != null && $this->config->item('qrz_password') != null) {
						// Lookup using QRZ
						$this->load->library('qrz');

						if(!$this->session->userdata('qrz_session_key')) {
							$qrz_session_key = $this->qrz->session($this->config->item('qrz_username'), $this->config->item('qrz_password'));
							$this->session->set_userdata('qrz_session_key', $qrz_session_key);
						}

						$data['callsign'] = $this->qrz->search($id, $this->session->userdata('qrz_session_key'), $this->config->item('use_fullname'));
						if (isset($data['callsign']['gridsquare'])) {
							$CI = &get_instance();
							$CI->load->model('logbook_model');
							$data['grid_worked'] = $CI->logbook_model->check_if_grid_worked_in_logbook(strtoupper(substr($data['callsign']['gridsquare'],0,4)), 0, $this->session->userdata('user_default_band'));
						}
						if (isset($data['callsign']['error'])) {
							$data['error'] = $data['callsign']['error'];
						}
					} else if ($this->config->item('callbook') == "hamqth" && $this->config->item('hamqth_username') != null && $this->config->item('hamqth_password') != null) {
						// Load the HamQTH library
						$this->load->library('hamqth');

						if(!$this->session->userdata('hamqth_session_key')) {
							$hamqth_session_key = $this->hamqth->session($this->config->item('hamqth_username'), $this->config->item('hamqth_password'));
							$this->session->set_userdata('hamqth_session_key', $hamqth_session_key);
						}

						$data['callsign'] = $this->hamqth->search($id, $this->session->userdata('hamqth_session_key'));

						// If HamQTH session has expired, start a new session and retry the search.
						if($data['callsign']['error'] == "Session does not exist or expired") {
							$hamqth_session_key = $this->hamqth->session($this->config->item('hamqth_username'), $this->config->item('hamqth_password'));
							$this->session->set_userdata('hamqth_session_key', $hamqth_session_key);
							$data['callsign'] = $this->hamqth->search($callsign, $this->session->userdata('hamqth_session_key'));
						}
						if (isset($data['callsign']['gridsquare'])) {
							$CI = &get_instance();
							$CI->load->model('logbook_model');
							$data['grid_worked'] = $CI->logbook_model->check_if_grid_worked_in_logbook(strtoupper(substr($data['callsign']['gridsquare'],0,4)), 0, $this->session->userdata('user_default_band'));
						}
						if (isset($data['callsign']['error'])) {
							$data['error'] = $data['callsign']['error'];
						}
					} else {
						$data['error'] = 'Lookup not configured. Please review configuration.';
					} /*else {
						// Lookup using hamli
						$this->load->library('hamli');

						$data['callsign'] = $this->hamli->callsign($id);
					}*/

					$data['id'] = strtoupper($id);

					$this->load->view('search/result', $data);
				}
			}
		} else {
			$data['results'] = $query;
			$this->load->view('view_log/partial/log_ajax.php', $data);
		}
	}

	function querydb($id) {
		$this->db->from($this->config->item('table_name'));
		$this->db->join('station_profile', 'station_profile.station_id = '.$this->config->item('table_name').'.station_id');
		$this->db->join('dxcc_entities', 'dxcc_entities.adif = '.$this->config->item('table_name').'.COL_DXCC', 'left outer');
		$this->db->join('lotw_users', 'lotw_users.callsign = '.$this->config->item('table_name').'.col_call', 'left outer');
		$this->db->group_start();
		$this->db->like(''.$this->config->item('table_name').'.COL_CALL', $id);
		$this->db->or_like(''.$this->config->item('table_name').'.COL_GRIDSQUARE', $id);
		$this->db->or_like(''.$this->config->item('table_name').'.COL_VUCC_GRIDS', $id);
		$this->db->group_end();
		$this->db->where('station_profile.user_id', $this->session->userdata('user_id'));
		$this->db->order_by(''.$this->config->item('table_name').'.COL_TIME_ON', 'desc');
		return $this->db->get();
  }

	function search_duplicates($station_id) {
		$station_id = $this->security->xss_clean($station_id);

		$this->load->model('user_model');

		if(!$this->user_model->authorize($this->config->item('auth_mode'))) { return; }

		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		if (!$logbooks_locations_array) {
			return null;
		}

		$location_list = "'".implode("','",$logbooks_locations_array)."'";

		$sql = 'select count(*) as occurence, COL_CALL, COL_MODE, COL_SUBMODE, station_callsign, COL_SAT_NAME, COL_BAND,  min(col_time_on) Mintime, max(col_time_on) Maxtime from ' . $this->config->item('table_name') .
		' join station_profile on ' . $this->config->item('table_name') . '.station_id = station_profile.station_id where ' . $this->config->item('table_name') .'.station_id in ('. $location_list . ')';

		if ($station_id != 'All') {
			$sql .= ' and station_profile.station_id = ' . $station_id;
		}

		$sql .= ' group by col_call, col_mode, COL_SUBMODE, STATION_CALLSIGN, col_band, COL_SAT_NAME having count(*) > 1 and timediff(maxtime, mintime) < 3000';

		$query = $this->db->query($sql);

		$data['qsos'] = $query;

		$this->load->view('search/duplicates_result.php', $data);

	}

	function search_lotw_unconfirmed($station_id) {
		$station_id = $this->security->xss_clean($station_id);

		$this->load->model('user_model');

		if(!$this->user_model->authorize($this->config->item('auth_mode'))) { return; }

		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		if (!$logbooks_locations_array) {
			return null;
		}

		$location_list = "'".implode("','",$logbooks_locations_array)."'";

		$sql = 'select COL_CALL, COL_MODE, COL_SUBMODE, station_callsign, COL_SAT_NAME, COL_BAND, COL_TIME_ON, lotw_users.lastupload from ' . $this->config->item('table_name') .
		' join station_profile on ' . $this->config->item('table_name') . '.station_id = station_profile.station_id
		join lotw_users on ' . $this->config->item('table_name') . '.col_call = lotw_users.callsign
		where ' . $this->config->item('table_name') .'.station_id in ('. $location_list . ')';

		if ($station_id != 'All') {
			$sql .= ' and station_profile.station_id = ' . $station_id;
		}

		$sql .= " and COL_LOTW_QSL_RCVD <> 'Y' and " . $this->config->item('table_name') . ".COL_TIME_ON < lotw_users.lastupload";

		$query = $this->db->query($sql);

		$data['qsos'] = $query;

		$this->load->view('search/lotw_unconfirmed_result.php', $data);

	}

	function search_incorrect_cq_zones($station_id) {
		$station_id = $this->security->xss_clean($station_id);

		$this->load->model('user_model');

		if(!$this->user_model->authorize($this->config->item('auth_mode'))) { return; }

		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		if (!$logbooks_locations_array) {
			return null;
		}

		$location_list = "'".implode("','",$logbooks_locations_array)."'";

		$sql = 'select *, (select group_concat(distinct cqzone order by cqzone) from dxcc_master where countrycode = thcv.col_dxcc and cqzone <> \'\' order by cqzone asc) as correctcqzone from ' . $this->config->item('table_name') .
		' thcv join station_profile on thcv.station_id = station_profile.station_id where thcv.station_id in ('. $location_list . ')
		and not exists (select 1 from dxcc_master where countrycode = thcv.col_dxcc and cqzone = col_cqz) and col_dxcc > 0
		';

		if ($station_id != 'All') {
			$sql .= ' and station_profile.station_id = ' . $station_id;
		}

		$query = $this->db->query($sql);

		$data['qsos'] = $query;

		$this->load->view('search/cqzones_result.php', $data);
	}

	/*
	 * Provide a dxcc search, returning results json encoded
	 */
	function local_find_dxcc($call = "", $date = "") {
		$this->load->model("logbook_model");
		if ($date == ''){
			$date = date("Y-m-d");
		}
		$ans = $this->logbook_model->check_dxcc_stored_proc($call, $date);
		print json_encode($ans);
	}

	function dxcheck($call = "", $date = "") {
		$this->load->model("logbook_model");
		if ($date == ''){
			$date = date("Y-m-d");
		}
		$ans = $this->logbook_model->dxcc_lookup($call, $date);
		return $ans;
	}

    function getentity($adif) {
        $this->load->model("logbook_model");

        $entity = $this->logbook_model->get_entity($adif);
        return $entity;
    }


	/* return station bearing */
	function searchbearing() {
			$locator = xss_clean($this->input->post('grid'));
			$station_id = xss_clean($this->input->post('stationProfile'));
			$this->load->library('Qra');

			if($locator != null) {
				if (isset($station_id)) {
					// be sure that station belongs to user
					$this->load->model('Stations');
					if (!$this->Stations->check_station_is_accessible($station_id)) {
						return "";
					}

					// get station profile
					$station_profile = $this->Stations->profile_clean($station_id);

					// get locator
					$mylocator = $station_profile->station_gridsquare;
				} else if($this->session->userdata('user_locator') != null){
					$mylocator = $this->session->userdata('user_locator');
				} else {
					$mylocator = $this->config->item('locator');
				}

				if ($this->session->userdata('user_measurement_base') == NULL) {
					$measurement_base = $this->config->item('measurement_base');
				}
				else {
					$measurement_base = $this->session->userdata('user_measurement_base');
				}

				$bearing = $this->qra->bearing($mylocator, $locator, $measurement_base);

				echo $bearing;
			}
			return "";
	}

	/* return distance */
	function searchdistance() {
			$locator = xss_clean($this->input->post('grid'));
			$station_id = xss_clean($this->input->post('stationProfile'));
			$this->load->library('Qra');

			if($locator != null) {
				if (isset($station_id)) {
					// be sure that station belongs to user
					$this->load->model('Stations');
					if (!$this->Stations->check_station_is_accessible($station_id)) {
						return 0;
					}

					// get station profile
					$station_profile = $this->Stations->profile_clean($station_id);

					// get locator
					$mylocator = $station_profile->station_gridsquare;
				} else if($this->session->userdata('user_locator') != null){
					$mylocator = $this->session->userdata('user_locator');
				} else {
					$mylocator = $this->config->item('locator');
				}

				$distance = $this->qra->distance($mylocator, $locator, 'K');

				echo $distance;
			}
			return 0;
	}

	/* return station bearing */
	function bearing($locator, $unit = 'M', $station_id = null) {
			$this->load->library('Qra');

			if($locator != null) {
				if (isset($station_id)) {
					// be sure that station belongs to user
					$this->load->model('Stations');
					if (!$this->Stations->check_station_is_accessible($station_id)) {
						return "";
					}

					// get station profile
					$station_profile = $this->Stations->profile_clean($station_id);

					// get locator
					$mylocator = $station_profile->station_gridsquare;
				} else if($this->session->userdata('user_locator') != null){
					$mylocator = $this->session->userdata('user_locator');
				} else {
					$mylocator = $this->config->item('locator');
				}

				$bearing = $this->qra->bearing($mylocator, $locator, $unit);

				return $bearing;
			}
			return "";
	}

	/* return distance */
	function distance($locator, $station_id = null) {
			$distance = 0;
			$this->load->library('Qra');

			if($locator != null) {
				if (isset($station_id)) {
					// be sure that station belongs to user
					$this->load->model('Stations');
					if (!$this->Stations->check_station_is_accessible($station_id)) {
						return 0;
					}

					// get station profile
					$station_profile = $this->Stations->profile_clean($station_id);

					// get locator
					$mylocator = $station_profile->station_gridsquare;
				} else if($this->session->userdata('user_locator') != null){
					$mylocator = $this->session->userdata('user_locator');
				} else {
					$mylocator = $this->config->item('locator');
				}

				$distance = $this->qra->distance($mylocator, $locator, 'K');

			}
			return $distance;
	}

	function qralatlng($qra) {
		$this->load->library('Qra');
		$latlng = $this->qra->qra2latlong($qra);
		return $latlng;
	}

	function qralatlngjson() {
		$qra = xss_clean($this->input->post('qra'));
		$this->load->library('Qra');
		$latlng = $this->qra->qra2latlong($qra);
		print json_encode($latlng);
	}

    function get_qsos($num, $offset) {
        $this->db->select(''.$this->config->item('table_name').'.COL_CALL, '.$this->config->item('table_name').'.COL_BAND, '.$this->config->item('table_name').'.COL_TIME_ON, '.$this->config->item('table_name').'.COL_RST_RCVD, '.$this->config->item('table_name').'.COL_RST_SENT, '.$this->config->item('table_name').'.COL_MODE, '.$this->config->item('table_name').'.COL_SUBMODE, '.$this->config->item('table_name').'.COL_NAME, '.$this->config->item('table_name').'.COL_COUNTRY, '.$this->config->item('table_name').'.COL_PRIMARY_KEY, '.$this->config->item('table_name').'.COL_SAT_NAME, '.$this->config->item('table_name').'.COL_GRIDSQUARE, '.$this->config->item('table_name').'.COL_QSL_RCVD, '.$this->config->item('table_name').'.COL_EQSL_QSL_RCVD, '.$this->config->item('table_name').'.COL_EQSL_QSL_SENT, '.$this->config->item('table_name').'.COL_QSL_SENT, '.$this->config->item('table_name').'.COL_STX, '.$this->config->item('table_name').'.COL_STX_STRING, '.$this->config->item('table_name').'.COL_SRX, '.$this->config->item('table_name').'.COL_SRX_STRING, '.$this->config->item('table_name').'.COL_LOTW_QSL_SENT, '.$this->config->item('table_name').'.COL_LOTW_QSL_RCVD, '.$this->config->item('table_name').'.COL_VUCC_GRIDS, station_profile.*');
        $this->db->from($this->config->item('table_name'));

        $this->db->join('station_profile', 'station_profile.station_id = '.$this->config->item('table_name').'.station_id');
        $this->db->order_by(''.$this->config->item('table_name').'.COL_TIME_ON', "desc");

        $this->db->limit($num);
        $this->db->offset($offset);

        return $this->db->get();
    }

	function part_table_header_col($ctx, $name) {
		$ret='';
		switch($name) {
		case 'Mode': $ret.= '<th>'.$ctx->lang->line('gen_hamradio_mode').'</th>'; break;
		case 'RSTS': $ret.= '<th class="d-none d-sm-table-cell">'.$ctx->lang->line('gen_hamradio_rsts').'</th>'; break;
		case 'RSTR': $ret.= '<th class="d-none d-sm-table-cell">'.$ctx->lang->line('gen_hamradio_rstr').'</th>'; break;
		case 'Country': $ret.= '<th>'.$ctx->lang->line('general_word_country').'</th>'; break;
		case 'IOTA': $ret.= '<th>'.$ctx->lang->line('gen_hamradio_iota').'</th>'; break;
		case 'SOTA': $ret.= '<th>'.$ctx->lang->line('gen_hamradio_sota').'</th>'; break;
		case 'WWFF': $ret.= '<th>'.$ctx->lang->line('gen_hamradio_wwff').'</th>'; break;
		case 'POTA': $ret.= '<th>'.$ctx->lang->line('gen_hamradio_pota').'</th>'; break;
		case 'State': $ret.= '<th>'.$ctx->lang->line('gen_hamradio_state').'</th>'; break;
		case 'Grid': $ret.= '<th>'.$ctx->lang->line('gen_hamradio_gridsquare').'</th>'; break;
		case 'Distance': $ret.= '<th>'.$ctx->lang->line('gen_hamradio_distance').'</th>'; break;
		case 'Band': $ret.= '<th>'.$ctx->lang->line('gen_hamradio_band').'</th>'; break;
		case 'Frequency': $ret.= '<th>'.$ctx->lang->line('gen_hamradio_frequency').'</th>'; break;
		case 'Operator': $ret.= '<th>'.$ctx->lang->line('gen_hamradio_operator').'</th>'; break;
		}
		return $ret;
	}

	function part_QrbCalcLink($mygrid, $grid, $vucc) {
		$ret='';
		if (!empty($grid)) {
			$ret.= $grid . ' <a href="javascript:spawnQrbCalculator(\'' . $mygrid . '\',\'' . $grid . '\')"><i class="fas fa-globe"></i></a>';
		} else if (!empty($vucc)) {
			$ret.= $vucc .' <a href="javascript:spawnQrbCalculator(\'' . $mygrid . '\',\'' . $vucc . '\')"><i class="fas fa-globe"></i></a>';
		}
		return $ret;
	}

	function part_table_col($row, $name) {
		$ret='';
		$ci =& get_instance();
		switch($name) {
		case 'Mode':    $ret.= '<td>'; $ret.= $row->COL_SUBMODE==null?$row->COL_MODE:$row->COL_SUBMODE . '</td>'; break;
		case 'RSTS':    $ret.= '<td class="d-none d-sm-table-cell">' . $row->COL_RST_SENT; if ($row->COL_STX) { $ret.= ' <span data-toggle="tooltip" data-original-title="'.($row->COL_CONTEST_ID!=""?$row->COL_CONTEST_ID:"n/a").'" class="badge badge-light">'; $ret.=sprintf("%03d", $row->COL_STX); $ret.= '</span>';} if ($row->COL_STX_STRING) { $ret.= ' <span data-toggle="tooltip" data-original-title="'.($row->COL_CONTEST_ID!=""?$row->COL_CONTEST_ID:"n/a").'" class="badge badge-light">' . $row->COL_STX_STRING . '</span>';} $ret.= '</td>'; break;
		case 'RSTR':    $ret.= '<td class="d-none d-sm-table-cell">' . $row->COL_RST_RCVD; if ($row->COL_SRX) { $ret.= ' <span data-toggle="tooltip" data-original-title="'.($row->COL_CONTEST_ID!=""?$row->COL_CONTEST_ID:"n/a").'" class="badge badge-light">'; $ret.=sprintf("%03d", $row->COL_SRX); $ret.= '</span>';} if ($row->COL_SRX_STRING) { $ret.= ' <span data-toggle="tooltip" data-original-title="'.($row->COL_CONTEST_ID!=""?$row->COL_CONTEST_ID:"n/a").'" class="badge badge-light">' . $row->COL_SRX_STRING . '</span>';} $ret.= '</td>'; break;
		case 'Country': $ret.= '<td>' . ucwords(strtolower(($row->COL_COUNTRY))); if ($row->end != NULL) $ret.= ' <span class="badge badge-danger">'.$ci->lang->line('gen_hamradio_deleted_dxcc').'</span>'  . '</td>'; break;
		case 'IOTA':    $ret.= '<td>' . ($row->COL_IOTA) . '</td>'; break;
		case 'SOTA':    $ret.= '<td>' . ($row->COL_SOTA_REF) . '</td>'; break;
		case 'WWFF':    $ret.= '<td>' . ($row->COL_WWFF_REF) . '</td>'; break;
		case 'POTA':    $ret.= '<td>' . ($row->COL_POTA_REF) . '</td>'; break;
		case 'Grid':    $ret.= '<td>' . $this->part_QrbCalcLink($row->COL_MY_GRIDSQUARE, $row->COL_VUCC_GRIDS, $row->COL_GRIDSQUARE) . '</td>'; break;
		case 'Distance':    $ret.= '<td>' . ($row->COL_DISTANCE ? $row->COL_DISTANCE . '&nbsp;km' : '') . '</td>'; break;
		case 'Band':    $ret.= '<td>'; if($row->COL_SAT_NAME != null) { $ret.= '<a href="https://db.satnogs.org/search/?q='.$row->COL_SAT_NAME.'" target="_blank">'.$row->COL_SAT_NAME.'</a></td>'; } else { $ret.= strtolower($row->COL_BAND); } $ret.= '</td>'; break;
		case 'Frequency':    $ret.= '<td>'; if($row->COL_SAT_NAME != null) { $ret.= '<a href="https://db.satnogs.org/search/?q='.$row->COL_SAT_NAME.'" target="_blank">'.$row->COL_SAT_NAME.'</a></td>'; } else { if($row->COL_FREQ != null) { $ret.= $ci->frequency->hz_to_mhz($row->COL_FREQ); } else { $ret.= strtolower($row->COL_BAND); } } $ret.= '</td>'; break;
		case 'State':   $ret.= '<td>' . ($row->COL_STATE) . '</td>'; break;
		case 'Operator': $ret.= '<td>' . ($row->COL_OPERATOR) . '</td>'; break;
		}
		return $ret;
	}
}
