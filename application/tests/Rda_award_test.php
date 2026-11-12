<?php
/*
	Run: php application/tests/Rda_award_test.php
*/

define('BASEPATH', true);
require __DIR__ . '/../libraries/Rda_award.php';

ini_set('zend.assertions', 1);
ini_set('assert.exception', 1);

$rda = new Rda_award();

assert($rda->normalize('TB-02') === 'TB-02', 'Hyphenated code');
assert($rda->normalize('tb02') === 'TB-02', 'Lower case without hyphen');
assert($rda->normalize('TB 02') === 'TB-02', 'Space instead of hyphen');
assert($rda->normalize(' tb-2 ') === 'TB-02', 'Single digit is padded');
assert($rda->normalize('MA,Middlesex') === null, 'US county is not an RDA code');
assert($rda->normalize('TB-00') === null, 'District 00 does not exist');
assert($rda->normalize('') === null, 'Empty county');
assert($rda->normalize('TB-02,TB-03') === null, 'One code per QSO');

$text = <<<TXT
 "RUSSIAN DISTRICTS AWARD LIST"
 (Last update 01.01.2026)

 Agunsky-Burjatsky AO (AB) UA8V

AB-01 deleted ZK-37
AB-02 deleted ***

 Altaysky Kraj (AL) UA9Y

AL-01 ZHELEZNODOROZHNY DISTRICT of BARNAUL
AL-79 ZATO SIBIRSKY since 01.02.2016
BO-29 ALEKSEEVKA NEW RDA since 01.01.2025

 Donetsk People`s Republic (DO) UA6O
 Districts of the Donetsk People`s Republic
 (Valid since 01.01.2026)

DO-01 BUDYONNOVSKY DISTRICT OF DONETSK

 Belgorodskaya oblast (BO) UA3Z

BO-29 ALEKSEEVKA NEW RDA since 01.01.2025

 Tambovskaya oblast (TB) UA3R

TB-02 OKTYABRSKY DISTRICT of TAMBOV
TXT;

$ref = $rda->parse_list($text);
$by_code = array();
foreach ($ref['districts'] as $d) {
	$by_code[$d['code']] = $d;
}

assert($ref['updated'] === '2026-01-01', 'List date');
assert(count($ref['oblasts']) === 5, 'Five oblast headers');
assert($ref['oblasts']['AL']['prefix'] === 'UA9Y', 'Oblast prefix');
assert($ref['oblasts']['DO']['name'] === "Donetsk People's Republic", 'Backtick becomes apostrophe');
assert($by_code['AB-01']['deleted'] === true && $by_code['AB-01']['replaced_by'] === 'ZK-37', 'Deleted with replacement');
assert($by_code['AB-02']['deleted'] === true && $by_code['AB-02']['replaced_by'] === '', 'Deleted without replacement');
assert($by_code['AL-79']['valid_from'] === '2016-02-01' && $by_code['AL-79']['name'] === 'ZATO SIBIRSKY', 'Since date split from name');
assert($by_code['BO-29']['oblast'] === 'BO' && $by_code['BO-29']['name'] === 'ALEKSEEVKA', 'A code under the wrong header is skipped, NEW RDA trimmed');
assert($by_code['DO-01']['valid_from'] === '2026-01-01', 'Oblast-wide start date');
assert($rda->current_count($ref) === 5, 'Deleted codes are not current');
assert($ref['oblasts']['AB']['total'] === 0 && $ref['oblasts']['AL']['total'] === 2, 'Oblast totals count current districts');

