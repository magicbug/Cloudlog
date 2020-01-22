<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
		This controller "Register" handles all things todo with creating an account within Cloudlog
*/

class Register extends CI_Controller {

    public function index()
	{
		// Load DXCC Model
		$this->load->model('dxcc');

		$data['page_title'] = "Register";
		$data['dxcc_list'] = $this->dxcc->list();
        
        $this->load->helper(array('form', 'url'));

        $this->load->library('form_validation');

        $this->form_validation->set_rules('username', 'Username', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == FALSE)
        {
			$this->load->view('interface_assets/mini_header.php', $data);
			$this->load->view('authentication/register/register.php');
			$this->load->view('interface_assets/mini_footer.php', $data);
        }
        else
        {
        	// Success!

        	/*
				Todo
				- Need to create fields for legal in table
				- Assign Station Profile to user
				- Send confirmation email to user
        	*/

			// Store Field Information
			$account_data['username'] = $this->input->post('username');
        	$account_data['firstname'] = $this->input->post('firstname');
        	$account_data['lastname'] = $this->input->post('lastname');
        	$account_data['email'] = $this->input->post('email');
        	$account_data['password'] = $this->input->post('password');

        	$account_data['callsign'] = strtoupper($this->input->post('callsign'));
        	$account_data['gridsquare'] = strtoupper($this->input->post('gridsquare'));
        	$account_data['dxcc_adif_value'] = $this->input->post('dxcc');
        	$account_data['station_country'] = $this->input->post('station_country');
        	$account_data['cq_zone'] = $this->input->post('cq_zone');
        	$account_data['itu_zone'] = $this->input->post('itu_zone');

        	$this->load->model('user_model');

        	// Create User Account
        	$user_create_results = $this->user_model->add($account_data['username'], $account_data['password'], $account_data['email'], 5, $account_data['firstname'], $account_data['lastname'], $account_data['callsign'], $account_data['gridsquare'], 0);

        	// If account creation failed then go back to register
        	if($user_create_results != "OK") {
        		$this->session->set_flashdata('Error', 'Account creation failed contact support');
        		redirect('register');
        	}

        	// Get User account details
        	$user_details = $this->user_model->get_user_details($account_data['username']);

        	// Load Stations model
        	$this->load->model('stations');

        	// Create a basic station profile
        	$this->stations->create_basic_profile("Home QTH", $account_data['gridsquare'], $account_data['callsign'], $account_data['dxcc_adif_value'], $account_data['station_country'], $account_data['cq_zone'], $account_data['itu_zone'], $user_details->user_id);

        	// Send Email to User
        		/*
        			- Username
        			- Link to a quick start guide
        			- Mention CloudlogCAT
        			- Mention how to support Cloudlog
        			- Plug for funders.
        		*/

        	// Display confirmation page
        }
	}

    function username_check($username) {
        $this->load->model('user_model');
        $result = $this->user_model->exists($username);

        header("Content-Type: application/json; charset=UTF-8");
        if($result != 1) {
            echo json_encode(array('Status' => 'Available'));
        } else {
            echo json_encode(array('Status' => 'Taken'));
        }
    }
	
}