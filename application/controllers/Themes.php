<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/*
	This controller will contain features for contesting
*/

class Themes extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->lang->load('contesting');

		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
	}

	public function index()
	{
		$this->load->model('Themes_model');

		$data['themes'] = $this->Themes_model->getThemes();

		// Render Page
		$data['page_title'] = "Themes";
		$this->load->view('interface_assets/header', $data);
		$this->load->view('themes/index.php');
		$this->load->view('interface_assets/footer');
	}

	public function add()
	{
		$this->load->model('Themes_model');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('name', 'Theme Name', 'required');
		$this->form_validation->set_rules('foldername', 'Folder Name', 'required');

		if ($this->form_validation->run() == FALSE)
		{
			$data['page_title'] = "Create Theme";
			$this->load->view('themes/add', $data);
		}
		else
		{
			$this->Themes_model->add();
		}
	}

	public function edit($id)
	{
		$this->load->library('form_validation');

		$this->load->model('Themes_model');

		$item_id_clean = $this->security->xss_clean($id);

		$data['theme'] = $this->Themes_model->theme($item_id_clean);

		$data['page_title'] = "Edit Theme";

		$this->form_validation->set_rules('name', 'Theme Name', 'required');
		$this->form_validation->set_rules('foldername', 'Folder Name', 'required');

		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('interface_assets/header', $data);
			$this->load->view('themes/edit');
			$this->load->view('interface_assets/footer');
		}
		else
		{
			$this->Themes_model->edit($item_id_clean);

			$data['notice'] = "Theme ".$this->security->xss_clean($this->input->post('name', true))." Updated";

			redirect('themes');
		}
	}

	public function delete() {
		$id = $this->input->post('id');
		$this->load->model('Themes_model');
		$this->Themes_model->delete($id);
	}
}
