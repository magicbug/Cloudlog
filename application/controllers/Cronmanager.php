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
        $data['jobs'] = $this->cron_jobs_model->get_all()->result();
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

    private function is_scheduler_enabled()
    {
        $enabled = $this->optionslib->get_option('cron_scheduler_enabled');
        return ($enabled === 'true' || $enabled === '1');
    }
}
