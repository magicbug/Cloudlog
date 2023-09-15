<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
	Controller to interact with the Clublog API
*/

class Hamsat extends CI_Controller {

	function __construct() {
		parent::__construct();

		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
	}

    public function index() {
        // Load public view
        $data['page_title'] = "Hamsat - Satellite Roving";
        $this->load->view('interface_assets/header', $data);
        $this->load->view('/hamsat/index');
        $this->load->view('interface_assets/footer');
    }
}
