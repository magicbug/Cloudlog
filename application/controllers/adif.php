<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class adif extends CI_Controller {

	/* Controls ADIF Import/Export Functions */
	
	/* Shows Export Views */
	public function export() {

		$data['page_title'] = "ADIF Export";

		$this->load->view('layout/header', $data);
		$this->load->view('adif/main');
		$this->load->view('layout/footer');
	}
	
	// Export all QSO Data in ASC Order of Date.
	public function exportall()
	{
		// Set memory limit to unlimited to allow heavy usage
		ini_set('memory_limit', '-1');
		
		$this->load->model('adif_data');

		$data['qsos'] = $this->adif_data->export_all();

		$this->load->view('adif/data/exportall', $data);
	}
	
	public function export_custom() {
	
		// Set memory limit to unlimited to allow heavy usage
		ini_set('memory_limit', '-1');
	
		$this->load->model('adif_data');

		$data['qsos'] = $this->adif_data->export_custom($this->input->post('from'), $this->input->post('to'));
		
		$this->load->view('adif/data/exportall', $data);
		
		
	}

	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */