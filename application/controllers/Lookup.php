<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*

	Data lookup functions used within Cloudlog

*/

class Lookup extends CI_Controller {


	function __construct()
	{
		parent::__construct();

		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
	}
	
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

		$arCalls = array();

		// SCP results from master scp db
		$file = 'updates/masterscp.txt';

		if (is_readable($file)) {

			$lines = file($file, FILE_IGNORE_NEW_LINES);
			$input = preg_quote($call, '~');

			$result = preg_grep('~' . $input . '~', $lines, 0);

			foreach ($result as &$value) {
				$arCalls[] = $value;
			}
		}

		// SCP from Club Log
		$file = "updates/clublog_scp.txt";
		if (is_readable($file)) {

			$lines = file($file, FILE_IGNORE_NEW_LINES);
			
			foreach ($lines as $strCall) 
			{
				if (in_array($strCall, $arCalls) == false)
				{
					$arCalls[] = $value;
				}
			}
		}

		sort($arCalls);

		foreach ($arCalls as $strCall)
		{
			echo " " . $strCall . " ";
		}
	}
}
