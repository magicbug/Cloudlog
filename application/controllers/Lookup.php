<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*

	Data lookup functions used within Cloudlog

*/

class Lookup extends CI_Controller {

	public function index()
	{

	}

	public function scp($call) {
		
		// SCP results from logbook
		$this->load->model('logbook_model');

		$log_calls = $this->logbook_model->get_callsigns($call);

		if($log_calls != "") {
			echo $log_calls ." ";
		}



		// SCP results from master scp db
		$file = 'updates/masterscp.txt';

		if (is_readable($file)) {

			$lines = file($file, FILE_IGNORE_NEW_LINES);
			$input = preg_quote($call, '~');

			$result = preg_grep('~' . $input . '~', $lines, 0);

			foreach ($result as &$value) {
				echo " ".$value. " ";
			}
		}
		
	}

}
