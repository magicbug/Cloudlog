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
		
		if($call) {
			$uppercase_callsign = strtoupper($call);
		}

		// SCP results from logbook
		$this->load->model('logbook_model');

		$arCalls = array();

		$query = $this->logbook_model->get_callsigns($uppercase_callsign);

		foreach ($query->result() as $row)
	    {
	    	if (in_array($row->COL_CALL, $arCalls) == false)
			{
					$arCalls[] = $row->COL_CALL;
			}
	    }

		// SCP results from master scp db
		$file = 'updates/clublog_scp.txt';

		if (is_readable($file)) {
			$lines = file($file, FILE_IGNORE_NEW_LINES);
			$input = preg_quote($uppercase_callsign, '~');
			$result = preg_grep('~' . $input . '~', $lines, 0);
			foreach ($result as &$value) {
				if (in_array($value, $arCalls) == false)
				{
					$arCalls[] = $value;
				}
			}
		}

		$file = 'updates/masterscp.txt';

		if (is_readable($file)) {
			$lines = file($file, FILE_IGNORE_NEW_LINES);
			$input = preg_quote($uppercase_callsign, '~');
			$result = preg_grep('~' . $input . '~', $lines, 0);
			foreach ($result as &$value) {
				if (in_array($value, $arCalls) == false)
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
