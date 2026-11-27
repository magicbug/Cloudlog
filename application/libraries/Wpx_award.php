<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
	CQ Worked All Prefixes (WPX) award scoring.
	Rules: CQ WPX award program (HF 160-10 and 6 metres).

	Works on plain QSO arrays so it can be run without the database.
	Each QSO: call, pfx (optional ADIF PFX), band, mode, submode, cont, confirmed.
*/

class Wpx_award {

	const MIXED_BASIC = 400;
	const MODE_BASIC = 300;
	const ENDORSEMENT_STEP = 50;
	const HONOR_MIXED = 1000;
	const HONOR_MODE = 600;

	// Bands that count toward WPX certificates (160 m through 6 m).
	public $bands = array('160m', '80m', '60m', '40m', '30m', '20m', '17m', '15m', '12m', '10m', '6m');

	public $band_endorsements = array(
		'160m' => 50,
		'80m' => 175,
		'60m' => 175,
		'40m' => 250,
		'30m' => 250,
		'20m' => 300,
		'17m' => 300,
		'15m' => 300,
		'12m' => 300,
		'10m' => 300,
		'6m' => 250,
	);

	// Non-WARC band endorsements required for Honor Roll.
	public $honor_bands = array('80m', '40m', '20m', '15m', '10m');

	public $continent_endorsements = array(
		'NA' => array('name' => 'North America', 'target' => 160),
		'SA' => array('name' => 'South America', 'target' => 95),
		'EU' => array('name' => 'Europe', 'target' => 160),
		'AF' => array('name' => 'Africa', 'target' => 90),
		'AS' => array('name' => 'Asia', 'target' => 75),
		'OC' => array('name' => 'Oceania', 'target' => 60),
	);

	// Portable / licence-class suffixes that never form a prefix.
	private $ignored_suffixes = array('P', 'M', 'MM', 'AM', 'A', 'E', 'J', 'QRP', 'LH', 'B', 'AE', 'AG', 'AI');

	/*
		CQ WPX prefix from a callsign. Returns '' when the call cannot form a prefix.
	*/
	public function prefix_from_call($call) {
		$call = strtoupper(trim((string) $call));
		if ($call === '') {
			return '';
		}
		$parts = array();
		foreach (explode('/', $call) as $part) {
			$part = trim($part);
			if ($part === '' || in_array($part, $this->ignored_suffixes, true)) {
				continue;
			}
			$parts[] = $part;
		}
		if (!$parts) {
			return '';
		}

		if (count($parts) === 1) {
			return $this->prefix_of_body($parts[0]);
		}

		// CALL/digit — replace the number in the home prefix (WN5N/7 → WN7).
		if (count($parts) === 2 && preg_match('/^\d+$/', $parts[1])) {
			$home = $this->prefix_of_body($parts[0]);
			if ($home === '') {
				return '';
			}
			if (preg_match('/^([A-Z]+)\d+$/', $home, $m)) {
				return $m[1] . $parts[1];
			}
			return $home;
		}

		// X/CALL or CALL/X — the portable designator becomes the prefix.
		$a = $parts[0];
		$b = $parts[1];

		// Prefer a leading guest-country / call-area designator when it looks like a prefix.
		if ($this->looks_like_prefix_designator($a) && $this->looks_like_call($b)) {
			return $this->prefix_of_designator($a);
		}
		if ($this->looks_like_prefix_designator($b)) {
			return $this->prefix_of_designator($b);
		}
		if ($this->looks_like_prefix_designator($a)) {
			return $this->prefix_of_designator($a);
		}
		return $this->prefix_of_body($b);
	}

	/*
		Prefer a stored ADIF PFX when present; otherwise compute from the call.
	*/
	public function prefix_of_qso($qso) {
		$stored = strtoupper(trim((string) (isset($qso['pfx']) ? $qso['pfx'] : '')));
		if ($stored !== '') {
			return $stored;
		}
		return $this->prefix_from_call(isset($qso['call']) ? $qso['call'] : '');
	}

	private function looks_like_call($part) {
		return (bool) preg_match('/[A-Z].*\d|\d.*[A-Z]/', $part);
	}

	private function looks_like_prefix_designator($part) {
		// A short letter/digit run used as a portable indicator (PA, LX, J6, KH6, WK9).
		return (bool) preg_match('/^[A-Z]{1,3}\d{0,4}$|^[A-Z0-9]{1,4}$/', $part)
			&& !preg_match('/^[A-Z]+\d+[A-Z]+$/', $part);
	}

