<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
	Handles Displaying of information for Station Logbooks
*/

class Logbooks extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));

		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
	}

    function index() {
		$this->load->model('logbooks_model');

		$data['my_logbooks'] = $this->logbooks_model->show_all();

		// Render Page
		$data['page_title'] = "Station Logbooks";
		$this->load->view('interface_assets/header', $data);
		$this->load->view('logbooks/index');
		$this->load->view('interface_assets/footer');
    }

    public function create() 
	{
		$this->load->library('form_validation');

		$this->form_validation->set_rules('stationLogbook_Name', 'Station Logbook Name', 'required');

		if ($this->form_validation->run() == FALSE)
		{
			$data['page_title'] = "Create Station Logbook";
			$this->load->view('interface_assets/header', $data);
			$this->load->view('logbooks/create');
			$this->load->view('interface_assets/footer');
		}
		else
		{	
            $this->load->model('logbooks_model');
			$this->logbooks_model->add();
			
			redirect('logbooks');
		}
	}

    public function edit($id)
	{
		$this->load->library('form_validation');

		$this->load->model('logbooks_model');
		$this->load->model('stations');

		$station_logbook_id = $this->security->xss_clean($id);

		$station_logbook_details_query = $this->logbooks_model->logbook($station_logbook_id);
		$data['station_locations_array'] = $this->logbooks_model->list_logbook_relationships($station_logbook_id);

		$data['station_logbook_details'] = $station_logbook_details_query->row();
		$data['station_locations_list'] = $this->stations->all_of_user();

		$data['station_locations_linked'] = $this->logbooks_model->list_logbooks_linked($station_logbook_id);
		
		$data['page_title'] = "Edit Station Logbook";

		$this->form_validation->set_rules('station_logbook_id', 'Station Logbook Name', 'required');

        if ($this->form_validation->run() == FALSE)
        {
        	$this->load->view('interface_assets/header', $data);
            $this->load->view('logbooks/edit');
            $this->load->view('interface_assets/footer');
        }
        else
        {

            $data['notice'] = "Station Logbooks ".$this->security->xss_clean($this->input->post('station_logbook_name', true))." Updated";

			if($this->input->post('SelectedStationLocation') != "") {
				if($this->logbooks_model->relationship_exists($this->input->post('station_logbook_id'), $this->input->post('SelectedStationLocation')) != TRUE) {
					// If no link exisits create
					$this->logbooks_model->create_logbook_location_link($this->input->post('station_logbook_id'), $this->input->post('SelectedStationLocation'));
				}
			} else {
				$this->logbooks_model->edit();
			}

            redirect('logbooks/edit/'.$this->input->post('station_logbook_id'));
        }
	}

	public function set_active($id) {
		$this->load->model('logbooks_model');
		$this->logbooks_model->set_logbook_active($id);
		$this->user_model->update_session($this->session->userdata('user_id'));

		redirect('logbooks');
	}

    public function delete($id) {
		$this->load->model('logbooks_model');
		$this->logbooks_model->delete($id);
		
		redirect('logbooks');
	}

	public function delete_relationship($logbook_id, $station_id) {
		$this->load->model('logbooks_model');
		$this->logbooks_model->delete_relationship($logbook_id, $station_id);
		
		redirect('logbooks/edit/'.$logbook_id);
	}

	public function publicslug_validate() {
		$this->load->model('logbooks_model');
		$result = $this->logbooks_model->is_public_slug_available($this->input->post('public_slug'));
		
		if($result == true) {
			$data['slugAvailable'] = true;
		} else {
			$data['slugAvailable'] = false;
		}

		$this->load->view('logbooks/components/publicSlugInputValidation', $data);
	}

	public function save_publicsearch() {
		$this->load->model('logbooks_model');
			$returndata = $this->logbooks_model->save_public_search($this->input->post('public_search'), $this->input->post('logbook_id'));
			echo "<div class=\"alert alert-success\" role=\"alert\">Public Search Settings Saved</div>";
	}

	public function save_publicslug() {
		$this->load->model('logbooks_model');

		$this->load->library('form_validation');

		$this->form_validation->set_rules('public_slug', 'Public Slug', 'required|alpha_numeric');

		if ($this->form_validation->run() == FALSE)
		{
			echo "<div class=\"alert alert-danger\" role=\"alert\">Oops! This Public Slug is unavailable</div>";
			echo validation_errors();
		}
		else
		{
			$this->load->model('logbooks_model');
			$result = $this->logbooks_model->is_public_slug_available($this->input->post('public_slug'));
			
	
			if($result == true) {
				$returndata = $this->logbooks_model->save_public_slug($this->input->post('public_slug'), $this->input->post('logbook_id'));
				echo "<div class=\"alert alert-success\" role=\"alert\">Public Slug Saved</div>";
			} else {
				echo "<div class=\"alert alert-danger\" role=\"alert\">Oops! This Public Slug is unavailable</div>";
			}
		}
	}

	public function remove_publicslug() {
		$this->load->model('logbooks_model');

		$this->logbooks_model->remove_public_slug($this->input->post('logbook_id'));
		if ($this->db->affected_rows() > 0) {
			echo "<div class=\"alert alert-success\" role=\"alert\">Public Slug Removed</div>";
		} else {
			echo "<div class=\"alert alert-danger\" role=\"alert\">Oops! This Public Slug could not be removed</div>";
		}
	}

}
