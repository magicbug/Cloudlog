<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Unified cron scheduler entry point.
 *
 * CLI:    php index.php cron run
 * Web:    GET /cron/run?key=<cron_scheduler_key>  (key required when not CLI)
 */
class Cron extends CI_Controller {

    public function run()
    {
        if (!is_cli()) {
            $this->require_web_key();
        }

        $enabled = $this->optionslib->get_option('cron_scheduler_enabled');
        if ($enabled !== 'true' && $enabled !== '1') {
            if (!is_cli()) {
                header('Content-Type: text/plain; charset=UTF-8');
            }
            echo "disabled\n";
            return;
        }

        $this->load->library('cron_runner');
        $this->cron_runner->run_due();
    }

    private function require_web_key()
    {
        $stored = (string) $this->optionslib->get_option('cron_scheduler_key');
        $provided = (string) $this->input->get('key', true);

        if ($stored === '' || $provided === '' || !hash_equals($stored, $provided)) {
            show_error('Forbidden', 403);
        }
    }
}
