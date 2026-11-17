<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
	Club Log log matches, from https://clublog.org/getmatches.php.
	A match is a QSO that both stations uploaded to Club Log. The reply is JSON:
	[["G0LGJ/M","223","2005-07-16 08:00:00","20","CW"], ...], callsign, DXCC, UTC time, band, mode.
	The band is a number without a unit, so 20 is 20m and 70 is 70cm. The mode can be false.

	Works on plain arrays so it can be run without the database.
*/

class Clublog_matches {

	const WINDOW_SECONDS = 900;

	/*
		Returns ['error' => null or a short reason, 'matches' => [['call', 'time', 'band'], ...]].
		Reasons: login (credentials refused), callsign (not on the account), html, json.
	*/
	public function parse($body) {
		$text = trim((string) $body);
		if (stripos($text, 'Login rejected') !== false) {
			return array('error' => 'login', 'matches' => array());
		}
		if (stripos($text, 'Invalid callsign') !== false) {
			return array('error' => 'callsign', 'matches' => array());
		}
		if ($text === '' || $text[0] === '<') {
			return array('error' => $text === '' ? 'json' : 'html', 'matches' => array());
		}
		$rows = json_decode($text, true);
		if (!is_array($rows)) {
			return array('error' => 'json', 'matches' => array());
		}
		$matches = array();
		foreach ($rows as $row) {
			if (!is_array($row) || count($row) < 4) {
				continue;
			}
			$call = strtoupper(trim((string) $row[0]));
			$time = trim((string) $row[2]);
			if ($call === '' || !preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}(:\d{2})?$/', $time)) {
				continue;
			}
			$matches[] = array('call' => $call, 'time' => $time, 'band' => trim((string) $row[3]));
		}
		return array('error' => null, 'matches' => $matches);
	}

	// The Cloudlog band without its unit: 20m is 20, 70cm is 70, 2m is 2.
	public function band_number($band) {
		$band = strtolower(trim((string) $band));
		return preg_replace('/(mm|cm|m)$/', '', $band);
	}

	/*
		The logged QSO a match belongs to: same band, within 15 minutes, closest in time.
		$candidates is a list of ['id', 'time', 'band'] for the matched callsign. Returns the id or null.
	*/
	public function pick($match, $candidates) {
		$at = strtotime($match['time'] . ' UTC');
		if ($at === false) {
			return null;
		}
		$best = null;
		$best_gap = null;
		foreach ($candidates as $c) {
			if ($match['band'] !== '' && $this->band_number($c['band']) !== $match['band']) {
				continue;
			}
			$gap = abs(strtotime($c['time'] . ' UTC') - $at);
			if ($gap > self::WINDOW_SECONDS) {
				continue;
			}
			if ($best_gap === null || $gap < $best_gap) {
				$best = $c['id'];
				$best_gap = $gap;
			}
		}
		return $best;
	}

	public function url($api_key, $email, $password, $callsign, $since = null) {
		$params = array(
			'api' => $api_key,
			'email' => $email,
			'password' => $password,
			'callsign' => strtoupper(trim($callsign)),
		);
		if ($since && preg_match('/^(\d{4})-(\d{2})-(\d{2})/', $since, $m)) {
			$params['startyear'] = $m[1];
			$params['startmonth'] = $m[2];
			$params['startday'] = $m[3];
		}
		return 'https://clublog.org/getmatches.php?' . http_build_query($params);
	}
}
