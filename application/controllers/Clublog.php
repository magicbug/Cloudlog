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
	public function upload() {
		$this->load->model('clublog_model');

		$users = $this->clublog_model->get_clublog_users();

		foreach ($users as $user) {
			$this->uploadUser($user->user_id, $user->user_clublog_name, $user->user_clublog_password);
		}
	}

	function uploadUser($userid, $username, $password) {
		$clean_username = $this->security->xss_clean($username);
		$clean_passord = $this->security->xss_clean($password);
		$clean_userid = $this->security->xss_clean($userid);

		$this->config->load('config');
		ini_set('memory_limit', '-1');
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);

		$this->load->helper('file');

		$this->load->model('clublog_model');

		$station_profiles = $this->clublog_model->all_with_count($clean_userid);

		if($station_profiles->num_rows()){
			foreach ($station_profiles->result() as $station_row)
			{
				if($station_row->qso_total > 0) {
					$data['qsos'] = $this->clublog_model->get_clublog_qsos($station_row->station_id);

					if($data['qsos']->num_rows()){
						$string = $this->load->view('adif/data/clublog', $data, TRUE);

						$ranid = uniqid();

						if ( ! write_file('uploads/clublog'.$ranid.$station_row->station_id.'.adi', $string)) {
						     echo 'Unable to write the file - Make the folder Upload folder has write permissions.';
						}
						else {

							$file_info = get_file_info('uploads/clublog'.$ranid.$station_row->station_id.'.adi');

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
							      'email' => $clean_username,
							      'password' => $clean_passord,
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
								echo "QSOs uploaded and Logbook QSOs marked as sent to Clublog"."<br>";

								$this->load->model('clublog_model');
								$this->clublog_model->mark_qsos_sent($station_row->station_id);
								echo "Clublog upload for ".$station_row->station_callsign."<br>";
								log_message('info', 'Clublog upload for '.$station_row->station_callsign.' successfully sent.');
							} else {
								echo "Error ".$response;
								log_message('error', 'Clublog upload for '.$station_row->station_callsign.' failed reason '.$response);
							}

							// Delete the ADIF file used for clublog
							unlink('uploads/clublog'.$ranid.$station_row->station_id.'.adi');

						}

					} else {
						echo "Nothing awaiting upload to clublog for ".$station_row->station_callsign."<br>";
						
						log_message('info', 'Nothing awaiting upload to clublog for '.$station_row->station_callsign);
					}
				}
			}
		}
	}

	function markqso($station_id) {
		$clean_station_id = $this->security->xss_clean($station_id);
		$this->load->model('clublog_model');
		$this->clublog_model->mark_qsos_sent($clean_station_id);
	}

	function markallnotsent() {
		$clean_station_id = $this->security->xss_clean($station_id);
		$this->load->model('clublog_model');
		$this->clublog_model->mark_all_qsos_notsent($clean_station_id);
	}


	public function realtime($username) {
		$clean_username = $this->security->xss_clean($username);

		$this->load->model('stations');
		$this->load->model('clublog_model');

		$clublog_info = $this->clublog_model->get_clublog_auth_info($clean_username);

		if(!isset($clublog_info['user_name'])) {
			echo "Username unknown";
			exit;
		}

		$station_profiles = $this->stations->all_with_count();

		// if station profiles exist
		if($station_profiles->num_rows()){
			// Loop through station profiles
			foreach ($station_profiles->result() as $station_row)
			{
				// if the station profile has more than 1 qso
				if($station_row->qso_total > 0) {
					$myqsos = $this->clublog_model->get_last_five($station_row->station_id);

					foreach ($myqsos->result() as $qso)
					{
						$data['qso'] = $qso;
						$adif_string = $this->load->view('adif/data/clublog_realtime', $data, true);

						// initialise the curl request
						$request = curl_init('https://clublog.org/realtime.php');

						curl_setopt($request, CURLOPT_POST, true);
						curl_setopt(
							$request,
							CURLOPT_POSTFIELDS,
								array(
								      'email' => $clublog_info['user_clublog_name'],
								      'password' => $clublog_info['user_clublog_password'],
								      'callsign' => $station_row->station_callsign,
								      'adif' => $adif_string,
								      'api' => "a11c3235cd74b88212ce726857056939d52372bd",
								   ));

						// output the response
						curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
						$response = curl_exec($request);
						$info = curl_getinfo($request);

					// If Clublog Accepts mark the QSOs
						if (preg_match('/\bOK\b/', $response)) {
							echo "QSOs uploaded and Logbook QSOs marked as sent to Clublog<br>";

							$this->clublog_model->mark_qso_sent($qso->COL_PRIMARY_KEY);
							echo "Clublog upload for ".$station_row->station_callsign."<br>";
						} else {
							echo "Error ".$response."<br>";
						}
						curl_close ($request); 
					}
				} else {
					echo "No QSOs to upload";
				}
			}
		}
	}

	// Find DXCC
	function find_dxcc($callsign) {
		$clean_callsign = $this->security->xss_clean($callsign);
		// Live lookup against Clublogs API
		$url = "https://clublog.org/dxcc?call=".$clean_callsign."&api=a11c3235cd74b88212ce726857056939d52372bd&full=1";

		$json = file_get_contents($url);
		$data = json_decode($json, TRUE);

		// echo ucfirst(strtolower($data['Name']));
		return $data;
	}
	
	
}
