<?php defined('BASEPATH') or exit('No direct script access allowed');

/***
 * Sota library is a Summit On The Air client
 */
class Sota
{
	// return summit references matching the provided query
	public function get($query): array
	{
		if (empty($query)) {
			return [];
		}

		$json = [];
		$ref = strtoupper($query);

		$file = 'assets/json/sota.txt';

		if (is_readable($file)) {
			$lines = file($file, FILE_IGNORE_NEW_LINES);
			$input = preg_quote($ref, '~');
			$reg = '~^' . $input . '(.*)$~';
			$result = preg_grep($reg, $lines);

			foreach ($result as &$value) {
				// Limit to 100 as to not slowdown browser too much
				if (count($json) <= 100) {
					$json[] = ["name" => $value];
				}
			}
		}

		return $json;
	}

	// fetches the summit information from SOTA
	public function info($summit) {
		$url = 'https://api2.sota.org.uk/api/summits/' . $summit;

		// Let's use cURL instead of file_get_contents
		// begin script
		$ch = curl_init();

		// basic curl options for all requests
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);

		// use the URL we built
		curl_setopt($ch, CURLOPT_URL, $url);

		$summit_info = curl_exec($ch);

		// Close cURL handle
		curl_close($ch);

		return $summit_info;
	}
}
