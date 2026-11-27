<?php
/*
	Run: php application/tests/Wae_award_test.php
*/

define('BASEPATH', true);
require __DIR__ . '/../libraries/Wae_award.php';

ini_set('zend.assertions', 1);
ini_set('assert.exception', 1);

$wae = new Wae_award();

assert($wae->current_count() === 73);

function qso($fields) {
	return array_merge(array(
		'call' => 'W1AW', 'dxcc' => 0, 'time' => '2024-06-01 12:00:00',
		'band' => '20m', 'mode' => 'CW', 'submode' => '', 'confirmed' => true,
	), $fields);
}

// Simple DXCC mapping.
assert($wae->country_of_qso(qso(array('call' => 'DL1AA', 'dxcc' => 230))) === 'DL');
assert($wae->country_of_qso(qso(array('call' => 'F6ABC', 'dxcc' => 227))) === 'F');
assert($wae->country_of_qso(qso(array('call' => 'IS0AAA', 'dxcc' => 225))) === 'IS');

// Sicily vs Italy vs African Italy.
assert($wae->country_of_qso(qso(array('call' => 'IT9AAA', 'dxcc' => 248))) === 'IT');
assert($wae->country_of_qso(qso(array('call' => 'IZ9AAA', 'dxcc' => 248))) === 'IT');
assert($wae->country_of_qso(qso(array('call' => 'IK0AAA', 'dxcc' => 248))) === 'I');
assert($wae->country_of_qso(qso(array('call' => 'IG9AAA', 'dxcc' => 248))) === null);

// Shetland vs Scotland.
assert($wae->country_of_qso(qso(array('call' => 'GM3ABC/S', 'dxcc' => 279))) === 'GM/S');
assert($wae->country_of_qso(qso(array('call' => 'GS3ABC', 'dxcc' => 279))) === 'GM/S');
assert($wae->country_of_qso(qso(array('call' => 'GM3ABC', 'dxcc' => 279))) === 'GM');

// Bear Island vs Spitsbergen.
assert($wae->country_of_qso(qso(array('call' => 'JW5ABC/B', 'dxcc' => 259))) === 'JW/B');
assert($wae->country_of_qso(qso(array('call' => 'JW5ABC', 'dxcc' => 259))) === 'JW');

// European Turkey only.
assert($wae->country_of_qso(qso(array('call' => 'TA1ABC', 'dxcc' => 390))) === 'TA1');
assert($wae->country_of_qso(qso(array('call' => 'TA2ABC', 'dxcc' => 390))) === null);

// 4U1V and 4U1ITU.
assert($wae->country_of_qso(qso(array('call' => '4U1V', 'dxcc' => 206))) === '4U1V');
assert($wae->country_of_qso(qso(array('call' => '4U1ITU', 'dxcc' => 289))) === '4U1I');

// Non-WAE European DXCC entities.
assert($wae->country_of_qso(qso(array('call' => 'EA8AA', 'dxcc' => 29))) === null); // Canaries
assert($wae->country_of_qso(qso(array('call' => 'CT3AA', 'dxcc' => 256))) === null); // Madeira

// Date windows.
assert($wae->country_of_qso(qso(array('call' => 'Z61AA', 'dxcc' => 522, 'time' => '2007-01-01 00:00:00'))) === null);
assert($wae->country_of_qso(qso(array('call' => 'Z61AA', 'dxcc' => 522, 'time' => '2008-02-17 00:00:00'))) === 'Z6');
assert($wae->country_of_qso(qso(array('call' => 'Y21AA', 'dxcc' => 229, 'time' => '1980-01-01 00:00:00'))) === 'Y2');
assert($wae->country_of_qso(qso(array('call' => 'OK1AA', 'dxcc' => 218, 'time' => '1990-01-01 00:00:00'))) === 'OK-old');
assert($wae->country_of_qso(qso(array('call' => 'OK1AA', 'dxcc' => 503, 'time' => '2000-01-01 00:00:00'))) === 'OK');

// Band-point scoring and classes.
$qsos = array();
$codes = array_keys($wae->countries);
for ($i = 0; $i < 40; $i++) {
	$code = $codes[$i];
	$info = $wae->countries[$code];
	$dxcc = $info['dxcc'] !== null ? $info['dxcc'] : 248;
	$call = $code === 'IT' ? 'IT9AAA' : ($code === 'TA1' ? 'TA1AAA' : ($code === '4U1V' ? '4U1V' : ($code === 'GM/S' ? 'GS3AAA' : ($code === 'JW/B' ? 'JW5AAA/B' : 'W1AW'))));
	foreach (array('80m', '40m', '20m') as $band) {
		$qsos[] = qso(array('call' => $call, 'dxcc' => $dxcc, 'band' => $band));
	}
}
$result = $wae->evaluate($qsos);
assert($result['classes']['confirmed']['III']['earned'] === true);
assert($result['classes']['confirmed']['III']['countries'] === 40);
assert($result['classes']['confirmed']['III']['points'] === 120);
assert($result['classes']['confirmed']['II']['earned'] === false);

// Cap at five bands.
$multi = array();
foreach (array('160m', '80m', '40m', '20m', '15m', '10m', '6m') as $band) {
	$multi[] = qso(array('call' => 'DL1AA', 'dxcc' => 230, 'band' => $band));
}
$result = $wae->evaluate($multi);
$row = null;
foreach ($result['rows'] as $r) {
	if ($r['code'] === 'DL') {
		$row = $r;
		break;
	}
}
assert($row !== null);
assert($row['confirmed_points'] === 5);
assert(count($row['confirmed_bands']) === 7);

echo "Wae_award_test: all assertions passed\n";
