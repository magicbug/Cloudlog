<?php 

require_once './src/Label/vendor/autoload.php';
use Cloudlog\Label\PDF_Label;
use Cloudlog\Label\tfpdf;
use Cloudlog\Label\font\unifont\ttfonts;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Labels extends CI_Controller {
	/*
	|--------------------------------------------------------------------------
	| Controller: Labels
	|--------------------------------------------------------------------------
	| 
	| This Controller handles all things Labels, creating, editing and printing
	|
	|
	 */

	function __construct() {
		parent::__construct();
		$this->load->helper(array('form', 'url', 'psr4_autoloader'));

		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
	}


	/*
	|--------------------------------------------------------------------------
	| Function: index
	|--------------------------------------------------------------------------
	| 
	| Nothing fancy just shows the main display of how many labels are waiting 
	| to be printed per station profile.
	|
	 */
	public function index() {
		$data['page_title'] = "QSL Card Labels";

		$this->load->model('labels_model');

		$data['labels'] = $this->labels_model->fetchLabels($this->session->userdata('user_id'));

		$data['qsos'] = $this->labels_model->fetchQsos($this->session->userdata('user_id'));

		$footerData = [];
		$footerData['scripts'] = [
			'assets/js/sections/labels.js',
		];

		$this->load->view('interface_assets/header', $data);
		$this->load->view('labels/index');
		$this->load->view('interface_assets/footer', $footerData);

	}

	/*
	|--------------------------------------------------------------------------
	| Function: create
	|--------------------------------------------------------------------------
	| 
	| Shows the form used to create a label type.
	|
	 */
	public function create() {

		$data['page_title'] = "Create Label Type";

		$this->load->library('form_validation');

		$this->form_validation->set_rules('label_name', 'Label Name', 'required');

		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('interface_assets/header', $data);
			$this->load->view('labels/create');
			$this->load->view('interface_assets/footer');
		}
		else
		{	
			$this->load->model('labels_model');
			$this->labels_model->addLabel();

			redirect('labels');
		}

	}

	public function printids() {
		$ids = xss_clean(json_decode($this->input->post('id')));
		$this->load->model('labels_model');
		$result = $this->labels_model->export_printrequestedids($ids);

		$this->prepareLabel($result, true);
	}

	public function print($station_id) {
		$clean_id = xss_clean($station_id);

		$this->load->model('labels_model');
		$result = $this->labels_model->export_printrequested($clean_id);

		$this->prepareLabel($result);
	}

	function prepareLabel($qsos, $jscall = false) {
		$this->load->model('labels_model');
		$label = $this->labels_model->getDefaultLabel();
		$label->font='DejaVuSans'; // Fix font to DejaVuSans


		try {
			if ($label) {
				$pdf = new PDF_Label(array(
					'paper-size'	=> $label->paper_type, 
					'metric'		=> $label->metric, 
					'marginLeft'	=> $label->marginleft, 
					'marginTop'		=> $label->margintop, 
					'NX'			=> $label->nx, 
					'NY'			=> $label->ny, 
					'SpaceX'		=> $label->spacex, 
					'SpaceY'		=> $label->spacey, 
					'width'			=> $label->width, 
					'height'		=> $label->height, 
					'font-size'		=> $label->font_size
				));
			} else {
				if ($jscall) {
					header('Content-Type: application/json');
					echo json_encode(array('message' => 'You need to create a label and set it to be used for print.'));
					return;
				} else {
					$this->session->set_flashdata('error', 'You need to create a label and set it to be used for print.'); 
					redirect('labels');
				}
			}
		} catch (\Throwable $th) {
			if ($jscall) {
				header('Content-Type: application/json');
				echo json_encode(array('message' => 'Something went wrong! The label could not be generated. Check label size and font size.'));
				return;
			} else {
				$this->session->set_flashdata('error', 'Something went wrong! The label could not be generated. Check label size and font size.'); 
				redirect('labels');
			}
		}
		define('FPDF_FONTPATH', './src/Label/font/');

		$pdf->AddPage();

		if ($label->font == 'DejaVuSans') {	// leave this here, for future Use
			$pdf->AddFont($label->font,'','DejaVuSansMono.ttf',true);
			$pdf->SetFont($label->font);
		} else {
			$pdf->AddFont($label->font);
			$pdf->SetFont($label->font);
		}

		if ($qsos->num_rows() > 0) {
			if ($label->qsos == 1) {
				$this->makeMultiQsoLabel($qsos->result(), $pdf,1);
			} else {
				$this->makeMultiQsoLabel($qsos->result(), $pdf, $label->qsos);
			}
		} else {
			$this->session->set_flashdata('message', '0 QSOs found for print!'); 
			redirect('labels');
		}
		$pdf->Output();
	}

	function makeMultiQsoLabel($qsos, $pdf, $numberofqsos) {
		$text = '';
		$current_callsign = '';
		$current_sat = '';
		$qso_data = [];
		foreach($qsos as $qso) {
			if (($qso->COL_SAT_NAME !== $current_sat) || ($qso->COL_CALL !== $current_callsign)) {
				if (!empty($qso_data)) {
					$this->finalizeData($pdf, $current_callsign, $qso_data, $numberofqsos);
					$qso_data = [];
				}
				$current_callsign = $qso->COL_CALL;
				$current_sat = $qso->COL_SAT_NAME;
			}

			$qso_data[] = [
				'time' => $qso->COL_TIME_ON,
				'band' => $qso->COL_BAND,
				'mode' => $qso->COL_MODE,
				'rst' => $qso->COL_RST_SENT,
				'mygrid' => $qso->station_gridsquare,
				'sat' => $qso->COL_SAT_NAME,
				'sat_mode' => $qso->COL_SAT_MODE,
				'qsl_recvd' => $qso->COL_QSL_RCVD
			];
		}
		if (!empty($qso_data)) {
			$this->finalizeData($pdf, $current_callsign, $qso_data, $numberofqsos);
		}
	}
	// New begin

	function finalizeData($pdf, $current_callsign, &$preliminaryData, $qso_per_label) {
		$tableData = [];
		$count_qso = 0;
		$qso=[];
		foreach ($preliminaryData as $key => $row) {
			$qso=$row;
			$time = strtotime($qso['time']);
			$myFormatForView = date("Y-m-d H:i", $time);
			$rowData = [
				'Date/Time (UTC)' => $myFormatForView,
				'Band' => $row['band'],
				'Mode' => $row['mode'],
				'RST' => $row['rst'],
			];
			$tableData[] = $rowData;
			$count_qso++;

			if($count_qso == $qso_per_label){
				$this->generateLabel($pdf, $current_callsign, $tableData,$count_qso,$qso);
				$tableData = []; // reset the data
				$count_qso = 0;  // reset the counter
			}
			unset($preliminaryData[$key]);
		}
		// generate label for remaining QSOs
		if($count_qso > 0){
			$this->generateLabel($pdf, $current_callsign, $tableData,$count_qso,$qso);
			$preliminaryData = []; // reset the data
		}
	}

	function generateLabel($pdf, $current_callsign, $tableData,$numofqsos,$qso){
		$builder = new \AsciiTable\Builder();
		$builder->addRows($tableData);
		$text = "Confirming QSO".($numofqsos>1 ? 's' : '')." with ";
		$text .= $current_callsign;
		$text .= "\n";
		$text .= $builder->renderTable();
		if($qso['sat'] != "") {
			$text .= "\n".'Satellite: '.$qso['sat'].' Mode: '.strtoupper($qso['sat_mode'][0]).'/'.strtoupper($qso['sat_mode'][1]);
		}
		$text .= "\nThanks for the QSO".($numofqsos>1 ? 's' : '');
		$text .= " | ".($qso['qsl_recvd'] == 'Y' ? 'TNX' : 'PSE')." QSL";
		$pdf->Add_Label($text);
	}

	// New End

	public function edit($id) {
		$this->load->model('labels_model');

		$cleanid = $this->security->xss_clean($id);

		$data['label'] = $this->labels_model->getLabel($cleanid);

		$data['page_title'] = "Edit Label";

		$this->load->view('interface_assets/header', $data);
		$this->load->view('labels/edit');
		$this->load->view('interface_assets/footer');
	}

	public function updateLabel($id) {
		$this->load->model('labels_model');
		$this->labels_model->updateLabel($id);
		$this->session->set_flashdata('message', 'Label was saved.'); 
		redirect('labels');
	}

	public function delete($id) {
		$this->load->model('labels_model');
		$this->labels_model->deleteLabel($id);
		$this->session->set_flashdata('warning', 'Label was deleted.'); 
		redirect('labels');
	}

	public function saveDefaultLabel() {
		$id = $this->input->post('id');
		$this->load->model('labels_model');
		$this->labels_model->saveDefaultLabel($id);
	}

}
