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

		// Check if user has at least write access
		if (!$this->logbooks_model->check_logbook_is_accessible($station_logbook_id, 'write')) {
			$this->session->set_flashdata('notice', 'You don\'t have permission to edit this logbook');
			redirect('logbooks');
		}

		$station_logbook_details_query = $this->logbooks_model->logbook($station_logbook_id);
		$data['station_locations_array'] = $this->logbooks_model->list_logbook_relationships($station_logbook_id);

		$data['station_logbook_details'] = $station_logbook_details_query->row();
		$data['station_locations_list'] = $this->stations->all_of_user();

		$data['station_locations_linked'] = $this->logbooks_model->list_logbooks_linked($station_logbook_id);
		$data['is_owner'] = $this->logbooks_model->is_logbook_owner($station_logbook_id);
		
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
				// Only owners can rename logbooks
				if ($this->logbooks_model->is_logbook_owner($this->input->post('station_logbook_id'))) {
					$this->logbooks_model->edit();
				} else {
					$this->session->set_flashdata('notice', 'Only the owner can rename a logbook');
				}
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
		$this->load->model('stations');
		
		// Check if user has at least write access to the logbook
		if (!$this->logbooks_model->check_logbook_is_accessible($logbook_id, 'write')) {
			$this->session->set_flashdata('notice', 'You don\'t have permission to modify this logbook');
			redirect('logbooks');
		}
		
		// Get station location details
		$station = $this->stations->profile($station_id);
		
		if ($station) {
			$is_owner = $this->logbooks_model->is_logbook_owner($logbook_id);
			$owns_station = ($station->user_id == $this->session->userdata('user_id'));
			
			// Only allow unlinking if user is logbook owner OR owns the station location
			if ($is_owner || $owns_station) {
				$this->logbooks_model->delete_relationship($logbook_id, $station_id);
			} else {
				$this->session->set_flashdata('notice', 'You can only unlink your own station locations');
			}
		}
		
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
		
		$logbook_id = $this->input->post('logbook_id');
		
		// Only owners can modify public settings
		if (!$this->logbooks_model->is_logbook_owner($logbook_id)) {
			echo "<div class=\"alert alert-danger\" role=\"alert\">Only the owner can modify public settings</div>";
			return;
		}
		
		// Handle checkbox - if not checked, it won't be sent, so default to 0
		$public_search = $this->input->post('public_search') ? 1 : 0;
		$returndata = $this->logbooks_model->save_public_search($public_search, $logbook_id);
		echo "<div class=\"alert alert-success\" role=\"alert\">Public Search Settings Saved</div>";
	}

	public function save_publicradiostatus() {
		$this->load->model('logbooks_model');
		
		$logbook_id = $this->input->post('logbook_id');
		
		// Only owners can modify public settings
		if (!$this->logbooks_model->is_logbook_owner($logbook_id)) {
			echo "<div class=\"alert alert-danger\" role=\"alert\">Only the owner can modify public settings</div>";
			return;
		}
		
		// Handle checkbox - if not checked, it won't be sent, so default to 0
		$public_radio_status = $this->input->post('public_radio_status') ? 1 : 0;
		$returndata = $this->logbooks_model->save_public_radio_status($public_radio_status, $logbook_id);
		echo "<div class=\"alert alert-success\" role=\"alert\">Public Radio Status Settings Saved</div>";
	}

	public function save_publicslug() {
		$this->load->model('logbooks_model');

		$logbook_id = $this->input->post('logbook_id');
		
		// Only owners can modify public settings
		if (!$this->logbooks_model->is_logbook_owner($logbook_id)) {
			echo "<div class=\"alert alert-danger\" role=\"alert\">Only the owner can modify public settings</div>";
			return;
		}

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
				$returndata = $this->logbooks_model->save_public_slug($this->input->post('public_slug'), $logbook_id);
				echo "<div class=\"alert alert-success\" role=\"alert\">Public Slug Saved</div>";
			} else {
				echo "<div class=\"alert alert-danger\" role=\"alert\">Oops! This Public Slug is unavailable</div>";
			}
		}
	}

	public function remove_publicslug() {
		$this->load->model('logbooks_model');

		$logbook_id = $this->input->post('logbook_id');
		
		// Only owners can modify public settings
		if (!$this->logbooks_model->is_logbook_owner($logbook_id)) {
			echo "<div class=\"alert alert-danger\" role=\"alert\">Only the owner can modify public settings</div>";
			return;
		}

		$this->logbooks_model->remove_public_slug($logbook_id);
		if ($this->db->affected_rows() > 0) {
			echo "<div class=\"alert alert-success\" role=\"alert\">Public Slug Removed</div>";
		} else {
			echo "<div class=\"alert alert-danger\" role=\"alert\">Oops! This Public Slug could not be removed</div>";
		}
	}

	public function manage_sharing($logbook_id) {
		// Display sharing management interface
		$this->load->model('logbooks_model');
		
		$clean_id = $this->security->xss_clean($logbook_id);
		
		// Check if user has admin access or is owner
		if (!$this->logbooks_model->is_logbook_owner($clean_id) && 
			!$this->logbooks_model->check_logbook_is_accessible($clean_id, 'admin')) {
			$this->session->set_flashdata('notice', 'You\'re not allowed to manage sharing for this logbook!');
			redirect('logbooks');
		}
		
		$data['logbook'] = $this->logbooks_model->logbook($clean_id)->row();
		$data['collaborators'] = $this->logbooks_model->list_logbook_collaborators($clean_id);
		$data['is_owner'] = $this->logbooks_model->is_logbook_owner($clean_id);
		
		$data['page_title'] = "Manage Logbook Sharing";
		$this->load->view('interface_assets/header', $data);
		$this->load->view('logbooks/manage_sharing');
		$this->load->view('interface_assets/footer');
	}

	public function add_user() {
		// Add a user to a logbook via AJAX/HTMX
		$this->load->model('logbooks_model');
		
		$logbook_id = $this->security->xss_clean($this->input->post('logbook_id'));
		$user_identifier = $this->security->xss_clean($this->input->post('user_identifier'));
		$permission_level = $this->security->xss_clean($this->input->post('permission_level'));
		
		// Check if current user has admin rights or is owner
		if (!$this->logbooks_model->is_logbook_owner($logbook_id) && 
			!$this->logbooks_model->check_logbook_is_accessible($logbook_id, 'admin')) {
			echo "<div class=\"alert alert-danger\" role=\"alert\">You don't have permission to add users to this logbook</div>";
			return;
		}
		
		// Try to find user by email first, then by callsign
		$user_query = $this->user_model->get_by_email($user_identifier);
		if ($user_query->num_rows() == 0) {
			$user_query = $this->user_model->get_by_callsign($user_identifier);
		}
		
		if ($user_query->num_rows() == 0) {
			echo "<div class=\"alert alert-danger\" role=\"alert\">User not found. Please check the email or callsign.</div>";
			return;
		}
		
		$user = $user_query->row();
		
		// Check if user is trying to add themselves
		if ($user->user_id == $this->session->userdata('user_id')) {
			echo "<div class=\"alert alert-danger\" role=\"alert\">You cannot add yourself to the logbook.</div>";
			return;
		}
		
		// Check if user is the owner
		if ($this->logbooks_model->get_user_permission($logbook_id, $user->user_id) == 'owner') {
			echo "<div class=\"alert alert-danger\" role=\"alert\">This user is the owner of the logbook.</div>";
			return;
		}
		
		// Add permission
		$result = $this->logbooks_model->add_logbook_permission($logbook_id, $user->user_id, $permission_level);
		
		if ($result) {
			// Return updated collaborators list
			$data['collaborators'] = $this->logbooks_model->list_logbook_collaborators($logbook_id);
			$data['is_owner'] = $this->logbooks_model->is_logbook_owner($logbook_id);
			$this->load->view('logbooks/components/collaborators_table', $data);
		} else {
			echo "<div class=\"alert alert-danger\" role=\"alert\">Failed to add user. Please try again.</div>";
		}
	}

	public function remove_user() {
		// Remove a user from a logbook via AJAX/HTMX
		$this->load->model('logbooks_model');
		
		$logbook_id = $this->security->xss_clean($this->input->post('logbook_id'));
		$user_id = $this->security->xss_clean($this->input->post('user_id'));
		
		// Check if current user has admin rights or is owner
		if (!$this->logbooks_model->is_logbook_owner($logbook_id) && 
			!$this->logbooks_model->check_logbook_is_accessible($logbook_id, 'admin')) {
			echo "<div class=\"alert alert-danger\" role=\"alert\">You don't have permission to remove users from this logbook</div>";
			return;
		}
		
		// Remove permission
		$result = $this->logbooks_model->remove_logbook_permission($logbook_id, $user_id);
		
		if ($result) {
			// Return updated collaborators list
			$data['collaborators'] = $this->logbooks_model->list_logbook_collaborators($logbook_id);
			$data['is_owner'] = $this->logbooks_model->is_logbook_owner($logbook_id);
			$this->load->view('logbooks/components/collaborators_table', $data);
		} else {
			echo "<div class=\"alert alert-danger\" role=\"alert\">Failed to remove user. Please try again.</div>";
		}
	}

	public function validate_user() {
		// Validate user exists via AJAX/HTMX
		$this->load->model('user_model');
		
		$user_identifier = $this->security->xss_clean($this->input->post('user_identifier'));
		
		if (empty($user_identifier)) {
			echo '';
			return;
		}
		
		// Try to find user by email first, then by callsign
		$user_query = $this->user_model->get_by_email($user_identifier);
		if ($user_query->num_rows() == 0) {
			$user_query = $this->user_model->get_by_callsign($user_identifier);
		}
		
		if ($user_query->num_rows() > 0) {
			$user = $user_query->row();
			// Check if it's the current user
			if ($user->user_id == $this->session->userdata('user_id')) {
				echo '<span class="text-warning"><i class="fas fa-exclamation-triangle"></i> Cannot add yourself</span>';
			} else {
				echo '<span class="text-success"><i class="fas fa-check-circle"></i> User found: ' . htmlspecialchars($user->user_callsign) . '</span>';
			}
		} else {
			echo '<span class="text-danger"><i class="fas fa-times-circle"></i> User not found</span>';
		}
	}

}
