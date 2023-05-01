<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Backup extends CI_Controller {
	function __construct()
	{
		parent::__construct();
	}
	
	/* User Facing Links to Backup URLs */
	public function index()
	{
		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }

		$data['page_title'] = "Backup";

		$this->load->view('interface_assets/header', $data);
		$this->load->view('backup/main');
		$this->load->view('interface_assets/footer');
	}

	/* Gets all QSOs and Dumps them to logbook.adi */
	public function adif($key){ 
		$this->load->helper('file');
		// Set memory limit to unlimited to allow heavy usage
		ini_set('memory_limit', '-1');
		
		$this->load->model('adif_data');

		$data['qsos'] = $this->adif_data->export_all($key);

		if ( ! write_file('backup/logbook.adi', $this->load->view('backup/exportall', $data, true)))
		{
		     $data['status'] = false;
		}
		else
		{
		      $data['status'] = true;
		}

		$data['page_title'] = "ADIF - Backup";

		$this->load->view('interface_assets/header', $data);
		$this->load->view('backup/adif_view');
		$this->load->view('interface_assets/footer');

	}

	/* Export the notes to XML */
	public function notes() {
		$this->load->helper('file');
		$this->load->model('note');

		$data['list_note'] = $this->note->list_all();

		if ( ! write_file('backup/notes.xml', $this->load->view('backup/notes', $data, true)))
		{
		     $data['status'] = false;
		}
		else
		{
		      $data['status'] = true;
		}

		$data['page_title'] = "Notes - Backup";

		$this->load->view('interface_assets/header', $data);
		$this->load->view('backup/notes_view');
		$this->load->view('interface_assets/footer');

	}
}

/* End of file Backup.php */