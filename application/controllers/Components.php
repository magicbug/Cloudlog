<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
	Controller to interact with the Clublog API
*/

class Components extends CI_Controller {

	function __construct() {
		parent::__construct();

		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
	}

    public function index() {
        $this->load->model('stations');
        $url = 'https://oscarwatch.org/scripts/hamsat_json.php';
        $json = file_get_contents($url);
        $data['rovedata'] = json_decode($json, true);
        $data['gridsquare'] = strtoupper($this->stations->find_gridsquare());
        
        // load view
        $this->load->view('components/hamsat/table', $data);
    }
}
