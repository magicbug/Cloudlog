<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
	Radio Amateurs of Canada awards: Canadaward, Canadian Century Club, and Worked All RAC.
	Rules: https://www.rac.ca/operating/rac-operating-awards/

	Works on plain QSO arrays so it can be run without the database.
	Each QSO: call, time, band, mode, submode, prop, dxcc, state, confirmed.
*/

class Rac_awards {

	const CANADA = 1;
	const CANADAWARD_SINCE = '1977-07-01';
	const CENTURY_SINCE = '1946-01-01';
	const WARAC_SINCE = '1998-07-01';
	const CENTURY_TARGET = 100;
	const CENTURY_CAP = 20;
	const WARAC_TARGET = 10;

	// Propagation modes that mean the contact went through a repeater or a linked system.
	public $non_direct = array('RPT', 'INTERNET', 'ECH', 'IRL');

	public $modes = array('Mixed', 'Phone', 'CW', 'Digital');

	public $groups = array(
		'canadaward' => array('name' => 'Canadaward', 'since' => self::CANADAWARD_SINCE),
		'century' => array('name' => 'Canadian Century Club', 'since' => self::CENTURY_SINCE),
		'warac' => array('name' => 'Worked All RAC', 'since' => self::WARAC_SINCE),
	);

	// Provinces first, then territories, in the order RAC lists them.
	public $provinces = array(
		'NS' => 'Nova Scotia',
		'QC' => 'Quebec',
		'ON' => 'Ontario',
		'MB' => 'Manitoba',
		'SK' => 'Saskatchewan',
		'AB' => 'Alberta',
		'BC' => 'British Columbia',
		'NB' => 'New Brunswick',
		'NL' => 'Newfoundland and Labrador',
		'PE' => 'Prince Edward Island',
		'NT' => 'Northwest Territories',
		'NU' => 'Nunavut',
		'YT' => 'Yukon',
	);

	public $territories = array('NT', 'NU', 'YT');

	// Older ADIF codes still found in logs.
	private $state_aliases = array('PQ' => 'QC', 'NF' => 'NL', 'LB' => 'NL', 'YK' => 'YT');

	// VE1 and VA1 are left out: older New Brunswick and Prince Edward Island calls still use them.
	private $prefix_provinces = array(
		'VE2' => 'QC', 'VA2' => 'QC',
		'VE3' => 'ON', 'VA3' => 'ON',
		'VE4' => 'MB', 'VA4' => 'MB',
		'VE5' => 'SK', 'VA5' => 'SK',
		'VE6' => 'AB', 'VA6' => 'AB',
		'VE7' => 'BC', 'VA7' => 'BC',
		'VE8' => 'NT',
		'VE9' => 'NB',
		'VO1' => 'NL', 'VO2' => 'NL',
		'VY0' => 'NU',
		'VY1' => 'YT',
		'VY2' => 'PE',
	);

	// A slot with two calls counts once.
	public $warac_slots = array(
		'NS' => array('name' => 'Nova Scotia', 'calls' => array('VA1RAC', 'VE1RAC')),
		'QC' => array('name' => 'Quebec', 'calls' => array('VA2RAC')),
		'ON' => array('name' => 'Ontario', 'calls' => array('VA3RAC', 'VE3RAC')),
		'MB' => array('name' => 'Manitoba', 'calls' => array('VA4RAC', 'VE4RAC')),
		'SK' => array('name' => 'Saskatchewan', 'calls' => array('VA5RAC', 'VE5RAC')),
		'AB' => array('name' => 'Alberta', 'calls' => array('VA6RAC', 'VE6RAC')),
		'BC' => array('name' => 'British Columbia', 'calls' => array('VA7RAC', 'VE7RAC')),
		'NT' => array('name' => 'Northwest Territories', 'calls' => array('VE8RAC')),
		'NB' => array('name' => 'New Brunswick', 'calls' => array('VE9RAC')),
		'NF' => array('name' => 'Newfoundland', 'calls' => array('VO1RAC')),
		'LB' => array('name' => 'Labrador', 'calls' => array('VO2RAC')),
		'NU' => array('name' => 'Nunavut', 'calls' => array('VY0RAC')),
		'YT' => array('name' => 'Yukon', 'calls' => array('VY1RAC')),
		'PE' => array('name' => 'Prince Edward Island', 'calls' => array('VY2RAC')),
		'HQ' => array('name' => 'RAC Headquarters', 'calls' => array('VA3RHQ', 'VE3RHQ')),
	);

