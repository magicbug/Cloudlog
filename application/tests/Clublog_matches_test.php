<?php
/*
	Run: php application/tests/Clublog_matches_test.php
*/

define('BASEPATH', true);
require __DIR__ . '/../libraries/Clublog_matches.php';

ini_set('zend.assertions', 1);
ini_set('assert.exception', 1);

$cl = new Clublog_matches();

// Replies
$r = $cl->parse('[["G0LGJ/M","223","2005-07-16 08:00:00","20","CW"],["ea8xyz","29","2024-01-02 10:11:12","70",false],["","1","x","20","CW"]]');
assert($r['error'] === null);
assert(count($r['matches']) === 2, 'Rows without a callsign or a time are skipped');
assert($r['matches'][0] === array('call' => 'G0LGJ/M', 'time' => '2005-07-16 08:00:00', 'band' => '20'));
assert($r['matches'][1]['call'] === 'EA8XYZ');
assert($cl->parse('[]') === array('error' => null, 'matches' => array()));
assert($cl->parse('Login rejected')['error'] === 'login');
assert($cl->parse('Invalid callsign')['error'] === 'callsign');
assert($cl->parse('<html><body>403 Forbidden</body></html>')['error'] === 'html');
assert($cl->parse('')['error'] === 'json');
assert($cl->parse('not json')['error'] === 'json');

// Bands
assert($cl->band_number('20m') === '20');
assert($cl->band_number('70cm') === '70');
assert($cl->band_number('2m') === '2');
assert($cl->band_number('23CM') === '23');

// Picking the QSO
$match = array('call' => 'G4ABC', 'time' => '2024-06-01 12:00:00', 'band' => '20');
$candidates = array(
	array('id' => 1, 'time' => '2024-06-01 11:50:00', 'band' => '20m'),
	array('id' => 2, 'time' => '2024-06-01 12:03:00', 'band' => '20m'),
	array('id' => 3, 'time' => '2024-06-01 12:00:00', 'band' => '40m'),
	array('id' => 4, 'time' => '2024-06-01 12:20:00', 'band' => '20m'),
);
assert($cl->pick($match, $candidates) === 2, 'The closest QSO on the same band wins');
assert($cl->pick($match, array($candidates[2], $candidates[3])) === null, 'Another band or more than 15 minutes away is not a match');
assert($cl->pick(array('call' => 'G4ABC', 'time' => '2024-06-01 12:00', 'band' => '70'), array(array('id' => 9, 'time' => '2024-06-01 12:00:30', 'band' => '70cm'))) === 9);
assert($cl->pick(array('call' => 'G4ABC', 'time' => '2024-06-01 12:00:00', 'band' => ''), array($candidates[2])) === 3, 'No band from Club Log matches on time');

// URL
$url = $cl->url('KEY', 'me@example.com', 'p&w', 'm0abc', '2024-03-09 10:00:00');
assert(strpos($url, 'https://clublog.org/getmatches.php?') === 0);
assert(strpos($url, 'callsign=M0ABC') !== false);
assert(strpos($url, 'password=p%26w') !== false);
assert(strpos($url, 'startyear=2024&startmonth=03&startday=09') !== false);
assert(strpos($cl->url('KEY', 'a@b.c', 'p', 'M0ABC'), 'startyear') === false);

echo "All Club Log match tests passed\n";
