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
			$this->session->set_flashdata('notice', $this->lang->line('error_no_logbook_found') . ' <a href="' . site_url('logbooks') . '" title="Station Logbooks">Station Logbooks</a>');
		}

		// Calculate Lat/Lng from Locator to use on Maps
		if($this->session->userdata('user_locator')) {
				$this->load->library('qra');
				$qra_position = $this->qra->qra2latlong($this->session->userdata('user_locator'));
				$data['qra'] = "set";
				$data['qra_lat'] = $qra_position[0];
				$data['qra_lng'] = $qra_position[1];
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

	function json($callsign, $type, $band, $mode, $station_id = null)
	{
		$this->load->model('user_model');
		if(!$this->user_model->authorize($this->config->item('auth_mode'))) { return; }

		// Convert - in Callsign to / Used for URL processing
		$callsign = str_replace("-","/",$callsign);

		// Check if callsign is an LOTW User
		$lotw_member = "";
		$lotw_file_name = "./updates/lotw_users.csv";

		if (file_exists($lotw_file_name)) {
			$f = fopen($lotw_file_name, "r");
			$result = false;
			while ($row = fgetcsv($f)) {
				if ($row[0] == strtoupper($callsign)) {
					$result = $row[0];
					$lotw_member = "active";
					break;
				}
			}

			if($lotw_member != "active") {
				$lotw_member = "not found";
			}
			fclose($f);
		} else {
			$lotw_member = "not found";
		}

		// Check Database for all other data
		$this->load->model('logbook_model');

		$return = [
			"dxcc" => false,
			"callsign_name" => "",
			"callsign_qra"  => "",
			"callsign_qth"  => "",
			"callsign_iota" => "",
			"callsign_state" => "",
			"callsign_us_county" => "",
			"qsl_manager" => "",
			"bearing" 		=> "",
			"workedBefore" => false,
			"lotw_member" => $lotw_member,
			"image" => "",
		];

		$return['dxcc'] = $this->dxcheck($callsign);
		$return['partial'] = $this->partial($callsign);

		$callbook = $this->logbook_model->loadCallBook($callsign, $this->config->item('use_fullname'));

		// Do we have local data for the Callsign?
		if($this->logbook_model->call_name($callsign) != null)
		{
			if ($this->session->userdata('user_measurement_base') == NULL) {
				$measurement_base = $this->config->item('measurement_base');
			} else {
				$measurement_base = $this->session->userdata('user_measurement_base');
			}

			$return['callsign_name'] =  $this->logbook_model->call_name($callsign);
			$return['callsign_qra'] = $this->logbook_model->call_qra($callsign);
			$return['callsign_qth'] = $this->logbook_model->call_qth($callsign);
			$return['callsign_iota'] = $this->logbook_model->call_iota($callsign);
			$return['qsl_manager'] = $this->logbook_model->call_qslvia($callsign);
			$return['callsign_state'] = $this->logbook_model->call_state($callsign);
			$return['callsign_us_county'] = $this->logbook_model->call_us_county($callsign);
			$return['bearing'] = $this->bearing($return['callsign_qra'], $measurement_base, $station_id);
			$return['workedBefore'] = $this->worked_grid_before($return['callsign_qra'], $type, $band, $mode);
			if ($this->session->userdata('user_show_profile_image')) {
				if (isset($callbook)) {
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
			}

			echo json_encode($return, JSON_PRETTY_PRINT);
			return;
		}

		if (isset($callbook))
		{
			$return['callsign_name'] = $callbook['name'];
			$return['callsign_qra'] = $callbook['gridsquare'];
			$return['callsign_qth'] = $callbook['city'];
			$return['callsign_iota'] = $callbook['iota'];
			$return['callsign_state'] = $callbook['state'];
			$return['callsign_us_county'] = $callbook['us_county'];
			if ($this->session->userdata('user_show_profile_image')) {
				if ($callbook['image'] == "") {
					$return['image'] = "n/a";
				} else {
					$return['image'] = $callbook['image'];
				}
			}

			if(isset($callbook['qslmgr'])) {
				$return['qsl_manager'] = $callbook['qslmgr'];
			}
			if ($return['callsign_qra'] != "") {
				$return['latlng'] = $this->qralatlng($return['callsign_qra']);
			}
			$return['workedBefore'] = $this->worked_grid_before($return['callsign_qra'], $type, $band, $mode);
		}

		if ($this->session->userdata('user_measurement_base') == NULL) {
			$measurement_base = $this->config->item('measurement_base');
		} else {
			$measurement_base = $this->session->userdata('user_measurement_base');
		}

		$return['bearing'] = $this->bearing($return['callsign_qra'], $measurement_base, $station_id);

		echo json_encode($return, JSON_PRETTY_PRINT);

		return;
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
				$this->db->where('COL_MODE', $mode);
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
		];

		$CI =& get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		if($type == "SAT") {
			$this->db->where('COL_PROP_MODE', 'SAT');
		} else {
			$this->db->where('COL_MODE', $mode);
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

		header('Content-Type: application/json');
		echo json_encode($return, JSON_PRETTY_PRINT);

		return;
	}

	function jsonlookupdxcc($country, $type, $band, $mode) {

		$return = [
			"workedBefore" => false,
		];

		$CI =& get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		if(!empty($logbooks_locations_array)) { 
			if($type == "SAT") {
				$this->db->where('COL_PROP_MODE', 'SAT');
			} else {
				$this->db->where('COL_MODE', $mode);
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

			header('Content-Type: application/json');
			echo json_encode($return, JSON_PRETTY_PRINT);

			return;
		} else {
			$return['workedBefore'] = false;

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
		];

		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		if(!empty($logbooks_locations_array)) { 
			if($type == "SAT") {
				$this->db->where('COL_PROP_MODE', 'SAT');
			} else {
				$this->db->where('COL_MODE', $mode);
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

			header('Content-Type: application/json');
			echo json_encode($return, JSON_PRETTY_PRINT);
			return;
		} else {
			$return['workedBefore'] = false;
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
	    $this->db->select(''.$this->config->item('table_name').'.COL_CALL, '.$this->config->item('table_name').'.COL_BAND, '.$this->config->item('table_name').'.COL_TIME_ON, '.$this->config->item('table_name').'.COL_RST_RCVD, '.$this->config->item('table_name').'.COL_RST_SENT, '.$this->config->item('table_name').'.COL_MODE, '.$this->config->item('table_name').'.COL_SUBMODE, '.$this->config->item('table_name').'.COL_PRIMARY_KEY, '.$this->config->item('table_name').'.COL_SAT_NAME, '.$this->config->item('table_name').'.COL_GRIDSQUARE, '.$this->config->item('table_name').'.COL_QSL_RCVD, '.$this->config->item('table_name').'.COL_EQSL_QSL_RCVD, '.$this->config->item('table_name').'.COL_EQSL_QSL_SENT, '.$this->config->item('table_name').'.COL_QSL_SENT, '.$this->config->item('table_name').'.COL_STX, '.$this->config->item('table_name').'.COL_STX_STRING, '.$this->config->item('table_name').'.COL_SRX, '.$this->config->item('table_name').'.COL_SRX_STRING, '.$this->config->item('table_name').'.COL_LOTW_QSL_SENT, '.$this->config->item('table_name').'.COL_LOTW_QSL_RCVD, '.$this->config->item('table_name').'.COL_VUCC_GRIDS, station_profile.*');
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
					$html .= "<td>Date</td>";
					$html .= "<td>Callsign</td>";
					$html .= "<td>RST (S)</td>";
					$html .= "<td>RST (R)</td>";
					$html .= "<td>Band</td>";
					$html .= "<td>Mode</td>";
					$html .= "<td>QSL</td>";
					$html .= "<td></td>";
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
					$html .= "<td>".$row->COL_RST_SENT."</td>";
					$html .= "<td>".$row->COL_RST_RCVD."</td>";
					if($row->COL_SAT_NAME != null) {
									$html .= "<td>".$row->COL_SAT_NAME."</td>";
					} else {
								$html .= "<td>".$row->COL_BAND."</td>";
					}
					if ($row->COL_SUBMODE==null)
						$html .= "<td>".$row->COL_MODE."</td>";
					else
						$html .= "<td>".$row->COL_SUBMODE."</td>";
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
                    $data= $this->qrz->search($id, $this->session->userdata('qrz_session_key'), $this->config->item('use_fullname'));

                    if (empty($data['callsign']))
                    {
                        $qrz_session_key = $this->qrz->session($this->config->item('qrz_username'), $this->config->item('qrz_password'));
                        $this->session->set_userdata('qrz_session_key', $qrz_session_key);
                        $data = $this->qrz->search($id, $this->session->userdata('qrz_session_key'), $this->config->item('use_fullname'));
                    }
				}

				// There's no hamli integration? Disabled for now.
				/*else {
					// Lookup using hamli
					$this->load->library('hamli');

					$data['callsign'] = $this->hamli->callsign($id);
				}*/

				$data['id'] = strtoupper($id);

				return $this->load->view('search/result', $data, true);
		}
	}

	function search_result($id="") {
		$this->load->model('user_model');

		if(!$this->user_model->authorize($this->config->item('auth_mode'))) { return; }

		//$this->db->select(''.$this->config->item('table_name').'.COL_CALL, '.$this->config->item('table_name').'.COL_BAND, '.$this->config->item('table_name').'.COL_TIME_ON, '.$this->config->item('table_name').'.COL_RST_RCVD, '.$this->config->item('table_name').'.COL_RST_SENT, '.$this->config->item('table_name').'.COL_MODE, '.$this->config->item('table_name').'.COL_SUBMODE, '.$this->config->item('table_name').'.COL_NAME, '.$this->config->item('table_name').'.COL_COUNTRY, '.$this->config->item('table_name').'.COL_PRIMARY_KEY, '.$this->config->item('table_name').'.COL_SAT_NAME, '.$this->config->item('table_name').'.COL_GRIDSQUARE, '.$this->config->item('table_name').'.COL_QSL_RCVD, '.$this->config->item('table_name').'.COL_EQSL_QSL_RCVD, '.$this->config->item('table_name').'.COL_EQSL_QSL_SENT, '.$this->config->item('table_name').'.COL_QSL_SENT, '.$this->config->item('table_name').'.COL_STX, '.$this->config->item('table_name').'.COL_STX_STRING, '.$this->config->item('table_name').'.COL_SRX, '.$this->config->item('table_name').'.COL_SRX_STRING, '.$this->config->item('table_name').'.COL_LOTW_QSL_SENT, '.$this->config->item('table_name').'.COL_LOTW_QSL_RCVD, '.$this->config->item('table_name').'.COL_VUCC_GRIDS, station_profile.*');

		$this->db->from($this->config->item('table_name'));
		$this->db->join('station_profile', 'station_profile.station_id = '.$this->config->item('table_name').'.station_id');
		$this->db->group_start();
		$this->db->like(''.$this->config->item('table_name').'.COL_CALL', $id);
		$this->db->or_like(''.$this->config->item('table_name').'.COL_GRIDSQUARE', $id);
		$this->db->or_like(''.$this->config->item('table_name').'.COL_VUCC_GRIDS', $id);
		$this->db->group_end();
		$this->db->where('station_profile.user_id', $this->session->userdata('user_id'));
		$this->db->order_by(''.$this->config->item('table_name').'.COL_TIME_ON', 'desc');
		$query = $this->db->get();

		if ($query->num_rows() > 0)
		{
			$data['results'] = $query;
			$this->load->view('view_log/partial/log_ajax.php', $data);
		} else {
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
				} /*else {
					// Lookup using hamli
					$this->load->library('hamli');

					$data['callsign'] = $this->hamli->callsign($id);
				}*/

				$data['id'] = strtoupper($id);

				$this->load->view('search/result', $data);
			}
		}
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
	function searchbearing($locator, $station_id = null) {
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

	function qralatlng($qra) {
		$this->load->library('Qra');
		$latlng = $this->qra->qra2latlong($qra);
		return $latlng;
	}

	function qralatlngjson($qra) {
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

}
