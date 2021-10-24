<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
	This controller contains features for contesting
*/

class Contesting extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->lang->load('contesting');

		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
	}

    public function index() {
        $this->load->model('cat');
        $this->load->model('stations');
        $this->load->model('modes');
		$this->load->model('contesting_model');

		$data['my_gridsquare'] = $this->stations->find_gridsquare();
        $data['radios'] = $this->cat->radios();
        $data['modes'] = $this->modes->active();
		$data['contestnames'] = $this->contesting_model->getActivecontests();

		$this->load->library('form_validation');

        $this->form_validation->set_rules('start_date', 'Date', 'required');
        $this->form_validation->set_rules('start_time', 'Time', 'required');
        $this->form_validation->set_rules('callsign', 'Callsign', 'required');

		$data['page_title'] = "Contest Logging";

		$this->load->view('interface_assets/header', $data);
		$this->load->view('contesting/index');
		$this->load->view('interface_assets/footer');
    }

    public function getSessionQsos() {
        //load model
        $this->load->model('Contesting_model');

        $qso = $this->input->post('qso');

        // get QSOs to fill the table
        $data = $this->Contesting_model->getSessionQsos($qso);

        return json_encode($data);
    }

	public function create() {
		$this->load->model('Contesting_model');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('name', 'Contest Name', 'required');
		$this->form_validation->set_rules('adifname', 'Contest Adif Name', 'required');

		if ($this->form_validation->run() == FALSE)
		{
			$data['page_title'] = "Create Mode";
			$this->load->view('contesting/create', $data);
		}
		else
		{
			$this->Contesting_model->add();
		}
	}

	public function add() {
		$this->load->model('Contesting_model');

		$data['contests'] = $this->Contesting_model->getAllContests();

		// Render Page
		$data['page_title'] = "Contests";
		$this->load->view('interface_assets/header', $data);
		$this->load->view('contesting/add');
		$this->load->view('interface_assets/footer');
	}

	public function edit($id) {
		$this->load->library('form_validation');

		$this->load->model('Contesting_model');

		$item_id_clean = $this->security->xss_clean($id);

		$data['contest'] = $this->Contesting_model->contest($item_id_clean);

		$data['page_title'] = "Edit Contest";

		$this->form_validation->set_rules('name', 'Contest Name', 'required');
		$this->form_validation->set_rules('adifname', 'Adif Contest Name', 'required');

		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('interface_assets/header', $data);
			$this->load->view('contesting/edit');
			$this->load->view('interface_assets/footer');
		}
		else
		{
			$this->Contesting_model->edit($item_id_clean);

			$data['notice'] = "Contest ".$this->security->xss_clean($this->input->post('name', true))." Updated";

			redirect('contesting/add');
		}
	}

	public function delete() {
		$id = $this->input->post('id');
		$this->load->model('Contesting_model');
		$this->Contesting_model->delete($id);
	}

	public function activate() {
		$id = $this->input->post('id');
		$this->load->model('Contesting_model');
		$this->Contesting_model->activate($id);
		header('Content-Type: application/json');
		echo json_encode(array('message' => 'OK'));
		return;
	}

	public function deactivate() {
		$id = $this->input->post('id');
		$this->load->model('Contesting_model');
		$this->Contesting_model->deactivate($id);
		header('Content-Type: application/json');
		echo json_encode(array('message' => 'OK'));
		return;
	}

	public function deactivateall() {
		$this->load->model('Contesting_model');
		$this->Contesting_model->deactivateall();
		header('Content-Type: application/json');
		echo json_encode(array('message' => 'OK'));
		return;
	}

	public function activateall() {
		$this->load->model('Contesting_model');
		$this->Contesting_model->activateall();
		header('Content-Type: application/json');
		echo json_encode(array('message' => 'OK'));
		return;
	}

	/*
	 *  Function is used for dupe-checking in contestinglogging
	 */
	public function checkIfWorkedBefore() {
		$call = $this->input->post('call');
		$band = $this->input->post('band');
		$mode = $this->input->post('mode');
		$contest = $this->input->post('contest');
		$qso = $this->input->post('qso');

		$this->load->model('Contesting_model');
		$result = $this->Contesting_model->checkIfWorkedBefore($call, $band, $mode, $contest, $qso);
		
		header('Content-Type: application/json');
		if ($result->num_rows()) {
			echo json_encode(array('message' => 'Worked before'));
		}
		return;
	}
}
