<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
	Controller to interact with the QRZ.com API
 */

class Qrz extends CI_Controller {

	// Show frontend if there is one
	public function index() {
		$this->config->load('config');
	}

	/*
	 * Upload QSO to QRZ.com
	 * When called from the url cloudlog/qrz/upload, the function loops through all station_id's with a qrz api key defined.
	 * All QSOs not previously uploaded, will then be uploaded, one at a time
	 */
	public function upload() {
		$this->setOptions();

		$this->load->model('logbook_model');

		$station_ids = $this->logbook_model->get_station_id_with_qrz_api();

		if ($station_ids) {
			foreach ($station_ids as $station) {
				$qrz_api_key = $station->qrzapikey;
				if($this->mass_upload_qsos($station->station_id, $qrz_api_key, true)) {
					echo "QSOs have been uploaded to QRZ.com.";
					log_message('info', 'QSOs have been uploaded to QRZ.com.');
				} else{
					echo "No QSOs found for upload.";
					log_message('info', 'No QSOs found for upload.');
				}
			}
		} else {
			echo "No station profiles with a QRZ API Key found.";
			log_message('error', "No station profiles with a QRZ API Key found.");
		}

	}

	function setOptions() {
		$this->config->load('config');
		ini_set('memory_limit', '-1');
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
	}

	/*
	 * Function gets all QSOs from given station_id, that are not previously uploaded to qrz.
	 * Adif is build for each qso, and then uploaded, one at a time
	 */
	function mass_upload_qsos($station_id, $qrz_api_key, $trusted = false) {
		$i = 0;
		$data['qsos'] = $this->logbook_model->get_qrz_qsos($station_id, $trusted);
		$errormessages=array();

		$this->load->library('AdifHelper');

		if ($data['qsos']) {
			foreach ($data['qsos']->result() as $qso) {
				$adif = $this->adifhelper->getAdifLine($qso);

				if ($qso->COL_QRZCOM_QSO_UPLOAD_STATUS == 'M') {
					$result = $this->logbook_model->push_qso_to_qrz($qrz_api_key, $adif, true);
				} else {
					$result = $this->logbook_model->push_qso_to_qrz($qrz_api_key, $adif);
				}

				if ( ($result['status'] == 'OK') || ( ($result['status'] == 'error') && ($result['message'] == 'STATUS=FAIL&REASON=Unable to add QSO to database: duplicate&EXTENDED=')) ){
					$this->markqso($qso->COL_PRIMARY_KEY);
					$i++;
					$result['status'] = 'OK';
				} elseif ( ($result['status']=='error') && (substr($result['message'],0,11)  == 'STATUS=AUTH')) {
					log_message('error', 'QRZ upload failed for qso: Call: ' . $qso->COL_CALL . ' Band: ' . $qso->COL_BAND . ' Mode: ' . $qso->COL_MODE . ' Time: ' . $qso->COL_TIME_ON);
					log_message('error', 'QRZ upload failed with the following message: ' .$result['message']);
					log_message('error', 'QRZ upload stopped for Station_ID: ' .$station_id);
					$errormessages[] = $result['message'] . ' Call: ' . $qso->COL_CALL . ' Band: ' . $qso->COL_BAND . ' Mode: ' . $qso->COL_MODE . ' Time: ' . $qso->COL_TIME_ON;
					$result['status'] = 'Error';
					break; /* If key is invalid, immediate stop syncing for more QSOs of this station */
				} else {
					log_message('error', 'QRZ upload failed for qso: Call: ' . $qso->COL_CALL . ' Band: ' . $qso->COL_BAND . ' Mode: ' . $qso->COL_MODE . ' Time: ' . $qso->COL_TIME_ON);
					log_message('error', 'QRZ upload failed with the following message: ' .$result['message']);
					$errormessages[] = $result['message'] . ' Call: ' . $qso->COL_CALL . ' Band: ' . $qso->COL_BAND . ' Mode: ' . $qso->COL_MODE . ' Time: ' . $qso->COL_TIME_ON;
					$result['status'] = 'Error';
				}
			}
			if ($i == 0) {
			    $result['status']='OK';
		    }
			$result['count'] = $i;
			$result['errormessages'] = $errormessages;
			return $result;
		} else {
			$result['status'] = 'Error';
			$result['count'] = $i;
			$result['errormessages'] = $errormessages;
			return $result;
		}
	}

	/*
	 * Function marks QSO with given primarykey as uploaded to qrz
	 */
	function markqso($primarykey) {
		$this->logbook_model->mark_qrz_qsos_sent($primarykey);
	}

