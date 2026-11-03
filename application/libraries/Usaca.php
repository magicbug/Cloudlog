<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * MARAC USA-CA county list and mapping of logged COL_CNTY values onto award counties.
 *
 * The award list is US_counties.csv with Alaska replaced by its four judicial districts,
 * independent cities removed and renamed counties folded into their current name.
 */
class Usaca {

	const TOTAL_COUNTIES = 3077;

	public $tiers = array(500, 1000, 1500, 2000, 2500, 3000, 3077);

	private $counties = array();
	private $alaska = array();
	private $renamed = array();
	private $cities = array();
	private $state_abbr = array();

	public function __construct($params = array()) {
		$base = isset($params['base_path']) ? rtrim($params['base_path'], '/\\') . '/' : $this->default_base_path();
		$this->load($base . 'assets/json/usaca_map.json', $base . 'assets/json/US_counties.csv', isset($params['states']) ? $params['states'] : null);
	}

	private function default_base_path() {
		if (defined('FCPATH') && is_readable(FCPATH . 'assets/json/usaca_map.json')) {
			return FCPATH;
		}
		return APPPATH . '../';
	}

	private function load($map_file, $csv_file, $states) {
		if ($states === null) {
			$CI =& get_instance();
			$CI->config->load('cloudlog_data_lists', false, true);
			$states = (array)$CI->config->item('usa_states_list');
		}
		foreach ($states as $abbr => $name) {
			$this->state_abbr[strtoupper($name)] = strtoupper($abbr);
		}

		$map = json_decode((string)@file_get_contents($map_file), true);
		if (!is_array($map)) {
			return;
		}

		foreach ($map['alaska'] as $district => $names) {
			$this->counties['AK'][self::key($district)] = $district;
			foreach ($names as $name) {
				$this->alaska[self::key($name)] = $district;
			}
		}

		foreach ($map['renamed'] as $state => $names) {
			foreach ($names as $old => $new) {
				$this->renamed[$state][self::key($old)] = $new;
			}
		}

		foreach ($map['independent_cities'] as $state => $cities) {
			$shared = array_map(array('Usaca', 'key'), isset($map['city_names_shared_with_county'][$state]) ? $map['city_names_shared_with_county'][$state] : array());
			foreach ($cities as $city => $neighbors) {
				$info = array('label' => $city . ' City', 'neighbors' => $neighbors);
				$this->cities[$state][self::key($city . ' City')] = $info;
				if (!in_array(self::key($city), $shared, true)) {
					$this->cities[$state][self::key($city)] = $info;
				}
			}
		}

		if (($handle = @fopen($csv_file, 'r')) === false) {
			return;
		}
		while (($row = fgetcsv($handle, 0, ',', '"', '\\')) !== false) {
			if (count($row) < 2) {
				continue;
			}
			$state_name = strtoupper(trim($row[0]));
			$state = strlen($state_name) === 2 ? $state_name : (isset($this->state_abbr[$state_name]) ? $this->state_abbr[$state_name] : '');
			$key = self::key($row[1]);
			if ($state === '' || $state === 'AK' || $key === '' || isset($this->cities[$state][$key]) || isset($this->renamed[$state][$key])) {
				continue;
			}
			$this->counties[$state][$key] = trim($row[1]);
		}
		fclose($handle);
	}

	/*
	 * Folds spelling variants (case, punctuation, St./Saint, County/Parish/Borough suffixes) to one key.
	 */
	public static function key($name) {
		$name = strtolower(trim((string)$name));
		$ascii = function_exists('iconv') ? @iconv('UTF-8', 'ASCII//TRANSLIT', $name) : false;
		if ($ascii !== false) {
			$name = $ascii;
		}
		$name = str_replace('&', ' and ', $name);
		$name = preg_replace('/^city of\s+(.*)$/', '$1 city', $name);
		$name = preg_replace('/^ste\.?\s+/', 'sainte ', $name);
		$name = preg_replace('/^st\.?\s+/', 'saint ', $name);
		$name = preg_replace('/\s+(county|parish|city and borough|borough|census area|municipality)$/', '', $name);
		return preg_replace('/[^a-z0-9]/', '', $name);
	}

	public function state_targets() {
		$targets = array();
		foreach ($this->counties as $state => $list) {
			$targets[$state] = count($list);
		}
		ksort($targets);
		return $targets;
	}

	public function total() {
		return array_sum($this->state_targets());
	}

	/*
	 * Returns null for a blank county, otherwise an array with 'type':
	 *   county  - award county 'name' (via_city set when credited from a one-neighbor independent city)
	 *   city    - independent city with several neighbors; 'name' is the city, 'neighbors' the choices
	 *   unknown - not on the award list
	 */
	public function map($state, $county) {
		$state = strtoupper(trim((string)$state));
		$key = self::key($county);
		if ($key === '') {
			return null;
		}

		if ($state === 'AK' && isset($this->alaska[$key])) {
			return array('type' => 'county', 'name' => $this->alaska[$key]);
		}
		if (isset($this->renamed[$state][$key])) {
			return array('type' => 'county', 'name' => $this->renamed[$state][$key]);
		}
		if (isset($this->cities[$state][$key])) {
			$city = $this->cities[$state][$key];
			if (count($city['neighbors']) === 1) {
				return array('type' => 'county', 'name' => $city['neighbors'][0], 'via_city' => $city['label']);
			}
			return array('type' => 'city', 'name' => $city['label'], 'neighbors' => $city['neighbors']);
		}
		if (isset($this->counties[$state][$key])) {
			return array('type' => 'county', 'name' => $this->counties[$state][$key]);
		}
		return array('type' => 'unknown', 'name' => trim((string)$county));
	}
}
