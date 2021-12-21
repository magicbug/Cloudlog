<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dxatlas extends CI_Controller {

	public function index()	{
		$this->load->model('user_model');

		if(!$this->user_model->authorize(99)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }

		$this->load->model('modes');
		$this->load->model('logbook_model');
		$this->load->model('stations');
		$this->load->model('bands');

		$data['station_profile'] = $this->stations->all_of_user();			// Used in the view for station location select
		$data['worked_bands'] = $this->bands->get_worked_bands(); 	// Used in the view for band select
		$data['modes'] = $this->modes->active(); 					// Used in the view for mode select
		$data['dxcc'] = $this->logbook_model->fetchDxcc(); 			// Used in the view for dxcc select

		$data['page_title'] = "DX Atlas Gridsquare Export";

		$this->load->view('interface_assets/header', $data);
		$this->load->view('dxatlas/index');
		$this->load->view('interface_assets/footer');

	}

	public function export()  {
		$this->load->model('dxatlas_model');

		// Parameters
		$station_id = $this->security->xss_clean($this->input->post('station_profile'));
		$band = $this->security->xss_clean($this->input->post('band'));
		$mode = $this->security->xss_clean($this->input->post('mode'));
		$dxcc = $this->security->xss_clean($this->input->post('dxcc_id'));
		$cqz = $this->security->xss_clean($this->input->post('cqz'));
		$propagation = $this->security->xss_clean($this->input->post('prop_mode'));
		$fromdate = $this->security->xss_clean($this->input->post('fromdate'));
		$todate = $this->security->xss_clean($this->input->post('todate'));

		// Get QSOs with Valid QRAs
		$grids = $this->dxatlas_model->get_gridsquares($station_id, $band, $mode, $dxcc, $cqz, $propagation, $fromdate, $todate);

		$this->generateFiles($grids['worked'], $grids['confirmed'], $band);
	}

	function generateFiles($wkdArray, $cfmArray, $band) {

		$gridCfmArray = [];
		$gridWkdArray = [];
		$fieldCfmArray = [];
		$fieldWkdArray = [];

		foreach ($cfmArray as $grid) {
			$field = substr($grid, 0, 2);
			if (!in_array($field, $fieldCfmArray)) {
				$fieldCfmArray[] = $field;
			}
			$gridCfmArray[] = $grid;
		}


		foreach ($wkdArray as $grid) {
			$field = substr($grid, 0, 2);
			if (!in_array($field, $fieldCfmArray)) {
				if (!in_array($field, $fieldWkdArray)) {
					$fieldWkdArray[] = $field;
				}
			}
			if (!in_array($grid, $gridCfmArray)) {
				$gridWkdArray[] = $grid;
			}
		}

		$gridWkdString = '';
		$gridCfmString = '';

		asort($gridWkdArray);
		asort($gridCfmArray);
		asort($fieldWkdArray);
		asort($fieldCfmArray);

		foreach ($fieldWkdArray as $fields) {
			$gridWkdString .= $fields . "\r\n";
		}

		foreach ($gridWkdArray as $grids) {
			$gridWkdString .= $grids . "\r\n";
		}

		foreach ($fieldCfmArray as $fields) {
			$gridCfmString .= $fields . "\r\n";
		}

		foreach ($gridCfmArray as $grids) {
			$gridCfmString .= $grids . "\r\n";
		}

		$this->makeZip($gridWkdString, $gridCfmString, $band);
	}

	function makeZip($gridWkdString, $gridCfmString, $band) {
		$zipFileName = 'dxatlas_gridsquares_'. $band . '.zip';
		// Prepare File
		$file = tempnam("tmp", "zip");
		$zip = new ZipArchive();
		$zip->open($file, ZipArchive::OVERWRITE);

		// Stuff with content
		$zip->addFromString($band . '_grids.wkd', $gridWkdString);
		$zip->addFromString($band . '_grids.cfm', $gridCfmString);

		// Close and send to users
		$zip->close();
		$length = filesize($file);
		header('Content-Type: application/zip');
		header('Content-Length: ' . $length);
		header('Content-Disposition: attachment; filename="' . $zipFileName . '"');
		readfile($file);
		unlink($file);
	}
}
