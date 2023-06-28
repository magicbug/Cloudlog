<?php 

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
	
		if ($label->font == 'DejaVuSans') {
			$pdf->AddFont($label->font,'','DejaVuSansMono.ttf',true);
			$pdf->SetFont($label->font);
		} else {
			$pdf->AddFont($label->font);
			$pdf->SetFont($label->font);
		}
	
		if ($qsos->num_rows() > 0) {
			if ($label->qsos == 1) {
				$this->makeOneQsoLabel($qsos->result(), $pdf);
			} else {
				$this->makeMultiQsoLabel($qsos->result(), $pdf, $label->qsos);
			}
		} else {
			$this->session->set_flashdata('message', '0 QSOs found for print!'); 
			redirect('labels');
		}
		$pdf->Output();
	}

	function makeOneQsoLabel($qsos, $pdf) {
		foreach($qsos as $qso) {
			$time = strtotime($qso->COL_TIME_ON);
			$myFormatForView = date("d/m/Y H:i", $time);
				if($qso->COL_SAT_NAME != "") {
					$text = sprintf("%s\n\n%s %s\n%s %s \n\n%s", 'To: '.$qso->COL_CALL, $myFormatForView, 'on '.$qso->COL_BAND.' 2x'.$qso->COL_MODE.' RST '.$qso->COL_RST_SENT.'', 'Satellite: '.$qso->COL_SAT_NAME.' Mode: '.strtoupper($qso->COL_SAT_MODE).' ', '', 'Thanks for QSO.');
				} else {
					$text = sprintf("%s\n\n%s %s\n%s %s \n\n%s", 'To: '.$qso->COL_CALL, $myFormatForView, 'on '.$qso->COL_BAND.' 2x'.$qso->COL_MODE.' RST '.$qso->COL_RST_SENT.'', '', '', 'Thanks for QSO.');
				}

				$pdf->Add_Label($text);
		}
	}

	function makeMultiQsoLabel($qsos, $pdf, $numberofqsos) {
		$text = '';
		$current_callsign = '';
		$qso_data = [];
		foreach($qsos as $qso) {
			if ($qso->COL_CALL !== $current_callsign) {
				if (!empty($qso_data)) {
					$this->makeLabel($pdf, $current_callsign, $qso_data, $numberofqsos);
					$qso_data = [];
				}
				$current_callsign = $qso->COL_CALL;
			}

			$qso_data[] = [
				'time' => $qso->COL_TIME_ON,
				'band' => $qso->COL_BAND,
				'mode' => $qso->COL_MODE,
				'rst' => $qso->COL_RST_SENT,
				'mygrid' => $qso->station_gridsquare,
				'sat' => $qso->COL_SAT_NAME,
				'sat_mode' => $qso->COL_SAT_MODE,
			];
		}
		if (!empty($qso_data)) {
			$this->makeLabel($pdf, $current_callsign, $qso_data, $numberofqsos);
		}
	}

	function makeLabel($pdf, $current_callsign, $qso_data, $numberofqsos) {
		$text = 'To: ' . $current_callsign . "\n\n";
		$count = 0;
		$qsotext = '';
		foreach ($qso_data as $key => $qso) {
			$time = strtotime($qso['time']);
			$myFormatForView = date("d/m/Y H:i", $time);

			if($qso['sat'] != "") {
				$qsotext .= sprintf("%s %s %s %s\n", $myFormatForView, 'on '.$qso['band'].' 2x'.$qso['mode'].' RST '.$qso['rst'].'', 'Satellite: '.$qso['sat'].' Mode: '.strtoupper($qso['sat_mode']).' ', '');
			} else {
				$qsotext .= sprintf("%s %s\n", $myFormatForView, 'on '.$qso['band'].' 2x'.$qso['mode'].' RST '.$qso['rst']);
			}
			$count++;

			if ($count == $numberofqsos) {
				$text .= $qsotext;
				$text .= "\n" . 'Thanks for QSOs.';
				$pdf->Add_Label($text);
				$text = 'To: ' . $current_callsign . "\n\n";
				$count = 0;
				$qsotext = '';
			}
			unset($qso_data[$key]);
		}

		if ($qsotext != '') {
			$text .= $qsotext;
			$text .= "\n" . 'Thanks for QSOs.';
			$pdf->Add_Label($text);
		}

	}

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