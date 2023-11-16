<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Debug extends CI_Controller {
    function __construct()
    {
        parent::__construct();

        $this->load->model('user_model');
        if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
    }

    /* User Facing Links to Backup URLs */
    public function index()
    {
        $this->load->helper('file');

        $this->load->model('MigrationVersion');

        $data['migration_version'] = $this->MigrationVersion->getMigrationVersion();

        // Test writing to backup folder
        $backup_folder = $this->is_really_writable('backup');
        $data['backup_folder'] = $backup_folder;

        // Test writing to updates folder
        $updates_folder = $this->is_really_writable('updates');
        $data['updates_folder'] = $updates_folder;

        // Test writing to uploads folder
        $uploads_folder = $this->is_really_writable('uploads');
        $data['uploads_folder'] = $uploads_folder;

        $data['page_title'] = "Debug";

        $this->load->view('interface_assets/header', $data);
        $this->load->view('debug/main');
        $this->load->view('interface_assets/footer');
    }

    private function is_really_writable($folder)
    {
        // Get the absolute path to the folder
        $path = FCPATH . $folder;

        // Check if the folder exists
        if (!file_exists($path)) {
            return false;
        }

        // Check if the folder is writable
        if (is_writable($path)) {
            // Check if the subdirectories are writable (recursive check)
            $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
            foreach ($iterator as $item) {
               if ($item->isDir() && basename($item->getPathName()) != '..') {
                  if (!is_writable($item->getRealPath())) {
                     return false;
                  }
               }
            }

            return true;
        }

        return false;
    }
}
