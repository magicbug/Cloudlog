<?php defined('BASEPATH') or exit('No direct script access allowed');

/***
 * Sota library is a Summit On The Air client
 */
class Sota
{
	private $csvUrl = 'https://storage.sota.org.uk/summitslist.csv';
	private $autocompletePath = 'assets/json/sota.txt';
	private $fullCsvPath = 'assets/json/sota_summits.csv';

	// return summit references matching the provided query
	public function get($query): array
	{
		if (empty($query)) {
			return [];
		}

		$json = [];
		$ref = strtoupper($query);

		$file = FCPATH . $this->autocompletePath;

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

	/**
	 * Download and persist the latest SOTA summits list (full CSV + autocomplete file).
	 */
	public function refreshFiles(bool $force = false): array
	{
		$fullPath = FCPATH . $this->fullCsvPath;
		$autocompletePath = FCPATH . $this->autocompletePath;

		if (!$force && is_readable($fullPath) && is_readable($autocompletePath)) {
			return [
				'ok' => true,
				'csv_saved' => true,
				'auto_saved' => true,
				'saved_refs' => count(file($autocompletePath, FILE_IGNORE_NEW_LINES)),
				'message' => 'SOTA files already present',
			];
		}

		$csv = $this->fetchCsv();
		if ($csv === null) {
			return [
				'ok' => false,
				'csv_saved' => false,
				'auto_saved' => false,
				'saved_refs' => 0,
				'message' => 'Something went wrong with fetching the SOTA file',
			];
		}

		$csvSaved = @file_put_contents($fullPath, $csv) !== false;

		$refs = $this->extractRefsFromCsv($csv);
		$autoSaved = @file_put_contents($autocompletePath, implode(PHP_EOL, $refs)) !== false;

		$ok = $csvSaved && $autoSaved;
		return [
			'ok' => $ok,
			'csv_saved' => $csvSaved,
			'auto_saved' => $autoSaved,
			'saved_refs' => count($refs),
			'message' => $ok ? 'SOTA data refreshed' : 'Failed to write SOTA data files',
		];
	}

	private function fetchCsv(): ?string
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->csvUrl);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Cloudlog SOTA Updater');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		$csv = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
		curl_close($ch);

		if ($csv === false || (is_int($httpCode) && $httpCode >= 400)) {
			return null;
		}

		return $csv;
	}

	private function extractRefsFromCsv(string $csv): array
	{
		$lines = str_getcsv($csv, "\n");
		$refs = [];
		foreach ($lines as $line) {
			$row = str_getcsv($line, ',');
			if (!isset($row[0])) {
				continue;
			}
			$candidate = trim($row[0]);
			if ($candidate === '') {
				continue;
			}
			$lower = strtolower($candidate);
			if (strpos($candidate, '/') === false || strpos($lower, 'summitcode') === 0 || strpos($lower, 'sota summits') === 0) {
				continue;
			}
			$refs[] = $candidate;
		}
		return $refs;
	}
}
