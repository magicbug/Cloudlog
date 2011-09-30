<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Backup extends CI_Controller {

	/* User Facing Links to Backup URLs */
	public function index()
	{

		$this->load->view('layout/header');
		$this->load->view('backup/main');
		$this->load->view('layout/footer');


	}

	public function adif(){ 
		$this->load->helper('file');
		// Set memory limit to unlimited to allow heavy usage
		ini_set('memory_limit', '-1');
		
		$this->load->model('adif_data');

		$data['qsos'] = $this->adif_data->export_all();

		if ( ! write_file('backup/logbook.adi', $this->load->view('backup/exportall', $data, true)))
		{
		     $data['status'] = false;
		}
		else
		{
		      $data['status'] = true;
		}

		$this->load->view('layout/header');
		$this->load->view('backup/adif_view', $data);
		$this->load->view('layout/footer');

	}
}

/* End of file Backup.php */