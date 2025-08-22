<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
	Controller to interact with the Clublog API
*/

class Components extends CI_Controller {

	function __construct() {
		parent::__construct();

		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
	}

	public function index() {
		$this->load->model("user_options_model");
		$hkey_opt=$this->user_options_model->get_options('hamsat',array('option_name'=>'hamsat_key','option_key'=>'api'))->result();
		if (count($hkey_opt)>0) {
			$data['user_hamsat_key'] = $hkey_opt[0]->option_value;
		} else {
			$data['user_hamsat_key']='';
		}
		$url = 'https://hams.at/api/alerts/upcoming';
		
		// Initialize error state
		$data['error'] = false;
		$data['error_message'] = '';
		
		try {
			if ($data['user_hamsat_key'] ?? '' != '') {
				$options = array(
					'http' => array(
						'method' => 'GET',
						'header' => "Authorization: Bearer ".$data['user_hamsat_key']."\r\n",
						'timeout' => 10 // Add timeout to prevent long hangs
					)
				);
				$context = stream_context_create($options);
				$json = @file_get_contents($url, false, $context);
			} else {
				$json = @file_get_contents($url, false, stream_context_create(array(
					'http' => array('timeout' => 10)
				)));
			}
			
			// Check if the request failed
			if ($json === false) {
				$data['error'] = true;
				$data['error_message'] = 'Unable to connect to hams.at service. Please check your internet connection or try again later.';
				$data['rovedata'] = null;
			} else {
				$decoded_data = json_decode($json, true);
				if ($decoded_data === null && json_last_error() !== JSON_ERROR_NONE) {
					$data['error'] = true;
					$data['error_message'] = 'Invalid response received from hams.at service. Please try again later.';
					$data['rovedata'] = null;
				} else {
					$data['rovedata'] = $decoded_data;
				}
			}
		} catch (Exception $e) {
			$data['error'] = true;
			$data['error_message'] = 'An error occurred while fetching satellite data. Please try again later.';
			$data['rovedata'] = null;
		}
		
		$hkey_opt=$this->user_options_model->get_options('hamsat',array('option_name'=>'hamsat_key','option_key'=>'workable'))->result();
		if (count($hkey_opt)>0) {
			$data['user_hamsat_workable_only'] = $hkey_opt[0]->option_value;
		} else {
			$data['user_hamsat_workable_only'] = 0;
		}

		$this->load->model('stations');
		$data['gridsquare'] = strtoupper($this->stations->find_gridsquare());

		// load view
		$this->load->view('components/hamsat/table', $data);
	}
}
