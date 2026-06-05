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
		$this->load->model('user_options_model');
		$this->load->library('plugin_manager');

		$data['user_awards'] = $this->awards_model->get_user_awards();
		$data['plugin_award_entries'] = $this->plugin_manager->get_award_menu_entries();

		$plugin_award_visibility = array();
		$visibility_query = $this->user_options_model->get_options('plugin_awards_menu');
		foreach ($visibility_query->result() as $option_row) {
			if ($option_row->option_key === 'show') {
				$plugin_award_visibility[$option_row->option_name] = (string)$option_row->option_value === '1';
			}
		}
		$data['plugin_award_visibility'] = $plugin_award_visibility;
		
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

	public function savePluginAward() {
		$plugin_slug = strtolower(trim((string)$this->security->xss_clean($this->input->post('plugin_slug'))));
		$show_in_menu = trim((string)$this->security->xss_clean($this->input->post('show_in_menu')));

		if (!preg_match('/^[a-z0-9_-]+$/', $plugin_slug)) {
			header('Content-Type: application/json');
			echo json_encode(array('message' => 'Invalid plugin slug'));
			return;
		}

		$this->load->library('plugin_manager');
		$entries = $this->plugin_manager->get_award_menu_entries();
		$known_slugs = array();
		foreach ($entries as $entry) {
			$known_slugs[] = $entry['slug'];
		}

		if (!in_array($plugin_slug, $known_slugs, true)) {
			header('Content-Type: application/json');
			echo json_encode(array('message' => 'Plugin award entry not found'));
			return;
		}

		$this->load->model('user_options_model');
		$this->user_options_model->set_option('plugin_awards_menu', $plugin_slug, array(
			'show' => $show_in_menu === '1' ? '1' : '0',
		));

		header('Content-Type: application/json');
		echo json_encode(array('message' => 'OK'));
		return;
	}
}
