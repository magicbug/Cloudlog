#!/usr/bin/env php
<?php

define('BASEPATH', true);
require_once __DIR__ . '/../application/helpers/client_ref_helper.php';

$valid = 'a1b2c3d4-e5f6-4789-a012-3456789abcde';
$invalid = 'not-a-uuid';

$passed = 0;
$failed = 0;

function assert_true($label, $condition): void
{
	global $passed, $failed;
	if ($condition) {
		$passed++;
		return;
	}
	$failed++;
	fwrite(STDERR, "FAIL: {$label}\n");
}

assert_true('valid uuid accepted', client_ref_is_valid($valid) === true);
assert_true('invalid uuid rejected', client_ref_is_valid($invalid) === false);
assert_true('normalise lowers case', client_ref_normalize($valid) === strtolower($valid));
assert_true('normalise invalid is null', client_ref_normalize($invalid) === null);

if ($failed > 0) {
	fwrite(STDERR, "Smoke test failed ({$failed} failures, {$passed} passed).\n");
	exit(1);
}

echo "Smoke test passed ({$passed} assertions).\n";
exit(0);
