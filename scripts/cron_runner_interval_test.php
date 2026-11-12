<?php
/**
 * Lightweight sanity check for cron interval guard policy (no database).
 * Run: php scripts/cron_runner_interval_test.php
 */

function interval_margin($interval_seconds)
{
    $interval_seconds = (int) $interval_seconds;
    $five_percent = (int) floor($interval_seconds * 0.05);
    return min(300, max(60, $five_percent));
}

function should_skip_by_interval($interval_seconds, $elapsed_since_finish)
{
    $margin = interval_margin($interval_seconds);
    $min_gap = $interval_seconds - $margin;
    return $elapsed_since_finish < $min_gap;
}

/**
 * Mirrors Cron_runner::should_apply_interval_guard() behaviour.
 */
function should_apply_interval_guard($scheduler_enabled, $source)
{
    if ($source === 'scheduler' || $source === 'legacy') {
        return $scheduler_enabled;
    }
    return false;
}

$failures = 0;

// Policy: legacy with scheduler off never uses interval guard
if (should_apply_interval_guard(false, 'legacy')) {
    echo "FAIL: legacy should not use interval guard when scheduler is off\n";
    $failures++;
}

// Policy: legacy with scheduler on uses interval guard
if (!should_apply_interval_guard(true, 'legacy')) {
    echo "FAIL: legacy should use interval guard when scheduler is on\n";
    $failures++;
}

// Scheduler ticks use interval guard only when scheduler is enabled
if (!should_apply_interval_guard(true, 'scheduler')) {
    echo "FAIL: scheduler source should use interval guard when enabled\n";
    $failures++;
}

// Interval math when guard is active (hourly job)
if (!should_skip_by_interval(3600, 1800)) {
    echo "FAIL: expected interval skip within 30m of hourly finish\n";
    $failures++;
}
if (should_skip_by_interval(3600, 3540)) {
    echo "FAIL: expected allow at 59m after hourly finish (jitter margin)\n";
    $failures++;
}

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
