<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class QSLPrint extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));

		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
	}

	public function index()
	{
		$this->load->model('user_model');
		if(!$this->user_model->authorize(99)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
	
		$data['page_title'] = "Export requested QSLs for printing";

		$this->load->view('interface_assets/header', $data);
		$this->load->view('qslprint/index');
		$this->load->view('interface_assets/footer');
	
	}

	public function exportadif()
	{
		// Set memory limit to unlimited to allow heavy usage
		ini_set('memory_limit', '-1');

		$this->load->model('adif_data');

		$data['qsos'] = $this->adif_data->export_printrequested();

		$this->load->view('adif/data/exportall', $data);
	}	
	
	public function exportcsv()
	{
		// Set memory limit to unlimited to allow heavy usage
		ini_set('memory_limit', '-1');

		$this->load->model('logbook_model');

		$data['qsos'] = $this->logbook_model->get_qsos_for_printing();

		$this->load->view('qslprint/data/csv', $data);
	}
	
	function qsl_printed() {
		$this->load->model('qslprint_model');
		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }

			// Update Logbook to Mark Paper Card Received

			$this->qslprint_model->mark_qsos_printed();

			$this->session->set_flashdata('notice', 'QSOs are marked as sent via buro');

			redirect('logbook');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */