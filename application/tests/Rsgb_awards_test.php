<?php
/*
	Run: php application/tests/Rsgb_awards_test.php
*/

define('BASEPATH', true);
require __DIR__ . '/../libraries/Rsgb_awards.php';

ini_set('zend.assertions', 1);
ini_set('assert.exception', 1);

$rsgb = new Rsgb_awards();

function qso($fields) {
	return array_merge(array(
		'call' => 'G4ABC', 'time' => '2024-06-01 12:00:00', 'band' => '20m', 'freq' => 14074000,
		'mode' => 'SSB', 'submode' => '', 'prop' => '', 'dxcc' => 223, 'cont' => 'EU', 'ituz' => 27,
		'grid' => '', 'vucc' => '', 'station_call' => 'G4XYZ', 'confirmed' => true,
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

// Licence class from the station callsign.
assert($rsgb->licence_class('M7ABC') === 'foundation', 'M7 is Foundation');
assert($rsgb->licence_class('M3ABC') === 'foundation', 'M3 is Foundation');
assert($rsgb->licence_class('MW6ABC/P') === 'foundation', 'Welsh Foundation, portable');
assert($rsgb->licence_class('EA/M7ABC') === 'foundation', 'CEPT prefix is ignored');
assert($rsgb->licence_class('2E0ABC') === 'intermediate', '2E0 is Intermediate');
assert($rsgb->licence_class('2M1ABC') === 'intermediate', 'Scottish 2M1 is Intermediate');
assert($rsgb->licence_class('M8ABC') === 'intermediate', 'M8 is Intermediate');
assert($rsgb->licence_class('M9ABC') === 'intermediate', 'M9 is Intermediate');
assert($rsgb->licence_class('MM9ABC') === 'intermediate', 'Scottish M9 is Intermediate');
assert($rsgb->licence_class('MW8ABC/P') === 'intermediate', 'Welsh M8, portable');
assert($rsgb->licence_class('M0ABC') === null, 'M0 is Full');
assert($rsgb->licence_class('G7ABC') === null, 'G7 is Full');
assert($rsgb->licence_class('GB0RSG') === null, 'Special event call');
assert($rsgb->licence_class('') === null, 'Blank call');

assert($rsgb->base_call('G4ABC/P') === 'G4ABC');
assert($rsgb->base_call('EA/G4ABC') === 'G4ABC');
assert($rsgb->mode_group('MFSK', 'FT4') === 'FT8/FT4');
assert($rsgb->mode_group('FT8', '') === 'FT8/FT4');
assert($rsgb->mode_group('SSB', 'USB') === 'SSB');
assert($rsgb->mode_group('PSK', 'PSK63') === 'PSK');

// The data snapshots.
assert(count($rsgb->iaru_societies()) === 102, 'IARU Region 1 list has 102 societies');
$dxcc_seen = array();
foreach ($rsgb->iaru_societies() as $society) {
	foreach ($society['dxcc'] as $dxcc) {
		assert(!isset($dxcc_seen[$dxcc]), 'Entity ' . $dxcc . ' is in one society only');
		$dxcc_seen[$dxcc] = true;
	}
}

// Foundation HF: four bands, 10 to 25 a band, two modes or more, 15 to 50 a mode.
$qsos = array();
foreach (array('80m', '40m', '20m', '15m') as $band) {
	for ($i = 0; $i < 20; $i++) {
		$qsos[] = qso(array('band' => $band, 'mode' => 'SSB', 'station_call' => 'M7ABC'));
	}
	for ($i = 0; $i < 10; $i++) {
		$qsos[] = qso(array('band' => $band, 'mode' => 'FT8', 'station_call' => 'M7ABC'));
	}
}
$result = $rsgb->evaluate('foundation', $qsos);
$hf = variant($result, 'foundation_hf');
// 30 a band but 25 is the cap, and SSB has 80 but 50 is the cap: SSB 50 + FT8 40 = 90.
assert($hf['confirmed'] === 90, 'Foundation HF respects the band and mode caps, got ' . $hf['confirmed']);
assert($hf['level'] === 'Silver');
assert($hf['next'] === 'Gold');

// QSOs from a Full call do not count toward Foundation.
foreach ($qsos as $k => $q) {
	$qsos[$k]['station_call'] = 'M0ABC';
}
$result = $rsgb->evaluate('foundation', $qsos);
assert(variant($result, 'foundation_hf')['confirmed'] === 0, 'Full callsign QSOs are left out');

// A single mode is not enough.
$qsos = array();
foreach (array('80m', '40m', '20m', '15m') as $band) {
	for ($i = 0; $i < 25; $i++) {
		$qsos[] = qso(array('band' => $band, 'mode' => 'SSB', 'station_call' => 'M6ABC'));
	}
}
assert(variant($rsgb->evaluate('foundation', $qsos), 'foundation_hf')['confirmed'] === 0, 'Foundation HF needs two modes');

// Lower bound: a mode used with fewer than 15 QSOs cannot count.
$counts = array('80m' => array('SSB' => 25, 'CW' => 5), '40m' => array('SSB' => 25), '20m' => array('SSB' => 25), '15m' => array('SSB' => 25, 'CW' => 9));
$spec = $rsgb->foundation_specs['hf'];
assert($rsgb->allocate($counts, $spec)['total'] === 0, 'CW with 14 QSOs cannot be the second mode');
$counts['20m']['CW'] = 10;
$best = $rsgb->allocate($counts, $spec);
assert($best['total'] === 74, 'SSB capped at 50 plus all 24 CW, got ' . $best['total']);
assert($best['modes']['SSB'] === 50 && $best['modes']['CW'] === 24);

// Foundation VHF leaves out repeater contacts.
$qsos = array();
foreach (array('6m', '2m', '70cm') as $band) {
	for ($i = 0; $i < 15; $i++) {
		$qsos[] = qso(array('band' => $band, 'mode' => 'FM', 'station_call' => 'M7ABC'));
		$qsos[] = qso(array('band' => $band, 'mode' => 'SSB', 'station_call' => 'M7ABC'));
		$qsos[] = qso(array('band' => $band, 'mode' => 'FM', 'prop' => 'RPT', 'station_call' => 'M7ABC'));
	}
}
$vhf = variant($rsgb->evaluate('foundation', $qsos), 'foundation_vhf');
assert($vhf['confirmed'] === 60, 'Foundation VHF: FM and SSB capped at 30 each, got ' . $vhf['confirmed']);

// Intermediate 100: one amateur counts once.
$qsos = array();
foreach (array('80m', '40m', '30m', '17m') as $b => $band) {
	for ($i = 0; $i < 25; $i++) {
		$qsos[] = qso(array('call' => 'G' . $b . 'A' . chr(65 + $i) . 'A', 'band' => $band, 'mode' => 'CW', 'station_call' => '2E0ABC'));
	}
}
$result = $rsgb->evaluate('intermediate', $qsos);
assert(variant($result, 'intermediate')['level'] === 'Intermediate 100');
$qsos[] = qso(array('call' => 'G0AAA/P', 'band' => '80m', 'mode' => 'CW', 'station_call' => 'M8ABC'));
$qsos[] = qso(array('call' => 'G0AAA', 'band' => '40m', 'mode' => 'CW', 'station_call' => 'M8ABC'));
$v = variant($rsgb->evaluate('intermediate', $qsos), 'intermediate');
assert($v['confirmed'] === 100, 'A repeat amateur adds nothing once every band is full');
$qsos[] = qso(array('call' => 'G9ZZZ', 'band' => '80m', 'mode' => 'FM', 'station_call' => '2E0ABC'));
assert(variant($rsgb->evaluate('intermediate', $qsos), 'intermediate')['worked'] === 100, 'FM is not an Intermediate 100 mode');

// IARU Region 1: the UK nations are one society, and eQSL-only work stays worked.
$qsos = array(
	qso(array('dxcc' => 223, 'band' => '20m')),
	qso(array('dxcc' => 279, 'band' => '40m')),
	qso(array('dxcc' => 230, 'band' => '30m')),
	qso(array('dxcc' => 21, 'band' => '30m', 'confirmed' => false)),
	qso(array('dxcc' => 291, 'band' => '20m')),
	qso(array('dxcc' => 263, 'band' => '10m', 'time' => '1982-01-01 00:00:00')),
);
$result = $rsgb->evaluate('iaru', $qsos);
$all = variant($result, 'iaru_all');
assert($all['confirmed'] === 3, 'UK, Germany, Netherlands confirmed');
assert($all['worked'] === 4, 'Spain worked');
assert(variant($result, 'iaru_30m')['confirmed'] === 1);
assert(variant($result, 'iaru_warc')['worked'] === 2);
assert(variant($result, 'iaru_10m')['worked'] === 0, '10 m before July 1983 does not count for the 10 m award');

// Commonwealth call areas.
assert($rsgb->call_area(qso(array('dxcc' => 1, 'call' => 'VE7ABC')))['area'] === 'VE7');
assert($rsgb->call_area(qso(array('dxcc' => 1, 'call' => 'VA3XYZ')))['area'] === 'VE3');
assert($rsgb->call_area(qso(array('dxcc' => 1, 'call' => 'VO1AA')))['area'] === 'VO1');
assert($rsgb->call_area(qso(array('dxcc' => 1, 'call' => 'VE3ABC/7')))['area'] === 'VE7', 'Portable district numeral');
assert($rsgb->call_area(qso(array('dxcc' => 1, 'call' => 'G4ABC/VE2')))['area'] === 'VE2', 'Reciprocal prefix');
assert($rsgb->call_area(qso(array('dxcc' => 150, 'call' => 'VK4ABC')))['area'] === 'VK4');
assert($rsgb->call_area(qso(array('dxcc' => 170, 'call' => 'ZL2ABC')))['area'] === 'ZL2');
assert($rsgb->call_area(qso(array('dxcc' => 170, 'call' => 'G4ABC/ZL')))['area'] === 'ZL0', 'Reciprocal ZL');
assert($rsgb->call_area(qso(array('dxcc' => 462, 'call' => 'ZS6ABC')))['area'] === 'ZS6');
assert($rsgb->call_area(qso(array('dxcc' => 215, 'call' => 'P3A')))['area'] === '5B', 'P3 is Cyprus');
assert($rsgb->call_area(qso(array('dxcc' => 13, 'call' => 'VP8ABC')))['area'] === 'VP8/A');
assert(!empty($rsgb->call_area(qso(array('dxcc' => 13, 'call' => 'KC4AAA')))['unmatched']), 'US Antarctic station');
assert($rsgb->call_area(qso(array('dxcc' => 230, 'call' => 'DL1ABC'))) === null, 'Germany is not Commonwealth');

$qsos = array(
	qso(array('dxcc' => 1, 'call' => 'VE7ABC', 'band' => '20m')),
	qso(array('dxcc' => 1, 'call' => 'VE3ABC', 'band' => '17m')),
	qso(array('dxcc' => 223, 'call' => 'G4ABC', 'band' => '40m', 'confirmed' => false)),
	qso(array('dxcc' => 13, 'call' => 'KC4AAA', 'band' => '20m')),
);
$result = $rsgb->evaluate('cca', $qsos);
assert(variant($result, 'cca_5')['confirmed'] === 1, 'VE7 on 20 m');
assert(variant($result, 'cca_5')['worked'] === 2);
assert(variant($result, 'cca_9')['confirmed'] === 2, '17 m counts for the nine bands');
assert(strpos(implode(' ', $result['notes']), 'KC4AAA') !== false, 'Unmatched calls are listed');

// ITU zones: per-band minimum and the WARC gate.
$qsos = array();
for ($zone = 1; $zone <= 70; $zone++) {
	$qsos[] = qso(array('ituz' => $zone, 'band' => '20m'));
}
for ($zone = 1; $zone <= 30; $zone++) {
	$qsos[] = qso(array('ituz' => $zone, 'band' => '30m'));
}
$result = $rsgb->evaluate('ituz', $qsos);
assert(variant($result, 'ituz_classic')['level'] === 'Classic');
assert(variant($result, 'ituz_5band')['level'] === null, '5 Band needs 30 on each band');
assert(variant($result, 'ituz_warc')['locked'] === false, 'Classic unlocks WARC');
$result = $rsgb->evaluate('ituz', array_slice($qsos, 1));
assert(variant($result, 'ituz_warc')['locked'] === true, '69 zones keeps WARC locked');
$result = $rsgb->evaluate('ituz', array(qso(array('ituz' => '')), qso(array('ituz' => 28))));
assert(variant($result, 'ituz_classic')['worked'] === 1);
assert(strpos(implode(' ', $result['notes']), '1 HF contact has no ITU zone') !== false, 'Contacts without a zone are reported');

// 50 MHz: date cutoff, four-character squares.
$qsos = array(
	qso(array('band' => '6m', 'grid' => 'IO91wm', 'dxcc' => 223, 'cont' => 'EU')),
	qso(array('band' => '6m', 'grid' => 'IO91', 'dxcc' => 223, 'cont' => 'EU')),
	qso(array('band' => '6m', 'grid' => 'JO01', 'dxcc' => 230, 'cont' => 'EU', 'time' => '1987-05-31 23:59:00')),
	qso(array('band' => '6m', 'vucc' => 'FN20,FN21', 'dxcc' => 291, 'cont' => 'NA')),
);
$result = $rsgb->evaluate('sixm', $qsos);
assert(variant($result, 'six_squares')['confirmed'] === 3, 'IO91, FN20, FN21');
assert(variant($result, 'six_countries')['confirmed'] === 2, 'Before 1 June 1987 is left out');

// VHF squares and countries: a level needs both numbers.
$qsos = array();
for ($i = 0; $i < 40; $i++) {
	$qsos[] = qso(array('band' => '2m', 'grid' => 'IO' . str_pad($i, 2, '0', STR_PAD_LEFT), 'dxcc' => 223));
}
$v = variant($rsgb->evaluate('vhf', $qsos), 'vhf_2m');
assert($v['confirmed'] === 40 && $v['level'] === null, '40 squares and one country is not 40/10');
for ($i = 1; $i <= 9; $i++) {
	$qsos[] = qso(array('band' => '2m', 'grid' => 'IO00', 'dxcc' => 200 + $i));
}
assert(variant($rsgb->evaluate('vhf', $qsos), 'vhf_2m')['level'] === '144MHz 40/10');

// V/UHF Activity: simplex only, a different station each band.
$qsos = array();
for ($i = 0; $i < 30; $i++) {
	$qsos[] = qso(array('call' => 'G' . $i . 'AAA', 'band' => '2m', 'mode' => 'FM', 'freq' => 145500000));
}
for ($i = 0; $i < 20; $i++) {
	$qsos[] = qso(array('call' => 'G' . $i . 'AAA', 'band' => '70cm', 'mode' => 'SSB', 'freq' => 432200000));
}
$qsos[] = qso(array('call' => 'G0AAA/P', 'band' => '2m', 'mode' => 'FM', 'freq' => 145500000));
$result = $rsgb->evaluate('activity', $qsos);
assert(variant($result, 'activity')['level'] === 'Bronze');
assert(variant($result, 'activity')['confirmed'] === 50, 'The repeat station adds nothing');
$qsos[] = qso(array('call' => 'G9ZZZ', 'band' => '2m', 'mode' => 'FM', 'prop' => 'RPT', 'freq' => 145600000));
$qsos[] = qso(array('call' => 'G9ZZY', 'band' => '2m', 'mode' => 'FM', 'freq' => 0));
$qsos[] = qso(array('call' => 'G9ZZX', 'band' => '2m', 'mode' => 'FM', 'time' => '2021-05-31 12:00:00'));
$result = $rsgb->evaluate('activity', $qsos);
assert(variant($result, 'activity')['confirmed'] === 50, 'Repeater, no frequency, and before June 2021 do not count');
assert(count($result['notes']) === 3);

echo "All RSGB award tests passed\n";