	/*
	 * Used for displaying the uid for manually selecting log for upload to qrz
	 */
	public function export() {
		$this->load->model('stations');

		$data['page_title'] = "QRZ Logbook";

		$data['station_profiles'] = $this->stations->all_of_user();
		$data['station_profile'] = $this->stations->stations_with_qrz_api_key();
		$this->load->model('Stations');
		$data['callsigns'] = $this->Stations->callsigns_of_user($this->session->userdata('user_id'));

		$this->load->view('interface_assets/header', $data);
		$this->load->view('qrz/export');
		$this->load->view('interface_assets/footer');
	}

	/*
	 * Used for ajax-function when selecting log for upload to qrz
	 */
	public function upload_station() {
		$this->setOptions();
		$this->load->model('stations');

		$postData = $this->input->post();

		$this->load->model('logbook_model');
		$result = $this->logbook_model->exists_qrz_api_key($postData['station_id']);
		$qrz_api_key = $result->qrzapikey;
		header('Content-type: application/json');
		$result = $this->mass_upload_qsos($postData['station_id'], $qrz_api_key);
		if ($result['status'] == 'OK') {
			$stationinfo = $this->stations->stations_with_qrz_api_key();
			$info = $stationinfo->result();

			$data['status'] = 'OK';
			$data['info'] = $info;
			$data['infomessage'] = $result['count'] . " QSOs are now uploaded to QRZ.com";
			$data['errormessages'] = $result['errormessages'];
			echo json_encode($data);
		} else {
			$data['status'] = 'Error';
			$data['info'] = 'Error: No QSOs found to upload.';
			$data['errormessages'] = $result['errormessages'];
			echo json_encode($data);
		}
	}

	public function mark_qrz() {
		// Set memory limit to unlimited to allow heavy usage
		ini_set('memory_limit', '-1');

		$station_id = $this->security->xss_clean($this->input->post('station_profile'));

		$this->load->model('adif_data');

		$data['qsos'] = $this->adif_data->export_custom($this->input->post('from'), $this->input->post('to'), $station_id);

		$this->load->model('logbook_model');

		foreach ($data['qsos']->result() as $qso)
		{
			$this->logbook_model->mark_qrz_qsos_sent($qso->COL_PRIMARY_KEY);
		}

		$this->load->view('interface_assets/header', $data);
		$this->load->view('qrz/mark_qrz', $data);
		$this->load->view('interface_assets/footer');
	}

	public function import_qrz() {
		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }

		$data['page_title'] = "QRZ QSL Import";

		$this->load->model('logbook_model');

		$customDate = $this->input->post('from');
		if ($customDate != NULL) {
			$qrz_last_date = date($customDate);
		} else {
			// Query the logbook to determine when the last LoTW confirmation was
			$qrz_last_date = null;
		}
		$this->download($this->session->userdata('user_id'),$qrz_last_date,true);
	} // end function

	function download($user_id_to_load = null, $lastqrz = null, $show_views = false) {
		$this->load->model('user_model');
		$this->load->model('logbook_model');


		$api_keys = $this->logbook_model->get_qrz_apikeys();

		if ($api_keys) {
			foreach ($api_keys as $station) {
				if ((($user_id_to_load != null) && ($user_id_to_load != $station->user_id))) {	// Skip User if we're called with a specific user_id
					continue;
				} 
				if ($lastqrz == null) {
					$lastqrz = $this->logbook_model->qrz_last_qsl_date($station->user_id);
				}
				$qrz_api_key = $station->qrzapikey;
				$result=($this->mass_download_qsos($qrz_api_key, $lastqrz));
				if (isset($result['tableheaders'])) {
					$data['tableheaders']=$result['tableheaders'];
					if (isset($data['table'])) {
						$data['table'].=$result['table'];
					} else {
						$data['table']=$result['table'];
					}
				}
			}
		} else {
			echo "No station profiles with a QRZ API Key found.";
			log_message('error', "No station profiles with a QRZ API Key found.");
		}

		$this->load->model('user_model');
		if ($this->user_model->authorize(2)) {	// Only Output results if authorized User
			if(isset($data['tableheaders'])) {
				if ($data['table'] != '') {
					$data['table'].='</table>';
				}
				if($show_views == TRUE) {
					$data['page_title'] = "QRZ ADIF Information";
					$this->load->view('interface_assets/header', $data);
					$this->load->view('qrz/analysis');
					$this->load->view('interface_assets/footer');
				} else {
					return '';
				}
			} else {
				echo "Downloaded QRZ report contains no matches.";
			}
		}	
	}

	function mass_download_qsos($qrz_api_key = '', $lastqrz = '1900-01-01', $trusted = false) {
		$config['upload_path'] = './uploads/';
		$file = $config['upload_path'] . 'qrzcom_download_report.adi';
		if (file_exists($file) && ! is_writable($file)) {
			$result = "Temporary download file ".$file." is not writable. Aborting!";
			return false;
		}
		$url = 'http://logbook.qrz.com/api'; 

		$post_data['KEY'] = $qrz_api_key;
		$post_data['ACTION'] = 'FETCH';
		$post_data['OPTION'] = 'MODSINCE:'.$lastqrz.';STATUS:CONFIRMED;TYPE:ADIF';

		$ch = curl_init( $url );
		curl_setopt( $ch, CURLOPT_POST, true);
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt( $ch, CURLOPT_HEADER, 0);
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true);

		$content = htmlspecialchars_decode(curl_exec($ch));
		file_put_contents($file, $content);
		if (strlen(file_get_contents($file, false, null, 0, 100))!=100) {
			$result = "QRZ downloading failed, either due to it being down or incorrect logins.";
			return "false";
		}

		ini_set('memory_limit', '-1');
		$result = $this->loadFromFile($file);

		return $result;
	}

