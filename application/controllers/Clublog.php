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
		$this->load->library('cron_runner');
		$this->cron_runner->run_guarded('clublog_upload', function () {
		$this->load->model('clublog_model');

		$users = $this->clublog_model->get_clublog_users();
		$uploaded_any_qsos = false;

		foreach ($users as $user) {
			$uploaded_any_qsos = $this->uploadUser($user->user_id, $user->user_clublog_name, $user->user_clublog_password) || $uploaded_any_qsos;
		}

		if (!$uploaded_any_qsos) {
			echo 'Nothing awaiting upload to Clublog.' . "<br>";
			log_message('info', 'Nothing awaiting upload to Clublog.');
		}
		});
	}

	function uploadUser($userid, $username, $password) {
		$clean_username = $this->security->xss_clean($username);
		$clean_passord = $this->security->xss_clean($password);
		$clean_userid = $this->security->xss_clean($userid);

		// Validate that username is an email address for Clublog
		if (!empty($clean_username) && !filter_var($clean_username, FILTER_VALIDATE_EMAIL)) {
			echo "Error: Clublog username must be a valid email address. Clublog no longer accepts callsigns as usernames for " . $clean_username . "<br>";
			log_message('error', 'Clublog upload failed for user ID ' . $clean_userid . ': invalid email format for username ' . $clean_username);
			return false;
		}

		$this->config->load('config');
		ini_set('memory_limit', '-1');
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);

		$this->load->helper('file');

		$this->load->model('clublog_model');

		// Retrieve all station profiles for the user with Clublog upload enabled and their QSO counts
		$station_profiles = $this->clublog_model->all_with_count($clean_userid);

		if($station_profiles->num_rows()){
			$uploaded_for_user = false;
			foreach ($station_profiles->result() as $station_row)
			{
				// Only process stations that have QSOs to upload
				if($station_row->qso_total > 0) {
					// Get QSOs for this station that haven't been uploaded to Clublog yet
					$data['qsos'] = $this->clublog_model->get_clublog_qsos($station_row->station_id);

					if($data['qsos']->num_rows()){
						// Generate ADIF file content from the view template
						$string = $this->load->view('adif/data/clublog', $data, TRUE);

						// Generate a unique ID for the temporary file
						$ranid = uniqid();

						// Write the ADIF data to a temporary file
						if ( ! write_file('uploads/clublog'.$ranid.$station_row->station_id.'.adi', $string)) {
						     echo 'Unable to write the file - Make the folder Upload folder has write permissions.';
						}
						else {
							// Get details of the created ADIF file
							$file_info = get_file_info('uploads/clublog'.$ranid.$station_row->station_id.'.adi');

							// Initialize the CURL request to Clublog's API endpoint
							$request = curl_init('https://clublog.org/putlogs.php');

							$filepath = FCPATH . ltrim($file_info['server_path'], '/\\');
							if (!file_exists($filepath) && !empty($this->config->item('directory'))) {
								$document_root = rtrim((string)($_SERVER['DOCUMENT_ROOT'] ?? ''), '/\\');
								$install_dir = trim((string)$this->config->item('directory'), '/\\');
								if ($document_root !== '') {
									$filepath = $document_root . '/' . $install_dir . '/' . ltrim($file_info['server_path'], '/\\');
								}
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
								$uploaded_for_user = true;

								$this->load->model('clublog_model');
								$this->clublog_model->mark_qsos_sent($station_row->station_id);
								echo "Clublog upload for ".$station_row->station_callsign."<br>";
								log_message('info', 'Clublog upload for '.$station_row->station_callsign.' successfully sent.');
							} else {
								echo "Error ".$response;
								log_message('error', 'Clublog upload for '.$station_row->station_callsign.' failed reason '.$response);

								// If Clublog responds with a 403
								if ($info['http_code'] == 403) {
									$this->load->model('clublog_model');
									echo "Clublog API access denied for ".$station_row->station_callsign."<br>";
									log_message('error', 'Clublog API access denied for '.$station_row->station_callsign);
									$this->clublog_model->reset_clublog_user_fields($station_row->user_id, true, 'Clublog batch upload failed with HTTP 403 for station ' . $station_row->station_callsign . '.');
								}
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

			return $uploaded_for_user;
		}

		return false;
	}

	// Download Club Log log matches and mark the QSOs they confirm
	public function download() {
		$this->load->library('cron_runner');
		$this->cron_runner->run_guarded('clublog_download', function () {
			$this->load->model('clublog_model');
			$this->load->library('clublog_matches');

			$requests = 0;
			foreach ($this->clublog_model->get_clublog_users() as $user) {
				if (!filter_var($user->user_clublog_name, FILTER_VALIDATE_EMAIL)) {
					continue;
				}
				foreach ($this->clublog_model->download_callsigns($user->user_id) as $station) {
					if ($requests++ > 0) {
						sleep(11); // Club Log answers 403 after about ten quick requests
					}
					if (!$this->download_callsign($user, $station)) {
						break;
					}
				}
			}
			if ($requests === 0) {
				echo 'No Club Log accounts with an enabled station location.' . "<br>";
			}
		});
	}

	// Returns false when the user's credentials were refused, so no more requests are sent for them.
	private function download_callsign($user, $station) {
		$callsign = $station['callsign'];
		$since = $this->clublog_model->last_download_date($station['station_ids']);
		$url = $this->clublog_matches->url('a11c3235cd74b88212ce726857056939d52372bd', $user->user_clublog_name, $user->user_clublog_password, $callsign, $since);

		$request = curl_init($url);
		curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($request, CURLOPT_TIMEOUT, 120);
		curl_setopt($request, CURLOPT_USERAGENT, 'Cloudlog');
		$response = curl_exec($request);
		$http_code = curl_getinfo($request, CURLINFO_HTTP_CODE);
		$curl_error = curl_errno($request) ? curl_error($request) : '';
		curl_close($request);

		if ($curl_error !== '') {
			echo 'Club Log download for ' . $callsign . ' failed: ' . $curl_error . "<br>";
			log_message('error', 'Club Log download for ' . $callsign . ' failed: ' . $curl_error);
			return true;
		}

		$result = $this->clublog_matches->parse($response);
		if ($result['error'] === 'login' || $http_code == 403) {
			echo 'Club Log refused the login for ' . $callsign . "<br>";
			log_message('error', 'Club Log download for ' . $callsign . ' refused (HTTP ' . $http_code . ')');
			$this->clublog_model->reset_clublog_user_fields($user->user_id, true, 'Club Log match download was refused for ' . $callsign . '.');
			return false;
		}
		if ($result['error'] === 'callsign') {
			echo 'Club Log has no log for ' . $callsign . ' on this account' . "<br>";
			log_message('info', 'Club Log download: ' . $callsign . ' is not on the Club Log account of user ' . $user->user_id);
			return true;
		}
		if ($result['error'] !== null) {
			echo 'Club Log sent an unexpected reply for ' . $callsign . ' (HTTP ' . $http_code . ')' . "<br>";
			log_message('error', 'Club Log download for ' . $callsign . ': unexpected reply, HTTP ' . $http_code);
			return true;
		}

		$marked = $this->clublog_model->apply_matches($result['matches'], $station['station_ids']);
		echo 'Club Log matches for ' . $callsign . ': ' . count($result['matches']) . ' downloaded, ' . $marked . ' newly confirmed' . "<br>";
		log_message('info', 'Club Log matches for ' . $callsign . ': ' . count($result['matches']) . ' downloaded, ' . $marked . ' newly confirmed');
		return true;
	}

	function markqso($station_id) {
		$clean_station_id = $this->security->xss_clean($station_id);
		$this->load->model('clublog_model');
		$this->clublog_model->mark_qsos_sent($clean_station_id);
	}

	function markallnotsent($station_id) {
		$clean_station_id = $this->security->xss_clean($station_id);
		$this->load->model('clublog_model');
		$this->clublog_model->mark_all_qsos_notsent($clean_station_id);
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
