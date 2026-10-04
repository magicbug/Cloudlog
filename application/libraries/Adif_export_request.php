<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Request parsing and validation for POST /api/get_contacts_adif.
 *
 * This class is deliberately free of any CodeIgniter / database dependency so
 * that it can be unit tested in isolation (see tests/unit). It never touches
 * the API key beyond checking its type and length: key validation is done by
 * Api_model in the controller.
 */
class Adif_export_request
{
	const DEFAULT_LIMIT   = 5000;
	const MAX_LIMIT       = 10000;
	const MAX_BODY_BYTES  = 4096;
	const MAX_KEY_LENGTH  = 128;
	const MAX_STATION_ID  = 2147483647;

	/**
	 * Parse and validate the request.
	 *
	 * @param string $content_type Value of the Content-Type request header.
	 * @param string $raw_body     Raw request body.
	 * @return array On success: ['ok' => true, 'key' => string, 'station_id' => int,
	 *               'fetchfromid' => int, 'limit' => int].
	 *               On failure: ['ok' => false, 'status' => int HTTP code, 'reason' => string].
	 */
	public static function parse($content_type, $raw_body)
	{
		$media_type = strtolower(trim(explode(';', (string)$content_type, 2)[0]));
		if ($media_type !== 'application/json') {
			return self::error(415, 'Content-Type must be application/json');
		}

		$raw_body = (string)$raw_body;
		if (strlen($raw_body) > self::MAX_BODY_BYTES) {
			return self::error(400, 'Request body too large');
		}

		$data = json_decode($raw_body, true, 8);
		if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
			return self::error(400, 'Malformed JSON');
		}
		// A JSON list (e.g. [1,2]) is not a valid request object.
		if ($data !== array() && array_keys($data) === range(0, count($data) - 1)) {
			return self::error(400, 'Malformed JSON');
		}

		// --- API key (only the JSON body is supported by Cloudlog) ---
		if (!array_key_exists('key', $data) || $data['key'] === null || $data['key'] === '') {
			return self::error(401, 'Missing API key');
		}
		if (!is_string($data['key'])) {
			return self::error(400, 'key must be a string');
		}
		if (strlen($data['key']) > self::MAX_KEY_LENGTH) {
			return self::error(401, 'Invalid API key');
		}

		// --- station_id: exactly one positive integer ---
		if (!array_key_exists('station_id', $data) || $data['station_id'] === null || $data['station_id'] === '') {
			return self::error(400, 'station_id is required');
		}
		$station_id = self::to_int($data['station_id'], 1, self::MAX_STATION_ID);
		if ($station_id === null) {
			return self::error(400, 'station_id must be a single positive integer');
		}

		// --- fetchfromid: non-negative integer, defaults to 0 ---
		$fetchfromid = 0;
		if (array_key_exists('fetchfromid', $data) && $data['fetchfromid'] !== null) {
			$fetchfromid = self::to_int($data['fetchfromid'], 0, PHP_INT_MAX);
			if ($fetchfromid === null) {
				return self::error(400, 'fetchfromid must be a non-negative integer');
			}
		}

		// --- limit: optional, 1..MAX_LIMIT ---
		$limit = self::DEFAULT_LIMIT;
		if (array_key_exists('limit', $data) && $data['limit'] !== null) {
			$limit = self::to_int($data['limit'], 1, self::MAX_LIMIT);
			if ($limit === null) {
				return self::error(400, 'limit must be an integer between 1 and ' . self::MAX_LIMIT);
			}
		}

		return array(
			'ok'          => true,
			'key'         => $data['key'],
			'station_id'  => $station_id,
			'fetchfromid' => $fetchfromid,
			'limit'       => $limit,
		);
	}

	/**
	 * Strict integer conversion. Accepts a JSON integer, or a canonical decimal
	 * string ("123", no sign, no leading zeros, no whitespace, no exponent).
	 * Rejects floats (including 1.0 and 1e3), booleans, arrays, objects and
	 * anything that does not round-trip exactly.
	 *
	 * @return int|null null when invalid or out of [$min, $max]
	 */
	private static function to_int($value, $min, $max)
	{
		if (is_int($value)) {
			$int = $value;
		} elseif (is_string($value) && preg_match('/^(0|[1-9][0-9]{0,18})$/', $value) === 1) {
			$int = (int)$value;
			if ((string)$int !== $value) {
				return null; // overflowed PHP_INT_MAX
			}
		} else {
			return null;
		}

		return ($int >= $min && $int <= $max) ? $int : null;
	}

	private static function error($status, $reason)
	{
		return array('ok' => false, 'status' => $status, 'reason' => $reason);
	}
}
