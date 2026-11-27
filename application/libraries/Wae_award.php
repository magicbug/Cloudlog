<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
	DARC Worked All Europe (WAE) award scoring.
	Rules: https://www.darc.de/der-club/referate/dx/en/awards-of-darc-dx-commitee/awards-of-darc-dx-commitee/rules-wae-award/

	Works on plain QSO arrays so it can be run without the database.
	Each QSO: call, dxcc, time, band, mode, submode, confirmed.
*/

class Wae_award {

	const BAND_CAP = 5;

	public $classes = array(
		'III' => array('name' => 'WAE III', 'countries' => 40, 'points' => 100),
		'II' => array('name' => 'WAE II', 'countries' => 50, 'points' => 150),
		'I' => array('name' => 'WAE I', 'countries' => 60, 'points' => 200),
		'TOP' => array('name' => 'WAE TOP', 'countries' => 70, 'points' => 300, 'current_only' => true),
		'Trophy' => array('name' => 'WAE Trophy', 'countries' => null, 'points' => 365, 'current_only' => true, 'five_band' => true),
	);

	public $modes = array('Mixed', 'CW', 'SSB', 'Phone', 'RTTY', 'FT8', 'Digital');

	/*
		Current WAE countries. Keys are stable WAE codes used in the UI.
		dxcc: ADIF entity id, or null when the country is resolved from the callsign alone.
		since: earliest valid QSO date (Y-m-d), or null.
	*/
	public $countries = array(
		'1A' => array('name' => 'S.M.O.M.', 'dxcc' => 246, 'since' => null),
		'3A' => array('name' => 'Monaco', 'dxcc' => 260, 'since' => null),
		'4O' => array('name' => 'Montenegro', 'dxcc' => 514, 'since' => '2006-06-28'),
		'4U1I' => array('name' => 'UN Geneva', 'dxcc' => 289, 'since' => null),
		'4U1V' => array('name' => 'UN Vienna', 'dxcc' => null, 'since' => null),
		'9A' => array('name' => 'Croatia', 'dxcc' => 497, 'since' => '1991-06-26'),
		'9H' => array('name' => 'Malta', 'dxcc' => 257, 'since' => null),
		'C3' => array('name' => 'Andorra', 'dxcc' => 203, 'since' => null),
		'CT' => array('name' => 'Portugal', 'dxcc' => 272, 'since' => null),
		'CU' => array('name' => 'Azores', 'dxcc' => 149, 'since' => null),
		'DL' => array('name' => 'Germany', 'dxcc' => 230, 'since' => '1973-09-17'),
		'E7' => array('name' => 'Bosnia-Herzegovina', 'dxcc' => 501, 'since' => '1991-10-15'),
		'EA' => array('name' => 'Spain', 'dxcc' => 281, 'since' => null),
		'EA6' => array('name' => 'Balearic Islands', 'dxcc' => 21, 'since' => null),
		'EI' => array('name' => 'Ireland', 'dxcc' => 245, 'since' => null),
		'ER' => array('name' => 'Moldova', 'dxcc' => 179, 'since' => null),
		'ES' => array('name' => 'Estonia', 'dxcc' => 52, 'since' => null),
		'EU' => array('name' => 'Belarus', 'dxcc' => 27, 'since' => null),
		'F' => array('name' => 'France', 'dxcc' => 227, 'since' => null),
		'G' => array('name' => 'England', 'dxcc' => 223, 'since' => null),
		'GD' => array('name' => 'Isle of Man', 'dxcc' => 114, 'since' => null),
		'GI' => array('name' => 'Northern Ireland', 'dxcc' => 265, 'since' => null),
		'GJ' => array('name' => 'Jersey', 'dxcc' => 122, 'since' => null),
		'GM' => array('name' => 'Scotland', 'dxcc' => 279, 'since' => null),
		'GM/S' => array('name' => 'Shetland Islands', 'dxcc' => null, 'since' => null),
		'GU' => array('name' => 'Guernsey', 'dxcc' => 106, 'since' => null),
		'GW' => array('name' => 'Wales', 'dxcc' => 294, 'since' => null),
		'HA' => array('name' => 'Hungary', 'dxcc' => 239, 'since' => null),
		'HB' => array('name' => 'Switzerland', 'dxcc' => 287, 'since' => null),
		'HB0' => array('name' => 'Liechtenstein', 'dxcc' => 251, 'since' => null),
		'HV' => array('name' => 'Vatican', 'dxcc' => 295, 'since' => null),
		'I' => array('name' => 'Italy', 'dxcc' => 248, 'since' => null),
		'IS' => array('name' => 'Sardinia', 'dxcc' => 225, 'since' => null),
		'IT' => array('name' => 'Sicily', 'dxcc' => null, 'since' => null),
		'JW' => array('name' => 'Spitsbergen', 'dxcc' => 259, 'since' => null),
		'JW/B' => array('name' => 'Bear Island', 'dxcc' => null, 'since' => null),
		'JX' => array('name' => 'Jan Mayen', 'dxcc' => 118, 'since' => null),
		'LA' => array('name' => 'Norway', 'dxcc' => 266, 'since' => null),
		'LX' => array('name' => 'Luxembourg', 'dxcc' => 254, 'since' => null),
		'LY' => array('name' => 'Lithuania', 'dxcc' => 146, 'since' => null),
		'LZ' => array('name' => 'Bulgaria', 'dxcc' => 212, 'since' => null),
		'OE' => array('name' => 'Austria', 'dxcc' => 206, 'since' => null),
		'OH' => array('name' => 'Finland', 'dxcc' => 224, 'since' => null),
		'OH0' => array('name' => 'Aland Islands', 'dxcc' => 5, 'since' => null),
		'OJ0' => array('name' => 'Market Reef', 'dxcc' => 167, 'since' => null),
		'OK' => array('name' => 'Czech Republic', 'dxcc' => 503, 'since' => '1993-01-01'),
		'OM' => array('name' => 'Slovakia', 'dxcc' => 504, 'since' => '1993-01-01'),
		'ON' => array('name' => 'Belgium', 'dxcc' => 209, 'since' => null),
		'OY' => array('name' => 'Faroe Islands', 'dxcc' => 222, 'since' => null),
		'OZ' => array('name' => 'Denmark', 'dxcc' => 221, 'since' => null),
		'PA' => array('name' => 'Netherlands', 'dxcc' => 263, 'since' => null),
		'R1FJ' => array('name' => 'Franz Josef Land', 'dxcc' => 61, 'since' => null),
		'S5' => array('name' => 'Slovenia', 'dxcc' => 499, 'since' => '1991-06-26'),
		'SM' => array('name' => 'Sweden', 'dxcc' => 284, 'since' => null),
		'SP' => array('name' => 'Poland', 'dxcc' => 269, 'since' => null),
		'SV' => array('name' => 'Greece', 'dxcc' => 236, 'since' => null),
		'SV/A' => array('name' => 'Mount Athos', 'dxcc' => 180, 'since' => null),
		'SV5' => array('name' => 'Dodecanese', 'dxcc' => 45, 'since' => null),
		'SV9' => array('name' => 'Crete', 'dxcc' => 40, 'since' => null),
		'T7' => array('name' => 'San Marino', 'dxcc' => 278, 'since' => null),
		'TA1' => array('name' => 'Turkey (European)', 'dxcc' => null, 'since' => null),
		'TF' => array('name' => 'Iceland', 'dxcc' => 242, 'since' => null),
		'TK' => array('name' => 'Corsica', 'dxcc' => 214, 'since' => null),
		'UA' => array('name' => 'European Russia', 'dxcc' => 54, 'since' => null),
		'UA2' => array('name' => 'Kaliningrad', 'dxcc' => 126, 'since' => null),
		'UR' => array('name' => 'Ukraine', 'dxcc' => 288, 'since' => null),
		'YL' => array('name' => 'Latvia', 'dxcc' => 145, 'since' => null),
		'YO' => array('name' => 'Romania', 'dxcc' => 275, 'since' => null),
		'YU' => array('name' => 'Serbia', 'dxcc' => 296, 'since' => null),
		'Z6' => array('name' => 'Kosovo', 'dxcc' => 522, 'since' => '2008-02-17'),
		'Z3' => array('name' => 'North Macedonia', 'dxcc' => 502, 'since' => '1991-09-08'),
		'ZA' => array('name' => 'Albania', 'dxcc' => 7, 'since' => null),
		'ZB' => array('name' => 'Gibraltar', 'dxcc' => 233, 'since' => null),
	);

