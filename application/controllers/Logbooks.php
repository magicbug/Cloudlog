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
		$data['station_locations_list'] = $this->stations->all();
		
		$data['page_title'] = "Edit Station Logbook";

		$this->form_validation->set_rules('station_logbook_name', 'Station Logbook Name', 'required');

        if ($this->form_validation->run() == FALSE)
        {
        	$this->load->view('interface_assets/header', $data);
            $this->load->view('logbooks/edit');
            $this->load->view('interface_assets/footer');
        }
        else
        {
            $this->logbooks_model->edit();

            $data['notice'] = "Station Logbooks ".$this->security->xss_clean($this->input->post('station_logbook_name', true))." Updated";

			foreach ($this->input->post('SelectedStationLocations') as $selectedOption){ 
				// Check if theres already a link between logbook and location
				if($this->logbooks_model->relationship_exists($this->input->post('station_logbook_id'), $selectedOption) != TRUE) {
					// If no link exisits create
					$this->logbooks_model->create_logbook_location_link($this->input->post('station_logbook_id'), $selectedOption);
				} else {
					echo "Already Linked";
				}

				// Delete link if removed
			}

            redirect('logbooks');
        }
	}

    public function delete($id) {
		$this->load->model('logbooks_model');
		$this->logbooks_model->delete($id);
		
		redirect('logbooks');
	}

}