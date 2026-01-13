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

		$tmpFile = tempnam(sys_get_temp_dir(), 'sota_');
		if ($tmpFile === false) {
			return [
				'ok' => false,
				'csv_saved' => false,
				'auto_saved' => false,
				'saved_refs' => 0,
				'message' => 'Cannot create temporary file',
			];
		}

		$fh = fopen($tmpFile, 'w');
		if ($fh === false) {
			unlink($tmpFile);
			return [
				'ok' => false,
				'csv_saved' => false,
				'auto_saved' => false,
				'saved_refs' => 0,
				'message' => 'Cannot open temporary file',
			];
		}

		// Download directly to file
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->csvUrl);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Cloudlog SOTA Updater');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
		curl_setopt($ch, CURLOPT_TIMEOUT, 120);
		curl_setopt($ch, CURLOPT_FILE, $fh);
		curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
		curl_close($ch);
		fclose($fh);

		if (!is_int($httpCode) || $httpCode >= 400) {
			unlink($tmpFile);
			return [
				'ok' => false,
				'csv_saved' => false,
				'auto_saved' => false,
				'saved_refs' => 0,
				'message' => 'Failed to download SOTA data',
			];
		}

		// Extract refs from file and save CSV
		$refs = $this->extractRefsFromFile($tmpFile);
		$csvSaved = copy($tmpFile, $fullPath);
		unlink($tmpFile);

		$autoSaved = false;
		if ($csvSaved && !empty($refs)) {
			$autoSaved = @file_put_contents($autocompletePath, implode(PHP_EOL, $refs)) !== false;
		}

		$ok = $csvSaved && $autoSaved;
		return [
			'ok' => $ok,
			'csv_saved' => $csvSaved,
			'auto_saved' => $autoSaved,
			'saved_refs' => count($refs),
			'message' => $ok ? 'SOTA data refreshed' : 'Failed to write SOTA data files',
		];
	}

	private function extractRefsFromFile(string $filePath): array
	{
		$refs = [];
		$fh = fopen($filePath, 'r');
		if ($fh === false) {
			return $refs;
		}

		while (($line = fgets($fh)) !== false) {
			$line = trim($line);
			if (empty($line)) {
				continue;
			}

			// Skip header lines
			if (stripos($line, 'summitcode') === 0 || stripos($line, 'sota summits') === 0) {
				continue;
			}

			// Parse CSV line (first column is the ref)
			$pos = strpos($line, ',');
			if ($pos === false) {
				continue;
			}

			$candidate = trim(substr($line, 0, $pos), '"');
			if ($candidate === '' || strpos($candidate, '/') === false) {
				continue;
			}

			$refs[] = $candidate;
		}

		fclose($fh);
		return $refs;
	}
}
