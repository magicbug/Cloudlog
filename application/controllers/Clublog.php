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

		$this->load->model('clublog_model');

		$clublog_info = $this->clublog_model->get_clublog_auth_info($username);

		if(!isset($clublog_info['user_name'])) {
			echo "Username unknown";
			exit;
		}

		$data['qsos'] = $this->logbook_model->get_clublog_qsos();

		print_r($data['qsos'])

		if($data['qsos']->num_rows()){
			// Create ADIF File of contacts not uploaded to Clublog
			$string = $this->load->view('adif/data/clublog', $data, TRUE);

			$ranid = uniqid();
			if ( ! write_file('uploads/clublog'.$ranid.'.adi', $string)) {
			     echo 'Unable to write the file - Make the folder Upload folder has write permissions.';
			}
			else {

				$file_info = get_file_info('uploads/clublog'.$ranid.'.adi');

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
				      'callsign' => $clublog_info['user_clublog_callsign'],
				      'api' => "a11c3235cd74b88212ce726857056939d52372bd",
				      'file' => $cFile
				    ));

				// output the response
				curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
				$response = curl_exec($request);
				$info = curl_getinfo($request);

				if(curl_errno($request)) {
				    $catch_error = curl_error($request);
				}
				curl_close ($request); 


				// If Clublog Accepts mark the QSOs
				if (preg_match('/\baccepted\b/', $response)) {
					echo "QSOs uploaded and Logbook QSOs marked as sent to Clublog";

					$this->load->model('clublog_model');
					$this->clublog_model->mark_qsos_sent();
				} else {
					echo $catch_error;

				}

			}
		} else {
			echo "Nothing awaiting upload to clublog";
		}

	}

	function markqso() {
		$this->load->model('clublog_model');
		$this->clublog_model->mark_qsos_sent();
	}
	
	
}