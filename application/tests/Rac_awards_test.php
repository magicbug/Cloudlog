<?php
/*
	Run: php application/tests/Rac_awards_test.php
*/

define('BASEPATH', true);
require __DIR__ . '/../libraries/Rac_awards.php';

ini_set('zend.assertions', 1);
ini_set('assert.exception', 1);

$rac = new Rac_awards();

function qso($fields) {
	return array_merge(array(
		'call' => 'VE3ABC', 'time' => '2024-06-01 12:00:00', 'band' => '20m',
		'mode' => 'SSB', 'submode' => '', 'prop' => '', 'dxcc' => 1, 'state' => '', 'confirmed' => true,
	), $fields);
}

function variant($result, $key) {
	foreach ($result['variants'] as $v) {
		if ($v['key'] === $key) {
			return $v;
		}
	}
	return null;
}

// One station in each province and territory, with the state logged.
function all_provinces($fields = array()) {
	$calls = array(
		'NS' => 'VE1AA', 'QC' => 'VE2AA', 'ON' => 'VE3AA', 'MB' => 'VE4AA', 'SK' => 'VE5AA', 'AB' => 'VE6AA',
		'BC' => 'VE7AA', 'NB' => 'VE9AA', 'NL' => 'VO1AA', 'PE' => 'VY2AA', 'NT' => 'VE8AA', 'NU' => 'VY0AA', 'YT' => 'VY1AA',
	);
	$qsos = array();
	foreach ($calls as $state => $call) {
		$qsos[] = qso(array_merge(array('call' => $call, 'state' => $state), $fields));
	}
	return $qsos;
}

// Province
assert($rac->province(qso(array('call' => 'VE7ABC'))) === 'BC', 'VE7 is British Columbia from the prefix');
assert($rac->province(qso(array('call' => 'VE1ABC'))) === null, 'VE1 is not guessed');
assert($rac->province(qso(array('call' => 'VE1ABC', 'state' => 'NB'))) === 'NB', 'Logged state wins');
assert($rac->province(qso(array('call' => 'VE3ABC/7'))) === 'BC', 'A call-area numeral moves the station');
assert($rac->province(qso(array('call' => 'VO2ABC'))) === 'NL', 'Labrador is Newfoundland and Labrador');
assert($rac->province(qso(array('call' => 'N1ABC/VE2'))) === 'QC', 'A Canadian location prefix places a visitor');
assert($rac->province(qso(array('call' => 'VE1ABC/VO1'))) === 'NL', 'Location prefix wins over the home call');
assert($rac->province(qso(array('call' => 'W1ABC/VE1'))) === null, 'VE1 location prefix is not guessed');
assert($rac->province(qso(array('call' => 'VE2ABC', 'state' => 'PQ'))) === 'QC', 'Old PQ code is Quebec');
assert($rac->province(qso(array('call' => 'CG3ABC'))) === null, 'Special prefixes are not guessed');

// Modes
assert($rac->mode_group('SSB') === 'Phone');
assert($rac->mode_group('DIGITALVOICE') === 'Phone', 'Digital voice counts as Phone');
assert($rac->mode_group('MFSK', 'FT4') === 'Digital');
assert($rac->mode_group('FT8') === 'Digital');
assert($rac->mode_group('CW') === 'CW');
assert($rac->mode_group('SSTV') === null, 'Image modes count for Mixed only');

// Canadaward: complete
$r = $rac->evaluate('canadaward', all_provinces());
assert(variant($r, 'canadaward_mixed')['confirmed'] === 13);
assert(variant($r, 'canadaward_mixed')['level'] === 'Earned');
assert(variant($r, 'canadaward_phone')['level'] === 'Earned');
assert(variant($r, 'canadaward_cw')['confirmed'] === 0);
assert(variant($r, 'canadaward_digital')['level'] === null);
$band_rows = $r['tables'][1]['rows'];
assert($band_rows[0][0] === '20m' && $band_rows[0][1] === '13 / 13' && $band_rows[0][5] === 'Mixed, Phone', '20 m endorsement earned');

// Canadaward: one province missing, then each exclusion fails to fill it
$base = array_slice(all_provinces(), 0, 12);
assert(variant($rac->evaluate('canadaward', $base), 'canadaward_mixed')['confirmed'] === 12);
$yukon = array('call' => 'VY1AA', 'state' => 'YT');
$excluded = array(
	'VE0' => array('call' => 'VE0XYZ', 'state' => 'YT'),
	'repeater' => array_merge($yukon, array('prop' => 'RPT')),
	'Sable Island' => array_merge($yukon, array('dxcc' => 211)),
	'before 1 July 1977' => array_merge($yukon, array('time' => '1977-06-30 23:00:00')),
);
foreach ($excluded as $why => $fields) {
	$r = $rac->evaluate('canadaward', array_merge($base, array(qso($fields))));
	assert(variant($r, 'canadaward_mixed')['confirmed'] === 12, $why . ' does not count');
}
$r = $rac->evaluate('canadaward', array_merge($base, array(qso(array_merge($yukon, array('time' => '1977-07-01 00:00:00'))))));
assert(variant($r, 'canadaward_mixed')['confirmed'] === 13, '1 July 1977 counts');

// Unconfirmed contacts are worked, not confirmed
$r = $rac->evaluate('canadaward', all_provinces(array('confirmed' => false)));
assert(variant($r, 'canadaward_mixed')['worked'] === 13 && variant($r, 'canadaward_mixed')['confirmed'] === 0);

// Unplaced VE1 is listed in a note
$r = $rac->evaluate('canadaward', array(qso(array('call' => 'VE1ZZZ'))));
assert(variant($r, 'canadaward_mixed')['confirmed'] === 0);
assert(strpos(implode(' ', $r['notes']), 'VE1ZZZ') !== false, 'VE1 call is listed as unplaced');