	private $band_order = array('2190m', '630m', '560m', '160m', '80m', '60m', '40m', '30m', '20m', '17m', '15m', '12m', '10m', '8m', '6m', '5m', '4m', '2m', '1.25m', '70cm', '33cm', '23cm', '13cm', '9cm', '6cm', '3cm', '1.25cm', '6mm', '4mm', '2.5mm', '2mm', '1mm');

	private $call_suffixes = array('P', 'M', 'MM', 'AM', 'A', 'QRP', 'LH');

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

	// The operator's own call, without a portable suffix or a prefix.
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

	public function is_ve0($call) {
		foreach ($this->call_parts($call) as $part) {
			if (strpos($part, 'VE0') === 0) {
				return true;
			}
		}
		return false;
	}

	/*
		Province code for a Canadian QSO, or null when it cannot be placed.
		The logged state wins. Otherwise the callsign prefix: VE3ABC/7 is in VE7, and K1ABC/VE2 in VE2.
	*/
	public function province($q) {
		$state = strtoupper(trim((string) (isset($q['state']) ? $q['state'] : '')));
		if (isset($this->state_aliases[$state])) {
			$state = $this->state_aliases[$state];
		}
		if (isset($this->provinces[$state])) {
			return $state;
		}
		$digit = null;
		$prefix = null;
		foreach ($this->call_parts($q['call']) as $part) {
			if (preg_match('/^\d$/', $part)) {
				$digit = $part;
			} elseif (preg_match('/^(VE|VA|VO|VY)\d$/', $part)) {
				$prefix = $part;
			}
		}
		if ($prefix === null) {
			if (!preg_match('/^([A-Z]{2})(\d)/', $this->base_call($q['call']), $m)) {
				return null;
			}
			$prefix = $m[1] . ($digit !== null ? $digit : $m[2]);
		}
		return isset($this->prefix_provinces[$prefix]) ? $this->prefix_provinces[$prefix] : null;
	}

	public function warac_slot($call) {
		$base = $this->base_call($call);
		foreach ($this->warac_slots as $key => $slot) {
			if (in_array($base, $slot['calls'], true)) {
				return $key;
			}
		}
		return null;
	}

	/*
		Modes: Phone, CW, Digital, or null for modes that count only toward Mixed.
		Digital voice counts as Phone.
	*/
	public function mode_group($mode, $submode = '') {
		$mode = strtoupper(trim((string) $mode));
		$sub = strtoupper(trim((string) $submode));
		if ($mode === '') {
			return null;
		}
		if ($mode === 'CW') {
			return 'CW';
		}
		if (in_array($mode, array('SSB', 'USB', 'LSB', 'AM', 'FM', 'DIGITALVOICE', 'DSTAR', 'C4FM', 'DMR', 'FREEDV', 'M17'), true)
			|| in_array($sub, array('USB', 'LSB'), true)) {
			return 'Phone';
		}
		if (in_array($mode, array('ATV', 'SSTV', 'FAX'), true)) {
			return null;
		}
		return 'Digital';
	}

	public function is_direct($q) {
		return !in_array(strtoupper(trim((string) $q['prop'])), $this->non_direct, true);
	}

	public function is_satellite($q) {
		return strtoupper(trim((string) $q['prop'])) === 'SAT';
	}

	private function on_or_after($q, $since) {
		return substr((string) $q['time'], 0, 10) >= $since;
	}

	/*
		Scoring
	*/

	public function evaluate($group, $qsos) {
		switch ($group) {
			case 'canadaward': return $this->canadaward($qsos);
			case 'century': return $this->century($qsos);
			case 'warac': return $this->warac($qsos);
		}
		return null;
	}

	private function variant($key, $name, $counts, $worked, $confirmed, $reqs, $label = 'Earned') {
		$met = true;
		$sum = 0;
		$missing = array();
		foreach ($reqs as $req) {
			$sum += $req[2] > 0 ? min(1, $req[1] / $req[2]) : 1;
			if ($req[1] < $req[2]) {
				$met = false;
				$missing[] = $req[2] . ' ' . $req[0] . ' (have ' . $req[1] . ')';
			}
		}
		return array(
			'key' => $key,
			'name' => $name,
			'counts' => $counts,
			'worked' => $worked,
			'confirmed' => $confirmed,
			'level' => $met ? $label : null,
			'next' => $met ? null : $label,
			'target' => $reqs[0][2],
			'missing' => $missing,
			'pct' => $met ? 100 : (int) floor($sum / count($reqs) * 100),
			'locked' => false,
			'gate' => '',
		);
	}

	private function result($variants, $tables = array(), $notes = array(), $eligible = array()) {
		return array('variants' => $variants, 'tables' => $tables, 'notes' => $notes, 'eligible' => array_values(array_unique($eligible)));
	}

	private function mark(&$seen, $band, $mode, $unit, $confirmed) {
		if ($confirmed) {
			$seen[$band][$mode][$unit] = 'C';
		} elseif (!isset($seen[$band][$mode][$unit])) {
			$seen[$band][$mode][$unit] = 'W';
		}
	}

	private function count_status($units, $want_confirmed) {
		$n = 0;
		foreach ($units as $status) {
			if (!$want_confirmed || $status === 'C') {
				$n++;
			}
		}
		return $n;
	}

	private function sort_bands($bands) {
		$order = array_flip($this->band_order);
		usort($bands, function ($a, $b) use ($order) {
			$x = isset($order[$a]) ? $order[$a] : 999;
			$y = isset($order[$b]) ? $order[$b] : 999;
			return $x === $y ? strcmp($a, $b) : $x - $y;
		});
		return $bands;
	}

	/*
		Canadaward and Worked All RAC share endorsements: Basic (any band), each band, and Satellite,
		each in Mixed, Phone, CW, and Digital. $units maps unit key to its name; $unit_of returns the
		unit for a QSO, or null when it does not count.
	*/
	private function endorsed($award, $qsos, $units, $target, $unit_label, $unit_of, $counts) {
		$seen = array();
		$eligible = array();
		foreach ($qsos as $i => $q) {
			$unit = $unit_of($q);
			if ($unit === null) {
				continue;
			}
			$confirmed = !empty($q['confirmed']);
			$mode = $this->mode_group($q['mode'], $q['submode']);
			$bands = array('Basic');
			if ($this->is_satellite($q)) {
				$bands[] = 'SAT';
			} elseif ((string) $q['band'] !== '') {
				$bands[] = $q['band'];
			}
			foreach ($bands as $band) {
				$this->mark($seen, $band, 'Mixed', $unit, $confirmed);
				if ($mode !== null) {
					$this->mark($seen, $band, $mode, $unit, $confirmed);
				}
			}
			$eligible[] = $i;
		}

		$variants = array();
		foreach ($this->modes as $mode) {
			$have = isset($seen['Basic'][$mode]) ? $seen['Basic'][$mode] : array();
			$confirmed = $this->count_status($have, true);
			$variants[] = $this->variant(
				$award . '_' . strtolower($mode),
				$this->groups[$award]['name'] . ', Basic ' . $mode,
				$counts . ($mode === 'Mixed' ? ', any mode' : ', ' . $mode),
				$this->count_status($have, false),
				$confirmed,
				array(array($unit_label, $confirmed, $target))
			);
		}

		$rows = array();
		foreach ($units as $key => $name) {
			$row = array($name);
			foreach ($this->modes as $mode) {
				$row[] = isset($seen['Basic'][$mode][$key]) ? $seen['Basic'][$mode][$key] : '';
			}
			$on = array();
			foreach ($seen as $band => $by_mode) {
				if ($band !== 'Basic' && isset($by_mode['Mixed'][$key]) && $by_mode['Mixed'][$key] === 'C') {
					$on[] = $band;
				}
			}
			$named = array();
			foreach ($this->sort_bands(array_diff($on, array('SAT'))) as $band) {
				$named[] = $band;
			}
			if (in_array('SAT', $on, true)) {
				$named[] = 'Satellite';
			}
			$row[] = implode(' ', $named);
			$rows[] = $row;
		}
		$tables = array(array(
			'title' => 'Check sheet (' . count($units) . ')',
			'columns' => array_merge(array(ucfirst($unit_label)), $this->modes, array('Confirmed on')),
			'rows' => $rows,
			'status' => true,
		));

		$band_keys = array_diff(array_keys($seen), array('Basic', 'SAT'));
		$band_keys = $this->sort_bands($band_keys);
		if (isset($seen['SAT'])) {
			$band_keys[] = 'SAT';
		}
		$rows = array();
		foreach ($band_keys as $band) {
			$row = array($band === 'SAT' ? 'Satellite' : $band);
			$earned = array();
			foreach ($this->modes as $mode) {
				$n = isset($seen[$band][$mode]) ? $this->count_status($seen[$band][$mode], true) : 0;
				$row[] = $n . ' / ' . $target;
				if ($n >= $target) {
					$earned[] = $mode;
				}
			}
			$row[] = implode(', ', $earned);
			$rows[] = $row;
		}
		if ($rows) {
			$tables[] = array(
				'title' => 'Band and satellite endorsements',
				'columns' => array_merge(array('Band'), $this->modes, array('Earned')),
				'rows' => $rows,
			);
		}

		return array('variants' => $variants, 'tables' => $tables, 'eligible' => $eligible);
	}

