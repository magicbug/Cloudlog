<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Unified cron job locking, scheduling, and dispatch.
 *
 * Anti-double-run rule:
 * - A MySQL GET_LOCK per job_key must be acquired before the job body runs.
 * - If the lock is not available, the invocation is skipped (HTTP 200, body "skipped").
 * - When cron_scheduler_enabled is off, legacy URL calls use the lock only (no interval
 *   guard), so custom crontab intervals behave as before migration 288.
 * - When the scheduler is enabled, legacy URLs and scheduler ticks also skip if the
 *   previous run finished less than (interval_seconds - margin) ago unless $force is
 *   true (Run now). Margin is min(300, max(60, 5% of interval)).
 * - Stuck runs (started, not finished within STALE_RUN_SECONDS) are reset before retry.
 */
class Cron_runner {

    const TICK_BUDGET_SECONDS = 50;
    const STALE_RUN_SECONDS = 7200;
    const LOCK_WAIT_SECONDS = 0;

    /** @var string */
    private $execution_source = 'legacy';

    /** @var bool */
    private $force_once = false;

    /** @var bool */
    private $lock_held = false;

    /** @var string|null */
    private $active_job_key = null;

    /** @var string|null */
    private $active_lock_name = null;

    /** @var string|null */
    private $last_skip_reason = null;

    public function set_execution_source($source)
    {
        $this->execution_source = $source;
    }

    public function get_execution_source()
    {
        return $this->execution_source;
    }

    /**
     * @return int Seconds before another non-forced run is allowed.
     */
    public function interval_margin($interval_seconds)
    {
        $interval_seconds = (int) $interval_seconds;
        $five_percent = (int) floor($interval_seconds * 0.05);
        return min(300, max(60, $five_percent));
    }

    /**
     * @return bool True if the job should run; false if skipped (response already sent when applicable).
     */
    public function begin($job_key, $source = null, $force = false)
    {
        $CI =& get_instance();
        $CI->load->model('cron_jobs_model');

        if ($source !== null) {
            $this->execution_source = $source;
        }

        if ($this->force_once) {
            $force = true;
            $this->force_once = false;
        }

        $job = $CI->cron_jobs_model->get_by_key($job_key);
        if (!$job) {
            log_message('error', 'Cron job unknown: '.$job_key);
            return true;
        }

        if (!$force && $this->execution_source === 'scheduler' && (int) $job->enabled !== 1) {
            $this->respond_skipped($job_key, 'Job disabled');
            return false;
        }

        if ($this->is_stale_in_progress($job)) {
            $CI->cron_jobs_model->clear_stale_run($job_key);
            $job = $CI->cron_jobs_model->get_by_key($job_key);
        }

        if (!$force && $this->should_apply_interval_guard() && $this->is_within_interval($job)) {
            $this->respond_skipped($job_key, 'Interval not elapsed');
            return false;
        }

        $lock_name = $this->lock_name($job_key);
        $lock_row = $CI->db->query(
            'SELECT GET_LOCK(?, ?) AS acquired',
            array($lock_name, self::LOCK_WAIT_SECONDS)
        )->row();

        if (!$lock_row || (int) $lock_row->acquired !== 1) {
            $this->respond_skipped($job_key, 'Lock not available');
            return false;
        }

        $this->lock_held = true;
        $this->active_job_key = $job_key;
        $this->active_lock_name = $lock_name;

        $CI->cron_jobs_model->mark_started($job_key, $this->execution_source);
        return true;
    }

    public function finish($job_key, $status = 'ok', $error = null)
    {
        $CI =& get_instance();
        $CI->load->model('cron_jobs_model');

        $job = $CI->cron_jobs_model->get_by_key($job_key);
        $interval = $job ? (int) $job->interval_seconds : 3600;

        if ($error !== null && $error !== '') {
            $status = 'error';
        }

        $CI->cron_jobs_model->mark_finished($job_key, $status, $error, $interval);
        $this->release_lock();
    }