	private function prefix_of_designator($part) {
		$part = strtoupper(trim((string) $part));
		if ($part === '') {
			return '';
		}
		if (preg_match('/\d/', $part)) {
			return $part;
		}
		// Portable designator without a number gets a trailing zero (PA → PA0).
		if (strlen($part) >= 2) {
			return substr($part, 0, 2) . '0';
		}
		return $part . '0';
	}

	private function prefix_of_body($call) {
		$call = strtoupper(trim((string) $call));
		if ($call === '' || preg_match('/^[0-9]+$/', $call)) {
			return '';
		}
		// Letter(s) then digit(s): keep the whole number (HG19, LY1000, WD8).
		if (preg_match('/^([A-Z]+\d+)/', $call, $m)) {
			return $m[1];
		}
		// Digit then letter(s) then digit(s): 3DA0, 4X1, 9A5.
		if (preg_match('/^(\d[A-Z]+\d+)/', $call, $m)) {
			return $m[1];
		}
		// No number: first two letters plus zero (RAEM → RA0, XEFTJW → XE0).
		if (preg_match('/^[A-Z]{2,}/', $call)) {
			return substr($call, 0, 2) . '0';
		}
		return '';
	}

	/*
		WPX mode groups. AM and FM count for Mixed only.
		SSB covers phone voice; Digital covers RTTY/PSK/FT* and other digital.
	*/
	public function mode_group($mode, $submode = '') {
		$mode = strtoupper(trim((string) $mode));
		$sub = strtoupper(trim((string) $submode));
		if ($mode === '' ) {
			return null;
		}
		if ($mode === 'CW') {
			return 'CW';
		}
		if ($mode === 'SSB' || in_array($sub, array('USB', 'LSB'), true)) {
			return 'SSB';
		}
		if (in_array($mode, array('AM', 'FM'), true)) {
			return null;
		}
		if (in_array($mode, array('DIGITALVOICE', 'DSTAR', 'C4FM', 'DMR', 'FREEDV', 'M17', 'USB', 'LSB'), true)) {
			return 'SSB';
		}
		if (in_array($mode, array('ATV', 'SSTV', 'FAX'), true)) {
			return null;
		}
		return 'Digital';
	}

	public function band_allowed($band) {
		return in_array($band, $this->bands, true);
	}

	/*
		Score an array of QSO rows. Each row needs at least call or pfx, plus band/mode/cont/confirmed.
		Returns certificate progress, endorsements, honor roll, and the prefix table.
	*/
	public function evaluate($qsos) {
		$prefixes = array();

		foreach ($qsos as $qso) {
			$pfx = $this->prefix_of_qso($qso);
			if ($pfx === '') {
				continue;
			}
			$band = isset($qso['band']) ? (string) $qso['band'] : '';
			if (!$this->band_allowed($band)) {
				continue;
			}
			$confirmed = !empty($qso['confirmed']);
			$mode = $this->mode_group(isset($qso['mode']) ? $qso['mode'] : '', isset($qso['submode']) ? $qso['submode'] : '');
			$cont = strtoupper(trim((string) (isset($qso['cont']) ? $qso['cont'] : '')));

			$count = isset($qso['count']) ? max(1, (int) $qso['count']) : 1;

			if (!isset($prefixes[$pfx])) {
				$prefixes[$pfx] = array(
					'prefix' => $pfx,
					'worked' => false,
					'confirmed' => false,
					'qsos' => 0,
					'bands' => array(),
					'modes' => array(),
					'cont' => $cont,
				);
			}
			$prefixes[$pfx]['worked'] = true;
			$prefixes[$pfx]['qsos'] += $count;
			if ($confirmed) {
				$prefixes[$pfx]['confirmed'] = true;
			}
			if ($band !== '' && !in_array($band, $prefixes[$pfx]['bands'], true)) {
				$prefixes[$pfx]['bands'][] = $band;
			}
			if ($mode !== null && !in_array($mode, $prefixes[$pfx]['modes'], true)) {
				$prefixes[$pfx]['modes'][] = $mode;
			}
			if ($cont !== '' && $prefixes[$pfx]['cont'] === '') {
				$prefixes[$pfx]['cont'] = $cont;
			}
		}

		ksort($prefixes, SORT_STRING);

		$certificates = array(
			'Mixed' => $this->certificate_progress($prefixes, null, self::MIXED_BASIC),
			'CW' => $this->certificate_progress($prefixes, 'CW', self::MODE_BASIC),
			'SSB' => $this->certificate_progress($prefixes, 'SSB', self::MODE_BASIC),
			'Digital' => $this->certificate_progress($prefixes, 'Digital', self::MODE_BASIC),
		);

		$band_rows = array();
		foreach ($this->band_endorsements as $band => $target) {
			$worked = 0;
			$confirmed = 0;
			foreach ($prefixes as $row) {
				if (!in_array($band, $row['bands'], true)) {
					continue;
				}
				$worked++;
				if ($row['confirmed']) {
					$confirmed++;
				}
			}
			$band_rows[$band] = array(
				'band' => $band,
				'target' => $target,
				'worked' => $worked,
				'confirmed' => $confirmed,
				'earned' => $confirmed >= $target,
			);
		}

		$continent_rows = array();
		foreach ($this->continent_endorsements as $code => $info) {
			$worked = 0;
			$confirmed = 0;
			foreach ($prefixes as $row) {
				if ($row['cont'] !== $code) {
					continue;
				}
				$worked++;
				if ($row['confirmed']) {
					$confirmed++;
				}
			}
			$continent_rows[$code] = array(
				'code' => $code,
				'name' => $info['name'],
				'target' => $info['target'],
				'worked' => $worked,
				'confirmed' => $confirmed,
				'earned' => $confirmed >= $info['target'],
			);
		}

		return array(
			'prefixes' => array_values($prefixes),
			'certificates' => $certificates,
			'band_endorsements' => $band_rows,
			'continent_endorsements' => $continent_rows,
			'honor_roll' => $this->honor_roll($certificates, $band_rows, $continent_rows),
			'totals' => array(
				'worked' => count($prefixes),
				'confirmed' => $this->count_confirmed($prefixes, null),
			),
		);
	}

