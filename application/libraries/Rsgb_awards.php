<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
	RSGB awards: Foundation, Intermediate 100, IARU Region 1, Commonwealth Century, Worked ITU Zones,
	the 50 MHz awards, VHF squares and countries, and the V/UHF Activity Award.
	Rules: https://rsgb.org/main/operating/amateur-radio-awards/

	Works on plain QSO arrays so it can be run without the database.
	Each QSO: call, time, band, freq (Hz), mode, submode, prop, dxcc, cont, ituz, grid, vucc,
	station_call, confirmed.
*/

class Rsgb_awards {

	const ACTIVITY_SINCE = '2021-06-01';
	const SIX_METRE_SINCE = '1987-06-01';
	const VHF_SINCE = '1978-12-31';
	const TEN_METRE_SINCE = '1983-07-01';

	public $hf_bands = array('160m', '80m', '40m', '30m', '20m', '17m', '15m', '12m', '10m');

	// Propagation modes that mean the contact went through a repeater, gateway, or satellite.
	public $non_direct = array('RPT', 'INTERNET', 'SAT', 'ECH', 'IRL');

	private $below_50mhz = array('2190m', '630m', '560m', '160m', '80m', '60m', '40m', '30m', '20m', '17m', '15m', '12m', '10m', '8m');
	private $call_suffixes = array('P', 'M', 'MM', 'AM', 'A', 'QRP', 'LH');
	private $continents = array('AF' => 'Africa', 'AS' => 'Asia', 'EU' => 'Europe', 'NA' => 'North America', 'OC' => 'Oceania', 'SA' => 'South America');

	public $groups = array(
		'foundation' => array('name' => 'Foundation', 'cards' => false, 'licence' => 'foundation', 'bands' => null, 'since' => null, 'no_sat' => false),
		'intermediate' => array('name' => 'Intermediate 100', 'cards' => false, 'licence' => 'intermediate', 'bands' => array('80m', '40m', '30m', '17m'), 'since' => null, 'no_sat' => false),
		'iaru' => array('name' => 'IARU Region 1', 'cards' => true, 'licence' => null, 'bands' => array('160m', '80m', '40m', '30m', '20m', '17m', '15m', '12m', '10m'), 'since' => null, 'no_sat' => true),
		'cca' => array('name' => 'Commonwealth Century', 'cards' => true, 'licence' => null, 'bands' => array('160m', '80m', '40m', '30m', '20m', '17m', '15m', '12m', '10m'), 'since' => null, 'no_sat' => true),
		'ituz' => array('name' => 'ITU Zones', 'cards' => true, 'licence' => null, 'bands' => array('160m', '80m', '40m', '30m', '20m', '17m', '15m', '12m', '10m'), 'since' => null, 'no_sat' => true),
		'sixm' => array('name' => '50 MHz', 'cards' => true, 'licence' => null, 'bands' => array('6m'), 'since' => self::SIX_METRE_SINCE, 'no_sat' => true),
		'vhf' => array('name' => 'VHF squares and countries', 'cards' => true, 'licence' => null, 'bands' => array('4m', '2m', '70cm'), 'since' => self::VHF_SINCE, 'no_sat' => true),
		'activity' => array('name' => 'V/UHF Activity', 'cards' => false, 'licence' => null, 'bands' => array('6m', '4m', '2m', '70cm', '23cm'), 'since' => self::ACTIVITY_SINCE, 'no_sat' => false),
	);

	public $foundation_specs = array(
		'hf' => array(
			'name' => 'Foundation HF', 'band_lo' => 10, 'band_hi' => 25, 'bands_min' => 4, 'bands_max' => 4,
			'mode_lo' => 15, 'mode_hi' => 50, 'modes_min' => 2, 'direct' => false,
			'modes' => array('CW', 'SSB', 'FM', 'AM', 'FT8/FT4', 'JS8'),
			'levels' => array('Bronze' => 40, 'Silver' => 70, 'Gold' => 100),
			'counts' => 'Four HF bands below 50 MHz, 10 to 25 contacts on each, and at least two of CW, SSB, FM, AM, FT8/FT4, and JS8, 15 to 50 in each',
		),
		'vhf' => array(
			'name' => 'Foundation VHF', 'band_lo' => 10, 'band_hi' => 30, 'bands_min' => 3, 'bands_max' => null,
			'mode_lo' => 15, 'mode_hi' => 30, 'modes_min' => 2, 'direct' => true,
			'modes' => null,
			'levels' => array('Bronze' => 40, 'Silver' => 70, 'Gold' => 100),
			'counts' => 'At least three bands from 50 MHz up, 10 to 30 contacts on each, and at least two modes, 15 to 30 in each. Direct contacts only',
		),
		'all' => array(
			'name' => 'Foundation All-Bands', 'band_lo' => 10, 'band_hi' => 25, 'bands_min' => 4, 'bands_max' => null,
			'mode_lo' => 15, 'mode_hi' => 30, 'modes_min' => 4, 'direct' => true,
			'modes' => null,
			'levels' => array('Bronze' => 50, 'Silver' => 100, 'Gold' => 150),
			'counts' => 'At least four bands, 10 to 25 contacts on each, and at least four modes, 15 to 30 in each. Direct contacts only',
		),
	);

	// Squares and countries, in the order the RSGB lists them.
	public $vhf_ladders = array(
		'4m' => array('label' => '70MHz', 'levels' => array(array(20, 4), array(25, 6), array(30, 8), array(35, 8), array(35, 10), array(40, 8), array(40, 10), array(45, 8), array(45, 10), array(50, 8), array(50, 10), array(55, 8), array(55, 10))),
		'2m' => array('label' => '144MHz', 'levels' => array(array(40, 10), array(60, 15), array(80, 18), array(100, 20), array(125, 20), array(150, 20), array(175, 20), array(200, 30), array(225, 30), array(250, 35), array(275, 35), array(300, 40), array(325, 40), array(350, 45), array(375, 45), array(400, 50), array(425, 50), array(450, 50), array(475, 20))),
		'70cm' => array('label' => '432MHz', 'levels' => array(array(30, 6), array(40, 10), array(50, 13), array(60, 15), array(70, 15), array(80, 15), array(90, 15), array(100, 15), array(110, 15), array(120, 18), array(130, 18), array(140, 20), array(150, 20), array(160, 20), array(170, 23), array(180, 25))),
	);