// Satellite counts toward Basic and the Satellite endorsement, not toward a band
$r = $rac->evaluate('canadaward', all_provinces(array('prop' => 'SAT', 'band' => '2m', 'mode' => 'FM')));
assert(variant($r, 'canadaward_phone')['level'] === 'Earned');
$band_rows = $r['tables'][1]['rows'];
assert(count($band_rows) === 1 && $band_rows[0][0] === 'Satellite' && $band_rows[0][2] === '13 / 13');

// Digital
$r = $rac->evaluate('canadaward', all_provinces(array('mode' => 'FT8')));
assert(variant($r, 'canadaward_digital')['level'] === 'Earned');
assert(variant($r, 'canadaward_phone')['confirmed'] === 0);

// Century Club: 20 per province cap
$qsos = array();
for ($i = 0; $i < 25; $i++) {
	$qsos[] = qso(array('call' => 'VE3' . chr(65 + $i) . 'AA'));
}
$r = $rac->evaluate('century', $qsos);
assert(variant($r, 'century')['confirmed'] === 20, 'At most 20 from one province');
assert(variant($r, 'century')['worked'] === 20);

// Portable calls are the same station
$r = $rac->evaluate('century', array(qso(array('call' => 'VE3AAA')), qso(array('call' => 'VE3AAA/P')), qso(array('call' => 'VE3AAA', 'band' => '40m'))));
assert(variant($r, 'century')['confirmed'] === 1, '/P is the same station');

// Territories share one cap of 20
$qsos = array();
foreach (array('VE8', 'VY0', 'VY1') as $p) {
	for ($i = 0; $i < 10; $i++) {
		$qsos[] = qso(array('call' => $p . chr(65 + $i) . 'BB'));
	}
}
$r = $rac->evaluate('century', $qsos);
assert(variant($r, 'century')['confirmed'] === 20, 'Territories together are capped at 20');

// Earned: 10 from each province and one territory = 101, with all minimums met
$qsos = array();
foreach (array('NS' => 'VE1', 'QC' => 'VE2', 'ON' => 'VE3', 'MB' => 'VE4', 'SK' => 'VE5', 'AB' => 'VE6', 'BC' => 'VE7', 'NB' => 'VE9', 'NL' => 'VO1', 'PE' => 'VY2') as $state => $p) {
	for ($i = 0; $i < 10; $i++) {
		$qsos[] = qso(array('call' => $p . chr(65 + $i) . 'CC', 'state' => $state));
	}
}
$r = $rac->evaluate('century', $qsos);
assert(variant($r, 'century')['confirmed'] === 100 && variant($r, 'century')['level'] === null, 'Needs a territory');
$qsos[] = qso(array('call' => 'VY1ZZZ'));
$r = $rac->evaluate('century', $qsos);
assert(variant($r, 'century')['level'] === 'Earned');

// A rover worked in Ontario and Saskatchewan counts once, in Saskatchewan where it fills a gap
$r = $rac->evaluate('century', array(
	qso(array('call' => 'VE3AAA')),
	qso(array('call' => 'VA3ROV', 'state' => 'ON')),
	qso(array('call' => 'VA3ROV', 'state' => 'SK')),
));
assert(variant($r, 'century')['confirmed'] === 2, 'Rover is one station');
$rows = $r['tables'][0]['rows'];
assert($rows[2][0] === 'Ontario' && $rows[2][2] === 1, 'Ontario keeps one station');
assert($rows[4][0] === 'Saskatchewan' && $rows[4][2] === 1, 'Rover fills Saskatchewan');

// Century Club excludes contacts before 1946
$r = $rac->evaluate('century', array(qso(array('time' => '1945-12-31 12:00:00'))));
assert(variant($r, 'century')['worked'] === 0);

// Worked All RAC
assert($rac->warac_slot('VA1RAC') === 'NS' && $rac->warac_slot('VE1RAC/P') === 'NS');
assert($rac->warac_slot('VE3RHQ') === 'HQ');
assert($rac->warac_slot('VE3ABC') === null);
$r = $rac->evaluate('warac', array(qso(array('call' => 'VA1RAC')), qso(array('call' => 'VE1RAC'))));
assert(variant($r, 'warac_mixed')['confirmed'] === 1, 'VA1RAC and VE1RAC are one call area');
$r = $rac->evaluate('warac', array(qso(array('call' => 'VE3RAC')), qso(array('call' => 'VA3RHQ'))));
assert(variant($r, 'warac_mixed')['confirmed'] === 2, 'Headquarters is its own call area');
$r = $rac->evaluate('warac', array(qso(array('call' => 'VE3RAC', 'time' => '1998-06-30 12:00:00'))));
assert(variant($r, 'warac_mixed')['worked'] === 0, 'Before 1 July 1998 does not count');
$r = $rac->evaluate('warac', array(qso(array('call' => 'VE3RAC', 'prop' => 'RPT'))));
assert(variant($r, 'warac_mixed')['worked'] === 0, 'Repeater does not count');
$qsos = array();
foreach (array('VA1RAC', 'VA2RAC', 'VA3RAC', 'VA4RAC', 'VA5RAC', 'VA6RAC', 'VA7RAC', 'VE8RAC', 'VE9RAC', 'VO1RAC') as $call) {
	$qsos[] = qso(array('call' => $call, 'mode' => 'CW'));
}
$r = $rac->evaluate('warac', $qsos);
assert(variant($r, 'warac_cw')['level'] === 'Earned');
assert(variant($r, 'warac_mixed')['level'] === 'Earned');
assert(variant($r, 'warac_phone')['confirmed'] === 0);
assert(count($r['eligible']) === 10);

echo "All RAC award tests passed\n";