	private function count_confirmed($prefixes, $mode) {
		$n = 0;
		foreach ($prefixes as $row) {
			if (!$row['confirmed']) {
				continue;
			}
			if ($mode === null || in_array($mode, $row['modes'], true)) {
				$n++;
			}
		}
		return $n;
	}

	private function count_worked($prefixes, $mode) {
		$n = 0;
		foreach ($prefixes as $row) {
			if ($mode === null || in_array($mode, $row['modes'], true)) {
				$n++;
			}
		}
		return $n;
	}

	private function certificate_progress($prefixes, $mode, $basic) {
		$confirmed = $this->count_confirmed($prefixes, $mode);
		$worked = $this->count_worked($prefixes, $mode);
		$earned = $confirmed >= $basic;
		$endorsement = 0;
		if ($earned) {
			$endorsement = intdiv($confirmed - $basic, self::ENDORSEMENT_STEP) * self::ENDORSEMENT_STEP;
		}
		$next = $earned
			? $basic + $endorsement + self::ENDORSEMENT_STEP
			: $basic;
		return array(
			'mode' => $mode === null ? 'Mixed' : $mode,
			'basic' => $basic,
			'worked' => $worked,
			'confirmed' => $confirmed,
			'earned' => $earned,
			'endorsement' => $endorsement,
			'next' => $next,
			'pct' => min(100, (int) floor($confirmed / $basic * 100)),
		);
	}

	private function honor_roll($certificates, $band_rows, $continent_rows) {
		$mixed_ok = $certificates['Mixed']['confirmed'] >= self::HONOR_MIXED;
		$ssb_ok = $certificates['SSB']['confirmed'] >= self::HONOR_MODE;
		$cw_ok = $certificates['CW']['confirmed'] >= self::HONOR_MODE;
		$prefix_ok = $mixed_ok || $ssb_ok || $cw_ok;

		$bands_ok = true;
		$band_checks = array();
		foreach ($this->honor_bands as $band) {
			$ok = !empty($band_rows[$band]['earned']);
			$band_checks[$band] = $ok;
			if (!$ok) {
				$bands_ok = false;
			}
		}

		$continents_ok = true;
		$continent_checks = array();
		foreach ($continent_rows as $code => $row) {
			$ok = !empty($row['earned']);
			$continent_checks[$code] = $ok;
			if (!$ok) {
				$continents_ok = false;
			}
		}

		return array(
			'earned' => $prefix_ok && $bands_ok && $continents_ok,
			'prefix_ok' => $prefix_ok,
			'mixed' => $certificates['Mixed']['confirmed'],
			'mixed_target' => self::HONOR_MIXED,
			'ssb' => $certificates['SSB']['confirmed'],
			'cw' => $certificates['CW']['confirmed'],
			'mode_target' => self::HONOR_MODE,
			'bands' => $band_checks,
			'continents' => $continent_checks,
		);
	}
}
