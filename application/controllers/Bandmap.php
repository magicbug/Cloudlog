<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bandmap extends CI_Controller {

	function __construct() {
		parent::__construct();

		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
		$this->load->model('bands');
	}

	function index() {
        $footerData = [];
		$footerData['scripts'] = [
			'assets/js/sections/bandmap.js',
		];

		$data['page_title'] = "Bandmap";
		$this->load->view('interface_assets/header', $data);
		$this->load->view('bandmap/index');
		$this->load->view('interface_assets/footer', $footerData);
	}
}