	/*
		Deleted countries count toward WAE III / II / I only.
	*/
	public $deleted = array(
		'9S4' => array('name' => 'Saarland', 'dxcc' => null, 'since' => '1947-11-08', 'until' => '1957-03-31'),
		'I1' => array('name' => 'Trieste', 'dxcc' => null, 'since' => null, 'until' => '1957-03-31'),
		'UN' => array('name' => 'Karelo-Finnish Republic', 'dxcc' => null, 'since' => null, 'until' => '1960-06-30'),
		'DL-old' => array('name' => 'Germany (pre-1973)', 'dxcc' => 230, 'since' => null, 'until' => '1973-09-16'),
		'Y2' => array('name' => 'German Democratic Republic', 'dxcc' => 229, 'since' => '1973-09-17', 'until' => '1990-10-02'),
		'UA1N' => array('name' => 'Karelia', 'dxcc' => null, 'since' => '1960-07-01', 'until' => '1991-12-31'),
		'OK-old' => array('name' => 'Czechoslovakia', 'dxcc' => 218, 'since' => null, 'until' => '1992-12-31'),
		'R1MV' => array('name' => 'Malyj Vysotskij Island', 'dxcc' => 151, 'since' => null, 'until' => '2012-02-17'),
	);

	private $ignored_suffixes = array('P', 'M', 'MM', 'AM', 'A', 'QRP', 'LH', 'AE', 'AG', 'AI');

