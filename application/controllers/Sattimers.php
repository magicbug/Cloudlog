<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sattimers extends CI_Controller {

	function __construct() {
		parent::__construct();

		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
	}

    public function index() {
        $footerData = [];
        $footerData['scripts'] = [
           'assets/js/sections/sattimers.js?'
        ];
        $url = 'https://www.df2et.de/tevel/api.php';
        $json = file_get_contents($url);
        $data['activations'] = json_decode($json, true)['data'];

        $data['page_title'] = "Satellite Timers";
        $this->load->view('interface_assets/header', $data);
        $this->load->view('/sattimers/index', $data);
        $this->load->view('interface_assets/footer', $footerData);
    }
}
