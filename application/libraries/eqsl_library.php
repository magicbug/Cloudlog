<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Eqsl_library {

	// Return formatted URL to get the eQSL card image
	public function card_image($username, $password, $callsign, $band, $mode, $year, $month, $day, $hour, $minute)
	{
		$url = "https://www.eqsl.cc/qslcard/GeteQSL.cfm?Username=".$username."&Password=".$password."&CallsignFrom=".$callsign."&QSOBand=".$band."&QSOMode=".$mode."&QSOYear=".$year."&QSOMonth=".$month."&QSODay=".$day."&QSOHour=".$hour."&QSOMinute=".$minute;

		return $url;

	}
}
