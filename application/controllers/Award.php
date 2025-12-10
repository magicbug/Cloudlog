<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
	Handles Displaying of award preference information
*/

class Award extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));

		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
	}

	public function index()
	{
		$this->load->model('awards_model');

		$data['user_awards'] = $this->awards_model->get_user_awards();
		
		// Render Page
		$data['page_title'] = "Award Settings";
		$this->load->view('interface_assets/header', $data);
		$this->load->view('awards/settings');
		$this->load->view('interface_assets/footer');
	}

	public function saveAward() {
		// Get the award type and value from POST
		$award_type = $this->security->xss_clean($this->input->post('award_type'));
		$award_value = $this->security->xss_clean($this->input->post('award_value'));
        
		$this->load->model('awards_model');
		$result = $this->awards_model->save_single_award($award_type, $award_value);

		header('Content-Type: application/json');
		echo json_encode(array('message' => 'OK'));
		return;
    }

	public function activateall() {
		$this->load->model('awards_model');
        $this->awards_model->activateall();
        
		header('Content-Type: application/json');
        echo json_encode(array('message' => 'OK'));
		return;
    }

	public function deactivateall() {
		$this->load->model('awards_model');
        $this->awards_model->deactivateall();
        
		header('Content-Type: application/json');
        echo json_encode(array('message' => 'OK'));
		return;
    }
}
