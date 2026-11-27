<?php
/*
	Run: php application/tests/Wpx_award_test.php
*/

define('BASEPATH', true);
require __DIR__ . '/../libraries/Wpx_award.php';

ini_set('zend.assertions', 1);
ini_set('assert.exception', 1);

$wpx = new Wpx_award();

// Published CQ prefix examples.
assert($wpx->prefix_from_call('WN5N/7') === 'WN7');
assert($wpx->prefix_from_call('LX/WN5N') === 'LX0');
assert($wpx->prefix_from_call('PA/N8BJQ') === 'PA0');
assert($wpx->prefix_from_call('J6/WN5N') === 'J6');
assert($wpx->prefix_from_call('KH6/WN5N') === 'KH6');
assert($wpx->prefix_from_call('XEFTJW') === 'XE0');
assert($wpx->prefix_from_call('RAEM') === 'RA0');
assert($wpx->prefix_from_call('HG19AAA') === 'HG19');
assert($wpx->prefix_from_call('LY1000A') === 'LY1000');
assert($wpx->prefix_from_call('N8BJQ') === 'N8');
assert($wpx->prefix_from_call('WD8AAA') === 'WD8');
assert($wpx->prefix_from_call('OE25AAA') === 'OE25');
assert($wpx->prefix_from_call('KL7RA/WK9') === 'WK9');
assert($wpx->prefix_from_call('OE/K5ZD') === 'OE0');

// Ignored portable and licence-class suffixes.
assert($wpx->prefix_from_call('G0ABC/P') === 'G0');
assert($wpx->prefix_from_call('G0ABC/M') === 'G0');
assert($wpx->prefix_from_call('G0ABC/MM') === 'G0');
assert($wpx->prefix_from_call('G0ABC/AM') === 'G0');
assert($wpx->prefix_from_call('KB1ABC/AE') === 'KB1');

// Digit-leading calls.
assert($wpx->prefix_from_call('9A5ABC') === '9A5');
assert($wpx->prefix_from_call('3DA0BW') === '3DA0');
assert($wpx->prefix_from_call('4X1AA') === '4X1');

// Stored ADIF PFX wins.
assert($wpx->prefix_of_qso(array('call' => 'WN5N/7', 'pfx' => 'WN5')) === 'WN5');
assert($wpx->prefix_of_qso(array('call' => 'WN5N/7', 'pfx' => '')) === 'WN7');

// Mode groups: AM and FM are Mixed only.
assert($wpx->mode_group('CW') === 'CW');
assert($wpx->mode_group('SSB') === 'SSB');
assert($wpx->mode_group('USB') === 'SSB');
assert($wpx->mode_group('FT8') === 'Digital');
assert($wpx->mode_group('RTTY') === 'Digital');
assert($wpx->mode_group('AM') === null);
assert($wpx->mode_group('FM') === null);

assert($wpx->band_allowed('20m'));
assert($wpx->band_allowed('6m'));
assert(!$wpx->band_allowed('2m'));
assert(!$wpx->band_allowed('70cm'));

function qso($fields) {
	return array_merge(array(
		'call' => 'W1AW', 'pfx' => '', 'band' => '20m', 'mode' => 'CW', 'submode' => '',
		'cont' => 'NA', 'confirmed' => true,
	), $fields);
}

// Build enough confirmed Mixed prefixes for the basic certificate.
$qsos = array();
for ($i = 1; $i <= 400; $i++) {
	$qsos[] = qso(array('call' => 'W' . $i . 'AA', 'pfx' => 'W' . $i, 'mode' => 'CW', 'cont' => 'NA'));
}
$result = $wpx->evaluate($qsos);
assert($result['certificates']['Mixed']['earned'] === true);
assert($result['certificates']['Mixed']['confirmed'] === 400);
assert($result['certificates']['CW']['earned'] === true);
assert($result['certificates']['CW']['confirmed'] === 400);
assert($result['certificates']['SSB']['earned'] === false);
assert($result['totals']['confirmed'] === 400);

// Unconfirmed prefixes count as worked only.
$qsos[] = qso(array('call' => 'G0ABC', 'pfx' => 'G0', 'confirmed' => false, 'cont' => 'EU'));
$result = $wpx->evaluate($qsos);
assert($result['totals']['worked'] === 401);
assert($result['totals']['confirmed'] === 400);

// Band filter drops VHF/UHF.
$qsos[] = qso(array('call' => 'F1ABC', 'pfx' => 'F1', 'band' => '2m', 'cont' => 'EU'));
$result = $wpx->evaluate($qsos);
assert($result['totals']['worked'] === 401);

// Continent endorsement counting.
$more = array();
for ($i = 1; $i <= 160; $i++) {
	$more[] = qso(array('call' => 'K' . $i . 'Z', 'pfx' => 'K' . $i, 'cont' => 'NA'));
}
$result = $wpx->evaluate($more);
assert($result['continent_endorsements']['NA']['earned'] === true);
assert($result['continent_endorsements']['EU']['earned'] === false);

// Band endorsement target for 160 m is 50.
$top = array();
for ($i = 1; $i <= 50; $i++) {
	$top[] = qso(array('call' => 'N' . $i . 'A', 'pfx' => 'N' . $i, 'band' => '160m'));
}
$result = $wpx->evaluate($top);
assert($result['band_endorsements']['160m']['earned'] === true);
assert($result['band_endorsements']['20m']['earned'] === false);

echo "Wpx_award_test: all assertions passed\n";
