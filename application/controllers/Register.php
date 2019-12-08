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
				- Create User Account
				- Create Station Profile
				- Assign Station Profile to user
				- Send confirmation email to user
        	*/

			// Store Field Information
			$account_data['username'] = $this->input->post('username');
        	$account_data['firstname'] = $this->input->post('firstname');
        	$account_data['lastname'] = $this->input->post('lastname');
        	$account_data['email'] = $this->input->post('email');
        	$account_data['password'] = $this->input->post('password');

        	$account_data['callsign'] = $this->input->post('callsign');
        	$account_data['gridsquare'] = $this->input->post('gridsquare');
        	$account_data['dxcc_adif_value'] = $this->input->post('dxcc');
        	$account_data['station_country'] = $this->input->post('station_country');
        	$account_data['cq_zone'] = $this->input->post('cq_zone');
        	$account_data['itu_zone'] = $this->input->post('itu_zone');

        	$account_data['legal_terms'] = $this->input->post('terms');
        	$account_data['legal_marketing'] = $this->input->post('marketing');

        	print_r($account_data);

        	$this->load->model('user_model');

        	$this->user_model->add($account_data['username'], $account_data['password'], $account_data['email'], 5, $account_data['firstname'], $account_data['lastname'], $account_data['callsign'], $account_data['gridsquare'], 0);

        	echo $this->db->last_query();
        }
	}
	
}