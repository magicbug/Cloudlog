<?php defined('BASEPATH') or exit('No direct script access allowed');

// EqslImporter handle eQSL inboxes for multiple QTH locations.
// It can fetch confirmations from eqsl.cc or analyze a user-provided ADIF file
class EqslImporter
{
	private $name;
	private $callsign;
	private $qth_nickname;
	private $adif_file;

	// CodeIgniter super-ojbect
	private $CI;

	public function __construct() {
		// Assign the CodeIgniter super-object
		$this->CI =& get_instance();

		$this->CI->load->model('logbook_model');
		$this->CI->load->library('adif_parser');
	}

	private function init($name, $adif_file) {
		$this->name = $name;
		$this->adif_file = $adif_file;
	}

	public function from_callsign_and_QTH($callsign, $qth, $upload_path) {
		$this->init(
			$qth . " - " . $callsign,
			self::safe_filepath($callsign, $qth, $upload_path)
		);

		$this->callsign = $callsign;
		$this->qth_nickname = $qth;
	}

	public function from_file($adif_file) {
		$this->init('ADIF upload', $adif_file);
	}

	// generate a sanitized file name from a callsign and a QTH nickname
	private static function safe_filepath($callsign, $qth, $upload_path) {
		$eqsl_id = $callsign . '-' . $qth;

		// Replace anything which isn't a word, whitespace, number or any of the following caracters -_~,;[](). with a '.'
		$eqsl_id = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '.', $eqsl_id);
		$eqsl_id = mb_ereg_replace("([\.]{2,})", '', $eqsl_id);

		return $upload_path . $eqsl_id . '-eqslreport_download.adi';
	}

	// Download confirmed QSO from eQSL inbox and import them
	public function fetch($password) {
		if (empty($password) || empty($this->callsign)) {
			return $this->result('Missing username and/or password');
		}

		// Get URL for downloading the eqsl.cc inbox
		$query = $this->CI->db->query('SELECT eqsl_download_url FROM config');
		$q = $query->row();
		$eqsl_url = $q->eqsl_download_url;

		// Query the logbook to determine when the last eQSL confirmation was
		$eqsl_last_qsl_date = $this->CI->logbook_model->eqsl_last_qsl_rcvd_date($this->callsign, $this->qth_nickname);

		// Build parameters for eQSL inbox file
		$eqsl_params = http_build_query(array(
			'UserName' => $this->callsign,
			'Password' => $password,
			'RcvdSince' => $eqsl_last_qsl_date,
			'QTHNickname' => $this->qth_nickname,
			'ConfirmedOnly' => 1
		));

		// At this point, what we get isn't the ADI file we need, but rather
		// an HTML page, which contains a link to the generated ADI file that we want.
		// Adapted from Original PHP code by Chirp Internet: www.chirp.com.au (regex)

		// Let's use cURL instead of file_get_contents
		// begin script
		$ch = curl_init();
		try {
			// basic curl options for all requests
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 1);

			// use the URL and params we built
			curl_setopt($ch, CURLOPT_URL, $eqsl_url);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $eqsl_params);

			$input = curl_exec($ch);
			$chi = curl_getinfo($ch);

			// "You have no log entries" -> Nothing else to do here
			// "Your ADIF log file has been built" -> We've got an ADIF file we need to grab.

			if ($chi['http_code'] == "200") {
				if (stristr($input, "You have no log entries")) {
					return $this->result('There are no QSLs waiting for download at eQSL.cc.'); // success
				} else if (stristr($input, "Error: No such Username/Password found")) {
					return $this->result('No such Username/Password found This could mean the wrong callsign or the wrong password, or the user does not exist.'); // warning
				} else {
					if (stristr($input, "Your ADIF log file has been built")) {
						// Get all the links on the page and grab the URL for the ADI file.
						$regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";
						if (preg_match_all("/$regexp/siU", $input, $matches)) {
							foreach ($matches[2] as $match) {
								// Look for the link that has the .adi file, and download it to $file
								if (substr($match, -4, 4) == ".adi") {
									file_put_contents($this->adif_file, file_get_contents("http://eqsl.cc/qslcard/" . $match));
									return $this->import();
								}
							}
						}
					}
				}
			} else {
				if ($chi['http_code'] == "500") {
					return $this->result('eQSL.cc is experiencing issues. Please try importing QSOs later.'); // warning
				}
			}

			return $this->result('It seems that the eQSL site has changed. Please open up an issue on GitHub.');
		} finally {
			// Close cURL handle
			curl_close($ch);
		}
	}

	// Read the ADIF file and set QSO confirmation status according to the settings
	public function import(): array {
		// Figure out how we should be marking QSLs confirmed via eQSL
		$query = $this->CI->db->query('SELECT eqsl_rcvd_mark FROM config');
		$q = $query->row();
		$config['eqsl_rcvd_mark'] = $q->eqsl_rcvd_mark;

		$this->CI->adif_parser->load_from_file($this->adif_file);
		$this->CI->adif_parser->initialize();

		$qsos = array();
		$records = $updated = $not_found = $dupes = 0;
		while ($record = $this->CI->adif_parser->get_record()) {
			$records += 1;
			$time_on = date('Y-m-d', strtotime($record['qso_date'])) . " " . date('H:i', strtotime($record['time_on']));

			// The report from eQSL should only contain entries that have been confirmed via eQSL
			// If there's a match for the QSO from the report in our log, it's confirmed via eQSL.

			// If we have a positive match from eQSL, record it in the DB according to the user's preferences
			if ($record['qsl_sent'] == "Y") {
				$record['qsl_sent'] = $config['eqsl_rcvd_mark'];
			}

			$status = $this->CI->logbook_model->import_check($time_on, $record['call'], $record['band']);
			if ($status == "Found") {
				$dupe = $this->CI->logbook_model->eqsl_dupe_check($time_on, $record['call'], $record['band'], $config['eqsl_rcvd_mark']);
				if ($dupe == false) {
					$updated += 1;
					$eqsl_status = $this->CI->logbook_model->eqsl_update($time_on, $record['call'], $record['band'], $config['eqsl_rcvd_mark']);
				} else {
					$dupes += 1;
					$eqsl_status = "Already received an eQSL for this QSO.";
				}
			} else {
				$not_found += 1;
				$eqsl_status = "QSO not found";
			}

			$qsos[] = array(
				'date' => $time_on,
				'call' => str_replace("0", "&Oslash;", $record['call']),
				'mode' => $record['mode'],
				'submode' => $record['submode'] ?? null,
				'status' => $status,
				'eqsl_status' => $eqsl_status,
			);
		}

		unlink($this->adif_file);

		return $this->result("$records QSO: $updated updated / $dupes duplicates / $not_found not found", $qsos);
	}

	private function result($status, $qsos = array()): array {
		return array(
			'name' => $this->name,
			'adif_file' => $this->adif_file,
			'qsos' => $qsos,
			'status' => $status,
		);
	}
}