    public function run_guarded($job_key, $callable, $force = false)
    {
        if (!$this->begin($job_key, null, $force)) {
            return false;
        }

        $status = 'ok';
        $error = null;

        try {
            call_user_func($callable);
        } catch (Throwable $e) {
            $status = 'error';
            $error = $e->getMessage();
            log_message('error', 'Cron job '.$job_key.': '.$error);
        }

        $this->finish($job_key, $status, $error);
        return $status === 'ok';
    }

    /**
     * Run all due jobs within the tick time budget (scheduler only).
     */
    public function run_due()
    {
        $CI =& get_instance();
        $CI->load->model('cron_jobs_model');

        $this->execution_source = 'scheduler';
        $started = microtime(true);
        $ran = 0;
        $skipped = array();

        $jobs = $CI->cron_jobs_model->get_due_jobs();

        foreach ($jobs as $job) {
            if ((microtime(true) - $started) >= self::TICK_BUDGET_SECONDS) {
                log_message('info', 'Cron scheduler tick budget reached after '.$ran.' job(s).');
                break;
            }

            $this->execution_source = 'scheduler';
            $this->last_skip_reason = null;

            ob_start();
            try {
                $this->dispatch_legacy_uri($job->legacy_uri);
            } catch (Throwable $e) {
                log_message('error', 'Cron job '.$job->job_key.': '.$e->getMessage());
            }
            $output = ob_get_clean();

            if ($output === 'skipped') {
                $skipped[] = $job->job_key.': '.($this->last_skip_reason ?: 'skipped');
            } else {
                $ran++;
            }
        }

        $summary = 'Cron scheduler: ran '.$ran.', skipped '.count($skipped);
        if ($skipped) {
            $summary .= ' ('.implode('; ', $skipped).')';
        }
        log_message('info', $summary);

        if (!is_cli()) {
            header('Content-Type: text/plain; charset=UTF-8');
        }

        echo $summary."\n";
    }

    public function dispatch_legacy_uri($legacy_uri)
    {
        $parts = array_values(array_filter(explode('/', trim($legacy_uri, '/'))));
        if (count($parts) < 2) {
            throw new RuntimeException('Invalid legacy URI: '.$legacy_uri);
        }

        $controller_file = APPPATH.'controllers/'.ucfirst($parts[0]).'.php';
        if (!is_file($controller_file)) {
            throw new RuntimeException('Missing controller for URI: '.$legacy_uri);
        }

        require_once $controller_file;

        $class = ucfirst($parts[0]);
        $method = $parts[1];
        $params = array_slice($parts, 2);

        if (!class_exists($class) || !method_exists($class, $method)) {
            throw new RuntimeException('Cannot dispatch URI: '.$legacy_uri);
        }

        $controller = $this->make_controller($class, $method);
        call_user_func_array(array($controller, $method), $params);
    }

    /**
     * CI_Controller::__construct() re-runs load_class() on every loaded library, which
     * cannot find libraries in sub-folders (Session) and leaves the new instance without
     * the models the shared Loader thinks are loaded. Give the job controller the current
     * instance's objects first and hide the library list while its constructor runs.
     */
    private function make_controller($class, $method)
    {
        $CI =& get_instance();
        $CI->router->set_class(strtolower($class));
        $CI->router->set_method($method);

        $reflection = new ReflectionClass($class);
        $controller = $reflection->newInstanceWithoutConstructor();
        foreach (get_object_vars($CI) as $name => $value) {
            $controller->$name = $value;
        }

        $loaded =& is_loaded();
        $saved = $loaded;
        $loaded = array();
        try {
            $reflection->getConstructor()->invoke($controller);
        } finally {
            $loaded = array_merge($saved, $loaded);
        }

        return $controller;
    }

