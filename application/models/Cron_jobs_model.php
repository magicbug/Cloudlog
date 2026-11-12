<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cron_jobs_model extends CI_Model {

    public function get_all()
    {
        $this->db->order_by('description', 'ASC');
        return $this->db->get('cron_jobs');
    }

    public function get_by_key($job_key)
    {
        $this->db->where('job_key', $job_key);
        return $this->db->get('cron_jobs')->row();
    }

    public function set_enabled($job_key, $enabled)
    {
        $this->db->where('job_key', $job_key);
        $this->db->update('cron_jobs', array('enabled' => (int) $enabled));
    }

    public function get_due_jobs($limit = 50)
    {
        $now = date('Y-m-d H:i:s');
        $this->db->where('enabled', 1);
        $this->db->group_start();
        $this->db->where('next_run_at IS NULL', null, false);
        $this->db->or_where('next_run_at <=', $now);
        $this->db->group_end();
        $this->db->order_by('next_run_at', 'ASC');
        $this->db->limit($limit);
        return $this->db->get('cron_jobs')->result();
    }

    public function mark_started($job_key, $source)
    {
        $this->db->where('job_key', $job_key);
        $this->db->update('cron_jobs', array(
            'last_started_at' => date('Y-m-d H:i:s'),
            'last_source' => $source,
        ));
    }

    public function mark_finished($job_key, $status, $error, $interval_seconds)
    {
        $finished = date('Y-m-d H:i:s');
        $next = date('Y-m-d H:i:s', time() + (int) $interval_seconds);
        $this->db->where('job_key', $job_key);
        $this->db->update('cron_jobs', array(
            'last_finished_at' => $finished,
            'last_status' => $status,
            'last_error' => $error,
            'next_run_at' => $next,
        ));
    }

    public function clear_stale_run($job_key)
    {
        $this->db->where('job_key', $job_key);
        $this->db->update('cron_jobs', array(
            'last_started_at' => null,
            'last_status' => 'stale_reset',
        ));
    }
}
