<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dxatlas extends CI_Controller {

	public function index()
	{
		$this->load->model('user_model');
		$this->load->model('modes');
		$this->load->model('dxcc');
		$this->load->model('logbook_model');

		if(!$this->user_model->authorize(99)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }

		$data['worked_bands'] = $this->dxcc->get_worked_bands(); 	// Used in the view for band select
		$data['modes'] = $this->modes->active(); 					// Used in the view for mode select
		$data['dxcc'] = $this->logbook_model->fetchDxcc(); 			// Used in the view for dxcc select

		$data['page_title'] = "DX Atlas Gridsquare Export";

		$this->load->view('interface_assets/header', $data);
		$this->load->view('dxatlas/index');
		$this->load->view('interface_assets/footer');

	}

	public function export()
	{
		$this->load->model('dxatlas_model');

		// Parameters
		$band = $this->input->post('band');
		$mode = $this->input->post('mode');
		$dxcc = $this->input->post('dxcc_id');
		$cqz = $this->input->post('cqz');
		$propagation = $this->input->post('prop_mode');
		$fromdate = $this->input->post('fromdate');
		$todate = $this->input->post('todate');

		// Get QSOs with Valid QRAs
		$grids = $this->dxatlas_model->get_gridsquares($band, $mode, $dxcc, $cqz, $propagation, $fromdate, $todate);

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
		$zipFileName = $this->session->userdata('user_callsign') . '_'. $band . '.zip';
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
