<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Debug extends CI_Controller {
	function __construct()
	{
		parent::__construct();

        $this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
        if(ENVIRONMENT != "development") {
            show_error("You must have global enviroment set to development", '403', $heading = 'An Error Was Encountered');
        }
	}
	
	/* User Facing Links to Backup URLs */
	public function index()
	{
        $this->load->helper('file');

        // Test writing to backup folder
        if ( ! write_file('backup/myfile.txt', "dummydata"))
        {
            $data['backup_folder'] = false;
        }
        else
        {
            if(unlink(realpath('backup/myfile.txt'))) {
                $data['backup_folder'] = true;
            } else {
                $data['backup_folder'] = false;
            }
        }

        // Test writing to updates folder
        if ( ! write_file('updates/myfile.txt', "dummydata"))
        {
            $data['updates_folder'] = false;
        }
        else
        {
            if(unlink(realpath('updates/myfile.txt'))) {
                $data['updates_folder'] = true;
            } else {
                $data['updates_folder'] = false;
            }
        }

        // Test writing to uploads folder
        if ( ! write_file('uploads/myfile.txt', "dummydata"))
        {
            $data['uploads_folder'] = false;
        }
        else
        {
            if(unlink(realpath('uploads/myfile.txt'))) {
                $data['uploads_folder'] = true;
            } else {
                $data['uploads_folder'] = false;
            }
        }



		$data['page_title'] = "Debug";

		$this->load->view('interface_assets/header', $data);
		$this->load->view('debug/main');
		$this->load->view('interface_assets/footer');
	}


}