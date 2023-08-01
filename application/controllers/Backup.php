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
		if(!$this->user_model->authorize(99)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }

		$data['page_title'] = "Backup";

		$this->load->view('interface_assets/header', $data);
		$this->load->view('backup/main');
		$this->load->view('interface_assets/footer');
	}

	/* Gets all QSOs and Dumps them to logbook.adi */
	public function adif($key = null){ 
		if ($key == null) {
			$this->load->model('user_model');
			if(!$this->user_model->authorize(99)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
		}

		$this->load->helper('file');
		// Set memory limit to unlimited to allow heavy usage
		ini_set('memory_limit', '-1');
		
		$this->load->model('adif_data');

		$data['qsos'] = $this->adif_data->export_all($key);

		$data['filename'] = 'backup/logbook'. date('_Y_m_d_H_i_s') .'.adi';
		
		if ( ! write_file($data['filename'], $this->load->view('backup/exportall', $data, true)))
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
	public function notes($key = null) {
		if ($key == null) {
			$this->load->model('user_model');
			if(!$this->user_model->authorize(99)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
		}

		$this->load->helper('file');
		$this->load->model('note');

		$data['list_note'] = $this->note->list_all($key);

		$data['filename'] = 'backup/notes'. date('_Y_m_d_H_i_s') .'.xml';

		if ( ! write_file($data['filename'], $this->load->view('backup/notes', $data, true)))
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
