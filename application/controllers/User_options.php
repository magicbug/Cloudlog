<?php

class User_Options extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('user_options_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
	}

	public function add_edit_fav() {
		$obj = json_decode(file_get_contents("php://input"), true);
		if ($obj['sat_name'] ?? '' != '') {
			$option_name=$obj['sat_name'];
		} else {
			$option_name=$obj['band'].'/'.$obj['mode'];
		}
		$this->user_options_model->set_option('Favourite',$option_name, $obj);
	}
}


?>
