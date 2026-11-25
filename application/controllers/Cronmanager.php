<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cronmanager extends CI_Controller {

    public function index()
    {
        $this->load->model('user_model');
        if (!$this->user_model->authorize(99)) {
            $this->session->set_flashdata('notice', 'You\'re not allowed to do that!');
            redirect('dashboard');
        }

        $this->load->model('cron_jobs_model');
        $this->load->library('cron_runner');
        $this->lang->load('cron_scheduler');

        $data['page_title'] = lang('cron_scheduler_title');
        $jobs = $this->cron_jobs_model->get_all()->result();
        foreach ($jobs as $job) {
            $parts = $this->interval_parts((int) $job->interval_seconds);
            $job->interval_value = $parts['value'];
            $job->interval_unit = $parts['unit'];
            $job->interval_label = $parts['label'];
        }
        $data['jobs'] = $jobs;
        $data['scheduler_enabled'] = $this->is_scheduler_enabled();
        $data['scheduler_key'] = (string) $this->optionslib->get_option('cron_scheduler_key');
        $data['cli_cron_line'] = '* * * * * php '.FCPATH.'index.php cron run';
        $data['web_cron_line'] = site_url('cron/run?key='.$data['scheduler_key']);
        $data['duplicate_warning'] = $this->cron_runner->scheduler_duplicate_warning();

        $this->load->view('interface_assets/header', $data);
        $this->load->view('maintenance/cron_scheduler', $data);
        $this->load->view('interface_assets/footer');
    }

    public function save_scheduler()
    {
        $this->load->model('user_model');
        if (!$this->user_model->authorize(99)) {
            header('Content-Type: application/json');
            echo json_encode(array('ok' => false, 'message' => 'Forbidden'));
            return;
        }

        $enable = $this->input->post('scheduler_enabled', true);
        $want_enabled = ($enable === '1' || $enable === 'true' || $enable === 'on');

        $this->optionslib->update('cron_scheduler_enabled', $want_enabled ? 'true' : 'false');

        $key = (string) $this->optionslib->get_option('cron_scheduler_key');
        if ($want_enabled && $key === '') {
            $key = bin2hex(random_bytes(32));
            $this->optionslib->update('cron_scheduler_key', $key);
        }

        header('Content-Type: application/json');
        echo json_encode(array(
            'ok' => true,
            'scheduler_enabled' => $want_enabled,
            'scheduler_key' => $key,
            'web_cron_line' => site_url('cron/run?key='.$key),
        ));
    }

    public function regenerate_key()
    {
        $this->load->model('user_model');
        if (!$this->user_model->authorize(99)) {
            header('Content-Type: application/json');
            echo json_encode(array('ok' => false));
            return;
        }

        $key = bin2hex(random_bytes(32));
        $this->optionslib->update('cron_scheduler_key', $key);

        header('Content-Type: application/json');
        echo json_encode(array(
            'ok' => true,
            'scheduler_key' => $key,
            'web_cron_line' => site_url('cron/run?key='.$key),
        ));
    }

    public function toggle_job()
    {
        $this->load->model('user_model');
        if (!$this->user_model->authorize(99)) {
            header('Content-Type: application/json');
            echo json_encode(array('ok' => false));
            return;
        }

        $job_key = $this->input->post('job_key', true);
        $enabled = $this->input->post('enabled', true);

        $this->load->model('cron_jobs_model');
        $this->cron_jobs_model->set_enabled($job_key, ($enabled === '1' || $enabled === 'true') ? 1 : 0);

        header('Content-Type: application/json');
        echo json_encode(array('ok' => true));
    }

    public function run_job()
    {
        $this->load->model('user_model');
        if (!$this->user_model->authorize(99)) {
            header('Content-Type: application/json');
            echo json_encode(array('ok' => false));
            return;
        }

        $job_key = $this->input->post('job_key', true);
        $this->load->library('cron_runner');
        $result = $this->cron_runner->run_job_now($job_key);

        header('Content-Type: application/json');
        echo json_encode($result);
    }

    public function set_interval()
    {
        $this->load->model('user_model');
        $this->lang->load('cron_scheduler');
        header('Content-Type: application/json');

        if (!$this->user_model->authorize(99)) {
            echo json_encode(array(
                'ok' => false,
                'message' => lang('cron_scheduler_interval_forbidden'),
            ));
            return;
        }

        $job_key = trim((string) $this->input->post('job_key', true));
        $value = (int) $this->input->post('value', true);
        $unit = strtolower(trim((string) $this->input->post('unit', true)));

        $seconds = $this->interval_to_seconds($value, $unit);
        if ($seconds === null) {
            echo json_encode(array(
                'ok' => false,
                'message' => lang('cron_scheduler_interval_invalid'),
            ));
            return;
        }

        $this->load->model('cron_jobs_model');
        $job = $this->cron_jobs_model->set_interval($job_key, $seconds);
        if (!$job) {
            echo json_encode(array(
                'ok' => false,
                'message' => lang('cron_scheduler_interval_unknown_job'),
            ));
            return;
        }

        $parts = $this->interval_parts((int) $job->interval_seconds);
        echo json_encode(array(
            'ok' => true,
            'message' => lang('cron_scheduler_interval_saved'),
            'interval_seconds' => (int) $job->interval_seconds,
            'value' => $parts['value'],
            'unit' => $parts['unit'],
            'label' => $parts['label'],
            'next_run_at' => $job->next_run_at,
        ));
    }

    /**
     * @return array{value:int,unit:string,label:string}
     */
    public function interval_parts($interval_seconds)
    {
        $this->lang->load('cron_scheduler');
        $seconds = max(0, (int) $interval_seconds);

        if ($seconds >= 86400 && ($seconds % 86400) === 0) {
            $value = (int) ($seconds / 86400);
            $unit = 'days';
        } elseif ($seconds >= 3600 && ($seconds % 3600) === 0) {
            $value = (int) ($seconds / 3600);
            $unit = 'hours';
        } else {
            $value = max(1, (int) round($seconds / 60));
            $unit = 'minutes';
        }

        return array(
            'value' => $value,
            'unit' => $unit,
            'label' => $this->format_interval_label($value, $unit),
        );
    }

    private function format_interval_label($value, $unit)
    {
        $value = (int) $value;
        if ($unit === 'days') {
            if ($value === 1) {
                return lang('cron_scheduler_every_one_day');
            }
            return sprintf(lang('cron_scheduler_every_days'), $value);
        }
        if ($unit === 'hours') {
            if ($value === 1) {
                return lang('cron_scheduler_every_one_hour');
            }
            return sprintf(lang('cron_scheduler_every_hours'), $value);
        }
        if ($value === 1) {
            return lang('cron_scheduler_every_one_minute');
        }
        return sprintf(lang('cron_scheduler_every_minutes'), $value);
    }

    /**
     * @return int|null Seconds, or null if invalid
     */
    private function interval_to_seconds($value, $unit)
    {
        $value = (int) $value;
        if ($value < 1) {
            return null;
        }

        switch ($unit) {
            case 'minutes':
                $seconds = $value * 60;
                break;
            case 'hours':
                $seconds = $value * 3600;
                break;
            case 'days':
                $seconds = $value * 86400;
                break;
            default:
                return null;
        }

        $min = 60;
        $max = 90 * 86400;
        if ($seconds < $min || $seconds > $max) {
            return null;
        }

        return $seconds;
    }

    private function is_scheduler_enabled()
    {
        $enabled = $this->optionslib->get_option('cron_scheduler_enabled');
        return ($enabled === 'true' || $enabled === '1');
    }
}
