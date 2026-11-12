<?php
/**
 * Lightweight sanity check for cron interval margin logic (no database).
 * Run: php scripts/cron_runner_interval_test.php
 */

function interval_margin($interval_seconds)
{
    $interval_seconds = (int) $interval_seconds;
    $five_percent = (int) floor($interval_seconds * 0.05);
    return min(300, max(60, $five_percent));
}

function should_skip($interval_seconds, $elapsed_since_finish)
{
    $margin = interval_margin($interval_seconds);
    $min_gap = $interval_seconds - $margin;
    return $elapsed_since_finish < $min_gap;
}

$failures = 0;

// Hourly job: 30 minutes after finish should skip; 59 minutes after finish should allow (margin 60 for 3600)
if (!should_skip(3600, 1800)) {
    echo "FAIL: expected skip within 30m of hourly finish\n";
    $failures++;
}
if (should_skip(3600, 3540)) {
    echo "FAIL: expected allow at 59m after hourly finish (jitter)\n";
    $failures++;
}

// Six-hour job: margin capped at 300
$margin = interval_margin(21600);
if ($margin !== 300) {
    echo "FAIL: expected margin 300 for 6h interval, got $margin\n";
    $failures++;
}

if ($failures === 0) {
    echo "OK: cron interval guard tests passed\n";
    exit(0);
}

exit(1);