	/*
		Canadaward: every province and territory, since 1 July 1977.
	*/
	private function canadaward($qsos) {
		$unplaced = array();
		$self = $this;
		$unit_of = function ($q) use ($self, &$unplaced) {
			if (!$self->counts_for_canada($q, Rac_awards::CANADAWARD_SINCE)) {
				return null;
			}
			$province = $self->province($q);
			if ($province === null) {
				$unplaced[strtoupper($q['call'])] = true;
			}
			return $province;
		};
		$out = $this->endorsed('canadaward', $qsos, $this->provinces, count($this->provinces), 'provinces and territories', $unit_of, 'All 10 provinces and 3 territories');

		$notes = array(
			'Single-band endorsements need every contact on that band. The Satellite endorsement needs every contact through a satellite. Basic counts any band, satellite included.',
			'VE0 calls, repeater contacts, and contacts before 1 July 1977 do not count. Sable Island and St. Paul Island are separate DXCC entities and are not provinces.',
		);
		$notes = array_merge($notes, $this->unplaced_note($unplaced));
		return $this->result($out['variants'], $out['tables'], $notes, $out['eligible']);
	}

	/*
		Worked All RAC: 10 of the official RAC call-area slots, since 1 July 1998.
	*/
	private function warac($qsos) {
		$self = $this;
		$unit_of = function ($q) use ($self) {
			if (!$self->is_direct($q) || substr((string) $q['time'], 0, 10) < Rac_awards::WARAC_SINCE) {
				return null;
			}
			return $self->warac_slot($q['call']);
		};
		$units = array();
		foreach ($this->warac_slots as $key => $slot) {
			$units[$key] = $slot['name'] . ' (' . implode(', ', $slot['calls']) . ')';
		}
		$out = $this->endorsed('warac', $qsos, $units, self::WARAC_TARGET, 'official RAC call areas', $unit_of, '10 of the 15 official RAC call areas');

		$notes = array(
			'Where a call area has two official stations, such as VA1RAC and VE1RAC, only one counts. RAC Headquarters (VA3RHQ or VE3RHQ) is its own call area.',
			'Repeater contacts and contacts before 1 July 1998 do not count.',
		);
		return $this->result($out['variants'], $out['tables'], $notes, $out['eligible']);
	}

	// Canadian, direct, not VE0, and on or after the start date.
	public function counts_for_canada($q, $since) {
		return (int) $q['dxcc'] === self::CANADA
			&& $this->is_direct($q)
			&& !$this->is_ve0($q['call'])
			&& $this->on_or_after($q, $since);
	}

	private function unplaced_note($unplaced) {
		if (!$unplaced) {
			return array();
		}
		$calls = array_keys($unplaced);
		sort($calls);
		return array(count($calls) . ' Canadian contact' . (count($calls) == 1 ? '' : 's') . ' could not be placed in a province and do not count. Log the province in the State field to count them: ' . implode(', ', array_slice($calls, 0, 40)) . (count($calls) > 40 ? ', ...' : '') . '.');
	}

