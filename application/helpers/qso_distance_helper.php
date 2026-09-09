<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('qso_format_distance')) {
	function qso_format_distance($row, $html = true)
	{
		$CI =& get_instance();
		$CI->load->library('Qra');
		return $CI->qra->format_qso_distance($row, $html);
	}
}

if (!function_exists('qso_distance_tooltip')) {
	function qso_distance_tooltip($row)
	{
		$CI =& get_instance();
		$CI->load->library('Qra');
		return $CI->qra->qso_distance_tooltip($row);
	}
}
