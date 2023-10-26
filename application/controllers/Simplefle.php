<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SimpleFLE extends CI_Controller {

    public function index() {
        $this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }

		
		$this->load->model('stations');
		$this->load->model('logbook_model');
		$this->load->model('modes');
		$this->load->model('bands');

		$data['station_profile'] = $this->stations->all_of_user();			// Used in the view for station location select
		$data['bands'] = $this->bands->get_all_bands_for_user();			// Fetching Bands for FLE 


		$data['page_title'] = "Simple Fast Log Entry";
        
		$footerData = [];
		$footerData['scripts'] = [
			'assets/js/moment.min.js',
			'assets/js/tempusdominus-bootstrap-4.min.js',
			'assets/js/datetime-moment.js',
			'assets/js/sections/simplefle.js?' . filemtime(realpath(__DIR__ . "/../../assets/js/sections/simplefle.js"))
		];

		$this->load->view('interface_assets/header', $data);
		$this->load->view('simplefle/index', $data);
		$this->load->view('interface_assets/footer', $footerData);

    }

	public function displaySyntax() {
		$this->load->view('simplefle/syntax_help');
	}
}