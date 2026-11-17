<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
	Russian Districts Award (RDA), the hunter award from the Tambov Award Group.

	The district list is assets/json/rda.json, built from rdaward.org/rda_eng.txt.
	Scoring works on plain arrays so it can be run without the database.
	Each QSO: cnty, call, time, band, mode, confirmed.
*/

class Rda_award {

	const LIST_URL = 'https://www.rdaward.org/rda_eng.txt';
	const MIN_CURRENT_DISTRICTS = 2000;

	public $levels = array(
		array('name' => 'RDA-100', 'count' => 100),
		array('name' => 'RDA-250', 'count' => 250),
		array('name' => 'RDA-500', 'count' => 500),
		array('name' => 'RDA-1000', 'count' => 1000),
		array('name' => 'RDA-1500', 'count' => 1500),
		array('name' => 'RDA-2000', 'count' => 2000),
		array('name' => 'Honor Roll', 'count' => 2500),
		array('name' => 'Honor Roll #1', 'count' => null),
	);

	private $reference = null;

	public function reference_path() {
		return (defined('FCPATH') ? FCPATH : dirname(__DIR__, 2) . '/') . 'assets/json/rda.json';
	}

	public function load_reference($path = null) {
		if ($this->reference !== null && $path === null) {
			return $this->reference;
		}
		$path = $path ?: $this->reference_path();
		$data = is_readable($path) ? json_decode(file_get_contents($path), true) : null;
		if (!is_array($data) || empty($data['districts'])) {
			$data = array('updated' => '', 'source' => self::LIST_URL, 'oblasts' => array(), 'districts' => array());
		}
		$this->reference = $data;
		return $data;
	}

	public function set_reference($data) {
		$this->reference = $data;
	}

	/*
		A code is two letters and a number: TB-02. Loggers also write tb02 or TB 02.
		US counties are ST,Name and never match.
	*/
	public function normalize($value) {
		$value = strtoupper(trim((string) $value));
		if (!preg_match('/^([A-Z]{2})[\s-]?(\d{1,3})$/', $value, $m)) {
			return null;
		}
		if ((int) $m[2] === 0) {
			return null;
		}
		return $m[1] . '-' . str_pad((string) (int) $m[2], 2, '0', STR_PAD_LEFT);
	}

	public function parse_list($text) {
		if (!mb_check_encoding($text, 'UTF-8')) {
			$text = mb_convert_encoding($text, 'UTF-8', 'Windows-1251');
		}
		$lines = preg_split('/\r\n|\r|\n/', $text);

		$updated = '';
		$oblasts = array();
		$districts = array();
		$current = null;

		foreach ($lines as $line) {
			$line = trim($line);
			if ($line === '') {
				continue;
			}

			if ($updated === '' && preg_match('/Last update\s+(\d{2})\.(\d{2})\.(\d{4})/i', $line, $m)) {
				$updated = "{$m[3]}-{$m[2]}-{$m[1]}";
				continue;
			}

			if (preg_match('/^([A-Z]{2})-(\d{1,3})\s+(.*)$/', $line, $m)) {
				if ($current === null || $m[1] !== $current) {
					continue;
				}
				$code = $this->normalize($m[1] . '-' . $m[2]);
				$rest = trim($m[3]);
				$district = array(
					'code' => $code,
					'name' => '',
					'oblast' => $current,
					'oblast_name' => $oblasts[$current]['name'],
					'prefix' => $oblasts[$current]['prefix'],
					'deleted' => false,
					'replaced_by' => '',
					'valid_from' => $oblasts[$current]['valid_from'],
				);

				if (preg_match('/^deleted\b\s*(.*)$/i', $rest, $d)) {
					$district['deleted'] = true;
					$replacement = $this->normalize(preg_replace('/\s.*$/', '', trim($d[1])));
					$district['replaced_by'] = $replacement ?: '';
				} else {
					if (preg_match('/\bsince\s+(\d{2})\.(\d{2})\.(\d{4})/i', $rest, $s)) {
						$district['valid_from'] = "{$s[3]}-{$s[2]}-{$s[1]}";
					}
					$name = preg_replace('/\s*\bsince\s+\d{2}\.\d{2}\.\d{4}.*$/i', '', $rest);
					$name = preg_replace('/\s*\bNEW RDA\b\s*$/i', '', $name);
					$district['name'] = trim($name);
				}

				if (!isset($districts[$code])) {
					$districts[$code] = $district;
				}
				continue;
			}

			if (preg_match('/^(.+?)\s*\(([A-Z]{2})\)\s+(U[A-Z0-9]{2,4})$/', $line, $m)) {
				$current = $m[2];
				$oblasts[$current] = array(
					'code' => $current,
					'name' => str_replace('`', "'", trim($m[1])),
					'prefix' => $m[3],
					'valid_from' => '',
				);
				continue;
			}

			if ($current !== null && preg_match('/^\(Valid since (\d{2})\.(\d{2})\.(\d{4})\)$/i', $line, $m)) {
				$oblasts[$current]['valid_from'] = "{$m[3]}-{$m[2]}-{$m[1]}";
			}
		}

		ksort($oblasts);
		ksort($districts);
		foreach ($oblasts as $code => $oblast) {
			$oblasts[$code]['total'] = 0;
		}
		foreach ($districts as $district) {
			if (!$district['deleted']) {
				$oblasts[$district['oblast']]['total']++;
			}
		}

		return array(
			'updated' => $updated,
			'source' => self::LIST_URL,
			'oblasts' => $oblasts,
			'districts' => array_values($districts),
		);
	}

	public function current_count($reference) {
		$count = 0;
		foreach ($reference['districts'] as $district) {
			if (empty($district['deleted'])) {
				$count++;
			}
		}
		return $count;
	}

	/*
		One credit per current district, on any band. A QSO before the district's
		start date does not count. Deleted and unknown codes are listed, not counted.
	*/
	public function evaluate($qsos, $reference = null) {
		$reference = $reference ?: $this->load_reference();

		$lookup = array();
		foreach ($reference['districts'] as $district) {
			$lookup[$district['code']] = $district;
		}

		$districts = array();
		$deleted = array();
		$unknown = array();
		$too_early = 0;

		foreach ($qsos as $qso) {
			$code = $this->normalize(isset($qso['cnty']) ? $qso['cnty'] : '');
			if ($code === null) {
				continue;
			}
			$confirmed = !empty($qso['confirmed']);

			if (!isset($lookup[$code])) {
				$this->add_qso($unknown, $code, $qso, $confirmed, array('code' => $code));
				continue;
			}
			$district = $lookup[$code];
			if (!empty($district['deleted'])) {
				$this->add_qso($deleted, $code, $qso, $confirmed, array('code' => $code, 'replaced_by' => $district['replaced_by'], 'oblast_name' => $district['oblast_name']));
				continue;
			}
			if (!empty($district['valid_from']) && substr((string) $qso['time'], 0, 10) < $district['valid_from']) {
				$too_early++;
				continue;
			}
			$this->add_qso($districts, $code, $qso, $confirmed, array(
				'code' => $code,
				'name' => $district['name'],
				'oblast' => $district['oblast'],
				'oblast_name' => $district['oblast_name'],
			));
		}
		ksort($districts);

		$oblasts = array();
		foreach ($reference['oblasts'] as $code => $oblast) {
			if (empty($oblast['total'])) {
				continue;
			}
			$oblasts[$code] = array(
				'code' => $code,
				'name' => $oblast['name'],
				'prefix' => $oblast['prefix'],
				'total' => (int) $oblast['total'],
				'worked' => 0,
				'confirmed' => 0,
			);
		}
		$worked = 0;
		$confirmed = 0;
		foreach ($districts as $district) {
			$worked++;
			$oblasts[$district['oblast']]['worked']++;
			if ($district['confirmed']) {
				$confirmed++;
				$oblasts[$district['oblast']]['confirmed']++;
			}
		}

		$total = $this->current_count($reference);

		return array(
			'updated' => $reference['updated'],
			'total' => $total,
			'worked' => $worked,
			'confirmed' => $confirmed,
			'levels' => $this->level_rows($worked, $confirmed, $total),
			'oblasts' => $oblasts,
			'districts' => $districts,
			'deleted' => array_values($deleted),
			'unknown' => array_values($unknown),
			'too_early' => $too_early,
		);
	}

	public function level_rows($worked, $confirmed, $total) {
		$rows = array();
		foreach ($this->levels as $level) {
			$target = $level['count'] === null ? $total : $level['count'];
			$rows[] = array(
				'name' => $level['name'],
				'target' => $target,
				'worked' => $worked >= $target,
				'confirmed' => $confirmed >= $target,
				'remaining' => max(0, $target - $confirmed),
			);
		}
		return $rows;
	}

	private function add_qso(&$list, $code, $qso, $confirmed, $fields) {
		if (!isset($list[$code])) {
			$list[$code] = $fields + array(
				'call' => $qso['call'],
				'time' => $qso['time'],
				'band' => $qso['band'],
				'mode' => $qso['mode'],
				'qsos' => 0,
				'confirmed' => false,
			);
		}
		$list[$code]['qsos']++;
		if ($confirmed && !$list[$code]['confirmed']) {
			$list[$code]['confirmed'] = true;
			$list[$code]['call'] = $qso['call'];
			$list[$code]['time'] = $qso['time'];
			$list[$code]['band'] = $qso['band'];
			$list[$code]['mode'] = $qso['mode'];
		}
	}
}
