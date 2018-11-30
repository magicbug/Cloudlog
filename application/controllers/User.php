<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

	/* Displays all notes in a list */
	public function index()
	{
		$this->load->model('user_model');
		if(!$this->user_model->authorize(99)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }

		$data['results'] = $this->user_model->users();

		$data['page_title'] = "Users";

		$this->load->view('layout/header', $data);
		$this->load->view('user/main');
		$this->load->view('layout/footer');
	}

	function add() {
		$this->load->model('user_model');
		if(!$this->user_model->authorize(99)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
		
		$this->load->library('form_validation');

		$this->form_validation->set_rules('user_name', 'Username', 'required');
		$this->form_validation->set_rules('user_email', 'E-mail', 'required');
		$this->form_validation->set_rules('user_password', 'Password', 'required');
		$this->form_validation->set_rules('user_type', 'Type', 'required');
		$this->form_validation->set_rules('user_firstname', 'First name', 'required');
		$this->form_validation->set_rules('user_lastname', 'Last name', 'required');
		$this->form_validation->set_rules('user_callsign', 'Callsign', 'required');
		$this->form_validation->set_rules('user_locator', 'Locator', 'required');
		$this->form_validation->set_rules('user_timezone', 'Timezone', 'required');

		// Get timezones
		$data['timezones'] = $this->user_model->timezones();

		if ($this->form_validation->run() == FALSE)
		{
			$data['page_title'] = "Add User";

			$this->load->view('layout/header', $data);
			if($this->input->post('user_name'))
			{
				$data['user_name'] = $this->input->post('user_name');
				$data['user_email'] = $this->input->post('user_email');
				$data['user_password'] = $this->input->post('user_password');
				$data['user_type'] = $this->input->post('user_type');
				$data['user_firstname'] = $this->input->post('user_firstname');
				$data['user_lastname'] = $this->input->post('user_lastname');
				$data['user_callsign'] = $this->input->post('user_callsign');
				$data['user_locator'] = $this->input->post('user_locator');
				$data['user_timezone'] = $this->input->post('user_timezone');
				$this->load->view('user/add');
			} else {
				$this->load->view('user/add', $data);
			}
			$this->load->view('layout/footer');
		}
		else
		{
			switch($this->user_model->add($this->input->post('user_name'), $this->input->post('user_password'), $this->input->post('user_email'), $this->input->post('user_type'), $this->input->post('user_firstname'), $this->input->post('user_lastname'), $this->input->post('user_callsign'), $this->input->post('user_locator'), $this->input->post('user_timezone'))) {
				// Check for errors
				case EUSERNAMEEXISTS:
					$data['username_error'] = 'Username <b>'.$this->input->post('user_name').'</b> already in use!';
					break;
				case EEMAILEXISTS:
					$data['email_error'] = 'E-mail address <b>'.$this->input->post('user_email').'</b> already in use!';
					break;
				case EPASSWORDINVALID:
					$data['password_error'] = 'Invalid password!';
					break;
				// All okay, return to user screen
				case OK:
					$this->session->set_flashdata('notice', 'User '.$this->input->post('user_name').' added');
					redirect('user');
					return;
			}
			$data['page_title'] = "Users";

			$this->load->view('layout/header', $data);
			$data['user_name'] = $this->input->post('user_name');
			$data['user_email'] = $this->input->post('user_email');
			$data['user_password'] = $this->input->post('user_password');
			$data['user_type'] = $this->input->post('user_type');
			$data['user_firstname'] = $this->input->post('user_firstname');
			$data['user_lastname'] = $this->input->post('user_lastname');
			$data['user_callsign'] = $this->input->post('user_callsign');
			$data['user_locator'] = $this->input->post('user_locator');
			$this->load->view('user/add');
			$this->load->view('layout/footer');
		}
	}

	function edit() {
		$this->load->model('user_model');
		if((!$this->user_model->authorize(99)) && ($this->session->userdata('user_id') != $this->uri->segment(3))) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
		$query = $this->user_model->get_by_id($this->uri->segment(3));
		
		$this->load->library('form_validation');

		$this->form_validation->set_rules('user_name', 'Username', 'required');
		$this->form_validation->set_rules('user_email', 'E-mail', 'required');
		if($this->session->userdata('user_type') == 99)
		{
			$this->form_validation->set_rules('user_type', 'Type', 'required');
		}
		$this->form_validation->set_rules('user_firstname', 'First name', 'required');
		$this->form_validation->set_rules('user_lastname', 'Last name', 'required');
		$this->form_validation->set_rules('user_callsign', 'Callsign', 'required');
		$this->form_validation->set_rules('user_locator', 'Locator', 'required');
		$this->form_validation->set_rules('user_timezone', 'Timezone', 'required');

		// Get timezones
		$data['timezones'] = $this->user_model->timezones();

		if ($this->form_validation->run() == FALSE)
		{
			$data['page_title'] = "Edit User";

			$this->load->view('layout/header', $data);
			$q = $query->row();

			$data['id'] = $q->user_id;

			if($this->input->post('user_name')) {
				$data['user_name'] = $this->input->post('user_name');
			} else {
				$data['user_name'] = $q->user_name;
			}

			if($this->input->post('user_email')) {
				$data['user_email'] = $this->input->post('user_email');
			} else {
				$data['user_email'] = $q->user_email;
			}

			if($this->input->post('user_password')) {
				$data['user_password'] = $this->input->post('user_password');
			} else {
				$data['user_password'] = $q->user_password;
			}

			if($this->input->post('user_type')) {
				$data['user_type'] = $this->input->post('user_type');
			} else {
				$data['user_type'] = $q->user_type;
			}

			if($this->input->post('user_callsign')) {
				$data['user_callsign'] = $this->input->post('user_callsign');
			} else {
				$data['user_callsign'] = $q->user_callsign;
			}

			if($this->input->post('user_locator')) {
				$data['user_locator'] = $this->input->post('user_locator');
			} else {
				$data['user_locator'] = $q->user_locator;
			}

			if($this->input->post('user_firstname')) {
				$data['user_firstname'] = $this->input->post('user_firstname');
			} else {
				$data['user_firstname'] = $q->user_firstname;
			}

			if($this->input->post('user_lastname')) {
				$data['user_lastname'] = $this->input->post('user_lastname');
			} else {
				$data['user_lastname'] = $q->user_lastname;
			}

			if($this->input->post('user_callsign')) {
				$data['user_callsign'] = $this->input->post('user_callsign');
			} else {
				$data['user_callsign'] = $q->user_callsign;
			}

			if($this->input->post('user_locator')) {
				$data['user_locator'] = $this->input->post('user_locator');
			} else {
				$data['user_locator'] = $q->user_locator;
			}

			if($this->input->post('user_timezone')) {
				$data['user_timezone'] = $this->input->post('user_timezone');
			} else {
				$data['user_timezone'] = $q->user_timezone;
			}

			if($this->input->post('user_lotw_name')) {
				$data['user_lotw_name'] = $this->input->post('user_lotw_name');
			} else {
				$data['user_lotw_name'] = $q->user_lotw_name;
			}
			
			if($this->input->post('user_lotw_password')) {
				$data['user_lotw_password'] = $this->input->post('user_lotw_password');
			} else {
				$data['user_lotw_password'] = $q->user_lotw_password;
			}
			
			if($this->input->post('user_eqsl_name')) {
				$data['user_eqsl_name'] = $this->input->post('user_eqsl_name');
			} else {
				$data['user_eqsl_name'] = $q->user_eqsl_name;
			}
			
			if($this->input->post('user_eqsl_password')) {
				$data['user_eqsl_password'] = $this->input->post('user_eqsl_password');
			} else {
				$data['user_eqsl_password'] = $q->user_eqsl_password;
			}
			
			if($this->input->post('user_eqsl_qth_nickname')) {
				$data['user_eqsl_qth_nickname'] = $this->input->post('user_eqsl_qth_nickname');
			} else {
				$data['user_eqsl_qth_nickname'] = $q->user_eqsl_qth_nickname;
			}
			
			$this->load->view('user/edit', $data);
			$this->load->view('layout/footer');
		}
		else
		{
			unset($data);
			switch($this->user_model->edit($this->input->post())) {
				// Check for errors
				case EUSERNAMEEXISTS:
					$data['username_error'] = 'Username <b>'.$this->input->post('user_name').'</b> already in use!';
					break;
				case EEMAILEXISTS:
					$data['email_error'] = 'E-mail address <b>'.$this->input->post('user_email').'</b> already in use!';
					break;
				case EPASSWORDINVALID:
					$data['password_error'] = 'Invalid password!';
					break;
				// All okay, return to user screen
				case OK:
					if($this->session->userdata('user_id') == $this->input->post('id')) {
						$this->session->set_flashdata('notice', 'User '.$this->input->post('user_name').' edited');
						redirect('user/profile');
					} else {
						$this->session->set_flashdata('notice', 'User '.$this->input->post('user_name').' edited');
						redirect('user');
					}
					return;
			}
			$data['page_title'] = "Edit User";

			$this->load->view('layout/header', $data);
			$data['user_name'] = $this->input->post('user_name');
			$data['user_email'] = $this->input->post('user_email');
			$data['user_password'] = $this->input->post('user_password');
			$data['user_type'] = $this->input->post('user_type');
			$data['user_firstname'] = $this->input->post('user_firstname');
			$data['user_lastname'] = $this->input->post('user_lastname');
			$data['user_callsign'] = $this->input->post('user_callsign');
			$data['user_locator'] = $this->input->post('user_locator');
			$data['user_timezone'] = $this->input->post('user_timezone');
			$this->load->view('user/edit');
			$this->load->view('layout/footer');
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

		$this->load->view('layout/header', $data);
		$this->load->view('user/profile');
		$this->load->view('layout/footer');
	}

	function delete() {
		$this->load->model('user_model');
		if(!$this->user_model->authorize(99)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
		$query = $this->user_model->get_by_id($this->uri->segment(3));

		$this->load->library('form_validation');

		$this->form_validation->set_rules('id', 'user_id', 'required');

		$data = $query->row();

		if ($this->form_validation->run() == FALSE)
		{

			$this->load->view('layout/header', $data);
			$this->load->view('user/delete');
			$this->load->view('layout/footer');
		}
		else
		{
			if($this->user_model->delete($data->user_id))
			{
				$this->session->set_flashdata('notice', 'User deleted');
				redirect('user');
			} else {
				$this->session->set_flashdata('notice', '<b>Database error:</b> Could not delete user!');
				redirect('user');
			}
		}
	}

	function login() {
		$this->load->model('user_model');
		$query = $this->user_model->get($this->input->post('user_name'));

		$this->load->library('form_validation');

		$this->form_validation->set_rules('user_name', 'Username', 'required');
		$this->form_validation->set_rules('user_password', 'Password', 'required');

		$data['user'] = $query->row();

		if ($this->form_validation->run() == FALSE)
		{
			$data['page_title'] = "Login";
			$this->load->view('layout/header', $data);
			$this->load->view('user/login');
			$this->load->view('layout/footer');
		}
		else
		{
			if($this->user_model->login() == 1) {
				$this->session->set_flashdata('notice', 'User logged in');
				$this->user_model->update_session($data['user']->user_id);
				redirect('dashboard');
			} else {
				$this->session->set_flashdata('error', 'Incorrect username or password!');
				redirect('user/login');
			}
		}
	}

	function logout() {
		$this->load->model('user_model');

		$user_name = $this->session->userdata('user_name');

		$this->user_model->clear_session();

		$this->session->set_flashdata('notice', 'User '.$user_name.' logged out.');
		redirect('dashboard');
	}
}
