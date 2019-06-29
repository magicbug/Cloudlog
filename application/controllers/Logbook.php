<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Logbook extends CI_Controller {

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

	function json($callsign)
	{
		$this->load->model('user_model');
		if(!$this->user_model->authorize($this->config->item('auth_mode'))) { return; }

		$this->load->model('lotw_user');
		 
		$lotw_member = $this->lotw_user->check($callsign);


		$this->load->model('logbook_model');

		$return = [
			"dxcc" => false,
			"callsign_name" => "",
			"callsign_qra"  => "",
			"callsign_qth"  => "",
			"callsign_iota" => "",
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
		$return['callsign_name'] = $this->logbook_model->call_name($callsign);
		$return['callsign_qra'] = $this->logbook_model->call_qra($callsign);
		$return['callsign_qth'] = $this->logbook_model->call_qth($callsign);
		$return['callsign_iota'] = $this->logbook_model->call_iota($callsign);
		$return['qsl_manager'] = $this->logbook_model->call_qslvia($callsign);
		$return['bearing'] = $this->bearing($return['callsign_qra'], $this->config->item('measurement_base'));
		$return['workedBefore'] = $this->worked_grid_before($return['callsign_qra']);

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
	}

	if (isset($callbook))
	{

		$return['callsign_name'] = $callbook['name'];
		$return['callsign_qra'] = $callbook['gridsquare'];
		$return['callsign_qth'] = $callbook['city'];
		$return['callsign_iota'] = $callbook['iota'];
		$return['qsl_manager'] = $callbook['qslmgr'];
		if ($return['callsign_qra'] != "") {
			$return['latlng'] = $this->qralatlng($return['callsign_qra']);
		}
		$return['workedBefore'] = $this->worked_grid_before($return['callsign_qra']);
	}
	$return['bearing'] = $this->bearing($return['callsign_qra'], $this->config->item('measurement_base'));

	echo json_encode($return, JSON_PRETTY_PRINT);

	return;
	}

	function worked_grid_before($gridsquare)
	{
		if (strlen($gridsquare) < 4)
			return false; 

		$this->db->like('SUBSTRING(COL_GRIDSQUARE, 1, 4)', substr($gridsquare, 0, 4));
		$query = $this->db->get($this->config->item('table_name'), 1, 0);
		foreach ($query->result() as $workedBeforeRow)
		{
			return true;
		}
		return false;
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

		$this->db->where('COL_PRIMARY_KEY', $id);
		$data['query'] = $this->db->get($this->config->item('table_name'));
		
		$this->load->view('interface_assets/mini_header', $data);
		$this->load->view('view_log/qso');
		$this->load->view('interface_assets/footer');
	}

	function partial($id) {
		$this->load->model('user_model');
				if(!$this->user_model->authorize($this->config->item('auth_mode'))) { return; }
				
		$html = "";     
		$this->db->like('COL_CALL', $id);
		$this->db->order_by("COL_TIME_ON", "desc");
		$this->db->limit(5);
		$query = $this->db->get($this->config->item('table_name'));

		if ($query->num_rows() > 0)
		{
			$html .= "<div class=\"table-responsive\">";
			$html .= "<table class=\"table\">";
				$html .= "<tr>";
					$html .= "<td>Date</td>";
					$html .= "<td>Callsign</td>";
					$html .= "<td>RST Sent</td>";
					$html .= "<td>RST Recv</td>";
					$html .= "<td>Band</td>";
					$html .= "<td>Mode</td>";
				$html .= "</tr>";
			foreach ($query->result() as $row)
			{
				$html .= "<tr>";
					$html .= "<td>".$row->COL_TIME_ON."</td>";
					$html .= "<td>".str_replace("0","&Oslash;",strtoupper($row->COL_CALL))."</td>";
					$html .= "<td>".$row->COL_RST_SENT."</td>";
					$html .= "<td>".$row->COL_RST_RCVD."</td>";
					if($row->COL_SAT_NAME != null) {
									$html .= "<td>".$row->COL_SAT_NAME."</td>";
					} else {
								$html .= "<td>".$row->COL_BAND."</td>";
					}
					$html .= "<td>".$row->COL_MODE."</td>";
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

		$this->db->like('COL_CALL', $id);
		$this->db->or_like('COL_GRIDSQUARE', $id);
		$this->db->or_like('COL_VUCC_GRIDS', $id);
		$this->db->order_by("COL_TIME_ON", "desc");
		$query = $this->db->get($this->config->item('table_name'));

		if ($query->num_rows() > 0)
		{
			$data['results'] = $query;
			$this->load->view('view_log/partial/log.php', $data);
		} else {
			$this->load->model('search');

			$iota_search = $this->search->callsign_iota($id);

			if ($iota_search->num_rows() > 0)
			{
				$data['results'] = $iota_search;
				$this->load->view('view_log/partial/log.php', $data);
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

	// Find DXCC
	function find_dxcc($callsign) {
		// Live lookup against Clublogs API
		$url = "https://secure.clublog.org/dxcc?call=".$callsign."&api=a11c3235cd74b88212ce726857056939d52372bd&full=1";

		$json = file_get_contents($url);
		$data = json_decode($json, TRUE);

		// echo ucfirst(strtolower($data['Name']));
		return $data;
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


	/* return station bearing */
	function searchbearing($locator) {
			$this->load->library('Qra');

			if($locator != null) {
				if($this->session->userdata('user_locator') != null){
					$mylocator = $this->session->userdata('user_locator');
				} else {
					$mylocator = $this->config->item('locator');
				}

				$bearing = $this->qra->bearing($mylocator, $locator, $this->config->item('measurement_base'));

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


}