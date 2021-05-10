<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Logbook extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		// Load language files
		$this->lang->load(array(
			'qslcard',
			'lotw',
			'qso'
		));
	}

	function index()
	{
				$this->load->model('user_model');
				if(!$this->user_model->authorize($this->config->item('auth_mode'))) {
						if($this->user_model->validate_session()) {
								$this->user_model->clear_session();
								show_error('Access denied<p>Click <a href="'.site_url('user/login').'">here</a> to log in as another user', 403);
						} else {
								redirect('user/login');
						}
				}

		$this->load->library('pagination');
		$config['base_url'] = base_url().'index.php/logbook/index/';
		$config['total_rows'] = $this->db->count_all($this->config->item('table_name'));
		$config['per_page'] = '25';
		$config['num_links'] = 6;
		$config['full_tag_open'] = '';
		$config['full_tag_close'] = '';
		$config['cur_tag_open'] = '<strong class="active"><a href="">';
		$config['cur_tag_close'] = '</a></strong>';

		$this->pagination->initialize($config);

		//load the model and get results
		$this->load->model('logbook_model');
		$data['results'] = $this->logbook_model->get_qsos($config['per_page'],$this->uri->segment(3));

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

	function json($callsign, $type, $band, $mode)
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
		];

		$return['dxcc'] = $this->dxcheck($callsign);
		$return['partial'] = $this->partial($callsign);

	// Do we have local data for the Callsign?
	if($this->logbook_model->call_name($callsign) != null)
	{
        if ($this->session->userdata('user_measurement_base') == NULL) {
             $measurement_base = $this->config->item('measurement_base');
        }
        else {
            $measurement_base = $this->session->userdata('user_measurement_base');
        }

		$return['callsign_name'] = $this->logbook_model->call_name($callsign);
		$return['callsign_qra'] = $this->logbook_model->call_qra($callsign);
		$return['callsign_qth'] = $this->logbook_model->call_qth($callsign);
		$return['callsign_iota'] = $this->logbook_model->call_iota($callsign);
		$return['qsl_manager'] = $this->logbook_model->call_qslvia($callsign);
        $return['callsign_state'] = $this->logbook_model->call_state($callsign);
		$return['bearing'] = $this->bearing($return['callsign_qra'], $measurement_base);
		$return['workedBefore'] = $this->worked_grid_before($return['callsign_qra'], $type, $band, $mode);

		if ($return['callsign_qra'] != "") {
			$return['latlng'] = $this->qralatlng($return['callsign_qra']);
		}

		echo json_encode($return, JSON_PRETTY_PRINT);
		return;
	}


	if ($this->config->item('callbook') == "qrz" && $this->config->item('qrz_username') != null && $this->config->item('qrz_password') != null)
	{
		// Lookup using QRZ
		$this->load->library('qrz');

		if(!$this->session->userdata('qrz_session_key')) {
			$qrz_session_key = $this->qrz->session($this->config->item('qrz_username'), $this->config->item('qrz_password'));
			$this->session->set_userdata('qrz_session_key', $qrz_session_key);
		}

		$callbook = $this->qrz->search($callsign, $this->session->userdata('qrz_session_key'));
	}

	if ($this->config->item('callbook') == "hamqth" && $this->config->item('hamqth_username') != null && $this->config->item('hamqth_password') != null)
	{
		// Load the HamQTH library
		$this->load->library('hamqth');

		if(!$this->session->userdata('hamqth_session_key')) {
			$hamqth_session_key = $this->hamqth->session($this->config->item('hamqth_username'), $this->config->item('hamqth_password'));
			$this->session->set_userdata('hamqth_session_key', $hamqth_session_key);
		}

		$callbook = $this->hamqth->search($callsign, $this->session->userdata('hamqth_session_key'));

		// If HamQTH session has expired, start a new session and retry the search.
		if($callbook['error'] == "Session does not exist or expired") {
			$hamqth_session_key = $this->hamqth->session($this->config->item('hamqth_username'), $this->config->item('hamqth_password'));
			$this->session->set_userdata('hamqth_session_key', $hamqth_session_key);
			$callbook = $this->hamqth->search($callsign, $this->session->userdata('hamqth_session_key'));
		}
	}

	if (isset($callbook))
	{
		$return['callsign_name'] = $callbook['name'];
		$return['callsign_qra'] = $callbook['gridsquare'];
		$return['callsign_qth'] = $callbook['city'];
		$return['callsign_iota'] = $callbook['iota'];
		$return['callsign_state'] = $callbook['state'];
		$return['callsign_us_county'] = $callbook['us_county'];

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
    }
    else {
        $measurement_base = $this->session->userdata('user_measurement_base');
    }

	$return['bearing'] = $this->bearing($return['callsign_qra'], $measurement_base);

	echo json_encode($return, JSON_PRETTY_PRINT);

	return;
	}

	function worked_grid_before($gridsquare, $type, $band, $mode)
	{
		if (strlen($gridsquare) < 4)
			return false;

		$CI =& get_instance();
    	$CI->load->model('Stations');
    	$station_id = $CI->Stations->find_active();


		if($type == "SAT") {
			$this->db->where('COL_PROP_MODE', 'SAT');
		} else {
			$this->db->where('COL_MODE', $mode);
			$this->db->where('COL_BAND', $band);
			$this->db->where('COL_PROP_MODE !=','SAT');

		}
    	$this->db->where('station_id', $station_id);
		$this->db->like('SUBSTRING(COL_GRIDSQUARE, 1, 4)', substr($gridsquare, 0, 4));
		$this->db->order_by($this->config->item('table_name').".COL_TIME_ON", "desc");
		$this->db->limit(1);


		$query = $this->db->get($this->config->item('table_name'));


		foreach ($query->result() as $workedBeforeRow)
		{
			return true;
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
    	$CI->load->model('Stations');
    	$station_id = $CI->Stations->find_active();

		if($type == "SAT") {
			$this->db->where('COL_PROP_MODE', 'SAT');
		} else {
			$this->db->where('COL_MODE', $mode);
			$this->db->where('COL_BAND', $band);
			$this->db->where('COL_PROP_MODE !=','SAT');

		}

    	$this->db->where('station_id', $station_id);

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
    	$CI->load->model('Stations');
    	$station_id = $CI->Stations->find_active();

		if($type == "SAT") {
			$this->db->where('COL_PROP_MODE', 'SAT');
		} else {
			$this->db->where('COL_MODE', $mode);
			$this->db->where('COL_BAND', $band);
			$this->db->where('COL_PROP_MODE !=','SAT');

		}

    	$this->db->where('station_id', $station_id);
    	$this->db->where('COL_COUNTRY', urldecode($country));

		$query = $this->db->get($this->config->item('table_name'), 1, 0);
		foreach ($query->result() as $workedBeforeRow)
		{
			$return['workedBefore'] = true;
		}

		header('Content-Type: application/json');
		echo json_encode($return, JSON_PRETTY_PRINT);

		return;
	}

	function jsonlookupcallsign($callsign, $type, $band, $mode) {

		// Convert - in Callsign to / Used for URL processing
		$callsign = str_replace("-","/",$callsign);

		$return = [
			"workedBefore" => false,
		];

		$CI =& get_instance();
    	$CI->load->model('Stations');
    	$station_id = $CI->Stations->find_active();

		if($type == "SAT") {
			$this->db->where('COL_PROP_MODE', 'SAT');
		} else {
			$this->db->where('COL_MODE', $mode);
			$this->db->where('COL_BAND', $band);
			$this->db->where('COL_PROP_MODE !=','SAT');

		}

    	$this->db->where('station_id', $station_id);
    	$this->db->where('COL_CALL', strtoupper($callsign));

		$query = $this->db->get($this->config->item('table_name'), 1, 0);
		foreach ($query->result() as $workedBeforeRow)
		{
			$return['workedBefore'] = true;
		}

		header('Content-Type: application/json');
		echo json_encode($return, JSON_PRETTY_PRINT);

		return;
	}


	/* Used to generate maps for displaying on /logbook/ */
	function qso_map() {
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
						echo "{\"lat\":\"".$stn_loc[0]."\",\"lng\":\"".$stn_loc[1]."\", \"html\":\"Callsign: ".$row->COL_CALL."<br />Date/Time: ".$row->COL_TIME_ON."<br />SAT: ".$row->COL_SAT_NAME."<br />Mode: ".$row->COL_MODE."\",\"label\":\"".$row->COL_CALL."\"}";
				} else {
						echo "{\"lat\":\"".$stn_loc[0]."\",\"lng\":\"".$stn_loc[1]."\", \"html\":\"Callsign: ".$row->COL_CALL."<br />Date/Time: ".$row->COL_TIME_ON."<br />Band: ".$row->COL_BAND."<br />Mode: ".$row->COL_MODE."\",\"label\":\"".$row->COL_CALL."\"}";
				}

				$count++;

			} else {
				$query = $this->db->query('
					SELECT *
					FROM dxcc_entities
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

		$html = "";


	    $this->db->select(''.$this->config->item('table_name').'.COL_CALL, '.$this->config->item('table_name').'.COL_BAND, '.$this->config->item('table_name').'.COL_TIME_ON, '.$this->config->item('table_name').'.COL_RST_RCVD, '.$this->config->item('table_name').'.COL_RST_SENT, '.$this->config->item('table_name').'.COL_MODE, '.$this->config->item('table_name').'.COL_SUBMODE, '.$this->config->item('table_name').'.COL_PRIMARY_KEY, '.$this->config->item('table_name').'.COL_SAT_NAME, '.$this->config->item('table_name').'.COL_GRIDSQUARE, '.$this->config->item('table_name').'.COL_QSL_RCVD, '.$this->config->item('table_name').'.COL_EQSL_QSL_RCVD, '.$this->config->item('table_name').'.COL_EQSL_QSL_SENT, '.$this->config->item('table_name').'.COL_QSL_SENT, '.$this->config->item('table_name').'.COL_STX, '.$this->config->item('table_name').'.COL_STX_STRING, '.$this->config->item('table_name').'.COL_SRX, '.$this->config->item('table_name').'.COL_SRX_STRING, '.$this->config->item('table_name').'.COL_LOTW_QSL_SENT, '.$this->config->item('table_name').'.COL_LOTW_QSL_RCVD, '.$this->config->item('table_name').'.COL_VUCC_GRIDS, station_profile.*');
	    $this->db->from($this->config->item('table_name'));

	    $this->db->join('station_profile', 'station_profile.station_id = '.$this->config->item('table_name').'.station_id');
	    $this->db->order_by(''.$this->config->item('table_name').'.COL_TIME_ON', "desc");

		$this->db->like($this->config->item('table_name').'.COL_CALL', $id);
		$this->db->order_by($this->config->item('table_name').".COL_TIME_ON", "desc");
		$this->db->limit(5);

		$query = $this->db->get();

		if ($query->num_rows() > 0)
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
			foreach ($query->result() as $row)
			{
				$html .= "<tr>";
					$html .= "<td>".date($this->config->item('qso_date_format').' H:i',strtotime($row->COL_TIME_ON))."</td>";
					$html .= "<td>".str_replace("0","&Oslash;",strtoupper($row->COL_CALL))."</td>";
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
							echo "grey";
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
							echo "grey";
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

					$data['callsign'] = $this->qrz->search($id, $this->session->userdata('qrz_session_key'));
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

		$this->db->like(''.$this->config->item('table_name').'.COL_CALL', $id);
		$this->db->or_like(''.$this->config->item('table_name').'.COL_GRIDSQUARE', $id);
		$this->db->or_like(''.$this->config->item('table_name').'.COL_VUCC_GRIDS', $id);
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

					$data['callsign'] = $this->qrz->search($id, $this->session->userdata('qrz_session_key'));
				} else {
					// Lookup using hamli
					$this->load->library('hamli');

					$data['callsign'] = $this->hamli->callsign($id);
				}

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
	function searchbearing($locator) {
			$this->load->library('Qra');

			if($locator != null) {
				if($this->session->userdata('user_locator') != null){
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
	function bearing($locator, $unit = 'M') {
			$this->load->library('Qra');


			if($locator != null) {
				if($this->session->userdata('user_locator') != null){
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
