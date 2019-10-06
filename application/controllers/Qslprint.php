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

		$myData = $this->logbook_model->get_qsos_for_printing();

		// file name
		$filename = 'qsl_export.csv';
		header("Content-Description: File Transfer");
		header("Content-Disposition: attachment; filename=$filename");
		header("Content-Type: application/csv;charset=iso-8859-1");
 
		// file creation
		$file = fopen('php://output', 'w');
 
		$header = array("STATION_CALLSIGN",
						"COL_CALL", 
						"COL_QSL_VIA", 
						"COL_TIME_ON", 
						"COL_MODE", 
						"COL_FREQ", 
						"COL_BAND", 
						"COL_RST_SENT", 
						"COL_SAT_NAME", 
						"COL_SAT_MODE", 
						"COL_QSL_RCVD", 
						"COL_COMMENT",
						"COL_ROUTING", 
						"ADIF", 
						"ENTITY");

		fputcsv($file, $header);
 
		foreach ($myData->result() as $qso) {
			fputcsv($file, 
				array($qso->STATION_CALLSIGN,
				str_replace("0", "Ø", $qso->COL_CALL), 
				$qso->COL_QSL_VIA!=""?"Via ".str_replace("0", "Ø", $qso->COL_QSL_VIA):"", 
				$qso->COL_TIME_ON, 
				$qso->COL_MODE, 
				$qso->COL_FREQ, 
				$qso->COL_BAND, 
				$qso->COL_RST_SENT, 
				$qso->COL_SAT_NAME, 
				$qso->COL_SAT_MODE, 
				$qso->COL_QSL_RCVD =='Y'?'TNX QSL':'PSE QSL', 
				$qso->COL_COMMENT, 
				$qso->COL_ROUTING,
				$qso->ADIF, 
				$qso->ENTITY));
		}

		fclose($file);
		exit;
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

/* End of file Qslprint.php */
/* Location: ./application/controllers/Qslprint.php */