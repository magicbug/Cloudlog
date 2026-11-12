<?php
/**
 * Lightweight tests for the SKCC award ladder (no database).
 * Run: php application/tests/Skcc_award_test.php
 */

define('BASEPATH', true);
require dirname(__DIR__) . '/libraries/Skcc_award.php';

$skcc = new Skcc_award();

assert($skcc->parse('14086') === array('number' => 14086, 'suffix' => ''));
assert($skcc->parse(' 14086c ') === array('number' => 14086, 'suffix' => 'C'));
assert($skcc->parse('14086Tx2') === array('number' => 14086, 'suffix' => 'T'));
assert($skcc->parse('14086S') === array('number' => 14086, 'suffix' => 'S'));
assert($skcc->parse('') === null);
assert($skcc->parse('ABC') === null);
assert($skcc->parse('0') === null);

assert($skcc->number_from_qso(array('skcc' => '', 'sig' => 'skcc', 'sig_info' => '123T')) === array('number' => 123, 'suffix' => 'T'));
assert($skcc->number_from_qso(array('skcc' => '', 'sig' => 'POTA', 'sig_info' => '123')) === null);

assert($skcc->is_excluded_call('K9SKC'));
assert($skcc->is_excluded_call('K3Y/0'));
assert(!$skcc->is_excluded_call('K3YY'));

assert($skcc->level(99, 100) === 0);
assert($skcc->level(1000, 100) === 10);
assert($skcc->level(1400, 100) === 10);
assert($skcc->level(1500, 100) === 15);
assert($skcc->next_threshold(1000, 100) === 1500);
assert($skcc->next_threshold(250, 50) === 300);
assert($skcc->level(2600, 200, 10) === 10);
assert($skcc->next_threshold(2000, 200, 10) === null);

$time = strtotime('2024-01-01 00:00:00');
$qsos = array();
$add = function ($number, $suffix, $call = null) use (&$qsos, &$time) {
	$time += 60;
	$qsos[] = array(
		'call' => $call ?: 'W' . $number,
		'skcc' => $number . $suffix,
		'time' => date('Y-m-d H:i:s', $time),
		'band' => '40m',
		'mode' => 'CW',
		'name' => 'Op',
	);
};

// Special-event calls do not count.
$add(99999, 'S', 'K3Y/4');
$add(99998, 'S', 'K9SKC');

// 100 bare members reach Centurion. The 100th QSO itself is not a Tribune contact.
for ($i = 1; $i <= 100; $i++) {
	$add($i, $i === 100 ? 'C' : '');
}
$result = $skcc->evaluate($qsos);
assert($result['centurion']['level'] === 1);
assert($result['centurion']['label'] === 'C');
assert($result['tribune']['unlocked'] === true);
assert($result['tribune']['count'] === 0);
assert($result['bare_members'] === 99);
assert(!isset($result['members'][99999]));

// A repeat member with a suffix now counts for Tribune; a bare number never does.
$add(1, 'C');
$add(5000, '');
$result = $skcc->evaluate($qsos);
assert($result['tribune']['count'] === 1);
assert($result['members'][1]['suffix'] === 'C');
assert($result['members'][1]['qsos'] === 2);
assert($result['centurion']['count'] === 101);

// Reach Tx8 with 399 more C/T/S members. Senator stays locked until then.
for ($i = 1; $i <= 398; $i++) {
	$add(10000 + $i, 'T');
}
$result = $skcc->evaluate($qsos);
assert($result['tribune']['count'] === 399);
assert($result['tribune']['label'] === 'Tx7');
assert($result['senator']['unlocked'] === false);

$add(10399, 'C');
$result = $skcc->evaluate($qsos);
assert($result['tribune']['count'] === 400);
assert($result['tribune']['label'] === 'Tx8');
assert($result['senator']['unlocked'] === true);
assert($result['senator']['count'] === 0);

// After Tx8, earlier Tribune members count again for Senator, but only with T or S.
for ($i = 1; $i <= 199; $i++) {
	$add(10000 + $i, 'T');
}
$add(20000, 'C');
$result = $skcc->evaluate($qsos);
assert($result['senator']['count'] === 199);
assert($result['senator']['remaining'] === 1);
$add(20001, 'S');
$result = $skcc->evaluate($qsos);
assert($result['senator']['label'] === 'S');
assert($result['senator']['achieved_at'] !== null);

$labels = array_map(function ($e) { return $e['label']; }, $result['earned']);
assert(in_array('C', $labels, true));
assert(in_array('Cx5', $labels, true));
assert(in_array('Tx8', $labels, true));
assert(in_array('S', $labels, true));

echo "Skcc_award_test: OK\n";
