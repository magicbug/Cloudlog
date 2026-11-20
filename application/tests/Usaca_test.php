<?php
/*
	Run: php application/tests/Usaca_test.php
*/

define('BASEPATH', true);

$config = array();
require __DIR__ . '/../config/cloudlog_data_lists.php';
require __DIR__ . '/../libraries/Usaca.php';

ini_set('zend.assertions', 1);
ini_set('assert.exception', 1);

$usaca = new Usaca(array(
	'base_path' => dirname(__DIR__, 2) . '/',
	'states' => $config['usa_states_list'],
));

assert($usaca->total() === Usaca::TOTAL_COUNTIES, 'Total award counties');
assert(Usaca::key('St. Johns County') === 'saintjohns', 'Normalize St./County');
assert(Usaca::key('Oglala Lakota') === 'oglalalakota', 'Normalize renamed county');

$barrow = $usaca->map('GA', 'Barrow');
assert($barrow['type'] === 'county' && $barrow['name'] === 'Barrow', 'Valid Georgia county');

$shannon = $usaca->map('SD', 'Shannon');
assert($shannon['type'] === 'county' && $shannon['name'] === 'Oglala Lakota', 'Renamed Shannon -> Oglala Lakota');

$nome = $usaca->map('AK', 'Nome');
assert($nome['type'] === 'county' && $nome['name'] === 'Second Judicial District', 'Alaska borough maps to district');

$unknown = $usaca->map('GA', 'NotARealCounty');
assert($unknown['type'] === 'unknown', 'Unknown county type');

$ga = $usaca->counties_for_state('GA');
assert(count($ga) > 100, 'Georgia has many counties');
assert(in_array('Barrow', $ga, true), 'Barrow listed for Georgia');

$suggestions = $usaca->suggest('GA', 'Barow');
assert(in_array('Barrow', $suggestions, true), 'Typo Barow suggests Barrow');

$no_match = $usaca->suggest('GA', 'zzzzzzzzzz', 5, 2);
assert($no_match === array(), 'Far miss yields no suggestion');

$empty_state = $usaca->counties_for_state('XX');
assert($empty_state === array(), 'Unknown state has no counties');

echo "Usaca_test: all assertions passed\n";
