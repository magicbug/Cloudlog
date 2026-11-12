<?php
/**
 * Lightweight tests for merge default selection (no database).
 * Run: php application/tests/Qso_merge_field_chooser_test.php
 */

define('BASEPATH', true);
require dirname(__DIR__) . '/libraries/Qso_merge_field_chooser.php';

$chooser = new Qso_merge_field_chooser();

$qsos = [
    1 => [
        'COL_PRIMARY_KEY' => 1,
        'COL_CALL' => 'G0ABC',
        'COL_COMMENT' => '',
        'COL_QSL_RCVD' => 'N',
        'COL_LOTW_QSL_RCVD' => 'N',
        'COL_TIME_ON' => '2024-01-01 10:00:00',
    ],
    2 => [
        'COL_PRIMARY_KEY' => 2,
        'COL_CALL' => 'G0ABC',
        'COL_COMMENT' => 'Nice contact',
        'COL_QSL_RCVD' => 'Y',
        'COL_LOTW_QSL_RCVD' => 'Y',
        'COL_QSLRDATE' => '2024-02-01 12:00:00',
        'COL_TIME_ON' => '2024-01-01 10:05:00',
    ],
];

$keeper = $chooser->suggest_keeper_id($qsos);
assert($keeper === 2, 'Keeper should prefer confirmed QSO');

$choices = $chooser->default_choices($qsos);
assert($choices['COL_COMMENT'] === 2, 'Comment should come from non-empty QSO');
assert($choices['COL_QSL_RCVD'] === 2, 'QSL received should prefer confirmed');
assert($choices['COL_QSLRDATE'] === 2, 'QSL date should prefer confirmed source');

echo "Qso_merge_field_chooser_test: OK\n";