$qsos = array(
	array('call' => 'R3RA', 'cnty' => 'TB-02', 'time' => '2024-05-01 10:00:00', 'band' => '20m', 'mode' => 'CW', 'confirmed' => false),
	array('call' => 'R3RB', 'cnty' => 'tb02', 'time' => '2024-06-01 10:00:00', 'band' => '40m', 'mode' => 'SSB', 'confirmed' => true),
	array('call' => 'R9YA', 'cnty' => 'AL-79', 'time' => '2015-01-01 10:00:00', 'band' => '20m', 'mode' => 'CW', 'confirmed' => true),
	array('call' => 'R9YB', 'cnty' => 'AL-79', 'time' => '2016-03-01 10:00:00', 'band' => '20m', 'mode' => 'CW', 'confirmed' => false),
	array('call' => 'R8VA', 'cnty' => 'AB-01', 'time' => '2005-01-01 10:00:00', 'band' => '20m', 'mode' => 'CW', 'confirmed' => true),
	array('call' => 'R0XX', 'cnty' => 'XX-99', 'time' => '2020-01-01 10:00:00', 'band' => '20m', 'mode' => 'CW', 'confirmed' => true),
	array('call' => 'W1AW', 'cnty' => 'MA,Middlesex', 'time' => '2020-01-01 10:00:00', 'band' => '20m', 'mode' => 'CW', 'confirmed' => true),
	array('call' => 'R6OA', 'cnty' => 'DO-01', 'time' => '2025-12-31 23:00:00', 'band' => '20m', 'mode' => 'CW', 'confirmed' => true),
);
$result = $rda->evaluate($qsos, $ref);

assert($result['worked'] === 2, 'TB-02 and AL-79 are worked once each');
assert($result['confirmed'] === 1, 'Only TB-02 is confirmed after its start date');
assert($result['districts']['TB-02']['qsos'] === 2 && $result['districts']['TB-02']['call'] === 'R3RB', 'Confirmed QSO is shown for the district');
assert($result['districts']['AL-79']['confirmed'] === false, 'A confirmation before the start date does not count');
assert($result['too_early'] === 2, 'AL-79 in 2015 and DO-01 in 2025 are before their start dates');
assert(count($result['deleted']) === 1 && $result['deleted'][0]['replaced_by'] === 'ZK-37', 'Deleted code is listed');
assert(count($result['unknown']) === 1 && $result['unknown'][0]['code'] === 'XX-99', 'Unknown code is listed');
assert($result['oblasts']['TB']['confirmed'] === 1 && $result['oblasts']['AL']['worked'] === 1, 'Oblast progress');
assert(!isset($result['oblasts']['AB']), 'Oblast with only deleted codes has no progress row');

$levels = $rda->level_rows(260, 120, 2784);
assert($levels[0]['name'] === 'RDA-100' && $levels[0]['confirmed'] === true, 'RDA-100 reached on confirmed');
assert($levels[1]['worked'] === true && $levels[1]['confirmed'] === false && $levels[1]['remaining'] === 130, 'RDA-250 worked, 130 to confirm');
assert($levels[6]['target'] === 2500, 'Honor Roll is 2500');
assert($levels[7]['name'] === 'Honor Roll #1' && $levels[7]['target'] === 2784, 'Honor Roll #1 is every current district');

$shipped = $rda->load_reference(__DIR__ . '/../../assets/json/rda.json');
$codes = array_column($shipped['districts'], 'code');
assert(count($codes) === count(array_unique($codes)), 'Shipped list has unique codes');
assert($rda->current_count($shipped) > Rda_award::MIN_CURRENT_DISTRICTS, 'Shipped list has more than 2000 current districts');
foreach ($shipped['districts'] as $d) {
	assert($rda->normalize($d['code']) === $d['code'], 'Shipped code is normalised: ' . $d['code']);
	assert(isset($shipped['oblasts'][$d['oblast']]), 'Shipped district has an oblast: ' . $d['code']);
}

$shapes = json_decode(file_get_contents(__DIR__ . '/../../assets/json/rda_oblasts.geojson'), true);
foreach ($shapes['features'] as $f) {
	assert(isset($shipped['oblasts'][$f['properties']['rda']]), 'Map oblast is on the list: ' . $f['properties']['rda']);
}

echo "Rda_award tests passed\n";
