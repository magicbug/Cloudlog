<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		// Load language files
		$this->lang->load(array(
			'account',
		));
	}

	public function index()
	{
		$this->load->model('user_model');
		if(!$this->user_model->authorize(99)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }

		$data['results'] = $this->user_model->users();

		$data['page_title'] = "User Accounts";

		$this->load->view('interface_assets/header', $data);
		$this->load->view('user/main');
		$this->load->view('interface_assets/footer');
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
		$this->form_validation->set_rules('user_locator', 'Locator', 'callback_check_locator');
		$this->form_validation->set_rules('user_timezone', 'Timezone', 'required');

		// Get themes list
		$data['themes'] = $this->user_model->getThemes();

		// Get timezones
		$data['timezones'] = $this->user_model->timezones();

		if ($this->form_validation->run() == FALSE)
		{
			$data['page_title'] = "Add User";
            $data['measurement_base'] = $this->config->item('measurement_base');

			$this->load->view('interface_assets/header', $data);
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
                $data['user_measurement_base'] = $this->input->post('user_measurement_base');
                $data['user_stylesheet'] = $this->input->post('user_stylesheet');
				$data['user_qth_lookup'] = $this->input->post('user_qth_lookup');
				$data['user_sota_lookup'] = $this->input->post('user_sota_lookup');
				$data['user_wwff_lookup'] = $this->input->post('user_wwff_lookup');
				$data['user_show_notes'] = $this->input->post('user_show_notes');
				$data['user_column1'] = $this->input->post('user_column1');
				$data['user_column2'] = $this->input->post('user_column2');
				$data['user_column3'] = $this->input->post('user_column3');
				$data['user_column4'] = $this->input->post('user_column4');
				$data['user_column5'] = $this->input->post('user_column5');
				$data['user_show_profile_image'] = $this->input->post('user_show_profile_image');
				$this->load->view('user/add', $data);
			} else {
				$this->load->view('user/add', $data);
			}
			$this->load->view('interface_assets/footer');
		}
		else
		{
			switch($this->user_model->add($this->input->post('user_name'),
				$this->input->post('user_password'),
				$this->input->post('user_email'),
				$this->input->post('user_type'),
				$this->input->post('user_firstname'),
				$this->input->post('user_lastname'),
				$this->input->post('user_callsign'),
				$this->input->post('user_locator'),
				$this->input->post('user_timezone'),
				$this->input->post('user_measurement_base'),
				$this->input->post('user_date_format'),
				$this->input->post('user_stylesheet'),
				$this->input->post('user_qth_lookup'),
				$this->input->post('user_sota_lookup'),
				$this->input->post('user_wwff_lookup'),
				$this->input->post('user_show_notes'),
				$this->input->post('user_column1'),
				$this->input->post('user_column2'),
				$this->input->post('user_column3'),
				$this->input->post('user_column4'),
				$this->input->post('user_column5'),
				$this->input->post('user_show_profile_image'))) {
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

			$this->load->view('interface_assets/header', $data);
			$data['user_name'] = $this->input->post('user_name');
			$data['user_email'] = $this->input->post('user_email');
			$data['user_password'] = $this->input->post('user_password');
			$data['user_type'] = $this->input->post('user_type');
			$data['user_firstname'] = $this->input->post('user_firstname');
			$data['user_lastname'] = $this->input->post('user_lastname');
			$data['user_callsign'] = $this->input->post('user_callsign');
			$data['user_locator'] = $this->input->post('user_locator');
            $data['user_measurement_base'] = $this->input->post('user_measurement_base');
            $data['user_stylesheet'] = $this->input->post('user_stylesheet');
			$data['user_qth_lookup'] = $this->input->post('user_qth_lookup');
			$data['user_sota_lookup'] = $this->input->post('user_sota_lookup');
			$data['user_wwff_lookup'] = $this->input->post('user_wwff_lookup');
			$data['user_show_notes'] = $this->input->post('user_show_notes');
			$data['user_column1'] = $this->input->post('user_column1');
			$data['user_column2'] = $this->input->post('user_column2');
			$data['user_column3'] = $this->input->post('user_column3');
			$data['user_column4'] = $this->input->post('user_column4');
			$data['user_column5'] = $this->input->post('user_column5');
			$data['user_show_profile_image'] = $this->input->post('user_show_profile_image');
			$this->load->view('user/add', $data);
			$this->load->view('interface_assets/footer');
		}
	}

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
		$this->form_validation->set_rules('user_locator', 'Locator', 'callback_check_locator');
		$this->form_validation->set_rules('user_timezone', 'Timezone', 'required');

		// Get themes list
		$data['themes'] = $this->user_model->getThemes();

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
				$data['user_lastname'] = $this->input->post('user_lastname', true);
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

            if($this->input->post('user_measurement_base')) {
                $data['user_measurement_base'] = $this->input->post('user_measurement_base', true);
            } else {
                $data['user_measurement_base'] = $q->user_measurement_base;
            }

			if($this->input->post('user_date_format')) {
                $data['user_date_format'] = $this->input->post('user_date_format', true);
            } else {
                $data['user_date_format'] = $q->user_date_format;
            }

            if($this->input->post('user_stylesheet')) {
                $data['user_stylesheet'] = $this->input->post('user_stylesheet', true);
            } else {
                $data['user_stylesheet'] = $q->user_stylesheet;
            }

			if($this->input->post('user_qth_lookup')) {
				$data['user_qth_lookup'] = $this->input->post('user_qth_lookup', true);
			} else {
				$data['user_qth_lookup'] = $q->user_qth_lookup;
			}

			if($this->input->post('user_sota_lookup')) {
				$data['user_sota_lookup'] = $this->input->post('user_sota_lookup', true);
			} else {
				$data['user_sota_lookup'] = $q->user_sota_lookup;
			}

			if($this->input->post('user_wwff_lookup')) {
				$data['user_wwff_lookup'] = $this->input->post('user_wwff_lookup', true);
			} else {
				$data['user_wwff_lookup'] = $q->user_wwff_lookup;
			}

			if($this->input->post('user_show_notes')) {
				$data['user_show_notes'] = $this->input->post('user_show_notes', true);
			} else {
				$data['user_show_notes'] = $q->user_show_notes;
			}

			if($this->input->post('user_show_profile_image')) {
				$data['user_show_profile_image'] = $this->input->post('user_show_profile_image', false);
			} else {
				$data['user_show_profile_image'] = $q->user_show_profile_image;
			}

			if($this->input->post('user_column1')) {
				$data['user_column1'] = $this->input->post('user_column1', true);
			} else {
				$data['user_column1'] = $q->user_column1;
			}

			if($this->input->post('user_column2')) {
				$data['user_column2'] = $this->input->post('user_column2', true);
			} else {
				$data['user_column2'] = $q->user_column2;
			}

			if($this->input->post('user_column3')) {
				$data['user_column3'] = $this->input->post('user_column3', true);
			} else {
				$data['user_column3'] = $q->user_column3;
			}

			if($this->input->post('user_column4')) {
				$data['user_column4'] = $this->input->post('user_column4', true);
			} else {
				$data['user_column4'] = $q->user_column4;
			}

			if($this->input->post('user_column5')) {
				$data['user_column5'] = $this->input->post('user_column5', true);
			} else {
				$data['user_column5'] = $q->user_column5;
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
						$this->session->set_flashdata('success', 'User '.$this->input->post('user_name', true).' edited');
						redirect('user/edit/'.$this->uri->segment(3));
					} else {
						$this->session->set_flashdata('success', 'User '.$this->input->post('user_name', true).' edited');
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
            $data['user_stylesheet'] = $this->input->post('user_stylesheet');
			$data['user_qth_lookup'] = $this->input->post('user_qth_lookup');
			$data['user_sota_lookup'] = $this->input->post('user_sota_lookup');
			$data['user_wwff_lookup'] = $this->input->post('user_wwff_lookup');
			$data['user_show_notes'] = $this->input->post('user_show_notes');
			$data['user_column1'] = $this->input->post('user_column1');
			$data['user_column2'] = $this->input->post('user_column2');
			$data['user_column3'] = $this->input->post('user_column3');
			$data['user_column4'] = $this->input->post('user_column4');
			$data['user_column4'] = $this->input->post('user_column4');
			$data['user_column5'] = $this->input->post('user_column5');
			$data['user_show_profile_image'] = $this->input->post('user_show_profile_image');
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

	function delete() {
		$this->load->model('user_model');
		if(!$this->user_model->authorize(99)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
		$query = $this->user_model->get_by_id($this->uri->segment(3));

		$this->load->library('form_validation');

		$this->form_validation->set_rules('id', 'user_id', 'required');

		$data = $query->row();

		if ($this->form_validation->run() == FALSE)
		{

			$this->load->view('interface_assets/header', $data);
			$this->load->view('user/delete');
			$this->load->view('interface_assets/footer');
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
		// Check our version and run any migrations
		$this->load->library('Migration');
		$this->migration->current();

		$this->load->model('user_model');
		$query = $this->user_model->get($this->input->post('user_name', true));

		$this->load->library('form_validation');

		$this->form_validation->set_rules('user_name', 'Username', 'required');
		$this->form_validation->set_rules('user_password', 'Password', 'required');

		$data['user'] = $query->row();

		if ($this->form_validation->run() == FALSE)
		{
			$data['page_title'] = "Login";
			$this->load->view('interface_assets/mini_header', $data);
			$this->load->view('user/login');
			$this->load->view('interface_assets/footer');

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

	/**
	 * Function: forgot_password
	 * 
	 * Allows users to input an email address and a password will be sent to that address.
	 * 
	 */
	function forgot_password() {

		$this->load->helper(array('form', 'url'));

		$this->load->library('form_validation');

		$this->form_validation->set_rules('email', 'Email', 'required');

		if ($this->form_validation->run() == FALSE)
		{
			$data['page_title'] = "Forgot Password";
			$this->load->view('interface_assets/mini_header', $data);
			$this->load->view('user/forgot_password');
			$this->load->view('interface_assets/footer');
		}
		else
		{
			// Check email address exists
			$this->load->model('user_model');
			
			$check_email = $this->user_model->check_email_address($this->input->post('email', true));

			if($check_email == TRUE) {
				// Generate password reset code 50 characters long
				$this->load->helper('string');
				$reset_code = random_string('alnum', 50);

				$this->user_model->set_password_reset_code($this->input->post('email', true), $reset_code);
				
				// Send email with reset code

				$this->data['reset_code'] = $reset_code;
				$this->load->library('email');

				if($this->optionslib->get_option('emailProtocol') == "smtp") {
					$config = Array(
						'protocol' => $this->optionslib->get_option('emailProtocol'),
						'smtp_host' => $this->optionslib->get_option('smtpHost'),
						'smtp_port' => $this->optionslib->get_option('smtpPort'),
						'smtp_user' => $this->optionslib->get_option('smtpUsername'),
						'smtp_pass' => $this->optionslib->get_option('smtpPassword'),
						'crlf' => "\r\n",
						'newline' => "\r\n"
					  );

					  $this->email->initialize($config);
				}

				$message = $this->load->view('email/forgot_password', $this->data,  TRUE);

				$this->email->from('noreply@cloudlog.co.uk', 'Cloudlog');
				$this->email->to($this->input->post('email', true));

				$this->email->subject('Cloudlog Account Password Reset');
				$this->email->message($message);

				if (! $this->email->send())
				{
					// Redirect to login page with message
					$this->session->set_flashdata('warning', 'Email settings are incorrect.');
					redirect('user/login');
				} else {
					// Redirect to login page with message
					$this->session->set_flashdata('notice', 'Password Reset Processed.');
					redirect('user/login');
				}
			} else {
				// No account found just return to login page
				$this->session->set_flashdata('notice', 'Password Reset Processed.');
				redirect('user/login');
			}
		}
	}

	function reset_password($reset_code = NULL)
	{
		$data['reset_code'] = $reset_code;
		if($reset_code != NULL) {
			$this->load->helper(array('form', 'url'));

			$this->load->library('form_validation');
	
			$this->form_validation->set_rules('password', 'Password', 'required');
			$this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'required|matches[password]');
	
			if ($this->form_validation->run() == FALSE)
			{
				$data['page_title'] = "Reset Password";
				$this->load->view('interface_assets/mini_header', $data);
				$this->load->view('user/reset_password');
				$this->load->view('interface_assets/footer');
			}
			else
			{
				// Lets reset the password!
				$this->load->model('user_model');
			
				$this->user_model->reset_password($this->input->post('password', true), $reset_code);
				$this->session->set_flashdata('notice', 'Password Reset.');
				redirect('user/login');
			}
		} else {
			redirect('user/login');
		}
	}

   function check_locator($grid) {
      $grid = $this->input->post('user_locator');
      // Allow empty locator
      if (preg_match('/^$/', $grid)) return true;
      // Allow 6-digit locator
      if (preg_match('/^[A-Ra-r]{2}[0-9]{2}[A-Za-z]{2}$/', $grid)) return true;
      // Allow 4-digit locator
      else if (preg_match('/^[A-Ra-r]{2}[0-9]{2}$/', $grid)) return true;
      // Allow 4-digit grid line
      else if (preg_match('/^[A-Ra-r]{2}[0-9]{2},[A-Ra-r]{2}[0-9]{2}$/', $grid)) return true;
      // Allow 4-digit grid corner
      else if (preg_match('/^[A-Ra-r]{2}[0-9]{2},[A-Ra-r]{2}[0-9]{2},[A-Ra-r]{2}[0-9]{2},[A-Ra-r]{2}[0-9]{2}$/', $grid)) return true;
      // Allow 2-digit locator
      else if (preg_match('/^[A-Ra-r]{2}$/', $grid)) return true;
      // Allow 8-digit locator
      else if (preg_match('/^[A-Ra-r]{2}[0-9]{2}[A-Za-z]{2}[0-9]{2}$/', $grid)) return true;
      else {
         $this->form_validation->set_message('check_locator', 'Please check value for grid locator ('.strtoupper($grid).').');
         return false;
      }
   }
}
