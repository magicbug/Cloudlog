<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Clublog {

	/*
		Communicates with the Clublog.org API functions

		Send() 

		TODO: Finish ADIF output
		TODO: Curl Function
		TODO: Batch importing
		TODO: Delete
	*/

	/* Send QSO in real time */
	public function send($qso) {

		// Load Librarys
		$CI =& get_instance();
		$CI->load->library('curl');

		// API Key	
		$key = "a11c3235cd74b88212ce726857056939d52372bd";

		$username = "";
		$password = "";

		$adif = "<qso_date:".strlen($qso_date).">".$qso_date."<time_on:".strlen($time_on).">".$time_on."<call:".strlen($qso['call']).">".$qso['call']."<band:".strlen($qso['band']).">".$qso['band']."<mode:".strlen($qso['mode']).">".$qso['mode']."<freq:".strlen($qso['freq']).">".$qso['freq'];


		echo $CI->curl->simple_post('curl_test/message', array('message'=>'Sup buddy'));

	}
	public function check(){}
}

/* End of file Clublog.php */