	/*
		Stations that count toward the Century Club from $stations (base call => provinces it was worked in):
		at most 20 per province, and at most 20 from the three territories together.
		A station counts once. Stations heard from one province are placed first, then each rover goes
		to whichever of its provinces has the fewest so far.
	*/
	private function century_count($stations) {
		$options = array();
		foreach ($stations as $station => $provinces) {
			$keys = array();
			foreach (array_keys($provinces) as $province) {
				$keys[in_array($province, $this->territories, true) ? 'TERR' : $province] = $province;
			}
			$options[$station] = $keys;
		}
		uasort($options, function ($a, $b) { return count($a) - count($b); });

		$per = array();
		$placed = array();
		foreach ($options as $station => $keys) {
			$best = null;
			$per_best = 0;
			foreach ($keys as $key => $province) {
				$n = isset($per[$key]) ? $per[$key] : 0;
				if ($best === null || $n < $per_best) {
					$best = $key;
					$per_best = $n;
				}
			}
			$per[$best] = (isset($per[$best]) ? $per[$best] : 0) + 1;
			$placed[$station] = $keys[$best];
		}
		$total = 0;
		foreach ($per as $n) {
			$total += min(self::CENTURY_CAP, $n);
		}
		return array('per' => $per, 'total' => $total, 'placed' => $placed);
	}

	/*
		Canadian Century Club: 100 different Canadian stations since 1945, with every province and
		at least one territory.
	*/
	private function century($qsos) {
		$worked = array();
		$confirmed = array();
		$unplaced = array();
		$eligible = array();
		foreach ($qsos as $i => $q) {
			if (!$this->counts_for_canada($q, self::CENTURY_SINCE)) {
				continue;
			}
			$province = $this->province($q);
			if ($province === null) {
				$unplaced[strtoupper($q['call'])] = true;
				continue;
			}
			$station = $this->base_call($q['call']);
			$worked[$station][$province] = true;
			if (!empty($q['confirmed'])) {
				$confirmed[$station][$province] = true;
			}
			$eligible[] = $i;
		}

		$w = $this->century_count($worked);
		$c = $this->century_count($confirmed);

		$provinces_with = 0;
		foreach ($this->provinces as $code => $name) {
			if (!in_array($code, $this->territories, true) && !empty($c['per'][$code])) {
				$provinces_with++;
			}
		}
		$territory = !empty($c['per']['TERR']) ? 1 : 0;

		$variants = array($this->variant(
			'century',
			'Canadian Century Club',
			'100 different Canadian stations, at most 20 from each province and 20 from the territories together, with every province and one territory',
			$w['total'],
			$c['total'],
			array(
				array('stations that count', $c['total'], self::CENTURY_TARGET),
				array('provinces', $provinces_with, 10),
				array('territory', $territory, 1),
			)
		));

		$worked_per = array();
		foreach ($w['placed'] as $province) {
			$worked_per[$province] = (isset($worked_per[$province]) ? $worked_per[$province] : 0) + 1;
		}
		$confirmed_per = array();
		foreach ($c['placed'] as $province) {
			$confirmed_per[$province] = (isset($confirmed_per[$province]) ? $confirmed_per[$province] : 0) + 1;
		}
		$rows = array();
		foreach ($this->provinces as $code => $name) {
			if (in_array($code, $this->territories, true)) {
				continue;
			}
			$n = isset($confirmed_per[$code]) ? $confirmed_per[$code] : 0;
			$rows[] = array($name, isset($worked_per[$code]) ? $worked_per[$code] : 0, $n, min(self::CENTURY_CAP, $n), $n > 0 ? '' : 'Needs one');
		}
		$tw = 0;
		$tc = 0;
		$names = array();
		foreach ($this->territories as $code) {
			$tw += isset($worked_per[$code]) ? $worked_per[$code] : 0;
			$tc += isset($confirmed_per[$code]) ? $confirmed_per[$code] : 0;
			$names[] = $this->provinces[$code];
		}
		$rows[] = array('Territories (' . implode(', ', $names) . ')', $tw, $tc, min(self::CENTURY_CAP, $tc), $tc > 0 ? '' : 'Needs one');
		$rows[] = array('Total', count($worked), count($confirmed), $c['total'], '');

		$tables = array(array(
			'title' => 'Stations by province',
			'columns' => array('Province or territory', 'Worked', 'Confirmed', 'Count (max 20)', 'Missing'),
			'rows' => $rows,
		));
		$notes = array(
			'Each station counts once, whatever band or mode, and portable calls such as VE3ABC/P are the same station as VE3ABC. A station worked in more than one province, such as a rover, counts in whichever of them has the fewest stations, so it fills a gap first.',
			'VE0 calls, repeater contacts, and contacts before 1946 do not count.',
		);
		$notes = array_merge($notes, $this->unplaced_note($unplaced));
		return $this->result($variants, $tables, $notes, $eligible);
	}
}
