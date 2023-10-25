<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SimpleFLE extends CI_Controller {

    public function index() {
        $this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }

		$data['page_title'] = "Simple Fast Log Entry";

		$this->load->view('interface_assets/header', $data);
		$this->load->view('simplefle/index');
		$this->load->view('interface_assets/footer');
        

    }
}