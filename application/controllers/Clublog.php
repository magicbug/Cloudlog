<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
	Controller to interact with the Clublog API
*/

class Clublog extends CI_Controller {

	// Show frontend if there is one
	public function index() {
		$this->config->load('config');
	}

	// Upload ADIF to Clublog
	public function upload($username) {
		$this->config->load('config');
		ini_set('memory_limit', '-1');
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);

		$this->load->helper('file');

		$this->load->model('logbook_model');

		$this->load->model('stations');

		$this->load->model('clublog_model');

		$clublog_info = $this->clublog_model->get_clublog_auth_info($username);

		if(!isset($clublog_info['user_name'])) {
			echo "Username unknown";
			exit;
		}


		$station_profiles = $this->stations->all_with_count();

		if($station_profiles->num_rows()){
			foreach ($station_profiles->result() as $station_row)
			{
				if($station_row->qso_total > 0) {
					$data['qsos'] = $this->logbook_model->get_clublog_qsos($station_row->station_id);

					$string = $this->load->view('adif/data/clublog', $data, TRUE);

					$ranid = uniqid();

					if ( ! write_file('uploads/clublog'.$ranid.'-'.$station_row->station_id.'.adi', $string)) {
					     echo 'Unable to write the file - Make the folder Upload folder has write permissions.';
					}
					else {
						
						$file_info = get_file_info('uploads/clublog'.'-'.$station_row->station_id.'.adi');

						// initialise the curl request
						$request = curl_init('https://clublog.org/putlogs.php');

						if($this->config->item('directory') != "") {
							 $filepath = $_SERVER['DOCUMENT_ROOT']."/".$this->config->item('directory')."/".$file_info['server_path'];
						} else {
							 $filepath = $_SERVER['DOCUMENT_ROOT']."/".$file_info['server_path'];
						}

						if (function_exists('curl_file_create')) { // php 5.5+
						  $cFile = curl_file_create($filepath);
						} else { // 
						  $cFile = '@' . realpath($filepath);
						}

						// send a file
						curl_setopt($request, CURLOPT_POST, true);
						curl_setopt(
						    $request,
						    CURLOPT_POSTFIELDS,
						    array(
						      'email' => $clublog_info['user_clublog_name'],
						      'password' => $clublog_info['user_clublog_password'],
						      'callsign' => $station_row->station_callsign,
						      'api' => "a11c3235cd74b88212ce726857056939d52372bd",
						      'file' => $cFile
						    ));

						// output the response
						curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
						$response = curl_exec($request);
						$info = curl_getinfo($request);

						if(curl_errno($request)) {
						    echo curl_error($request);
						}
						curl_close ($request); 


						// If Clublog Accepts mark the QSOs
						if (preg_match('/\baccepted\b/', $response)) {
							echo "QSOs uploaded and Logbook QSOs marked as sent to Clublog";

							$this->load->model('clublog_model');
							$this->clublog_model->mark_qsos_sent($station_row->station_id);
						} else {
							echo "Error ".$response;
						}

					}
				}
			}
		} else {
				echo "Nothing awaiting upload to clublog";
		}
	}

	function markqso($station_id) {
		$this->load->model('clublog_model');
		$this->clublog_model->mark_qsos_sent($station_id);
	}

	function markallnotsent() {
		$this->load->model('clublog_model');
		$this->clublog_model->mark_all_qsos_notsent($station_id);
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
	
	
}