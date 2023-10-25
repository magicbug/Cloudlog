<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SimpleFLE extends CI_Controller {

    public function index() {
        $this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }

		$this->load->model('modes');
		$this->load->model('logbook_model');
		$this->load->model('stations');
		$this->load->model('bands');

		$data['station_profile'] = $this->stations->all_of_user();			// Used in the view for station location select

		$data['page_title'] = "Simple Fast Log Entry";

		$this->load->view('interface_assets/header', $data);
		$this->load->view('simplefle/index');
		$this->load->view('interface_assets/footer');
        

    }
}