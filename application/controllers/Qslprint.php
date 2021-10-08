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

		$this->load->model('stations');
		$data['station_profile'] = $this->stations->all();

		$this->load->model('qslprint_model');
		$data['qsos'] = $this->qslprint_model->get_qsos_for_print();

		$data['page_title'] = "Print Requested QSLs";

		$this->load->view('interface_assets/header', $data);
		$this->load->view('qslprint/index');
		$this->load->view('interface_assets/footer');

	}

	public function exportadif()
	{
		// Set memory limit to unlimited to allow heavy usage
		ini_set('memory_limit', '-1');

		if ($this->uri->segment(3) == 'all') {
			$station_id = 'All';
		} else {
			$station_id = $this->security->xss_clean($this->uri->segment(3));
		}

		$this->load->model('adif_data');

		$data['qsos'] = $this->adif_data->export_printrequested($station_id);

		$this->load->view('adif/data/exportall', $data);
	}

	public function exportcsv()
	{
		// Set memory limit to unlimited to allow heavy usage
		ini_set('memory_limit', '-1');

		if ($this->uri->segment(3) == 'all') {
			$station_id = 'All';
		} else {
			$station_id = $this->security->xss_clean($this->uri->segment(3));
		}

		$this->load->model('logbook_model');

		$myData = $this->logbook_model->get_qsos_for_printing($station_id);

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

		if ($this->uri->segment(3) == 'all') {
			$station_id = 'All';
		} else {
			$station_id = $this->security->xss_clean($this->uri->segment(3));
		}

		$this->load->model('qslprint_model');
		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }

			// Update Logbook to Mark Paper Card Received

			$this->qslprint_model->mark_qsos_printed($station_id);

			$this->session->set_flashdata('notice', 'QSOs are marked as sent via buro');

			redirect('logbook');
	}

	public function delete_from_qsl_queue() {
		$id = $this->input->post('id');
		$this->load->model('qslprint_model');

		$this->qslprint_model->delete_from_qsl_queue($this->security->xss_clean($id));
	}

	public function get_qsos_for_print_ajax() {
		$station_id = $this->input->post('station_id');
		$this->load->model('qslprint_model');

		$data['qsos'] = $this->qslprint_model->get_qsos_for_print_ajax($this->security->xss_clean($station_id));
		$data['station_id'] = $station_id;
		$this->load->view('qslprint/qslprint', $data);
	}

	public function open_qso_list() {
		$callsign = $this->input->post('callsign');
		$this->load->model('qslprint_model');

		$data['qsos'] = $this->qslprint_model->open_qso_list($this->security->xss_clean($callsign));
		$this->load->view('qslprint/qsolist', $data);
	}

	public function add_qso_to_print_queue() {
		$id = $this->input->post('id');
		$this->load->model('qslprint_model');

		$this->qslprint_model->add_qso_to_print_queue($this->security->xss_clean($id));
	}

}

/* End of file Qslprint.php */
/* Location: ./application/controllers/Qslprint.php */