	public function current_count() {
		return count($this->countries);
	}

	/*
		Resolve a QSO to a WAE country code, or null when it does not count.
	*/
	public function country_of_qso($qso) {
		$call = strtoupper(trim((string) (isset($qso['call']) ? $qso['call'] : '')));
		$dxcc = isset($qso['dxcc']) ? (int) $qso['dxcc'] : 0;
		$date = substr((string) (isset($qso['time']) ? $qso['time'] : ''), 0, 10);

		// Callsign-first specials that override a logged DXCC entity.
		if ($this->is_4u1v($call)) {
			return $this->dated('4U1V', $date, false);
		}
		if ($this->is_4u1itu($call)) {
			return $this->dated('4U1I', $date, false);
		}
		if ($this->is_bear_island($call)) {
			return $this->dated('JW/B', $date, false);
		}
		if ($this->is_shetland($call, $dxcc)) {
			return $this->dated('GM/S', $date, false);
		}
		if ($this->is_sicily($call, $dxcc)) {
			return $this->dated('IT', $date, false);
		}
		if ($this->is_african_italy($call)) {
			return null;
		}
		if ($this->is_european_turkey($call, $dxcc)) {
			return $this->dated('TA1', $date, false);
		}
		// Asiatic Turkey and other non-WAE Turkey do not count.
		if ($dxcc === 390) {
			return null;
		}

		// Deleted entities with clear DXCC ids (for III/II/I only).
		foreach ($this->deleted as $code => $info) {
			if ($info['dxcc'] === null) {
				continue;
			}
			if ($dxcc === (int) $info['dxcc'] && $this->in_window($date, $info['since'], $info['until'])) {
				// Pre-1973 Germany uses the same DXCC id as current DL.
				if ($code === 'DL-old' && ($date === '' || $date >= '1973-09-17')) {
					continue;
				}
				if ($code === 'OK-old' && ($date === '' || $date >= '1993-01-01')) {
					continue;
				}
				return $code;
			}
		}

		foreach ($this->countries as $code => $info) {
			if ($info['dxcc'] === null) {
				continue;
			}
			if ($dxcc === (int) $info['dxcc']) {
				// Scotland QSOs that are Shetland were already returned.
				if ($code === 'GM' && $this->is_shetland($call, $dxcc)) {
					continue;
				}
				// Italy QSOs that are Sicily were already returned.
				if ($code === 'I' && $this->is_sicily($call, $dxcc)) {
					continue;
				}
				// Spitsbergen vs Bear Island.
				if ($code === 'JW' && $this->is_bear_island($call)) {
					continue;
				}
				return $this->dated($code, $date, false);
			}
		}

		return null;
	}

	private function dated($code, $date, $deleted) {
		$info = $deleted ? $this->deleted[$code] : $this->countries[$code];
		$since = isset($info['since']) ? $info['since'] : null;
		$until = isset($info['until']) ? $info['until'] : null;
		if (!$this->in_window($date, $since, $until)) {
			return null;
		}
		return $code;
	}

	private function in_window($date, $since, $until) {
		if ($date === '') {
			return true;
		}
		if ($since !== null && $date < $since) {
			return false;
		}
		if ($until !== null && $date > $until) {
			return false;
		}
		return true;
	}

	private function call_parts($call) {
		$parts = array();
		foreach (explode('/', strtoupper(trim((string) $call))) as $part) {
			$part = trim($part);
			if ($part === '' || in_array($part, $this->ignored_suffixes, true)) {
				continue;
			}
			$parts[] = $part;
		}
		return $parts;
	}

	private function base_call($call) {
		$parts = $this->call_parts($call);
		$best = '';
		foreach ($parts as $part) {
			if (preg_match('/\d/', $part) && preg_match('/[A-Z]/', $part) && strlen($part) > strlen($best)) {
				$best = $part;
			}
		}
		if ($best === '' && $parts) {
			$best = $parts[0];
		}
		return $best;
	}

	private function is_4u1v($call) {
		$base = $this->base_call($call);
		return $base === '4U1V' || strpos($base, '4U1VIC') === 0;
	}

	private function is_4u1itu($call) {
		$base = $this->base_call($call);
		return $base === '4U1ITU' || $base === '4U1UN' || strpos($base, '4U1ITU') === 0;
	}

	private function is_bear_island($call) {
		$upper = strtoupper(trim((string) $call));
		// /B is otherwise an ignored portable suffix, so match the raw call.
		return (bool) preg_match('/(^|\/)JW[0-9A-Z]*\/B$/', $upper)
			|| (bool) preg_match('/^JW[0-9A-Z]*\/B$/', $upper);
	}

	private function is_shetland($call, $dxcc) {
		$upper = strtoupper(trim((string) $call));
		// GS / 2S / MS Shetland special prefixes.
		$base = $this->base_call($call);
		if (preg_match('/^(GS|2S|MS)[0-9]/', $base)) {
			return true;
		}
		// GM.../S (and similar) from Scotland.
		if (preg_match('/\/S$/', $upper) && ($dxcc === 279 || preg_match('/(^|\/)(GM|MM|2M)/', $upper))) {
			return true;
		}
		return false;
	}

	private function is_african_italy($call) {
		$base = $this->base_call($call);
		return (bool) preg_match('/^(IG9|IH9)/', $base);
	}

	private function is_sicily($call, $dxcc) {
		if ($dxcc !== 248 && $dxcc !== 0) {
			return false;
		}
		if ($this->is_african_italy($call)) {
			return false;
		}
		$base = $this->base_call($call);
		// Italian district 9 is Sicily (IT9, IZ9, IK9, IQ9, II9, ...).
		return (bool) preg_match('/^(I[A-Z]?|IK|IZ|IQ|II|IR|IU|IW|IX)9/', $base)
			|| (bool) preg_match('/^IT9/', $base);
	}

	private function is_european_turkey($call, $dxcc) {
		// Turkey DXCC is 390. Only call area 1 counts for WAE.
		if ($dxcc !== 390 && $dxcc !== 0) {
			return false;
		}
		$base = $this->base_call($call);
		return (bool) preg_match('/^(TA|TB|TC|YM)1/', $base);
	}

	/*
		Mode filters used on the award page.
	*/
	public function mode_matches($mode_filter, $mode, $submode = '') {
		$mode = strtoupper(trim((string) $mode));
		$sub = strtoupper(trim((string) $submode));
		if ($mode_filter === 'Mixed' || $mode_filter === 'All' || $mode_filter === '') {
			return true;
		}
		if ($mode_filter === 'CW') {
			return $mode === 'CW';
		}
		if ($mode_filter === 'SSB') {
			return $mode === 'SSB' || in_array($sub, array('USB', 'LSB'), true);
		}
		if ($mode_filter === 'Phone') {
			return in_array($mode, array('SSB', 'USB', 'LSB', 'AM', 'FM', 'DIGITALVOICE', 'DSTAR', 'C4FM', 'DMR', 'FREEDV', 'M17'), true)
				|| in_array($sub, array('USB', 'LSB'), true);
		}
		if ($mode_filter === 'RTTY') {
			return $mode === 'RTTY';
		}
		if ($mode_filter === 'FT8') {
			return $mode === 'FT8' || $sub === 'FT8';
		}
		if ($mode_filter === 'Digital') {
			if ($mode === 'CW') {
				return false;
			}
			if (in_array($mode, array('SSB', 'USB', 'LSB', 'AM', 'FM', 'DIGITALVOICE', 'DSTAR', 'C4FM', 'DMR', 'FREEDV', 'M17', 'ATV', 'SSTV', 'FAX'), true)) {
				return false;
			}
			return $mode !== '';
		}
		return true;
	}

