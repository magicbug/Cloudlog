<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
	Controller to interact with the Clublog API
*/

class Clublog extends CI_Controller {

	// Show frontend if there is one
	public function index() {

	}

	// Upload ADIF to Clublog
	public function upload($username) {
		$this->load->helper('file');

		$this->load->model('logbook_model');

		$this->load->model('clublog_model');

		$clublog_info = $this->clublog_model->get_clublog_auth_info($username);

		if(!isset($clublog_info['user_name'])) {
			echo "Username unknown";
			exit;
		}

		print_r($clublog_info);

		$data['qsos'] = $this->logbook_model->get_clublog_qsos();

		// Create ADIF File of contacts not uploaded to Clublog
		$string = $this->load->view('adif/data/clublog', $data, TRUE);

		if ( ! write_file('uploads/clublog.adi', $string)) {
		     echo 'Unable to write the file - Make the folder Upload folder has write permissions.';
		}
		else {
		    echo "uploads/clublog.adi file created.";
		}

		$file_info = get_file_info('uploads/clublog.adi');

		// initialise the curl request
		$request = curl_init('https://clublog.org/putlogs.php');

		// send a file
		curl_setopt($request, CURLOPT_POST, true);
		curl_setopt(
		    $request,
		    CURLOPT_POSTFIELDS,
		    array(
		      'email' => $clublog_info['user_clublog_name'],
		      'password' => $clublog_info['user_clublog_password'],
		      'callsign' => $clublog_info['user_clublog_callsign'],
		      'api' => "",
		      'file' => '@' . $file_info['server_path']
		    ));

		// output the response
		curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
		echo curl_exec($request);

		// close the session
		curl_close($request);


	}
	
	
}