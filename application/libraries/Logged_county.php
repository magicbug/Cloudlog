<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * County-line contacts are two QSOs at the same time with a different logged
 * county (CNTY) or operator county (MY_CNTY). Matching and duplicate checks
 * use these helpers so those QSOs stay distinct, while blank counties and
 * spelling variants (case, punctuation, a trailing "County") still match.
 */
class Logged_county {

	/*
	 * LoTW confirmations match a logged QSO within this many minutes of TIME_ON.
	 */
	public static function within_minutes($qso_time, $confirm_time, $minutes = 15)
	{
		$qso = is_int($qso_time) ? $qso_time : strtotime((string)$qso_time);
		$confirm = is_int($confirm_time) ? $confirm_time : strtotime((string)$confirm_time);
		if ($qso === false || $confirm === false) {
			return false;
		}
		$window = (int)$minutes * 60;
		return $qso >= ($confirm - $window) && $qso <= ($confirm + $window);
	}

	public static function key($value)
	{
		$value = strtoupper(trim((string)$value));
		if ($value === '' || $value === ',') {
			return '';
		}

		$state = '';
		$name = $value;
		$comma = strpos($value, ',');
		if ($comma !== false) {
			$state = preg_replace('/[^A-Z0-9]/', '', substr($value, 0, $comma));
			$name = substr($value, $comma + 1);
		}

		$name = preg_replace('/[^A-Z0-9]/', '', $name);
		$name = preg_replace('/(CENSUSAREA|MUNICIPALITY|BOROUGH|PARISH|COUNTY)$/', '', $name);
		if ($name === '') {
			return '';
		}
		if ($state === '') {
			return $name;
		}
		return $state . ',' . $name;
	}

	/*
	 * True only when both sides name a county and they are not the same county.
	 * A blank side is not a different county.
	 */
	public static function differs($existing, $incoming)
	{
		$a = self::key($existing);
		$b = self::key($incoming);
		if ($a === '' || $b === '') {
			return false;
		}
		if ($a === $b) {
			return false;
		}

		$a_state = strpos($a, ',') !== false;
		$b_state = strpos($b, ',') !== false;
		if ($a_state !== $b_state) {
			$a_name = $a_state ? substr($a, strpos($a, ',') + 1) : $a;
			$b_name = $b_state ? substr($b, strpos($b, ',') + 1) : $b;
			if ($a_name === $b_name) {
				return false;
			}
		}
		return true;
	}

	/*
	 * Existing rows already share call, time, band, mode and station.
	 * The incoming QSO is a duplicate when one of those rows does not
	 * disagree on CNTY or MY_CNTY.
	 */
	public static function is_duplicate($existing_rows, $incoming_cnty, $incoming_my_cnty)
	{
		foreach ($existing_rows as $row) {
			$cnty = is_array($row) ? ($row['COL_CNTY'] ?? '') : ($row->COL_CNTY ?? '');
			$my = is_array($row) ? ($row['COL_MY_CNTY'] ?? '') : ($row->COL_MY_CNTY ?? '');
			if (!self::differs($cnty, $incoming_cnty) && !self::differs($my, $incoming_my_cnty)) {
				return true;
			}
		}
		return false;
	}

	/*
	 * Choose the logged QSO a confirmation belongs to.
	 * $cnty null keeps the previous first-row behaviour.
	 * A named county prefers that county, then a QSO with no county.
	 * With several QSOs that each already have a different county, returns
	 * null so a county-line partner is not overwritten.
	 * $exclude_ids are QSOs already claimed by an earlier record in this file.
	 */
	public static function pick_match($rows, $cnty = null, $exclude_ids = array())
	{
		$exclude = array();
		foreach ($exclude_ids as $id) {
			$exclude[(int)$id] = true;
		}

		$candidates = array();
		foreach ($rows as $row) {
			$id = self::primary_key($row);
			if (!isset($exclude[$id])) {
				$candidates[] = $row;
			}
		}
		if ($candidates === array()) {
			return null;
		}
		if ($cnty === null || self::key($cnty) === '') {
			return self::primary_key($candidates[0]);
		}

		$blank = null;
		foreach ($candidates as $row) {
			$have = self::county_of($row);
			if (self::key($have) !== '' && !self::differs($have, $cnty)) {
				return self::primary_key($row);
			}
			if ($blank === null && self::key($have) === '') {
				$blank = $row;
			}
		}
		if ($blank !== null) {
			return self::primary_key($blank);
		}
		if (count($candidates) === 1) {
			return self::primary_key($candidates[0]);
		}
		return null;
	}

	private static function primary_key($row)
	{
		return (int)(is_array($row) ? $row['COL_PRIMARY_KEY'] : $row->COL_PRIMARY_KEY);
	}

	private static function county_of($row)
	{
		return is_array($row) ? ($row['COL_CNTY'] ?? '') : ($row->COL_CNTY ?? '');
	}
}