	/*
		Score QSOs. Optional $mode_filter limits which QSOs count (Mixed counts all).
	*/
	public function evaluate($qsos, $mode_filter = 'Mixed') {
		$worked = array();
		$confirmed = array();

		foreach ($qsos as $qso) {
			if (!$this->mode_matches($mode_filter, isset($qso['mode']) ? $qso['mode'] : '', isset($qso['submode']) ? $qso['submode'] : '')) {
				continue;
			}
			$code = $this->country_of_qso($qso);
			if ($code === null) {
				continue;
			}
			$band = (string) (isset($qso['band']) ? $qso['band'] : '');
			if ($band === '') {
				continue;
			}
			$is_confirmed = !empty($qso['confirmed']);

			if (!isset($worked[$code])) {
				$worked[$code] = array();
			}
			$worked[$code][$band] = true;

			if ($is_confirmed) {
				if (!isset($confirmed[$code])) {
					$confirmed[$code] = array();
				}
				$confirmed[$code][$band] = true;
			}
		}

		return array(
			'rows' => $this->build_rows($worked, $confirmed),
			'classes' => array(
				'worked' => $this->class_progress($worked, false),
				'confirmed' => $this->class_progress($confirmed, true),
			),
			'missing' => $this->missing_list($confirmed),
		);
	}

	private function is_deleted($code) {
		return isset($this->deleted[$code]);
	}

	private function band_points($bands) {
		return min(self::BAND_CAP, count($bands));
	}

	private function build_rows($worked, $confirmed) {
		$rows = array();
		foreach ($this->countries as $code => $info) {
			$w_bands = isset($worked[$code]) ? array_keys($worked[$code]) : array();
			$c_bands = isset($confirmed[$code]) ? array_keys($confirmed[$code]) : array();
			sort($w_bands);
			sort($c_bands);
			$rows[] = array(
				'code' => $code,
				'name' => $info['name'],
				'deleted' => false,
				'worked_bands' => $w_bands,
				'confirmed_bands' => $c_bands,
				'worked_points' => $this->band_points($w_bands),
				'confirmed_points' => $this->band_points($c_bands),
			);
		}
		foreach ($this->deleted as $code => $info) {
			if (!isset($worked[$code]) && !isset($confirmed[$code])) {
				continue;
			}
			$w_bands = isset($worked[$code]) ? array_keys($worked[$code]) : array();
			$c_bands = isset($confirmed[$code]) ? array_keys($confirmed[$code]) : array();
			sort($w_bands);
			sort($c_bands);
			$rows[] = array(
				'code' => $code,
				'name' => $info['name'] . ' (deleted)',
				'deleted' => true,
				'worked_bands' => $w_bands,
				'confirmed_bands' => $c_bands,
				'worked_points' => $this->band_points($w_bands),
				'confirmed_points' => $this->band_points($c_bands),
			);
		}
		return $rows;
	}

	private function class_progress($by_country, $confirmed_pass) {
		$out = array();
		foreach ($this->classes as $key => $spec) {
			$current_only = !empty($spec['current_only']);
			$five_band = !empty($spec['five_band']);
			$countries = 0;
			$points = 0;
			$five_band_count = 0;

			foreach ($by_country as $code => $bands) {
				if ($current_only && $this->is_deleted($code)) {
					continue;
				}
				if ($current_only && !isset($this->countries[$code])) {
					continue;
				}
				$n = $this->band_points($bands);
				if ($n <= 0) {
					continue;
				}
				$countries++;
				$points += $n;
				if (count($bands) >= 5) {
					$five_band_count++;
				}
			}

			if ($five_band) {
				$needed = $this->current_count();
				$earned = ($five_band_count >= $needed) && ($points >= $spec['points']);
				$out[$key] = array(
					'key' => $key,
					'name' => $spec['name'],
					'countries' => $five_band_count,
					'countries_target' => $needed,
					'points' => $points,
					'points_target' => $spec['points'],
					'earned' => $earned,
					'pct' => min(100, (int) floor(($five_band_count / max(1, $needed) + $points / max(1, $spec['points'])) / 2 * 100)),
				);
			} else {
				$c_target = $spec['countries'];
				$p_target = $spec['points'];
				$earned = ($countries >= $c_target) && ($points >= $p_target);
				$out[$key] = array(
					'key' => $key,
					'name' => $spec['name'],
					'countries' => $countries,
					'countries_target' => $c_target,
					'points' => $points,
					'points_target' => $p_target,
					'earned' => $earned,
					'pct' => min(100, (int) floor(($countries / max(1, $c_target) + $points / max(1, $p_target)) / 2 * 100)),
				);
			}
		}
		return $out;
	}

	private function missing_list($confirmed) {
		$missing = array();
		foreach ($this->countries as $code => $info) {
			$bands = isset($confirmed[$code]) ? count($confirmed[$code]) : 0;
			if ($bands < 5) {
				$missing[] = array(
					'code' => $code,
					'name' => $info['name'],
					'confirmed_bands' => $bands,
					'needed_for_trophy' => 5 - $bands,
				);
			}
		}
		return $missing;
	}
}