    public function run_job_now($job_key)
    {
        $CI =& get_instance();
        $CI->load->model('cron_jobs_model');
        $job = $CI->cron_jobs_model->get_by_key($job_key);

        if (!$job) {
            return array('ok' => false, 'message' => 'Unknown job');
        }

        $this->force_once = true;
        $this->execution_source = 'manual';

        ob_start();
        try {
            $this->dispatch_legacy_uri($job->legacy_uri);
        } catch (Throwable $e) {
            log_message('error', 'Cron job '.$job_key.': '.$e->getMessage());
        }
        $output = ob_get_clean();

        if ($output === 'skipped') {
            return array('ok' => false, 'message' => 'skipped');
        }

        $job_after = $CI->cron_jobs_model->get_by_key($job_key);
        $ok = $job_after && $job_after->last_status === 'ok';

        return array(
            'ok' => $ok,
            'message' => $job_after && $job_after->last_status === 'ok' ? 'completed' : ($job_after->last_error ?: 'error'),
        );
    }

    public function scheduler_duplicate_warning()
    {
        $CI =& get_instance();
        $enabled = $CI->optionslib->get_option('cron_scheduler_enabled');
        if ($enabled !== 'true' && $enabled !== '1') {
            return false;
        }

        $since = date('Y-m-d H:i:s', time() - 86400);

        $CI->db->from('cron_jobs');
        $CI->db->where('last_finished_at >=', $since);
        $CI->db->where('last_source', 'legacy');
        $legacy = $CI->db->count_all_results();

        $CI->db->reset_query();
        $CI->db->from('cron_jobs');
        $CI->db->where('last_finished_at >=', $since);
        $CI->db->where('last_source', 'scheduler');
        $scheduled = $CI->db->count_all_results();

        return ($legacy > 0 && $scheduled > 0);
    }

    private function should_apply_interval_guard()
    {
        if ($this->execution_source === 'scheduler') {
            return $this->is_scheduler_enabled();
        }

        if ($this->execution_source === 'legacy') {
            return $this->is_scheduler_enabled();
        }

        return false;
    }

    private function is_scheduler_enabled()
    {
        $CI =& get_instance();
        $enabled = $CI->optionslib->get_option('cron_scheduler_enabled');
        return ($enabled === 'true' || $enabled === '1');
    }

    private function is_stale_in_progress($job)
    {
        if (empty($job->last_started_at)) {
            return false;
        }

        if (!empty($job->last_finished_at) && strtotime($job->last_finished_at) >= strtotime($job->last_started_at)) {
            return false;
        }

        return (time() - strtotime($job->last_started_at)) > self::STALE_RUN_SECONDS;
    }

    private function is_within_interval($job)
    {
        if (empty($job->last_finished_at)) {
            return false;
        }

        $elapsed = time() - strtotime($job->last_finished_at);
        $margin = $this->interval_margin((int) $job->interval_seconds);
        $min_gap = (int) $job->interval_seconds - $margin;

        return $elapsed < $min_gap;
    }

    private function respond_skipped($job_key, $reason)
    {
        log_message('info', 'Cron job '.$job_key.' skipped: '.$reason);
        $this->last_skip_reason = $reason;

        if (!headers_sent()) {
            header('HTTP/1.1 200 OK');
            header('Content-Type: text/plain; charset=UTF-8');
        }

        echo 'skipped';
    }

    private function lock_name($job_key)
    {
        $prefix = 'cloudlog_cron_';
        $safe = preg_replace('/[^a-zA-Z0-9_]/', '_', $job_key);
        return $prefix.$safe;
    }

    private function release_lock()
    {
        if (!$this->lock_held || $this->active_lock_name === null) {
            return;
        }

        $CI =& get_instance();
        $CI->db->query('SELECT RELEASE_LOCK(?) AS released', array($this->active_lock_name));

        $this->lock_held = false;
        $this->active_job_key = null;
        $this->active_lock_name = null;
    }

    public function __destruct()
    {
        $this->release_lock();
    }
}