/*
	|--------------------------------------------------------------------------
	| Function: loadFromFile
	|--------------------------------------------------------------------------
	|
	|	$filepath is the ADIF file
	|
	|	Internal function that takes the QRZ ADIF and imports into the log
	|
 */
	private function loadFromFile($filepath) {

		// Figure out how we should be marking QSLs confirmed via LoTW
		$config['qrz_rcvd_mark'] = 'Y';

		ini_set('memory_limit', '-1');
		set_time_limit(0);

		$this->load->library('adif_parser');

		$this->adif_parser->load_from_file($filepath);

		$this->adif_parser->initialize();
		$tableheaders = "<table width=\"100%\">";
		$tableheaders .= "<tr class=\"titles\">";
		$tableheaders .= "<td>Station Callsign</td>";
		$tableheaders .= "<td>QSO Date</td>";
		$tableheaders .= "<td>Call</td>";
		$tableheaders .= "<td>Mode</td>";
		$tableheaders .= "<td>QRZ QSL Received</td>";
		$tableheaders .= "<td>QRZ Confirmed</td>";
		$tableheaders .= "<td>Log Status</td>";
		$tableheaders .= "</tr>";

		$table = "";
		while($record = $this->adif_parser->get_record()) {
			if ((!(isset($record['app_qrzlog_qsldate']))) || (!(isset($record['qso_date'])))) {
				continue;
			}
			$time_on = date('Y-m-d', strtotime($record['qso_date'])) ." ".date('H:i', strtotime($record['time_on']));

			$qsl_date = date('Y-m-d', strtotime($record['app_qrzlog_qsldate']));

			if (isset($record['time_off'])) {
				$time_off = date('Y-m-d', strtotime($record['qso_date'])) ." ".date('H:i', strtotime($record['time_off']));
			} else {
				$time_off = date('Y-m-d', strtotime($record['qso_date'])) ." ".date('H:i', strtotime($record['time_on']));
			}

			// If we have a positive match from LoTW, record it in the DB according to the user's preferences
			if ($record['app_qrzlog_status'] == "C") {
				$record['qsl_rcvd'] = $config['qrz_rcvd_mark'];
			}

			$record['call']=str_replace("_","/",$record['call']);
			$record['station_callsign']=str_replace("_","/",$record['station_callsign']);
			$status = $this->logbook_model->import_check($time_on, $record['call'], $record['band'], $record['mode'], $record['station_callsign']);

			if($status[0] == "Found") {
				$qrz_status = $this->logbook_model->qrz_update($time_on, $record['call'], $record['band'], $qsl_date, $record['qsl_rcvd'],$record['station_callsign']);

				$table .= "<tr>";
				$table .= "<td>".$record['station_callsign']."</td>";
				$table .= "<td>".$time_on."</td>";
				$table .= "<td>".$record['call']."</td>";
				$table .= "<td>".$record['mode']."</td>";
				$table .= "<td>".$record['qsl_rcvd']."</td>";
				$table .= "<td>".$qsl_date."</td>";
				$table .= "<td>QSO Record: ".$status[0]."</td>";
				$table .= "</tr>";
			} else {
				$table .= "<tr>";
				$table .= "<td>".$record['station_callsign']."</td>";
				$table .= "<td>".$time_on."</td>";
				$table .= "<td>".$record['call']."</td>";
				$table .= "<td>".$record['mode']."</td>";
				$table .= "<td>".$record['qsl_rcvd']."</td>";
				$table .= "<td>QSO Record: ".$status[0]."</td>";
				$table .= "</tr>";
			}
		}

		if ($table != "") {
			$data['tableheaders'] = $tableheaders;
			$data['table'] = $table;
		} else {
			$data['table'] = '';
		}

		unlink($filepath);
		return $data;

	}

}
