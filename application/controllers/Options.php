<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
	Handles Displaying of information for station tools.
*/

class Options extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));

		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
    }
    
    function index() {


        //echo $this->config->item('option_theme');

		//echo $this->optionslib->get_option('theme');
		
		$data['page_title'] = "Cloudlog Options";

		$this->load->view('interface_assets/header', $data);
		$this->load->view('options/index');
		$this->load->view('interface_assets/footer');
	}
	

	function appearance() {
		$data['page_title'] = "Cloudlog Options";
		$data['sub_heading'] = "Appearance";

		$this->load->view('interface_assets/header', $data);
		$this->load->view('options/appearance');
		$this->load->view('interface_assets/footer');
    }

	function appearance_save() {
		$data['page_title'] = "Cloudlog Options";
		$data['sub_heading'] = "Appearance";
		
		$this->load->helper(array('form', 'url'));

		$this->load->library('form_validation');

		$this->form_validation->set_rules('theme', 'theme', 'required');

		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('interface_assets/header', $data);
			$this->load->view('options/appearance');
			$this->load->view('interface_assets/footer');
		}
		else
		{
			$theme_update_status = $this->optionslib->update('theme', $this->input->post('theme'));

			if($theme_update_status == TRUE) {
				$this->session->set_flashdata('success', 'Theme changed to '.$this->input->post('theme'));
			}
			redirect('/options/appearance');
		}
    }

}