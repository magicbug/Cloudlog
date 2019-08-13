<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*

	Data lookup functions used within Cloudlog

*/

class Lookup extends CI_Controller {

	public function index()
	{

	}

	public function scp($string) {
		$file = 'updates/masterscp.txt';

		$lines = file($file, FILE_IGNORE_NEW_LINES);

		$input = preg_quote($string, '~');

		$result = preg_grep('~' . $input . '~', $lines, 0);

		$copy = $result;
		foreach ($result as &$value) {
		    echo $value;

		    if (next($copy )) {
		        echo ', '; // Add comma for all elements instead of last
		    }
		}
	}

}