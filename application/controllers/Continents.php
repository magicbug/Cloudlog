<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Continents extends CI_Controller {

	public function index()
	{
        $this->load->model('user_model');
        $this->load->model('bands');
        $this->load->model('logbookadvanced_model');

        $data['bands'] = $this->bands->get_worked_bands();
		$data['modes'] = $this->logbookadvanced_model->get_modes();

        if(!$this->user_model->authorize($this->config->item('auth_mode'))) {
            if($this->user_model->validate_session()) {
                $this->user_model->clear_session();
                show_error('Access denied<p>Click <a href="'.site_url('user/login').'">here</a> to log in as another user', 403);
            } else {
                redirect('user/login');
            }
        }	
		// Render User Interface

		// Set Page Title
		$data['page_title'] = "Continents";

		// Load Views
		$this->load->view('interface_assets/header', $data);
		$this->load->view('continents/index');
		$this->load->view('interface_assets/footer');
	}
	

	public function get_continents() {

		$searchCriteria = array(
			'mode' => xss_clean($this->input->post('mode')),
			'band' => xss_clean($this->input->post('band')),
		);

		$this->load->model('logbook_model');

		$continentsstats = array();

		$total_continents = $this->logbook_model->total_continents($searchCriteria);
		$i = 0;

		if ($total_continents) {
			foreach($total_continents->result() as $qso_numbers) {
				$continentsstats[$i]['cont'] = $qso_numbers->COL_CONT;
				$continentsstats[$i++]['count'] = $qso_numbers->count;
			}
		}

		header('Content-Type: application/json');
		echo json_encode($continentsstats);
	}

}
