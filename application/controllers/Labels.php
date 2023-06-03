<?php 

use Cloudlog\Label\PDF_Label;
use Cloudlog\Label\fpdf;

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

	public function print($station_id) {
		$clean_id = xss_clean($station_id);

		$this->load->model('adif_data');
		$result = $this->adif_data->export_printrequested($clean_id);

		$this->load->model('labels_model');
		$label = $this->labels_model->getDefaultLabel();

		// require_once('fpdf.php');
		// require('PDF_Label.php');
		// require_once APPPATH."/src/Label/PDF_Label.php";
		// require_once APPPATH."/src/Label/fpdf.php";

		// Example of custom format
		// $pdf = new PDF_Label(array('paper-size'=>'A4', 'metric'=>'mm', 'marginLeft'=>1, 'marginTop'=>1, 'NX'=>2, 'NY'=>7, 'SpaceX'=>0, 'SpaceY'=>0, 'width'=>99, 'height'=>38, 'font-size'=>14));

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
				)
			);
		} else {
			// Standard format
			$pdf = new PDF_Label('3422');
		}


		$pdf->AddPage();

		if ($result->num_rows() > 0) {
			// output data of each row
			foreach($result->result() as $qso) {
				$time = strtotime($qso->COL_TIME_ON);
				$myFormatForView = date("d/m/Y H:i", $time);
					if($qso->COL_SAT_NAME != "") {
						$text = sprintf("%s\n\n%s %s\n%s %s \n\n%s", 'To: '.$qso->COL_CALL, $myFormatForView, 'on '.$qso->COL_BAND.' 2x'.$qso->COL_MODE.' RST '.$qso->COL_RST_SENT.'', 'Satellite: '.$qso->COL_SAT_NAME.' Mode: '.strtoupper($qso->COL_SAT_MODE).' ', '', 'Thanks for QSO.');
					} else {
						$text = sprintf("%s\n\n%s %s\n%s %s \n\n%s", 'To: '.$qso->COL_CALL, $myFormatForView, 'on '.$qso->COL_BAND.' 2x'.$qso->COL_MODE.' RST '.$qso->COL_RST_SENT.'', '', '', 'Thanks for QSO.');
					}

					$pdf->Add_Label($text);
			}
		} else {
			echo "0 results";
		}
		$pdf->Output();
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
		redirect('labels');
	}

	public function delete($id) {
		$this->load->model('labels_model');
		$this->labels_model->deleteLabel($id);
		redirect('labels');
	}

	public function saveDefaultLabel() {
		$id = $this->input->post('id');
		$this->load->model('labels_model');
		$this->labels_model->saveDefaultLabel($id);
	}

}