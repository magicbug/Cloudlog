<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
	Handles Displaying of band information
*/

class Band extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));

		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
	}

	public function index()
	{
		$this->load->model('bands');

		$data['bands'] = $this->bands->get_all_bands_for_user();
		
		// Render Page
		$data['page_title'] = "Bands";
		$this->load->view('interface_assets/header', $data);
		$this->load->view('bands/index');
		$this->load->view('interface_assets/footer');
	}

	public function create() 
	{
		$this->load->model('bands');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('band', 'Band', 'required');

		if ($this->form_validation->run() == FALSE)
		{
			$data['page_title'] = "Create Mode";
			$this->load->view('bands/create', $data);
		}
		else
		{	
			$this->bands->add();
		}
	}

	public function edit()
	{
		$this->load->model('bands');

		$item_id_clean = $this->security->xss_clean($this->input->post('id'));

		$band_query = $this->bands->getband($item_id_clean);

		$data['my_band'] = $band_query->row();
		
		$data['page_title'] = "Edit Band";

        $this->load->view('bands/edit', $data);
	}

	public function saveupdatedband() {
		$this->load->model('bands');

		$id = $this->security->xss_clean($this->input->post('id', true));
		$band['band'] 		= $this->security->xss_clean($this->input->post('band', true));
		$band['bandgroup'] 	= $this->security->xss_clean($this->input->post('bandgroup', true));
		$band['ssbqrg'] 	= $this->security->xss_clean($this->input->post('ssbqrg', true));
		$band['dataqrg'] 	= $this->security->xss_clean($this->input->post('dataqrg', true));
		$band['cwqrg'] 		= $this->security->xss_clean($this->input->post('cwqrg', true));

        $this->bands->saveupdatedband($id, $band);
		echo json_encode(array('message' => 'OK'));
        return;
	}

	public function delete() {
	    $id = $this->input->post('id');
		$this->load->model('bands');
		$this->bands->delete($id);
	}

	public function activate() {
        $id = $this->input->post('id');
        $this->load->model('bands');
        $this->bands->activate($id);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'OK'));
        return;
    }

    public function deactivate() {
	    $id = $this->input->post('id');
        $this->load->model('bands');
        $this->bands->deactivate($id);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'OK'));
        return;
    }

	public function activateall() {
        $this->load->model('bands');
        $this->bands->activateall();
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'OK'));
        return;
    }

    public function deactivateall() {
        $this->load->model('bands');
        $this->bands->deactivateall();
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'OK'));
		return;
    }

	public function saveBand() {
		$id 				= $this->security->xss_clean($this->input->post('id'));
		$band['status'] 	= $this->security->xss_clean($this->input->post('status'));
		$band['cq'] 		= $this->security->xss_clean($this->input->post('cq'));
		$band['dok'] 		= $this->security->xss_clean($this->input->post('dok'));
		$band['dxcc'] 		= $this->security->xss_clean($this->input->post('dxcc'));
		$band['iota'] 		= $this->security->xss_clean($this->input->post('iota'));
		$band['sig'] 		= $this->security->xss_clean($this->input->post('sig'));
		$band['sota']		= $this->security->xss_clean($this->input->post('sota'));
		$band['uscounties'] = $this->security->xss_clean($this->input->post('uscounties'));
		$band['was'] 		= $this->security->xss_clean($this->input->post('was'));
		$band['wwff'] 		= $this->security->xss_clean($this->input->post('wwff'));
		$band['vucc'] 		= $this->security->xss_clean($this->input->post('vucc'));
        
		$this->load->model('bands');
        $this->bands->saveBand($id, $band);
        
		header('Content-Type: application/json');
        echo json_encode(array('message' => 'OK'));
		return;
    }

	public function saveBandAward() {
		$award  = $this->security->xss_clean($this->input->post('award'));
		$status	= $this->security->xss_clean($this->input->post('status'));
        
		$this->load->model('bands');
        $this->bands->saveBandAward($award, $status);
        
		header('Content-Type: application/json');
        echo json_encode(array('message' => 'OK'));
		return;
    }
}