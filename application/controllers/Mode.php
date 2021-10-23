<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
	Handles Displaying of information for mode tools.
*/

class Mode extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));

		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
	}

	public function index()
	{
		$this->load->model('modes');

		$data['modes'] = $this->modes->all();
		
		// Render Page
		$data['page_title'] = "Modes";
		$this->load->view('interface_assets/header', $data);
		$this->load->view('mode/index');
		$this->load->view('interface_assets/footer');
	}

	public function create() 
	{
		$this->load->model('modes');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('mode', 'Mode', 'required');
		$this->form_validation->set_rules('qrgmode', 'QRG-Mode', 'required');

		if ($this->form_validation->run() == FALSE)
		{
			$data['page_title'] = "Create Mode";
			$this->load->view('mode/create', $data);
		}
		else
		{	
			$this->modes->add();
		}
	}

	public function edit($id)
	{
		$this->load->library('form_validation');

		$this->load->model('modes');

		$item_id_clean = $this->security->xss_clean($id);

		$mode_query = $this->modes->mode($item_id_clean);

		$data['my_mode'] = $mode_query->row();
		
		$data['page_title'] = "Edit Mode";

		$this->form_validation->set_rules('mode', 'Mode', 'required');
		$this->form_validation->set_rules('qrgmode', 'QRG-Mode', 'required');
		
        if ($this->form_validation->run() == FALSE)
        {
        	$this->load->view('interface_assets/header', $data);
            $this->load->view('mode/edit');
            $this->load->view('interface_assets/footer');
        }
        else
        {
            $this->modes->edit();

            $data['notice'] = "Mode ".$this->security->xss_clean($this->input->post('mode', true))." Updated";

            redirect('mode');
        }
	}

	public function delete() {
	    $id = $this->input->post('id');
		$this->load->model('modes');
		$this->modes->delete($id);
	}

	public function activate() {
        $id = $this->input->post('id');
        $this->load->model('modes');
        $this->modes->activate($id);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'OK'));
        return;
    }

    public function deactivate() {
	    $id = $this->input->post('id');
        $this->load->model('modes');
        $this->modes->deactivate($id);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'OK'));
        return;
    }

	public function activateall() {
        $this->load->model('modes');
        $this->modes->activateall();
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'OK'));
        return;
    }

    public function deactivateall() {
        $this->load->model('modes');
        $this->modes->deactivateall();
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'OK'));
        return;
    }
}