<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Information extends CI_Controller
{

	public function welcome()
	{
        $this->load->model('user_model');
        // Make sure users logged in
        if ($this->user_model->validate_session() == 0) {
            // user is not logged in
            redirect('user/login');
        }
        $this->load->model('logbooks_model');
		$logbooks_locations_array = $this->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        echo "welcome to cloudlog";

        // check if user has any station logbooks

        // if user has no logbooks create a General Logbook

        // If logbooks_locations_array is empty
        if (empty($logbooks_locations_array)) {
            // user has no locations
            echo "You have no locations, please add one to continue.";
        }

        // Check if they have provided a valid grid locator

        // Check if Callbook information is provided

        // Check country files are present

        // Information about Cloudlog Aurora

        // If all is present welcome the user and redirect to the dashboard
    }
}