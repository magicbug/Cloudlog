<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class adif extends CI_Controller {

	/* Controls ADIF Import/Export Functions */
	
	/* Shows Export Views */
	public function export() {
		$this->load->view('layout/header');
		$this->load->view('adif/main');
		$this->load->view('layout/footer');
	}
	
	// Export all QSO Data in ASC Order of Date.
	public function exportall()
	{
		$this->load->model('adif_data');

		$data['qsos'] = $this->adif_data->export_all();

		$this->load->view('adif/data/exportall', $data);
	}
	
	public function export_custom() {
		$this->load->model('adif_data');

		$data['qsos'] = $this->adif_data->export_custom($this->input->post('from'), $this->input->post('to'));
		
		$this->load->view('adif/data/exportall', $data);
		
		
	}

	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */