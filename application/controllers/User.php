<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

	function edit() {
		$this->load->model('user_model');
		if((!$this->user_model->authorize(99)) && ($this->session->userdata('user_id') != $this->uri->segment(3))) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
		$query = $this->user_model->get_by_id($this->uri->segment(3));
		
		$this->load->library('form_validation');

		$this->form_validation->set_rules('user_name', 'Username', 'required|xss_clean');
		$this->form_validation->set_rules('user_email', 'E-mail', 'required|xss_clean');
		if($this->session->userdata('user_type') == 99)
		{
			$this->form_validation->set_rules('user_type', 'Type', 'required|xss_clean');
		}
		$this->form_validation->set_rules('user_firstname', 'First name', 'required|xss_clean');
		$this->form_validation->set_rules('user_lastname', 'Last name', 'required|xss_clean');
		$this->form_validation->set_rules('user_callsign', 'Callsign', 'trim|required|xss_clean');
		$this->form_validation->set_rules('user_locator', 'Locator', 'required|xss_clean');
		$this->form_validation->set_rules('user_timezone', 'Timezone', 'required');

		// Get timezones
		$data['timezones'] = $this->user_model->timezones();

		if ($this->form_validation->run() == FALSE)
		{
			$data['page_title'] = "Edit User";

			$this->load->view('interface_assets/header', $data);
			$q = $query->row();

			$data['id'] = $q->user_id;

			if($this->input->post('user_name', true)) {
				$data['user_name'] = $this->input->post('user_name', true);
			} else {
				$data['user_name'] = $q->user_name;
			}

			if($this->input->post('user_email', true)) {
				$data['user_email'] = $this->input->post('user_email', true);
			} else {
				$data['user_email'] = $q->user_email;
			}

			if($this->input->post('user_password', true)) {
				$data['user_password'] = $this->input->post('user_password',true);
			} else {
				$data['user_password'] = $q->user_password;
			}

			if($this->input->post('user_type', true)) {
				$data['user_type'] = $this->input->post('user_type',true);
			} else {
				$data['user_type'] = $q->user_type;
			}

			if($this->input->post('user_callsign', true)) {
				$data['user_callsign'] = $this->input->post('user_callsign', true);
			} else {
				$data['user_callsign'] = $q->user_callsign;
			}

			if($this->input->post('user_locator', true)) {
				$data['user_locator'] = $this->input->post('user_locator', true);
			} else {
				$data['user_locator'] = $q->user_locator;
			}

			if($this->input->post('user_firstname', true)) {
				$data['user_firstname'] = $this->input->post('user_firstname', true);
			} else {
				$data['user_firstname'] = $q->user_firstname;
			}

			if($this->input->post('user_lastname', true)) {
				$data['user_lastname'] = $this->input->post('user_lastname', tue);
			} else {
				$data['user_lastname'] = $q->user_lastname;
			}

			if($this->input->post('user_callsign', true)) {
				$data['user_callsign'] = $this->input->post('user_callsign', true);
			} else {
				$data['user_callsign'] = $q->user_callsign;
			}

			if($this->input->post('user_locator', true)) {
				$data['user_locator'] = $this->input->post('user_locator', true);
			} else {
				$data['user_locator'] = $q->user_locator;
			}

			if($this->input->post('user_timezone')) {
				$data['user_timezone'] = $this->input->post('user_timezone', true);
			} else {
				$data['user_timezone'] = $q->user_timezone;
			}

			if($this->input->post('user_lotw_name')) {
				$data['user_lotw_name'] = $this->input->post('user_lotw_name', true);
			} else {
				$data['user_lotw_name'] = $q->user_lotw_name;
			}

			if($this->input->post('user_clublog_name')) {
				$data['user_clublog_name'] = $this->input->post('user_clublog_name', true);
			} else {
				$data['user_clublog_name'] = $q->user_clublog_name;
			}
			
			if($this->input->post('user_clublog_password')) {
				$data['user_clublog_password'] = $this->input->post('user_clublog_password', true);
			} else {
				$data['user_clublog_password'] = $q->user_clublog_password;
			}

			if($this->input->post('user_lotw_password')) {
				$data['user_lotw_password'] = $this->input->post('user_lotw_password', true);
			} else {
				$data['user_lotw_password'] = $q->user_lotw_password;
			}
			
			if($this->input->post('user_eqsl_name')) {
				$data['user_eqsl_name'] = $this->input->post('user_eqsl_name', true);
			} else {
				$data['user_eqsl_name'] = $q->user_eqsl_name;
			}
			
			if($this->input->post('user_eqsl_password')) {
				$data['user_eqsl_password'] = $this->input->post('user_eqsl_password', true);
			} else {
				$data['user_eqsl_password'] = $q->user_eqsl_password;
			}
		
			
			$this->load->view('user/edit', $data);
			$this->load->view('interface_assets/footer');
		}
		else
		{
			unset($data);
			switch($this->user_model->edit($this->input->post())) {
				// Check for errors
				case EUSERNAMEEXISTS:
					$data['username_error'] = 'Username <b>'.$this->input->post('user_name', true).'</b> already in use!';
					break;
				case EEMAILEXISTS:
					$data['email_error'] = 'E-mail address <b>'.$this->input->post('user_email', true).'</b> already in use!';
					break;
				case EPASSWORDINVALID:
					$data['password_error'] = 'Invalid password!';
					break;
				// All okay, return to user screen
				case OK:
					if($this->session->userdata('user_id') == $this->input->post('id', true)) {
						$this->session->set_flashdata('notice', 'User '.$this->input->post('user_name', true).' edited');
						redirect('user/profile');
					} else {
						$this->session->set_flashdata('notice', 'User '.$this->input->post('user_name', true).' edited');
						redirect('user');
					}
					return;
			}
			$data['page_title'] = "Edit User";

			$this->load->view('interface_assets/header', $data);
			$data['user_name'] = $this->input->post('user_name', true);
			$data['user_email'] = $this->input->post('user_email', true);
			$data['user_password'] = $this->input->post('user_password', true);
			$data['user_type'] = $this->input->post('user_type', true);
			$data['user_firstname'] = $this->input->post('user_firstname', true);
			$data['user_lastname'] = $this->input->post('user_lastname', true);
			$data['user_callsign'] = $this->input->post('user_callsign', true);
			$data['user_locator'] = $this->input->post('user_locator', true);
			$data['user_timezone'] = $this->input->post('user_timezone', true);
			$this->load->view('user/edit');
			$this->load->view('interface_assets/footer');
		}
	}

	function profile() {
		$this->load->model('user_model');
		$query = $this->user_model->get_by_id($this->session->userdata('user_id'));
    $q = $query->row();
    $data['page_title'] = "Profile";
    $data['user_name'] = $q->user_name;
    $data['user_type'] = $q->user_type;
    $data['user_email'] = $q->user_email;
    $data['user_firstname'] = $q->user_firstname;
    $data['user_lastname'] = $q->user_lastname;
    $data['user_callsign'] = $q->user_callsign;
    $data['user_locator'] = $q->user_locator;

		$this->load->view('interface_assets/header', $data);
		$this->load->view('user/profile');
		$this->load->view('interface_assets/footer');
	}
}
