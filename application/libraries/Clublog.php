<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Clublog {

	/*
		Communicates with the Clublog.org API functions
	*/

	/* Send QSO in real time */
	public function send() {

		// Load Librarys
		$CI =& get_instance();
		$CI->load->library('curl');

		// API Key	
		$key = "a11c3235cd74b88212ce726857056939d52372bd";

		$username = "";
		$password = "";

		$qso = "QSO_DATE TIME_ON QSLRDATE QSLSDATE CALL OPERATOR MODE BAND FREQ QSL_RCVD LOTW_QSL_RCVD QSL_SENT DXCC PROP_MODE";

		echo $CI->curl->simple_post('curl_test/message', array('message'=>'Sup buddy'));

	}
	public function check(){}
}

/* End of file Clublog.php */