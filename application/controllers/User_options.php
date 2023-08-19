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
		foreach($obj as $option_key => $option_value) {
			$obj[$option_key]=$this->security->xss_clean($option_value);
		}
		if ($obj['sat_name'] ?? '' != '') {
			$option_name=$obj['sat_name'].'/'.$obj['mode'];
		} else {
			$option_name=$obj['band'].'/'.$obj['mode'];
		}
		$this->user_options_model->set_option('Favourite',$option_name, $obj);
		$jsonout['success']=1;
		header('Content-Type: application/json');
		echo json_encode($jsonout);
	}

	public function get_fav() {
		$result=$this->user_options_model->get_options('Favourite');
		$jsonout=[];
		foreach($result->result() as $options) {
			$jsonout[$options->option_name][$options->option_key]=$options->option_value;
		}
		header('Content-Type: application/json');
		echo json_encode($jsonout);
	}

	public function del_fav() {
		$result=$this->user_options_model->get_options('Favourite');
		$obj = json_decode(file_get_contents("php://input"), true);
		if ($obj['option_name'] ?? '' != '') {
			$option_name=$this->security->xss_clean($obj['option_name']);
			$this->user_options_model->del_option('Favourite',$option_name);	
		}
		$jsonout['success']=1;
		header('Content-Type: application/json');
		echo json_encode($jsonout);
	}
}


?>
