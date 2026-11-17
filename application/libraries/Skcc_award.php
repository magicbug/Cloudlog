<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
	Straight Key Century Club number awards: Centurion, Tribune, and Senator.

	Works on plain QSO arrays so it can be run without the database.
	Each QSO: call, skcc, sig, sig_info, time, band, mode, name.
*/

class Skcc_award {

	const CENTURION_STEP = 100;
	const TRIBUNE_STEP = 50;
	const SENATOR_STEP = 200;
	const TRIBUNE_SENATOR_GATE = 8;
	const SENATOR_MAX_LEVEL = 10;

	private $suffix_rank = array('' => 0, 'C' => 1, 'T' => 2, 'S' => 3);

	public function parse($value) {
		$value = strtoupper(trim((string) $value));
		if ($value === '') {
			return null;
		}
		if (!preg_match('/^(\d+)\s*([CTS])?(?:\s*X\s*\d+)?$/', $value, $matches)) {
			return null;
		}
		$number = (int) $matches[1];
		if ($number <= 0) {
			return null;
		}
		return array(
			'number' => $number,
			'suffix' => isset($matches[2]) ? $matches[2] : '',
		);
	}

	public function number_from_qso($qso) {
		$parsed = $this->parse(isset($qso['skcc']) ? $qso['skcc'] : '');
		if ($parsed === null && isset($qso['sig']) && strtoupper(trim((string) $qso['sig'])) === 'SKCC') {
			$parsed = $this->parse(isset($qso['sig_info']) ? $qso['sig_info'] : '');
		}
		return $parsed;
	}

	public function is_excluded_call($call) {
		foreach (explode('/', strtoupper(trim((string) $call))) as $part) {
			if ($part === 'K9SKC' || $part === 'K3Y') {
				return true;
			}
		}
		return false;
	}

	/*
		Centurion and Tribune levels go up by one step to x10, then by five steps (x15, x20, ...).
		Senator stops at x10.
	*/
	public function level($count, $step, $max_level = null) {
		$multiple = intdiv(max(0, (int) $count), $step);
		if ($multiple > 10) {
			$multiple = 10 + intdiv($multiple - 10, 5) * 5;
		}
		if ($max_level !== null && $multiple > $max_level) {
			$multiple = $max_level;
		}
		return $multiple;
	}

	public function next_threshold($count, $step, $max_level = null) {
		$level = $this->level($count, $step, $max_level);
		if ($max_level !== null && $level >= $max_level) {
			return null;
		}
		$next = $level < 10 ? $level + 1 : $level + 5;
		return $next * $step;
	}

	public function level_label($letter, $level) {
		if ($level <= 0) {
			return '';
		}
		return $level === 1 ? $letter : $letter . 'x' . $level;
	}

	private function is_threshold($count, $step, $max_level = null) {
		if ($count % $step !== 0) {
			return false;
		}
		$multiple = $count / $step;
		if ($max_level !== null && $multiple > $max_level) {
			return false;
		}
		return $multiple <= 10 || $multiple % 5 === 0;
	}

	public function evaluate($qsos) {
		$members = array();
		$earned = array();

		$centurion = array('count' => 0, 'achieved_at' => null);
		$tribune = array('count' => 0, 'achieved_at' => null, 'tx8_at' => null);
		$senator = array('count' => 0, 'achieved_at' => null);

		$tribune_seen = array();
		$senator_seen = array();
		$centurion_done = false;
		$tx8_done = false;

		foreach ($qsos as $qso) {
			$call = isset($qso['call']) ? $qso['call'] : '';
			if ($this->is_excluded_call($call)) {
				continue;
			}
			$parsed = $this->number_from_qso($qso);
			if ($parsed === null) {
				continue;
			}
			$number = $parsed['number'];
			$suffix = $parsed['suffix'];
			$time = isset($qso['time']) ? $qso['time'] : null;

			// Tribune and Senator only count QSOs logged after the gate QSO, so check before this QSO updates counts.
			$tribune_open = $centurion_done;
			$senator_open = $tx8_done;

			if (!isset($members[$number])) {
				$members[$number] = array(
					'number' => $number,
					'suffix' => $suffix,
					'call' => $call,
					'name' => isset($qso['name']) ? $qso['name'] : '',
					'time' => $time,
					'band' => isset($qso['band']) ? $qso['band'] : '',
					'mode' => isset($qso['mode']) ? $qso['mode'] : '',
					'qsos' => 0,
					'counted' => array('Centurion'),
				);
				$centurion['count']++;
				if ($this->is_threshold($centurion['count'], self::CENTURION_STEP)) {
					$level = $centurion['count'] / self::CENTURION_STEP;
					$earned[] = array('award' => 'Centurion', 'label' => $this->level_label('C', $level), 'count' => $centurion['count'], 'time' => $time);
					if ($level === 1) {
						$centurion['achieved_at'] = $time;
						$centurion_done = true;
					}
				}
			} elseif ($this->suffix_rank[$suffix] > $this->suffix_rank[$members[$number]['suffix']]) {
				$members[$number]['suffix'] = $suffix;
			}
			$members[$number]['qsos']++;

			if ($tribune_open && in_array($suffix, array('C', 'T', 'S'), true) && !isset($tribune_seen[$number])) {
				$tribune_seen[$number] = true;
				$tribune['count']++;
				$members[$number]['counted'][] = 'Tribune';
				if ($this->is_threshold($tribune['count'], self::TRIBUNE_STEP)) {
					$level = $tribune['count'] / self::TRIBUNE_STEP;
					$earned[] = array('award' => 'Tribune', 'label' => $this->level_label('T', $level), 'count' => $tribune['count'], 'time' => $time);
					if ($level === 1) {
						$tribune['achieved_at'] = $time;
					}
					if ($level === self::TRIBUNE_SENATOR_GATE) {
						$tribune['tx8_at'] = $time;
						$tx8_done = true;
					}
				}
			}

			if ($senator_open && in_array($suffix, array('T', 'S'), true) && !isset($senator_seen[$number])) {
				$senator_seen[$number] = true;
				$senator['count']++;
				$members[$number]['counted'][] = 'Senator';
				if ($this->is_threshold($senator['count'], self::SENATOR_STEP, self::SENATOR_MAX_LEVEL)) {
					$level = $senator['count'] / self::SENATOR_STEP;
					$earned[] = array('award' => 'Senator', 'label' => $this->level_label('S', $level), 'count' => $senator['count'], 'time' => $time);
					if ($level === 1) {
						$senator['achieved_at'] = $time;
					}
				}
			}
		}

		$bare = 0;
		foreach ($members as $member) {
			if ($member['suffix'] === '') {
				$bare++;
			}
		}

		return array(
			'members' => $members,
			'earned' => $earned,
			'bare_members' => $bare,
			'centurion' => $this->summary($centurion, true, self::CENTURION_STEP, 'C'),
			'tribune' => $this->summary($tribune, $centurion_done, self::TRIBUNE_STEP, 'T'),
			'senator' => $this->summary($senator, $tx8_done, self::SENATOR_STEP, 'S', self::SENATOR_MAX_LEVEL),
		);
	}

	private function summary($state, $unlocked, $step, $letter, $max_level = null) {
		$level = $this->level($state['count'], $step, $max_level);
		$next = $this->next_threshold($state['count'], $step, $max_level);
		return array_merge($state, array(
			'unlocked' => $unlocked,
			'level' => $level,
			'label' => $this->level_label($letter, $level),
			'next' => $next,
			'next_label' => $next === null ? '' : $this->level_label($letter, $next / $step),
			'remaining' => $next === null ? 0 : $next - $state['count'],
		));
	}
}