	private $societies = null;
	private $call_areas = null;

	public function iaru_societies() {
		if ($this->societies === null) {
			$this->societies = require __DIR__ . '/rsgb/iaru_r1_societies.php';
		}
		return $this->societies;
	}

	public function commonwealth_call_areas() {
		if ($this->call_areas === null) {
			$this->call_areas = require __DIR__ . '/rsgb/commonwealth_call_areas.php';
		}
		return $this->call_areas;
	}

	/*
		Callsigns
	*/

	public function call_parts($call) {
		$parts = array();
		foreach (explode('/', strtoupper(trim((string) $call))) as $part) {
			$part = trim($part);
			if ($part === '' || in_array($part, $this->call_suffixes, true)) {
				continue;
			}
			$parts[] = $part;
		}
		return $parts;
	}

	// The operator's own call, without a portable suffix or a CEPT prefix.
	public function base_call($call) {
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

	/*
		UK licence class from a station callsign: 'foundation', 'intermediate', or null for Full, club, and other calls.
		Foundation is M3, M6, M7. Intermediate is 2E0, 2E1, M8, M9. Each takes the regional letter
		(D, I, J, M, U, W), so MW7ABC, 2M0ABC, and MM9ABC.
	*/
	public function licence_class($call) {
		foreach ($this->call_parts($call) as $part) {
			if (preg_match('/^M[DIJMUW]?[367][A-Z]{3}$/', $part)) {
				return 'foundation';
			}
			if (preg_match('/^2[DEIJMUW][01][A-Z]{3}$/', $part) || preg_match('/^M[DIJMUW]?[89][A-Z]{3}$/', $part)) {
				return 'intermediate';
			}
		}
		return null;
	}

	/*
		Prefix letters and numeral of the place the station is in.
		VE3ABC/7 is in VE7, G4ABC/VE3 is in VE3, and G4ABC/ZL has letters only.
	*/
	public function location_prefix($call) {
		$parts = $this->call_parts($call);
		$digit = null;
		$rest = array();
		foreach ($parts as $part) {
			if (preg_match('/^\d$/', $part)) {
				$digit = $part;
			} else {
				$rest[] = $part;
			}
		}
		if (!$rest) {
			return null;
		}
		$where = $rest[0];
		if (count($rest) > 1) {
			usort($rest, function ($a, $b) { return strlen($a) - strlen($b); });
			$where = $rest[0];
		}
		if (!preg_match('/^([A-Z]{1,2})(\d)?/', $where, $m)) {
			return null;
		}
		$numeral = $digit !== null ? $digit : (isset($m[2]) ? $m[2] : null);
		return array('letters' => $m[1], 'digit' => $numeral);
	}

	/*
		Modes
	*/

	// FT8 and FT4 are one mode. So are the PSK variants, and MFSK variants that differ by tone or shift.
	public function mode_group($mode, $submode = '') {
		$mode = strtoupper(trim((string) $mode));
		$sub = strtoupper(trim((string) $submode));
		$m = $sub !== '' ? $sub : $mode;
		if ($mode === 'SSB' || in_array($m, array('USB', 'LSB', 'SSB'), true)) {
			return 'SSB';
		}
		if ($m === 'FT8' || $m === 'FT4') {
			return 'FT8/FT4';
		}
		if ($m === 'JS8') {
			return 'JS8';
		}
		if (in_array($mode, array('CW', 'FM', 'AM', 'RTTY'), true)) {
			return $mode;
		}
		if ($mode === 'PSK' || strpos($m, 'PSK') !== false) {
			return 'PSK';
		}
		return $mode;
	}

	public function is_data_mode($group) {
		return $group !== '' && !in_array($group, array('CW', 'SSB', 'FM', 'AM', 'DIGITALVOICE', 'DSTAR'), true);
	}

	public function is_direct($qso) {
		return !in_array(strtoupper(trim((string) $qso['prop'])), $this->non_direct, true);
	}

	/*
		Scoring
	*/

	public function evaluate($group, $qsos) {
		switch ($group) {
			case 'foundation': return $this->foundation($qsos);
			case 'intermediate': return $this->intermediate($qsos);
			case 'iaru': return $this->iaru($qsos);
			case 'cca': return $this->commonwealth($qsos);
			case 'ituz': return $this->itu_zones($qsos);
			case 'sixm': return $this->six_metres($qsos);
			case 'vhf': return $this->vhf($qsos);
			case 'activity': return $this->activity($qsos);
		}
		return null;
	}

	/*
		$ladder is a list of levels, lowest first. Each level is ['label' => ..., 'reqs' => [[name, have, need], ...]].
		The level reached is the highest one whose requirements are all met.
	*/
	public function variant($key, $name, $counts, $worked, $confirmed, $ladder, $extra = array()) {
		$reached = -1;
		foreach ($ladder as $i => $level) {
			$ok = true;
			foreach ($level['reqs'] as $req) {
				if ($req[1] < $req[2]) {
					$ok = false;
				}
			}
			if ($ok) {
				$reached = $i;
			}
		}
		$next = isset($ladder[$reached + 1]) ? $ladder[$reached + 1] : null;
		$pct = 100;
		$missing = array();
		$target = null;
		if ($next) {
			$sum = 0;
			foreach ($next['reqs'] as $req) {
				$sum += $req[2] > 0 ? min(1, $req[1] / $req[2]) : 1;
				if ($req[1] < $req[2]) {
					$missing[] = $req[2] . ' ' . $req[0] . ' (have ' . $req[1] . ')';
				}
			}
			$pct = (int) floor($sum / count($next['reqs']) * 100);
			$target = $next['reqs'][0][2];
		}
		return array_merge(array(
			'key' => $key,
			'name' => $name,
			'counts' => $counts,
			'worked' => $worked,
			'confirmed' => $confirmed,
			'level' => $reached >= 0 ? $ladder[$reached]['label'] : null,
			'next' => $next ? $next['label'] : null,
			'target' => $target,
			'missing' => $missing,
			'pct' => $pct,
			'locked' => false,
			'gate' => '',
		), $extra);
	}

	private function result($variants, $tables = array(), $notes = array(), $eligible = array()) {
		return array('variants' => $variants, 'tables' => $tables, 'notes' => $notes, 'eligible' => array_values(array_unique($eligible)));
	}

	private function band_label($band) {
		return rtrim($band, '*');
	}

	private function bands_text($statuses, $want) {
		$out = array();
		foreach ($statuses as $band => $status) {
			if ($status === $want) {
				$out[$this->band_label($band)] = true;
			}
		}
		return implode(' ', array_keys($out));
	}

	/*
		Foundation HF, VHF, and All-Bands. Only QSOs made from a Foundation callsign count.
	*/
	private function foundation($qsos) {
		$variants = array();
		$rows = array();
		$eligible = array();
		foreach ($this->foundation_specs as $key => $spec) {
			$counts = array();
			foreach ($qsos as $i => $q) {
				if ($this->licence_class($q['station_call']) !== 'foundation') {
					continue;
				}
				$band = $q['band'];
				if ($band === '' || $band === null) {
					continue;
				}
				$hf = in_array($band, $this->below_50mhz, true);
				if ($key === 'hf' && !$hf) {
					continue;
				}
				if ($key === 'vhf' && $hf) {
					continue;
				}
				if ($spec['direct'] && !$this->is_direct($q)) {
					continue;
				}
				$mode = $this->mode_group($q['mode'], $q['submode']);
				if ($mode === '' || ($spec['modes'] !== null && !in_array($mode, $spec['modes'], true))) {
					continue;
				}
				$counts[$band][$mode] = (isset($counts[$band][$mode]) ? $counts[$band][$mode] : 0) + 1;
				$eligible[] = $i;
			}

			$best = $this->allocate($counts, $spec);
			$logged = 0;
			foreach ($counts as $row) {
				$logged += array_sum($row);
			}

			$ladder = array();
			foreach ($spec['levels'] as $label => $need) {
				$ladder[] = array('label' => $label, 'reqs' => array(array('contacts that count', $best['total'], $need)));
			}
			$variants[] = $this->variant('foundation_' . $key, $spec['name'], $spec['counts'], $logged, $best['total'], $ladder);

			$band_totals = array();
			$mode_totals = array();
			foreach ($counts as $band => $row) {
				foreach ($row as $mode => $n) {
					$band_totals[$band] = (isset($band_totals[$band]) ? $band_totals[$band] : 0) + $n;
					$mode_totals[$mode] = (isset($mode_totals[$mode]) ? $mode_totals[$mode] : 0) + $n;
				}
			}
			foreach ($band_totals as $band => $n) {
				$rows[] = array($spec['name'], 'Band', $band, $n, isset($best['bands'][$band]) ? $best['bands'][$band] : 0);
			}
			foreach ($mode_totals as $mode => $n) {
				$rows[] = array($spec['name'], 'Mode', $mode, $n, isset($best['modes'][$mode]) ? $best['modes'][$mode] : 0);
			}
		}
		$tables = array(array(
			'title' => 'Bands and modes',
			'columns' => array('Award', 'Kind', 'Band or mode', 'Logged', 'Counted'),
			'rows' => $rows,
		));
		$notes = array('Counted is the best set that keeps every band and every mode inside its limits. FT8 and FT4 count as one mode.');
		return $this->result($variants, $tables, $notes, $eligible);
	}

	/*
		Most QSOs that fit the band and mode limits. $counts[band][mode] = QSOs.
		Returns total, and the counted QSOs per band and per mode.
	*/
	public function allocate($counts, $spec) {
		$best = array('total' => 0, 'bands' => array(), 'modes' => array());
		$band_tot = array();
		$mode_tot = array();
		foreach ($counts as $band => $row) {
			foreach ($row as $mode => $n) {
				$band_tot[$band] = (isset($band_tot[$band]) ? $band_tot[$band] : 0) + $n;
				$mode_tot[$mode] = (isset($mode_tot[$mode]) ? $mode_tot[$mode] : 0) + $n;
			}
		}
		arsort($band_tot);
		arsort($mode_tot);
		$bands = array_slice(array_keys(array_filter($band_tot, function ($n) use ($spec) { return $n >= $spec['band_lo']; })), 0, 10);
		$modes = array_slice(array_keys(array_filter($mode_tot, function ($n) use ($spec) { return $n >= $spec['mode_lo']; })), 0, 8);

		$candidates = array();
		foreach ($this->subsets($bands, $spec['bands_min'], $spec['bands_max']) as $band_set) {
			foreach ($this->subsets($modes, $spec['modes_min'], null) as $mode_set) {
				$by_band = 0;
				foreach ($band_set as $band) {
					$sum = 0;
					foreach ($mode_set as $mode) {
						$sum += isset($counts[$band][$mode]) ? $counts[$band][$mode] : 0;
					}
					$by_band += min($spec['band_hi'], $sum);
				}
				$by_mode = 0;
				foreach ($mode_set as $mode) {
					$sum = 0;
					foreach ($band_set as $band) {
						$sum += isset($counts[$band][$mode]) ? $counts[$band][$mode] : 0;
					}
					$by_mode += min($spec['mode_hi'], $sum);
				}
				$bound = min($by_band, $by_mode);
				if ($bound >= count($band_set) * $spec['band_lo'] && $bound >= count($mode_set) * $spec['mode_lo']) {
					$candidates[] = array($bound, $band_set, $mode_set);
				}
			}
		}
		usort($candidates, function ($a, $b) { return $b[0] - $a[0]; });

		foreach ($candidates as $candidate) {
			if ($candidate[0] <= $best['total']) {
				break;
			}
			$found = $this->bounded_flow($counts, $candidate[1], $candidate[2], $spec);
			if ($found !== null && $found['total'] > $best['total']) {
				$best = $found;
			}
		}
		return $best;
	}

	private function subsets($items, $min, $max) {
		$out = array();
		$n = count($items);
		for ($mask = 1; $mask < (1 << $n); $mask++) {
			$set = array();
			for ($i = 0; $i < $n; $i++) {
				if ($mask & (1 << $i)) {
					$set[] = $items[$i];
				}
			}
			$size = count($set);
			if ($size >= $min && ($max === null || $size <= $max)) {
				$out[] = $set;
			}
		}
		return $out;
	}

	/*
		Max flow with lower bounds: source to each band [band_lo, band_hi], band to mode [0, QSOs],
		mode to sink [mode_lo, mode_hi]. Null when the bounds cannot all be met.
	*/
	private function bounded_flow($counts, $bands, $modes, $spec) {
		$source = 0;
		$sink = 1;
		$super_source = 2;
		$super_sink = 3;
		$node = 4;
		$band_node = array();
		foreach ($bands as $band) {
			$band_node[$band] = $node++;
		}
		$mode_node = array();
		foreach ($modes as $mode) {
			$mode_node[$mode] = $node++;
		}
		$g = $this->graph($node);
		$excess = array_fill(0, $node, 0);

		$band_edge = array();
		foreach ($band_node as $band => $v) {
			$band_edge[$band] = $this->edge($g, $source, $v, $spec['band_hi'] - $spec['band_lo']);
			$excess[$v] += $spec['band_lo'];
			$excess[$source] -= $spec['band_lo'];
		}
		foreach ($band_node as $band => $u) {
			foreach ($mode_node as $mode => $v) {
				if (!empty($counts[$band][$mode])) {
					$this->edge($g, $u, $v, $counts[$band][$mode]);
				}
			}
		}
		$mode_edge = array();
		foreach ($mode_node as $mode => $u) {
			$mode_edge[$mode] = $this->edge($g, $u, $sink, $spec['mode_hi'] - $spec['mode_lo']);
			$excess[$sink] += $spec['mode_lo'];
			$excess[$u] -= $spec['mode_lo'];
		}
		$loop = $this->edge($g, $sink, $source, PHP_INT_MAX >> 2);

		$need = 0;
		$helpers = array();
		foreach ($excess as $v => $x) {
			if ($x > 0) {
				$helpers[] = $this->edge($g, $super_source, $v, $x);
				$need += $x;
			} elseif ($x < 0) {
				$helpers[] = $this->edge($g, $v, $super_sink, -$x);
			}
		}
		if ($this->max_flow($g, $super_source, $super_sink) < $need) {
			return null;
		}

		$base = $g['cap'][$loop ^ 1];
		$g['cap'][$loop] = 0;
		$g['cap'][$loop ^ 1] = 0;
		foreach ($helpers as $e) {
			$g['cap'][$e] = 0;
			$g['cap'][$e ^ 1] = 0;
		}
		$total = $base + $this->max_flow($g, $source, $sink);

		$per_band = array();
		foreach ($band_edge as $band => $e) {
			$per_band[$band] = $spec['band_lo'] + $g['cap'][$e ^ 1];
		}
		$per_mode = array();
		foreach ($mode_edge as $mode => $e) {
			$per_mode[$mode] = $spec['mode_lo'] + $g['cap'][$e ^ 1];
		}
		return array('total' => $total, 'bands' => $per_band, 'modes' => $per_mode);
	}

	private function graph($nodes) {
		return array('to' => array(), 'cap' => array(), 'adj' => array_fill(0, $nodes, array()));
	}

	// Adds u to v and its reverse. The reverse of edge e is e ^ 1.
	private function edge(&$g, $u, $v, $cap) {
		$e = count($g['to']);
		$g['to'][] = $v;
		$g['cap'][] = $cap;
		$g['adj'][$u][] = $e;
		$g['to'][] = $u;
		$g['cap'][] = 0;
		$g['adj'][$v][] = $e + 1;
		return $e;
	}

	private function max_flow(&$g, $s, $t) {
		$total = 0;
		while (true) {
			$prev = array($s => -1);
			$queue = array($s);
			$head = 0;
			while ($head < count($queue) && !isset($prev[$t])) {
				$u = $queue[$head++];
				foreach ($g['adj'][$u] as $e) {
					$v = $g['to'][$e];
					if ($g['cap'][$e] > 0 && !isset($prev[$v])) {
						$prev[$v] = $e;
						$queue[] = $v;
					}
				}
			}
			if (!isset($prev[$t])) {
				return $total;
			}
			$push = PHP_INT_MAX;
			for ($v = $t; $v !== $s; $v = $g['to'][$prev[$v] ^ 1]) {
				$push = min($push, $g['cap'][$prev[$v]]);
			}
			for ($v = $t; $v !== $s; $v = $g['to'][$prev[$v] ^ 1]) {
				$g['cap'][$prev[$v]] -= $push;
				$g['cap'][$prev[$v] ^ 1] += $push;
			}
			$total += $push;
		}
	}

	/*
		Intermediate 100: 25 different amateurs on each of 80, 40, 30, and 17 m, in CW, SSB, or data.
		An amateur counts once, on whichever band leaves the most room.
	*/
	private function intermediate($qsos) {
		$bands = $this->groups['intermediate']['bands'];
		$worked = array();
		$logged = array_fill_keys($bands, array());
		$eligible = array();
		foreach ($qsos as $i => $q) {
			if ($this->licence_class($q['station_call']) !== 'intermediate' || !in_array($q['band'], $bands, true)) {
				continue;
			}
			$mode = $this->mode_group($q['mode'], $q['submode']);
			if (!in_array($mode, array('CW', 'SSB'), true) && !$this->is_data_mode($mode)) {
				continue;
			}
			$call = $this->base_call($q['call']);
			if ($call === '') {
				continue;
			}
			$worked[$call][$q['band']] = true;
			$logged[$q['band']][$call] = true;
			$eligible[] = $i;
		}

		$g = $this->graph(2 + count($bands) + count($worked));
		$band_node = array();
		$band_edge = array();
		$node = 2;
		foreach ($bands as $band) {
			$band_node[$band] = $node++;
			$band_edge[$band] = $this->edge($g, $band_node[$band], 1, 25);
		}
		foreach ($worked as $call => $on) {
			$v = $node++;
			$this->edge($g, 0, $v, 1);
			foreach ($on as $band => $yes) {
				$this->edge($g, $v, $band_node[$band], 1);
			}
		}
		$total = $this->max_flow($g, 0, 1);

		$reqs = array();
		$rows = array();
		foreach ($bands as $band) {
			$counted = $g['cap'][$band_edge[$band] ^ 1];
			$reqs[] = array('on ' . $band, $counted, 25);
			$rows[] = array($band, count($logged[$band]), $counted);
		}
		$ladder = array(array('label' => 'Intermediate 100', 'reqs' => array_merge(array(array('amateurs', $total, 100)), $reqs)));
		$variants = array($this->variant('intermediate', 'Intermediate 100', '25 different amateurs on each of 80, 40, 30, and 17 m, in CW, SSB, or data', count($worked), $total, $ladder));

		$tables = array(array(
			'title' => 'Amateurs by band',
			'columns' => array('Band', 'Different amateurs worked', 'Counted'),
			'rows' => $rows,
		));
		$notes = array('Each amateur counts once across the award. Portable suffixes are ignored, so G4ABC and G4ABC/P are one amateur.');
		return $this->result($variants, $tables, $notes, $eligible);
	}

	/*
		IARU Region 1: member societies on HF, all bands, one WARC band, 10 m, or the WARC bands together.
	*/
	private function iaru($qsos) {
		$societies = $this->iaru_societies();
		$by_dxcc = array();
		foreach ($societies as $key => $society) {
			foreach ($society['dxcc'] as $dxcc) {
				$by_dxcc[$dxcc] = $key;
			}
		}

		$seen = array();
		$eligible = array();
		foreach ($qsos as $i => $q) {
			$dxcc = (int) $q['dxcc'];
			if (!isset($by_dxcc[$dxcc]) || !in_array($q['band'], $this->hf_bands, true)) {
				continue;
			}
			$band = $q['band'];
			if ($band === '10m' && substr((string) $q['time'], 0, 10) < self::TEN_METRE_SINCE) {
				$band = '10m*';
			}
			$key = $by_dxcc[$dxcc];
			if (!empty($q['confirmed'])) {
				$seen[$key][$band] = 'C';
			} elseif (!isset($seen[$key][$band])) {
				$seen[$key][$band] = 'W';
			}
			$eligible[] = $i;
		}

		$total = count($societies);
		$sets = array(
			'iaru_all' => array('IARU Region 1', 'Member societies on 160, 80, 40, 30, 20, 17, 15, 12, and 10 m', array_merge($this->hf_bands, array('10m*'))),
			'iaru_warc' => array('IARU Region 1, WARC bands', 'Member societies on 30, 17, and 12 m together', array('30m', '17m', '12m')),
			'iaru_30m' => array('IARU Region 1, 30 m', 'Member societies on 30 m', array('30m')),
			'iaru_17m' => array('IARU Region 1, 17 m', 'Member societies on 17 m', array('17m')),
			'iaru_12m' => array('IARU Region 1, 12 m', 'Member societies on 12 m', array('12m')),
			'iaru_10m' => array('IARU Region 1, 10 m', 'Member societies on 10 m since 1 July 1983', array('10m')),
		);
		$variants = array();
		foreach ($sets as $key => $set) {
			$worked = 0;
			$confirmed = 0;
			foreach ($seen as $bands) {
				$any = false;
				$card = false;
				foreach ($set[2] as $band) {
					if (isset($bands[$band])) {
						$any = true;
						$card = $card || $bands[$band] === 'C';
					}
				}
				$worked += $any ? 1 : 0;
				$confirmed += $card ? 1 : 0;
			}
			$ladder = array(
				array('label' => 'Class 3', 'reqs' => array(array('societies', $confirmed, 40))),
				array('label' => 'Class 2', 'reqs' => array(array('societies', $confirmed, 60))),
				array('label' => 'Class 1', 'reqs' => array(array('societies', $confirmed, $total))),
			);
			$variants[] = $this->variant($key, $set[0], $set[1], $worked, $confirmed, $ladder);
		}

		$rows = array();
		foreach ($societies as $key => $society) {
			$bands = isset($seen[$key]) ? $seen[$key] : array();
			$rows[] = array($society['name'], $this->bands_text($bands, 'C'), $this->bands_text($bands, 'W'));
		}
		$tables = array(array(
			'title' => 'Member societies (' . $total . ')',
			'columns' => array('Society', 'Confirmed on', 'Worked, not confirmed, on'),
			'rows' => $rows,
		));
		$notes = array('The list is the IARU Region 1 member societies. Island entities count with their country, such as Sardinia with Italy and the Canaries with Spain. Class 1 is every society on the list.');
		return $this->result($variants, $tables, $notes, $eligible);
	}

	/*
		Commonwealth call area for a QSO, or null. Unmatched is true when the entity is in the
		Commonwealth but the callsign does not say which area.
	*/
	public function call_area($q) {
		$data = $this->commonwealth_call_areas();
		$dxcc = (int) $q['dxcc'];
		if (isset($data['entities'][$dxcc])) {
			$entity = $data['entities'][$dxcc];
			if (isset($entity['call']) && strpos($this->base_call($q['call']), $entity['call']) !== 0) {
				return array('unmatched' => true);
			}
			return array('area' => $entity['area'], 'name' => $entity['name']);
		}
		if (!isset($data['split'][$dxcc])) {
			return null;
		}
		$where = $this->location_prefix($q['call']);
		$area = null;
		if ($where !== null) {
			$letters = $where['letters'];
			$digit = $where['digit'];
			if ($dxcc === 1 && $digit !== null) {
				if ($letters === 'VO') {
					$area = 'VO' . $digit;
				} elseif ($letters === 'VY') {
					$area = 'VY' . $digit;
				} else {
					$area = 'VE' . $digit;
				}
			} elseif ($dxcc === 150 && $digit !== null) {
				$area = 'VK' . $digit;
			} elseif ($dxcc === 170) {
				$area = $digit === null ? 'ZL0' : 'ZL' . $digit;
			} elseif ($dxcc === 462 && $digit !== null) {
				$area = 'ZS' . $digit;
			}
		}
		if ($area === null || !isset($data['split'][$dxcc][$area])) {
			return array('unmatched' => true);
		}
		return array('area' => $area, 'name' => $data['split'][$dxcc][$area]);
	}

	private function commonwealth($qsos) {
		$data = $this->commonwealth_call_areas();
		$areas = array();
		foreach ($data['entities'] as $entity) {
			$areas[$entity['area']] = $entity['name'];
		}
		foreach ($data['split'] as $split) {
			foreach ($split as $area => $name) {
				$areas[$area] = $name;
			}
		}
		ksort($areas);

		$seen = array();
		$unmatched = array();
		$eligible = array();
		foreach ($qsos as $i => $q) {
			if (!in_array($q['band'], $this->hf_bands, true)) {
				continue;
			}
			$found = $this->call_area($q);
			if ($found === null) {
				continue;
			}
			if (!empty($found['unmatched'])) {
				$unmatched[strtoupper($q['call'])] = true;
				continue;
			}
			$area = $found['area'];
			if (!empty($q['confirmed'])) {
				$seen[$area][$q['band']] = 'C';
			} elseif (!isset($seen[$area][$q['band']])) {
				$seen[$area][$q['band']] = 'W';
			}
			$eligible[] = $i;
		}

		$sets = array(
			'cca_5' => array('Commonwealth Century, 5 bands', 'Commonwealth call areas on 80, 40, 20, 15, and 10 m', array('80m', '40m', '20m', '15m', '10m')),
			'cca_9' => array('Commonwealth Century, 9 bands', 'Commonwealth call areas on 160, 80, 40, 30, 20, 17, 15, 12, and 10 m', $this->hf_bands),
		);
		$variants = array();
		foreach ($sets as $key => $set) {
			$worked = 0;
			$confirmed = 0;
			foreach ($seen as $bands) {
				$any = false;
				$card = false;
				foreach ($set[2] as $band) {
					if (isset($bands[$band])) {
						$any = true;
						$card = $card || $bands[$band] === 'C';
					}
				}
				$worked += $any ? 1 : 0;
				$confirmed += $card ? 1 : 0;
			}
			$ladder = array(
				array('label' => 'Bronze', 'reqs' => array(array('call areas', $confirmed, 40))),
				array('label' => 'Silver', 'reqs' => array(array('call areas', $confirmed, 70))),
				array('label' => 'Century', 'reqs' => array(array('call areas', $confirmed, 100))),
			);
			$variants[] = $this->variant($key, $set[0], $set[1], $worked, $confirmed, $ladder);
		}

		$rows = array();
		foreach ($areas as $area => $name) {
			$bands = isset($seen[$area]) ? $seen[$area] : array();
			$rows[] = array($area, $name, $this->bands_text($bands, 'C'), $this->bands_text($bands, 'W'));
		}
		$tables = array(array(
			'title' => 'Commonwealth call areas (' . count($areas) . ')',
			'columns' => array('Area', 'Name', 'Confirmed on', 'Worked, not confirmed, on'),
			'rows' => $rows,
		));
		$notes = array('Canada, mainland Australia, New Zealand, and South Africa are split by the numeral in the callsign, so VE7ABC is British Columbia. Antarctica, South Orkney, South Shetland, and Spratly count only for VP8 and 9M0 calls.');
		if ($unmatched) {
			$calls = array_keys($unmatched);
			sort($calls);
			$notes[] = count($calls) . ' Commonwealth contact' . (count($calls) == 1 ? '' : 's') . ' could not be placed in a call area and do not count: ' . implode(', ', array_slice($calls, 0, 40)) . (count($calls) > 40 ? ', ...' : '') . '.';
		}
		return $this->result($variants, $tables, $notes, $eligible);
	}

	/*
		Worked ITU Zones: Classic, 5 Band, WARC, and Top Band. WARC and Top Band unlock with Classic or 5 Band.
	*/
	private function itu_zones($qsos) {
		$seen = array();
		$eligible = array();
		$no_zone = 0;
		foreach ($qsos as $i => $q) {
			if (!in_array($q['band'], $this->hf_bands, true)) {
				continue;
			}
			$zone = (int) $q['ituz'];
			if ($zone < 1 || $zone > 90) {
				$no_zone++;
				continue;
			}
			if (!empty($q['confirmed'])) {
				$seen[$zone][$q['band']] = 'C';
			} elseif (!isset($seen[$zone][$q['band']])) {
				$seen[$zone][$q['band']] = 'W';
			}
			$eligible[] = $i;
		}

		$per_band = function ($bands) use ($seen) {
			$out = array('worked' => array_fill_keys($bands, 0), 'confirmed' => array_fill_keys($bands, 0), 'union_w' => 0, 'union_c' => 0);
			foreach ($seen as $zone => $on) {
				$any = false;
				$card = false;
				foreach ($bands as $band) {
					if (isset($on[$band])) {
						$out['worked'][$band]++;
						$any = true;
						if ($on[$band] === 'C') {
							$out['confirmed'][$band]++;
							$card = true;
						}
					}
				}
				$out['union_w'] += $any ? 1 : 0;
				$out['union_c'] += $card ? 1 : 0;
			}
			return $out;
		};

		$five = array('80m', '40m', '20m', '15m', '10m');
		$classic = $per_band($five);
		$variants = array();
		$variants[] = $this->variant('ituz_classic', 'Classic Worked ITU Zones', 'ITU zones on 80, 40, 20, 15, and 10 m', $classic['union_w'], $classic['union_c'], array(
			array('label' => 'Classic', 'reqs' => array(array('zones', $classic['union_c'], 70))),
		));

		$sum_c = array_sum($classic['confirmed']);
		$min_c = min($classic['confirmed']);
		$five_band = $this->variant('ituz_5band', '5 Band Worked ITU Zones', 'ITU zones on each of 80, 40, 20, 15, and 10 m, added up', array_sum($classic['worked']), $sum_c, array(
			array('label' => 'Class 2', 'reqs' => array(array('band-zones', $sum_c, 200), array('on each band', $min_c, 30))),
			array('label' => 'Class 1', 'reqs' => array(array('band-zones', $sum_c, 300), array('on each band', $min_c, 50))),
			array('label' => 'Supreme', 'reqs' => array(array('band-zones', $sum_c, 350))),
		));
		$variants[] = $five_band;

		$unlocked = $variants[0]['level'] !== null || $five_band['level'] !== null;
		$gate = array('locked' => !$unlocked, 'gate' => 'Unlocks with Classic or 5 Band Worked ITU Zones');

		$warc = $per_band(array('30m', '17m', '12m'));
		$sum_c = array_sum($warc['confirmed']);
		$min_c = min($warc['confirmed']);
		$variants[] = $this->variant('ituz_warc', 'WARC Band Worked ITU Zones', 'ITU zones on each of 30, 17, and 12 m, added up', array_sum($warc['worked']), $sum_c, array(
			array('label' => 'Class 2', 'reqs' => array(array('band-zones', $sum_c, 120), array('on each band', $min_c, 30))),
			array('label' => 'Class 1', 'reqs' => array(array('band-zones', $sum_c, 195), array('on each band', $min_c, 30))),
			array('label' => 'Supreme', 'reqs' => array(array('band-zones', $sum_c, 210))),
		), $gate);

		$top = $per_band(array('160m'));
		$variants[] = $this->variant('ituz_160', 'Top Band Worked ITU Zones', 'ITU zones on 160 m', $top['union_w'], $top['union_c'], array(
			array('label' => 'Class 2', 'reqs' => array(array('zones', $top['union_c'], 20))),
			array('label' => 'Class 1', 'reqs' => array(array('zones', $top['union_c'], 40))),
			array('label' => 'Supreme', 'reqs' => array(array('zones', $top['union_c'], 60))),
		), $gate);

		$rows = array();
		for ($zone = 1; $zone <= 90; $zone++) {
			$row = array((string) $zone);
			foreach ($this->hf_bands as $band) {
				$row[] = isset($seen[$zone][$band]) ? $seen[$zone][$band] : '';
			}
			$rows[] = $row;
		}
		$tables = array(array(
			'title' => 'ITU zones by band',
			'columns' => array_merge(array('Zone'), $this->hf_bands),
			'rows' => $rows,
			'status' => true,
		));
		$notes = array('Cloudlog reads the ITU zone stored on each contact. C is confirmed, W is worked.');
		if ($no_zone) {
			$notes[] = $no_zone . ' HF contact' . ($no_zone == 1 ? ' has' : 's have') . ' no ITU zone and do not count. A LoTW confirmation fills the zone in, or set it on the QSO.';
		}
		return $this->result($variants, $tables, $notes, $eligible);
	}

	private function squares($q) {
		$out = array();
		foreach (array_merge(array((string) $q['grid']), explode(',', (string) $q['vucc'])) as $grid) {
			$grid = strtoupper(trim($grid));
			if (preg_match('/^[A-R]{2}[0-9]{2}/', $grid)) {
				$out[substr($grid, 0, 4)] = true;
			}
		}
		return array_keys($out);
	}

	/*
		50 MHz Continents and Countries, Countries, and Squares, from 1 June 1987.
	*/
	private function six_metres($qsos) {
		$sets = array('W' => array('cont' => array(), 'dxcc' => array(), 'squares' => array()), 'C' => array('cont' => array(), 'dxcc' => array(), 'squares' => array()));
		$by_cont = array();
		$eligible = array();
		foreach ($qsos as $i => $q) {
			if ($q['band'] !== '6m' || substr((string) $q['time'], 0, 10) < self::SIX_METRE_SINCE) {
				continue;
			}
			$eligible[] = $i;
			$kinds = !empty($q['confirmed']) ? array('W', 'C') : array('W');
			$cont = strtoupper((string) $q['cont']);
			$dxcc = (int) $q['dxcc'];
			foreach ($kinds as $kind) {
				if (isset($this->continents[$cont])) {
					$sets[$kind]['cont'][$cont] = true;
				}
				if ($dxcc > 0) {
					$sets[$kind]['dxcc'][$dxcc] = true;
					if (isset($this->continents[$cont])) {
						$by_cont[$cont][$kind][$dxcc] = true;
					}
				}
				foreach ($this->squares($q) as $square) {
					$sets[$kind]['squares'][$square] = true;
				}
			}
		}
		$cont_c = count($sets['C']['cont']);
		$dxcc_c = count($sets['C']['dxcc']);
		$squares_c = count($sets['C']['squares']);

		$ladder = array();
		foreach (array(array(1, 30), array(1, 40), array(2, 50), array(3, 60), array(4, 70), array(5, 80), array(5, 100)) as $step) {
			$ladder[] = array('label' => $step[0] . '/' . $step[1], 'reqs' => array(array('countries', $dxcc_c, $step[1]), array('continents', $cont_c, $step[0])));
		}
		$variants = array();
		$variants[] = $this->variant('six_cc', '50MHz Continents and Countries', 'DXCC countries and continents on 6 m', count($sets['W']['dxcc']), $dxcc_c, $ladder);

		$ladder = array();
		for ($n = 10; $n <= 340; $n += 10) {
			$ladder[] = array('label' => $n . ' countries', 'reqs' => array(array('countries', $dxcc_c, $n)));
		}
		$variants[] = $this->variant('six_countries', '50MHz Countries', 'DXCC countries on 6 m', count($sets['W']['dxcc']), $dxcc_c, $ladder);

		$variants[] = $this->variant('six_squares', '50MHz Squares', 'Locator squares on 6 m. The RSGB issues further certificates above 25', count($sets['W']['squares']), $squares_c, array(
			array('label' => '25 squares', 'reqs' => array(array('squares', $squares_c, 25))),
		));

		$rows = array();
		foreach ($this->continents as $code => $name) {
			$rows[] = array($name, isset($by_cont[$code]['C']) ? count($by_cont[$code]['C']) : 0, isset($by_cont[$code]['W']) ? count($by_cont[$code]['W']) : 0);
		}
		$tables = array(array(
			'title' => 'Countries by continent on 6 m',
			'columns' => array('Continent', 'Countries confirmed', 'Countries worked'),
			'rows' => $rows,
		));
		$notes = array('Contacts from 1 June 1987. Countries are DXCC entities. Cloudlog does not check whether a country had authorised 50 MHz at the time.');
		return $this->result($variants, $tables, $notes, $eligible);
	}

	/*
		VHF squares and countries on 4 m, 2 m, and 70 cm, from 31 December 1978. A level needs both numbers.
	*/
	private function vhf($qsos) {
		$sets = array();
		$eligible = array();
		foreach ($qsos as $i => $q) {
			if (!isset($this->vhf_ladders[$q['band']]) || substr((string) $q['time'], 0, 10) < self::VHF_SINCE) {
				continue;
			}
			$eligible[] = $i;
			$kinds = !empty($q['confirmed']) ? array('W', 'C') : array('W');
			foreach ($kinds as $kind) {
				foreach ($this->squares($q) as $square) {
					$sets[$q['band']][$kind]['squares'][$square] = true;
				}
				if ((int) $q['dxcc'] > 0) {
					$sets[$q['band']][$kind]['dxcc'][(int) $q['dxcc']] = true;
				}
			}
		}
		$count = function ($band, $kind, $what) use ($sets) {
			return isset($sets[$band][$kind][$what]) ? count($sets[$band][$kind][$what]) : 0;
		};

		$variants = array();
		$rows = array();
		foreach ($this->vhf_ladders as $band => $ladder_data) {
			$squares = $count($band, 'C', 'squares');
			$countries = $count($band, 'C', 'dxcc');
			$ladder = array();
			foreach ($ladder_data['levels'] as $step) {
				$ladder[] = array('label' => $ladder_data['label'] . ' ' . $step[0] . '/' . $step[1], 'reqs' => array(array('squares', $squares, $step[0]), array('countries', $countries, $step[1])));
			}
			$variants[] = $this->variant('vhf_' . $band, $ladder_data['label'] . ' (' . $band . ') squares and countries', 'Locator squares and countries on ' . $band, $count($band, 'W', 'squares'), $squares, $ladder);
			$rows[] = array($band, $squares, $count($band, 'W', 'squares'), $countries, $count($band, 'W', 'dxcc'));
		}
		$tables = array(array(
			'title' => 'Squares and countries by band',
			'columns' => array('Band', 'Squares confirmed', 'Squares worked', 'Countries confirmed', 'Countries worked'),
			'rows' => $rows,
		));
		$notes = array('Contacts from 31 December 1978. A level counts only when both the squares and the countries are met. Countries are DXCC entities.');
		return $this->result($variants, $tables, $notes, $eligible);
	}

	/*
		V/UHF Activity Award, from 1 June 2021. Simplex only, a different station each band.
	*/
	private function activity($qsos) {
		$bands = $this->groups['activity']['bands'];
		$stations = array();
		$fm_ssb = array();
		$modes = array();
		$no_freq = 0;
		$not_direct = 0;
		$eligible = array();
		foreach ($qsos as $i => $q) {
			if (!in_array($q['band'], $bands, true) || substr((string) $q['time'], 0, 10) < self::ACTIVITY_SINCE) {
				continue;
			}
			$mode = $this->mode_group($q['mode'], $q['submode']);
			if (!in_array($mode, array('FM', 'SSB', 'CW'), true)) {
				continue;
			}
			if (!$this->is_direct($q)) {
				$not_direct++;
				continue;
			}
			if (empty($q['freq']) || (float) $q['freq'] <= 0) {
				$no_freq++;
				continue;
			}
			$call = $this->base_call($q['call']);
			if ($call === '') {
				continue;
			}
			$stations[$q['band']][$call] = true;
			$modes[$q['band']][$mode] = true;
			if ($mode !== 'CW') {
				$fm_ssb[$q['band']][$call] = true;
			}
			$eligible[] = $i;
		}
		$n = function ($set, $band) {
			return isset($set[$band]) ? count($set[$band]) : 0;
		};
		$mode_count = function ($band) use ($modes) {
			return isset($modes[$band]) ? count($modes[$band]) : 0;
		};

		$full_bands = 0;
		foreach (array('6m', '4m', '2m', '70cm') as $band) {
			if ($n($stations, $band) >= 100 && $mode_count($band) >= 2) {
				$full_bands++;
			}
		}
		$six_or_four = max($n($stations, '6m'), $n($stations, '4m'));
		$lowest_four = min($n($stations, '6m'), $n($stations, '4m'), $n($stations, '2m'), $n($stations, '70cm'));
		$fewest_modes = min($mode_count('6m'), $mode_count('4m'), $mode_count('2m'), $mode_count('70cm'));
		$total = 0;
		foreach ($bands as $band) {
			$total += $n($stations, $band);
		}

		$ladder = array(
			array('label' => 'Bronze', 'reqs' => array(array('FM or SSB on 2 m', $n($fm_ssb, '2m'), 30), array('FM or SSB on 70 cm', $n($fm_ssb, '70cm'), 20))),
			array('label' => 'Silver', 'reqs' => array(array('on 2 m', $n($stations, '2m'), 50), array('on 70 cm', $n($stations, '70cm'), 40))),
			array('label' => 'Gold', 'reqs' => array(array('on 2 m', $n($stations, '2m'), 75), array('on 70 cm', $n($stations, '70cm'), 60), array('on 6 m or 4 m', $six_or_four, 50))),
			array('label' => 'Platinum', 'reqs' => array(array('bands of 6 m, 4 m, 2 m, 70 cm with 100 stations in two modes', $full_bands, 3))),
			array('label' => 'Diamond', 'reqs' => array(array('on each of 6 m, 4 m, 2 m, 70 cm', $lowest_four, 100), array('modes on each band', $fewest_modes, 2), array('on 23 cm', $n($stations, '23cm'), 25), array('modes on 23 cm', $mode_count('23cm'), 2))),
		);
		$variants = array($this->variant('activity', 'V/UHF Activity Award', 'Different stations on each band, simplex, in FM, SSB, or CW', $total, $total, $ladder));

		$rows = array();
		foreach ($bands as $band) {
			$used = isset($modes[$band]) ? implode(', ', array_keys($modes[$band])) : '';
			$rows[] = array($band, $n($stations, $band), $n($fm_ssb, $band), $used);
		}
		$tables = array(array(
			'title' => 'Stations by band',
			'columns' => array('Band', 'Different stations', 'In FM or SSB', 'Modes used'),
			'rows' => $rows,
		));
		$notes = array('Contacts from 1 June 2021. A station counts once on each band. Mark repeater contacts with the RPT propagation mode so they are left out.');
		if ($not_direct) {
			$notes[] = $not_direct . ' contact' . ($not_direct == 1 ? ' was' : 's were') . ' made through a repeater, gateway, or satellite and do not count.';
		}
		if ($no_freq) {
			$notes[] = $no_freq . ' contact' . ($no_freq == 1 ? ' has' : 's have') . ' no frequency and do not count. The RSGB checks the frequency to confirm the contact was simplex.';
		}
		return $this->result($variants, $tables, $notes, $eligible);
	}
}
