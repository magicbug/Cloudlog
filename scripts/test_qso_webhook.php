<?php

/**
 * Cheap sanity checks for QSO webhook helpers (no database).
 *
 * Usage: php scripts/test_qso_webhook.php
 */

if (php_sapi_name() !== 'cli') {
	exit(1);
}

define('BASEPATH', true);

require_once dirname(__DIR__) . '/application/libraries/Qso_webhook.php';

class Qso_webhook_test_stub extends Qso_webhook {
	public function __construct() {}
}

$webhook = new Qso_webhook_test_stub();

$failures = 0;

function assert_true($condition, $message) {
	global $failures;
	if (!$condition) {
		fwrite(STDERR, "FAIL: {$message}\n");
		$failures++;
		return;
	}
	echo "OK: {$message}\n";
}

assert_true($webhook->is_allowed_webhook_url('https://example.com/hook'), 'HTTPS URL allowed');
assert_true($webhook->is_allowed_webhook_url('http://127.0.0.1:8080/path'), 'HTTP URL allowed');
assert_true(!$webhook->is_allowed_webhook_url('ftp://example.com/x'), 'FTP rejected');
assert_true(!$webhook->is_allowed_webhook_url('not-a-url'), 'Invalid URL rejected');

$body = '{"event":"webhook.test"}';
$sig = $webhook->sign_body($body, 's3cret');
assert_true(strlen($sig) === 64 && ctype_xdigit($sig), 'HMAC-SHA256 hex signature');

$payload = $webhook->build_test_payload();
assert_true($payload['event'] === Qso_webhook::EVENT_TEST, 'Test payload event type');
assert_true($payload['callsign'] === 'G0VGS', 'Test payload callsign');

exit($failures > 0 ? 1 : 0);
