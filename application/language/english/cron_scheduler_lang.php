<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$lang['cron_scheduler_title'] = 'Background job scheduler';
$lang['cron_scheduler_intro'] = 'Cloudlog can run integration and data update tasks from one crontab entry. The scheduler is off by default; legacy URLs keep working unchanged.';
$lang['cron_scheduler_enable_label'] = 'Enable unified scheduler';
$lang['cron_scheduler_key_label'] = 'Web trigger secret key';
$lang['cron_scheduler_regenerate_key'] = 'Regenerate key';
$lang['cron_scheduler_cli_line'] = 'Recommended crontab line (CLI, every minute)';
$lang['cron_scheduler_web_line'] = 'Alternative web trigger (requires key above)';
$lang['cron_scheduler_legacy_heading'] = 'Legacy crontab lines you can remove after switching';
$lang['cron_scheduler_duplicate_warning'] = 'The scheduler is enabled and some jobs have run from both legacy URLs and the scheduler recently. Remove duplicate crontab lines to avoid skipped runs.';
$lang['cron_scheduler_jobs_heading'] = 'Scheduled jobs';
$lang['cron_scheduler_col_description'] = 'Job';
$lang['cron_scheduler_col_interval'] = 'Interval';
$lang['cron_scheduler_col_last_run'] = 'Last finished';
$lang['cron_scheduler_col_status'] = 'Status';
$lang['cron_scheduler_col_error'] = 'Last error';
$lang['cron_scheduler_col_enabled'] = 'Enabled';
$lang['cron_scheduler_col_actions'] = 'Actions';
$lang['cron_scheduler_run_now'] = 'Run now';
$lang['cron_scheduler_back_maintenance'] = 'Back to Maintenance';
$lang['cron_scheduler_key_empty'] = 'No key set. Enable the scheduler or regenerate a key for the web trigger.';
$lang['cron_scheduler_antidouble_note'] = 'Each job always uses a shared MySQL lock. When the scheduler is enabled, an interval guard also applies to legacy URLs so duplicate crontab lines do not double-run jobs. With the scheduler off, legacy URLs are only protected by the lock. A skipped run returns HTTP 200 with body "skipped".';
