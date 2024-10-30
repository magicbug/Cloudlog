<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller
{

	function __construct()
	{
		parent::__construct();

		$this->lang->load(array(
			'account',
			'lotw',
			'eqsl',
			'admin',
		));
	}

	/**
	 * Index method for the User controller.
	 * This method loads the user model, authorizes the user, and displays the user accounts.
	 */
	public function index()
	{
		$this->load->model('user_model');
		
		// Check if the user is authorized
		if (!$this->user_model->authorize(99)) {
			$this->session->set_flashdata('notice', 'You\'re not allowed to do that!');
			redirect('dashboard');
		}

		// Get the user accounts
		$data['results'] = $this->user_model->users();

		// Set the page title
		$data['page_title'] = $this->lang->line('admin_user_accounts');

		// Load the views
		$this->load->view('interface_assets/header', $data);
		$this->load->view('user/main');
		$this->load->view('interface_assets/footer');
	}

	function add()
	{
		$this->load->model('user_model');
		if (!$this->user_model->authorize(99)) {
			$this->session->set_flashdata('notice', 'You\'re not allowed to do that!');
			redirect('dashboard');
		}

		$data['existing_languages'] = $this->find();

		$this->load->model('bands');
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

		$data['user_add'] = true;
		$data['user_form_action'] = site_url('user/add');
		$data['bands'] = $this->bands->get_user_bands();

		// Get themes list
		$data['themes'] = $this->user_model->getThemes();

		// Get timezones
		$data['timezones'] = $this->user_model->timezones();
		$data['language'] = 'english';

		// Set defaults
		$data['dashboard_upcoming_dx_card'] = false;
		$data['dashboard_qslcard_card'] = false;
		$data['dashboard_eqslcard_card'] = false;
		$data['dashboard_lotw_card'] = false;
		$data['dashboard_vuccgrids_card'] = false;

		$dashboard_options = $this->user_options_model->get_options('dashboard')->result();

		foreach ($dashboard_options as $item) {
			$option_name = $item->option_name;
			$option_key = $item->option_key;
			$option_value = $item->option_value;

			if ($option_name == 'dashboard_upcoming_dx_card' && $option_key == 'enabled') {
				if ($item->option_value == 'true') {
					$data['dashboard_upcoming_dx_card'] = true;
				} else {
					$data['dashboard_upcoming_dx_card'] = false;
				}
			}

			if ($option_name == 'dashboard_qslcards_card' && $option_key == 'enabled') {
				if ($item->option_value == 'true') {
					$data['dashboard_qslcard_card'] = true;
				} else {
					$data['dashboard_qslcard_card'] = false;
				}
			}

			if ($option_name == 'dashboard_eqslcards_card' && $option_key == 'enabled') {
				if ($item->option_value == 'true') {
					$data['dashboard_eqslcard_card'] = true;
				} else {
					$data['dashboard_eqslcard_card'] = false;
				}
			}

			if ($option_name == 'dashboard_lotw_card' && $option_key == 'enabled') {
				if ($item->option_value == 'true') {
					$data['dashboard_lotw_card'] = true;
				} else {
					$data['dashboard_lotw_card'] = false;
				}
			}

			if ($option_name == 'dashboard_vuccgrids_card' && $option_key == 'enabled') {
				if ($item->option_value == 'true') {
					$data['dashboard_vuccgrids_card'] = true;
				} else {
					$data['dashboard_vuccgrids_card'] = false;
				}
			}
		}

		if ($this->form_validation->run() == FALSE) {
			$data['page_title'] = "Add User";
			$data['measurement_base'] = $this->config->item('measurement_base');

			$this->load->view('interface_assets/header', $data);
			if ($this->input->post('user_name')) {
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
				$data['user_pota_lookup'] = $this->input->post('user_pota_lookup');
				$data['user_show_notes'] = $this->input->post('user_show_notes');
				$data['user_column1'] = $this->input->post('user_column1');
				$data['user_column2'] = $this->input->post('user_column2');
				$data['user_column3'] = $this->input->post('user_column3');
				$data['user_column4'] = $this->input->post('user_column4');
				$data['user_column5'] = $this->input->post('user_column5');
				$data['user_show_profile_image'] = $this->input->post('user_show_profile_image');
				$data['user_previous_qsl_type'] = $this->input->post('user_previous_qsl_type');
				$data['user_amsat_status_upload'] = $this->input->post('user_amsat_status_upload');
				$data['user_mastodon_url'] = $this->input->post('user_mastodon_url');
				$data['user_default_band'] = $this->input->post('user_default_band');
				$data['user_default_confirmation'] = ($this->input->post('user_default_confirmation_qsl') !== null ? 'Q' : '') . ($this->input->post('user_default_confirmation_lotw') !== null ? 'L' : '') . ($this->input->post('user_default_confirmation_eqsl') !== null ? 'E' : '') . ($this->input->post('user_default_confirmation_qrz') !== null ? 'Z' : '');
				$data['user_qso_end_times'] = $this->input->post('user_qso_end_times');
				$data['user_quicklog'] = $this->input->post('user_quicklog');
				$data['user_quicklog_enter'] = $this->input->post('user_quicklog_enter');
				$data['user_hamsat_key'] = $this->input->post('user_hamsat_key');
				$data['user_hamsat_workable_only'] = $this->input->post('user_hamsat_workable_only');
				$data['language'] = $this->input->post('language');
				$this->load->view('user/edit', $data);
			} else {
				$this->load->view('user/edit', $data);
			}
			$this->load->view('interface_assets/footer');
		} else {
			switch ($this->user_model->add(
				$this->input->post('user_name'),
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
				$this->input->post('user_pota_lookup'),
				$this->input->post('user_show_notes'),
				$this->input->post('user_column1'),
				$this->input->post('user_column2'),
				$this->input->post('user_column3'),
				$this->input->post('user_column4'),
				$this->input->post('user_column5'),
				$this->input->post('user_show_profile_image'),
				$this->input->post('user_previous_qsl_type'),
				$this->input->post('user_amsat_status_upload'),
				$this->input->post('user_mastodon_url'),
				$this->input->post('user_default_band'),
				($this->input->post('user_default_confirmation_qsl') !== null ? 'Q' : '') . ($this->input->post('user_default_confirmation_lotw') !== null ? 'L' : '') . ($this->input->post('user_default_confirmation_eqsl') !== null ? 'E' : '') . ($this->input->post('user_default_confirmation_qrz') !== null ? 'Z' : ''),
				$this->input->post('user_qso_end_times'),
				$this->input->post('user_quicklog'),
				$this->input->post('user_quicklog_enter'),
				$this->input->post('language'),
				$this->input->post('user_hamsat_key'),
				$this->input->post('user_hamsat_workable_only'),
				$this->input->post('user_callbook_type'),
				$this->input->post('user_callbook_username'),
				$this->input->post('user_callbook_password')
			)) {
					// Check for errors
				case EUSERNAMEEXISTS:
					$data['username_error'] = 'Username <b>' . $this->input->post('user_name') . '</b> already in use!';
					break;
				case EEMAILEXISTS:
					$data['email_error'] = 'E-mail address <b>' . $this->input->post('user_email') . '</b> already in use!';
					break;
				case EPASSWORDINVALID:
					$data['password_error'] = 'Invalid password!';
					break;
					// All okay, return to user screen
				case OK:
					$this->session->set_flashdata('notice', 'User ' . $this->input->post('user_name') . ' added');
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
			$data['user_pota_lookup'] = $this->input->post('user_pota_lookup');
			$data['user_show_notes'] = $this->input->post('user_show_notes');
			$data['user_column1'] = $this->input->post('user_column1');
			$data['user_column2'] = $this->input->post('user_column2');
			$data['user_column3'] = $this->input->post('user_column3');
			$data['user_column4'] = $this->input->post('user_column4');
			$data['user_column5'] = $this->input->post('user_column5');
			$data['user_show_profile_image'] = $this->input->post('user_show_profile_image');
			$data['user_previous_qsl_type'] = $this->input->post('user_previous_qsl_type');
			$data['user_amsat_status_upload'] = $this->input->post('user_amsat_status_upload');
			$data['user_mastodon_url'] = $this->input->post('user_mastodon_url');
			$data['user_default_band'] = $this->input->post('user_default_band');
			$data['user_default_confirmation'] = ($this->input->post('user_default_confirmation_qsl') !== null ? 'Q' : '') . ($this->input->post('user_default_confirmation_lotw') !== null ? 'L' : '') . ($this->input->post('user_default_confirmation_eqsl') !== null ? 'E' : '') . ($this->input->post('user_default_confirmation_qrz') !== null ? 'Z' : '');
			$data['user_qso_end_times'] = $this->input->post('user_qso_end_times');
			$data['user_quicklog'] = $this->input->post('user_quicklog');
			$data['user_quicklog_enter'] = $this->input->post('user_quicklog_enter');
			$data['language'] = $this->input->post('language');
			$this->load->view('user/edit', $data);
			$this->load->view('interface_assets/footer');
		}
	}

	function find()
	{
		$existing_langs = array();
		$lang_path = APPPATH . 'language';

		$results = scandir($lang_path);

		foreach ($results as $result) {
			if ($result === '.' or $result === '..') continue;

			if (is_dir(APPPATH . 'language' . '/' . $result)) {
				$dirs[] = $result;
			}
		}
		return $dirs;
	}

	function edit()
	{
		$this->load->model('user_model');
		if (($this->session->userdata('user_id') == '') || ((!$this->user_model->authorize(99)) && ($this->session->userdata('user_id') != $this->uri->segment(3)))) {
			$this->session->set_flashdata('notice', 'You\'re not allowed to do that!');
			redirect('dashboard');
		}
		$query = $this->user_model->get_by_id($this->uri->segment(3));

		$data['existing_languages'] = $this->find();

		$this->load->model('bands');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('user_name', 'Username', 'required|xss_clean');
		$this->form_validation->set_rules('user_email', 'E-mail', 'required|xss_clean');
		if ($this->session->userdata('user_type') == 99) {
			$this->form_validation->set_rules('user_type', 'Type', 'required|xss_clean');
		}
		$this->form_validation->set_rules('user_firstname', 'First name', 'required|xss_clean');
		$this->form_validation->set_rules('user_lastname', 'Last name', 'required|xss_clean');
		$this->form_validation->set_rules('user_callsign', 'Callsign', 'trim|required|xss_clean');
		$this->form_validation->set_rules('user_locator', 'Locator', 'callback_check_locator');
		$this->form_validation->set_rules('user_timezone', 'Timezone', 'required');

		$data['user_form_action'] = site_url('user/edit') . "/" . $this->uri->segment(3);;
		$data['bands'] = $this->bands->get_user_bands();

		// Get themes list
		$data['themes'] = $this->user_model->getThemes();

		// Get timezones
		$data['timezones'] = $this->user_model->timezones();

		if ($this->form_validation->run() == FALSE) {
			$data['page_title'] = "Edit User";

			$q = $query->row();

			$data['id'] = $q->user_id;

			if ($this->input->post('user_name', true)) {
				$data['user_name'] = $this->input->post('user_name', true);
			} else {
				$data['user_name'] = $q->user_name;
			}

			if ($this->input->post('user_email', true)) {
				$data['user_email'] = $this->input->post('user_email', true);
			} else {
				$data['user_email'] = $q->user_email;
			}

			if ($this->input->post('user_password', true)) {
				$data['user_password'] = $this->input->post('user_password', true);
			} else {
				$data['user_password'] = $q->user_password;
			}

			if ($this->input->post('user_type', true)) {
				$data['user_type'] = $this->input->post('user_type', true);
			} else {
				$data['user_type'] = $q->user_type;
			}

			if ($this->input->post('user_callsign', true)) {
				$data['user_callsign'] = $this->input->post('user_callsign', true);
			} else {
				$data['user_callsign'] = $q->user_callsign;
			}

			if ($this->input->post('user_locator', true)) {
				$data['user_locator'] = $this->input->post('user_locator', true);
			} else {
				$data['user_locator'] = $q->user_locator;
			}

			if ($this->input->post('user_firstname', true)) {
				$data['user_firstname'] = $this->input->post('user_firstname', true);
			} else {
				$data['user_firstname'] = $q->user_firstname;
			}

			if ($this->input->post('user_lastname', true)) {
				$data['user_lastname'] = $this->input->post('user_lastname', true);
			} else {
				$data['user_lastname'] = $q->user_lastname;
			}

			if ($this->input->post('user_callsign', true)) {
				$data['user_callsign'] = $this->input->post('user_callsign', true);
			} else {
				$data['user_callsign'] = $q->user_callsign;
			}

			if ($this->input->post('user_locator', true)) {
				$data['user_locator'] = $this->input->post('user_locator', true);
			} else {
				$data['user_locator'] = $q->user_locator;
			}

			if ($this->input->post('user_timezone')) {
				$data['user_timezone'] = $this->input->post('user_timezone', true);
			} else {
				$data['user_timezone'] = $q->user_timezone;
			}

			if ($this->input->post('user_lotw_name')) {
				$data['user_lotw_name'] = $this->input->post('user_lotw_name', true);
			} else {
				$data['user_lotw_name'] = $q->user_lotw_name;
			}

			if ($this->input->post('user_clublog_name')) {
				$data['user_clublog_name'] = $this->input->post('user_clublog_name', true);
			} else {
				$data['user_clublog_name'] = $q->user_clublog_name;
			}

			if ($this->input->post('user_clublog_password')) {
				$data['user_clublog_password'] = $this->input->post('user_clublog_password', true);
			} else {
				$data['user_clublog_password'] = $q->user_clublog_password;
			}

			if ($this->input->post('user_lotw_password')) {
				$data['user_lotw_password'] = $this->input->post('user_lotw_password', true);
			} else {
				$data['user_lotw_password'] = $q->user_lotw_password;
			}

			if ($this->input->post('user_eqsl_name')) {
				$data['user_eqsl_name'] = $this->input->post('user_eqsl_name', true);
			} else {
				$data['user_eqsl_name'] = $q->user_eqsl_name;
			}

			if ($this->input->post('user_eqsl_password')) {
				$data['user_eqsl_password'] = $this->input->post('user_eqsl_password', true);
			} else {
				$data['user_eqsl_password'] = $q->user_eqsl_password;
			}

			if ($this->input->post('user_measurement_base')) {
				$data['user_measurement_base'] = $this->input->post('user_measurement_base', true);
			} else {
				$data['user_measurement_base'] = $q->user_measurement_base;
			}

			if ($this->input->post('user_date_format')) {
				$data['user_date_format'] = $this->input->post('user_date_format', true);
			} else {
				$data['user_date_format'] = $q->user_date_format;
			}

			if ($this->input->post('language')) {
				$data['language'] = $this->input->post('language', true);
			} else {
				$data['language'] = $q->language;
			}


			if ($this->input->post('user_stylesheet')) {
				$data['user_stylesheet'] = $this->input->post('user_stylesheet', true);
			} else {
				$data['user_stylesheet'] = $q->user_stylesheet;
			}

			if ($this->input->post('user_qth_lookup')) {
				$data['user_qth_lookup'] = $this->input->post('user_qth_lookup', true);
			} else {
				$data['user_qth_lookup'] = $q->user_qth_lookup;
			}

			if ($this->input->post('user_sota_lookup')) {
				$data['user_sota_lookup'] = $this->input->post('user_sota_lookup', true);
			} else {
				$data['user_sota_lookup'] = $q->user_sota_lookup;
			}

			if ($this->input->post('user_wwff_lookup')) {
				$data['user_wwff_lookup'] = $this->input->post('user_wwff_lookup', true);
			} else {
				$data['user_wwff_lookup'] = $q->user_wwff_lookup;
			}

			if ($this->input->post('user_pota_lookup')) {
				$data['user_pota_lookup'] = $this->input->post('user_pota_lookup', true);
			} else {
				$data['user_pota_lookup'] = $q->user_pota_lookup;
			}

			if ($this->input->post('user_show_notes')) {
				$data['user_show_notes'] = $this->input->post('user_show_notes', true);
			} else {
				$data['user_show_notes'] = $q->user_show_notes;
			}

			if ($this->input->post('user_qso_end_times')) {
				$data['user_qso_end_times'] = $this->input->post('user_qso_end_times', true);
			} else {
				$data['user_qso_end_times'] = $q->user_qso_end_times;
			}

			if ($this->input->post('user_quicklog')) {
				$data['user_quicklog'] = $this->input->post('user_quicklog', true);
			} else {
				$data['user_quicklog'] = $q->user_quicklog;
			}

			if ($this->input->post('user_quicklog_enter')) {
				$data['user_quicklog_enter'] = $this->input->post('user_quicklog_enter', true);
			} else {
				$data['user_quicklog_enter'] = $q->user_quicklog_enter;
			}

			if ($this->input->post('user_show_profile_image')) {
				$data['user_show_profile_image'] = $this->input->post('user_show_profile_image', false);
			} else {
				$data['user_show_profile_image'] = $q->user_show_profile_image;
			}

			if ($this->input->post('user_previous_qsl_type')) {
				$data['user_previous_qsl_type'] = $this->input->post('user_previous_qsl_type', false);
			} else {
				$data['user_previous_qsl_type'] = $q->user_previous_qsl_type;
			}

			if ($this->input->post('user_amsat_status_upload')) {
				$data['user_amsat_status_upload'] = $this->input->post('user_amsat_status_upload', false);
			} else {
				$data['user_amsat_status_upload'] = $q->user_amsat_status_upload;
			}

			if ($this->input->post('user_mastodon_url')) {
				$data['user_mastodon_url'] = $this->input->post('user_mastodon_url', false);
			} else {
				$data['user_mastodon_url'] = $q->user_mastodon_url;
			}

			if ($this->input->post('user_default_band')) {
				$data['user_default_band'] = $this->input->post('user_default_band', false);
			} else {
				$data['user_default_band'] = $q->user_default_band;
			}

			if ($this->input->post('user_default_confirmation')) {
				$data['user_default_confirmation'] = ($this->input->post('user_default_confirmation_qsl') !== null ? 'Q' : '') . ($this->input->post('user_default_confirmation_lotw') !== null ? 'L' : '') . ($this->input->post('user_default_confirmation_eqsl') !== null ? 'E' : '') . ($this->input->post('user_default_confirmation_qrz') !== null ? 'Z' : '');
			} else {
				$data['user_default_confirmation'] = $q->user_default_confirmation;
			}

			if ($this->input->post('user_column1')) {
				$data['user_column1'] = $this->input->post('user_column1', true);
			} else {
				$data['user_column1'] = $q->user_column1;
			}

			if ($this->input->post('user_column2')) {
				$data['user_column2'] = $this->input->post('user_column2', true);
			} else {
				$data['user_column2'] = $q->user_column2;
			}

			if ($this->input->post('user_column3')) {
				$data['user_column3'] = $this->input->post('user_column3', true);
			} else {
				$data['user_column3'] = $q->user_column3;
			}

			if ($this->input->post('user_column4')) {
				$data['user_column4'] = $this->input->post('user_column4', true);
			} else {
				$data['user_column4'] = $q->user_column4;
			}

			if ($this->input->post('user_column5')) {
				$data['user_column5'] = $this->input->post('user_column5', true);
			} else {
				$data['user_column5'] = $q->user_column5;
			}

			if ($this->input->post('user_winkey')) {
				$data['user_winkey'] = $this->input->post('user_winkey', true);
			} else {
				$data['user_winkey'] = $q->winkey;
			}

			$this->load->model('user_options_model');
			$callbook_type_object = $this->user_options_model->get_options('callbook')->result();

			if ($this->input->post('user_callbook_type', true)) {
				$data['user_callbook_type'] = $this->input->post('user_callbook_type', true);
			} else {
				if (isset($callbook_type_object[1]->option_value)) {
					$data['user_callbook_type'] = $callbook_type_object[1]->option_value;
				} else {
					$data['user_callbook_type'] = "";
				}
			}


			// Handle user_callbook_username
			if ($this->input->post('user_callbook_username', true)) {
				$data['user_callbook_username'] = $this->input->post('user_callbook_username', true);
			} else {
				if (isset($callbook_type_object[2]->option_value)) {
					$data['user_callbook_username'] = $callbook_type_object[2]->option_value;
				} else {
					$data['user_callbook_username'] = "";
				}
			}

			// Handle user_callbook_password
			if ($this->input->post('user_callbook_password', true)) {
				$data['user_callbook_password'] = $this->input->post('user_callbook_password', true);
			} else {
				if (isset($callbook_type_object[0]->option_value)) {
					$data['user_callbook_password'] = $callbook_type_object[0]->option_value;
				} else {
					$data['user_callbook_password'] = "";
				}
			}


			$this->load->model('user_options_model');
			$hamsat_user_object = $this->user_options_model->get_options('hamsat')->result();

			if ($this->input->post('user_hamsat_key', true)) {
				$data['user_hamsat_key'] = $this->input->post('user_hamsat_key', true);
			} else {
				// get $q->hamsat_key if its set if not null
				if (isset($hamsat_user_object[0]->option_value)) {
					$data['user_hamsat_key'] = $hamsat_user_object[0]->option_value;
				} else {
					$data['user_hamsat_key'] = "";
				}
			}

			if ($this->input->post('user_hamsat_workable_only')) {
				$data['user_hamsat_workable_only'] = $this->input->post('user_hamsat_workable_only', false);
			} else {
				if (isset($hamsat_user_object[1]->option_value)) {
					$data['user_hamsat_workable_only'] = $hamsat_user_object[1]->option_value;
				} else {
					$data['user_hamsat_workable_only'] = "";
				}
			}

			// Set defaults
			$data['dashboard_upcoming_dx_card'] = false;
			$data['dashboard_qslcard_card'] = false;
			$data['dashboard_eqslcard_card'] = false;
			$data['dashboard_lotw_card'] = false;
			$data['dashboard_vuccgrids_card'] = false;

			$dashboard_options = $this->user_options_model->get_options('dashboard')->result();

			foreach ($dashboard_options as $item) {
				$option_name = $item->option_name;
				$option_key = $item->option_key;
				$option_value = $item->option_value;

				if ($option_name == 'dashboard_upcoming_dx_card' && $option_key == 'enabled') {
					if ($item->option_value == 'true') {
						$data['dashboard_upcoming_dx_card'] = true;
					} else {
						$data['dashboard_upcoming_dx_card'] = false;
					}
				}

				if ($option_name == 'dashboard_qslcards_card' && $option_key == 'enabled') {
					if ($item->option_value == 'true') {
						$data['dashboard_qslcard_card'] = true;
					} else {
						$data['dashboard_qslcard_card'] = false;
					}
				}

				if ($option_name == 'dashboard_eqslcards_card' && $option_key == 'enabled') {
					if ($item->option_value == 'true') {
						$data['dashboard_eqslcard_card'] = true;
					} else {
						$data['dashboard_eqslcard_card'] = false;
					}
				}

				if ($option_name == 'dashboard_lotw_card' && $option_key == 'enabled') {
					if ($item->option_value == 'true') {
						$data['dashboard_lotw_card'] = true;
					} else {
						$data['dashboard_lotw_card'] = false;
					}
				}

				if ($option_name == 'dashboard_vuccgrids_card' && $option_key == 'enabled') {
					if ($item->option_value == 'true') {
						$data['dashboard_vuccgrids_card'] = true;
					} else {
						$data['dashboard_vuccgrids_card'] = false;
					}
				}
			}

			// [MAP Custom] GET user options //
			$this->load->model('user_options_model');
			$options_object = $this->user_options_model->get_options('map_custom')->result();
			if (count($options_object) > 0) {
				foreach ($options_object as $row) {
					if ($row->option_name == 'icon') {
						$option_value = json_decode($row->option_value, true);
						foreach ($option_value as $ktype => $vtype) {
							if ($this->input->post('user_map_' . $row->option_key . '_icon')) {
								$data['user_map_' . $row->option_key . '_' . $ktype] = $this->input->post('user_map_' . $row->option_key . '_' . $ktype, true);
							} else {
								$data['user_map_' . $row->option_key . '_' . $ktype] = $vtype;
							}
						}
					} else {
						$data['user_map_' . $row->option_name . '_' . $row->option_key] = $row->option_value;
					}
				}
			} else {
				$data['user_map_qso_icon'] = "fas fa-dot-circle";
				$data['user_map_qso_color'] = "#FF0000";
				$data['user_map_station_icon'] = "0";
				$data['user_map_station_color'] = "#0000FF";
				$data['user_map_qsoconfirm_icon'] = "0";
				$data['user_map_qsoconfirm_color'] = "#00AA00";
				$data['user_map_gridsquare_show'] = "0";
			}
			$data['map_icon_select'] = array(
				'station' => array('0', 'fas fa-home', 'fas fa-broadcast-tower', 'fas fa-user', 'fas fa-dot-circle'),
				'qso' => array('fas fa-broadcast-tower', 'fas fa-user', 'fas fa-dot-circle'),
				'qsoconfirm' => array('0', 'fas fa-broadcast-tower', 'fas fa-user', 'fas fa-dot-circle', 'fas fa-check-circle')
			);

			$this->load->view('interface_assets/header', $data);
			$this->load->view('user/edit', $data);
			$this->load->view('interface_assets/footer');
		} else {
			unset($data);
			switch ($this->user_model->edit($this->input->post())) {
					// Check for errors
				case EUSERNAMEEXISTS:
					$data['username_error'] = 'Username <b>' . $this->input->post('user_name', true) . '</b> already in use!';
					break;
				case EEMAILEXISTS:
					$data['email_error'] = 'E-mail address <b>' . $this->input->post('user_email', true) . '</b> already in use!';
					break;
				case EPASSWORDINVALID:
					$data['password_error'] = 'Invalid password!';
					break;
					// All okay, return to user screen
				case OK:
					if ($this->session->userdata('user_id') == $this->uri->segment(3)) { // Editing own User? Set cookie!
						$cookie = array(

							'name'   => 'language',
							'value'  => $this->input->post('language', true),
							'expire' => time() + 1000,
							'secure' => FALSE

						);
						$this->input->set_cookie($cookie);
					}
					if ($this->session->userdata('user_id') == $this->input->post('id', true)) {
						
						// Handle user_callbook_password

						if (isset($_POST['user_callbook_password']) && !empty($_POST['user_callbook_password'])) {
							
							// Handle user_callbook_type
							if (isset($_POST['user_callbook_type'])) {
								$this->user_options_model->set_option('callbook', 'callbook_type', array('value' => $_POST['user_callbook_type']));
							} else {
								$this->user_options_model->set_option('callbook', 'callbook_type', array('value' => ''));
							}
							
							// Handle user_callbook_username
							if (isset($_POST['user_callbook_username'])) {
								$this->user_options_model->set_option('callbook', 'callbook_username', array('value' => $_POST['user_callbook_username']));
							} else {
								$this->user_options_model->set_option('callbook', 'callbook_username', array('value' => ''));
							}
							
							// Load the encryption library
							$this->load->library('encryption');

							// Encrypt the password
							$encrypted_password = $this->encryption->encrypt($_POST['user_callbook_password']);

							// Save the encrypted password
							$this->user_options_model->set_option('callbook', 'callbook_password', array('value' => $encrypted_password));

							// if callbook type is QRZ
							if ($_POST['user_callbook_type'] == 'QRZ') {
								// Lookup using QRZ
								$this->load->library('qrz');

								$qrz_session_key = $this->qrz->session($_POST['user_callbook_username'], $_POST['user_callbook_password']);
								$this->session->set_userdata('qrz_session_key', $qrz_session_key);
							} elseif ($_POST['user_callbook_type'] == "HamQTH") {
								$this->load->library('hamqth');
								$hamqth_session_key = $this->hamqth->session($_POST['user_callbook_username'], $_POST['user_callbook_password']);
								$this->session->set_userdata('hamqth_session_key', $hamqth_session_key);
							}

							// Update Session data
							$this->session->set_userdata('callbook_type', $_POST['user_callbook_type']);
							$this->session->set_userdata('callbook_username', $_POST['user_callbook_username']);
							$this->session->set_userdata('callbook_password', $encrypted_password);
						}

						if (isset($_POST['user_dashboard_enable_dxpedition_card'])) {
							$this->user_options_model->set_option('dashboard', 'dashboard_upcoming_dx_card', array('enabled' => 'true'));
						} else {
							$this->user_options_model->set_option('dashboard', 'dashboard_upcoming_dx_card', array('enabled' => 'false'));
						}

						if (isset($_POST['user_dashboard_enable_qslcards_card'])) {
							$this->user_options_model->set_option('dashboard', 'dashboard_qslcards_card', array('enabled' => 'true'));
						} else {
							$this->user_options_model->set_option('dashboard', 'dashboard_qslcards_card', array('enabled' => 'false'));
						}

						if (isset($_POST['user_dashboard_enable_eqslcards_card'])) {
							$this->user_options_model->set_option('dashboard', 'dashboard_eqslcards_card', array('enabled' => 'true'));
						} else {
							$this->user_options_model->set_option('dashboard', 'dashboard_eqslcards_card', array('enabled' => 'false'));
						}

						if (isset($_POST['user_dashboard_enable_lotw_card'])) {
							$this->user_options_model->set_option('dashboard', 'dashboard_lotw_card', array('enabled' => 'true'));
						} else {
							$this->user_options_model->set_option('dashboard', 'dashboard_lotw_card', array('enabled' => 'false'));
						}

						if (isset($_POST['user_dashboard_enable_vuccgrids_card'])) {
							$this->user_options_model->set_option('dashboard', 'dashboard_vuccgrids_card', array('enabled' => 'true'));
						} else {
							$this->user_options_model->set_option('dashboard', 'dashboard_vuccgrids_card', array('enabled' => 'false'));
						}

						// [MAP Custom] ADD to user options //
						$array_icon = array('station', 'qso', 'qsoconfirm');
						foreach ($array_icon as $icon) {
							$data_options['user_map_' . $icon . '_icon'] = xss_clean($this->input->post('user_map_' . $icon . '_icon', true));
							$data_options['user_map_' . $icon . '_color'] = xss_clean($this->input->post('user_map_' . $icon . '_color', true));
						}
						$this->load->model('user_options_model');
						if (!empty($data_options['user_map_qso_icon'])) {
							foreach ($array_icon as $icon) {
								$json = json_encode(array('icon' => $data_options['user_map_' . $icon . '_icon'], 'color' => $data_options['user_map_' . $icon . '_color']));
								$this->user_options_model->set_option('map_custom', 'icon', array($icon => $json));
							}
							$this->user_options_model->set_option('map_custom', 'gridsquare', array('show' => xss_clean($this->input->post('user_map_gridsquare_show', true))));
						} else {
							$this->user_options_model->del_option('map_custom', 'icon');
							$this->user_options_model->del_option('map_custom', 'gridsquare');
						}

						$this->session->set_flashdata('success', lang('account_user') . ' ' . $this->input->post('user_name', true) . ' ' . lang('account_word_edited'));
						redirect('user/edit/' . $this->uri->segment(3));
					} else {
						$this->session->set_flashdata('success', lang('account_user') . ' ' . $this->input->post('user_name', true) . ' ' . lang('account_word_edited'));
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
			$data['user_pota_lookup'] = $this->input->post('user_pota_lookup');
			$data['user_show_notes'] = $this->input->post('user_show_notes');
			$data['user_column1'] = $this->input->post('user_column1');
			$data['user_column2'] = $this->input->post('user_column2');
			$data['user_column3'] = $this->input->post('user_column3');
			$data['user_column4'] = $this->input->post('user_column4');
			$data['user_column4'] = $this->input->post('user_column4');
			$data['user_column5'] = $this->input->post('user_column5');
			$data['user_show_profile_image'] = $this->input->post('user_show_profile_image');
			$data['user_previous_qsl_type'] = $this->input->post('user_previous_qsl_type');
			$data['user_amsat_status_upload'] = $this->input->post('user_amsat_status_upload');
			$data['user_mastodon_url'] = $this->input->post('user_mastodon_url');
			$data['user_default_band'] = $this->input->post('user_default_band');
			$data['user_default_confirmation'] = ($this->input->post('user_default_confirmation_qsl') !== null ? 'Q' : '') . ($this->input->post('user_default_confirmation_lotw') !== null ? 'L' : '') . ($this->input->post('user_default_confirmation_eqsl') !== null ? 'E' : '') . ($this->input->post('user_default_confirmation_qrz') !== null ? 'Z' : '');
			$data['user_qso_end_times'] = $this->input->post('user_qso_end_times');
			$data['user_quicklog'] = $this->input->post('user_quicklog');
			$data['user_quicklog_enter'] = $this->input->post('user_quicklog_enter');
			$data['language'] = $this->input->post('language');
			$data['user_winkey'] = $this->input->post('user_winkey');
			$data['user_hamsat_key'] = $this->input->post('user_hamsat_key');
			$data['user_hamsat_workable_only'] = $this->input->post('user_hamsat_workable_only');



			$this->load->view('user/edit');
			$this->load->view('interface_assets/footer');
		}
	}

	function profile()
	{
		$this->load->model('user_model');
		if (!$this->user_model->authorize(2)) {
			$this->session->set_flashdata('notice', 'You\'re not allowed to do that!');
			redirect('dashboard');
		}
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


	/**
	 * Deletes a user by their ID.
	 *
	 * This function first loads the 'user_model'. It then checks if the current user has the authorization level of 99.
	 * If not, it sets a flash message and redirects the user to the dashboard.
	 * 
	 * If the user is authorized, it gets the user to be deleted by their ID from the URI segment 3.
	 * It then calls the 'delete' function from the 'user_model' with the user ID as a parameter.
	 * 
	 * If the 'delete' function executes successfully, it sets the HTTP status code to 200.
	 * If the 'delete' function fails, it sets the HTTP status code to 500.
	 *
	 * @param int $id The ID of the user to delete.
	 */
	function delete_new($id)
	{
		$this->load->model('user_model');
		if (!$this->user_model->authorize(99)) {
			$this->session->set_flashdata('notice', 'You\'re not allowed to do that!');
			redirect('dashboard');
		}
		$query = $this->user_model->get_by_id($this->uri->segment(3));

		// call $this->user_model->delete and if no errors return true
		if ($this->user_model->delete($id)) {
			// request responds with a 200 status code and empty content
			$this->output->set_status_header(200);
		} else {
			// request responds with a 500 status code and empty content
			$this->output->set_status_header(500);
		}
	}

	function delete()
	{
		$this->load->model('user_model');
		if (!$this->user_model->authorize(99)) {
			$this->session->set_flashdata('notice', 'You\'re not allowed to do that!');
			redirect('dashboard');
		}
		$query = $this->user_model->get_by_id($this->uri->segment(3));

		$this->load->library('form_validation');

		$this->form_validation->set_rules('id', 'user_id', 'required');

		$data = $query->row();

		if ($this->form_validation->run() == FALSE) {

			$this->load->view('interface_assets/header', $data);
			$this->load->view('user/delete');
			$this->load->view('interface_assets/footer');
		} else {
			if ($this->user_model->delete($data->user_id)) {
				$this->session->set_flashdata('notice', 'User deleted');
				redirect('user');
			} else {
				$this->session->set_flashdata('notice', '<b>Database error:</b> Could not delete user!');
				redirect('user');
			}
		}
	}

	function login()
	{
		// Check our version and run any migrations
		$this->load->library('Migration');
		$this->load->library('encryption');

		$this->migration->current();

		$this->load->model('user_model');
		$query = $this->user_model->get($this->input->post('user_name', true));

		$this->load->library('form_validation');

		$this->form_validation->set_rules('user_name', 'Username', 'required');
		$this->form_validation->set_rules('user_password', 'Password', 'required');

		$data['user'] = $query->row();

		// Read the cookie remeber_me and log the user in
		if ($this->input->cookie(config_item('cookie_prefix') . 'remember_me')) {
			try {
				$encrypted_string = $this->input->cookie(config_item('cookie_prefix') . 'remember_me');
				$decrypted_string = $this->encryption->decrypt($encrypted_string);
				$this->user_model->update_session($decrypted_string);
				$this->user_model->set_last_login($decrypted_string);

				log_message('debug', '[User ID: ' . $decrypted_string . '] Remember Me Login Successful');

				redirect('dashboard');
			} catch (Exception $e) {
				// Something went wrong with the cookie
				log_message('error', 'Remember Me Login Failed');
				$this->session->set_flashdata('error', 'Remember Me Login Failed');
				redirect('user/login');
			}
		}

		if ($this->form_validation->run() == FALSE) {
			$data['page_title'] = "Login";
			$this->load->view('interface_assets/mini_header', $data);
			$this->load->view('user/login');
			$this->load->view('interface_assets/footer');
		} else {
			if ($this->user_model->login() == 1) {
				$this->session->set_flashdata('notice', 'User logged in');
				$this->user_model->update_session($data['user']->user_id);
				$this->user_model->set_last_login($data['user']->user_id);
				$cookie = array(

					'name'   => 'language',
					'value'  => $data['user']->language,
					'expire' => time() + 1000,
					'secure' => FALSE

				);

				// Create a remember me cookie
				if ($this->input->post('remember_me') == '1') {
					$encrypted_string = $this->encryption->encrypt($data['user']->user_id);

					$cookie = array(
						'name'   => 'remember_me',
						'value'  => $encrypted_string,
						'expire' => '1209600',
						'secure' => FALSE
					);
					$this->input->set_cookie($cookie);
				}
				redirect('dashboard');
			} else {
				$this->session->set_flashdata('error', 'Incorrect username or password!');
				redirect('user/login');
			}
		}
	}

	function logout()
	{
		$this->load->model('user_model');

		$user_name = $this->session->userdata('user_name');

		// Delete remember_me cookie
		setcookie('remember_me', '', time() - 3600, '/');

		$this->user_model->clear_session();

		$this->session->set_flashdata('notice', 'User ' . $user_name . ' logged out.');
		redirect('dashboard');
	}

	/**
	 * Function: forgot_password
	 *
	 * Allows users to input an email address and a password will be sent to that address.
	 *
	 */
	function forgot_password()
	{

		$this->load->helper(array('form', 'url'));

		$this->load->library('form_validation');

		$this->form_validation->set_rules('email', 'Email', 'required');

		if ($this->form_validation->run() == FALSE) {
			$data['page_title'] = "Forgot Password";
			$this->load->view('interface_assets/mini_header', $data);
			$this->load->view('user/forgot_password');
			$this->load->view('interface_assets/footer');
		} else {
			// Check email address exists
			$this->load->model('user_model');

			$check_email = $this->user_model->check_email_address($this->input->post('email', true));

			if ($check_email == TRUE) {
				// Generate password reset code 50 characters long
				$this->load->helper('string');
				$reset_code = random_string('alnum', 50);

				$this->user_model->set_password_reset_code($this->input->post('email', true), $reset_code);

				// Send email with reset code

				$this->data['reset_code'] = $reset_code;
				$this->load->library('email');

				if ($this->optionslib->get_option('emailProtocol') == "smtp") {
					$config = array(
						'protocol' => $this->optionslib->get_option('emailProtocol'),
						'smtp_crypto' => $this->optionslib->get_option('smtpEncryption'),
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

				$this->email->from($this->optionslib->get_option('emailAddress'), $this->optionslib->get_option('emailSenderName'));
				$this->email->to($this->input->post('email', true));

				$this->email->subject('Cloudlog Account Password Reset');
				$this->email->message($message);

				if (!$this->email->send()) {
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

	// Send an E-Mail to the user. Function is similar to forgot_password()
	function admin_send_passwort_reset()
	{

		$this->load->model('user_model');
		if (!$this->user_model->authorize(99)) {
			$this->session->set_flashdata('notice', 'You\'re not allowed to do that!');
			redirect('dashboard');
		}
		$query = $this->user_model->get_by_id($this->uri->segment(3));

		$this->load->library('form_validation');

		$this->form_validation->set_rules('id', 'user_id', 'required');

		$data = $query->row();

		if ($this->form_validation->run() != FALSE) {
			$this->session->set_flashdata('notice', 'Something went wrong! User has no user_id.');
			redirect('user');
		} else {
			// Check email address exists
			$this->load->model('user_model');

			$check_email = $this->user_model->check_email_address($data->user_email);

			if ($check_email == TRUE) {
				// Generate password reset code 50 characters long
				$this->load->helper('string');
				$reset_code = random_string('alnum', 50);
				$this->user_model->set_password_reset_code(($data->user_email), $reset_code);

				// Send email with reset code and first Name of the User

				$this->data['reset_code'] = $reset_code;
				$this->data['user_firstname'] = $data->user_firstname; // We can call the user by their first name in the E-Mail
				$this->data['user_callsign'] = $data->user_callsign;
				$this->data['user_name'] = $data->user_name;
				$this->load->library('email');

				if ($this->optionslib->get_option('emailProtocol') == "smtp") {
					$config = array(
						'protocol' => $this->optionslib->get_option('emailProtocol'),
						'smtp_crypto' => $this->optionslib->get_option('smtpEncryption'),
						'smtp_host' => $this->optionslib->get_option('smtpHost'),
						'smtp_port' => $this->optionslib->get_option('smtpPort'),
						'smtp_user' => $this->optionslib->get_option('smtpUsername'),
						'smtp_pass' => $this->optionslib->get_option('smtpPassword'),
						'crlf' => "\r\n",
						'newline' => "\r\n"
					);

					$this->email->initialize($config);
				}

				$message = $this->load->view('email/admin_reset_password', $this->data,  TRUE);

				$this->email->from($this->optionslib->get_option('emailAddress'), $this->optionslib->get_option('emailSenderName'));
				$this->email->to($data->user_email);
				$this->email->subject('Cloudlog Account Password Reset');
				$this->email->message($message);

				if (!$this->email->send()) {
					// Redirect to user page with message
					$this->session->set_flashdata('danger', lang('admin_email_settings_incorrect'));
					redirect('user');
				} else {
					// Redirect to user page with message
					$this->session->set_flashdata('success', lang('admin_password_reset_processed'));
					redirect('user');
				}
			} else {
				// No account found just return to user page
				$this->session->set_flashdata('danger', 'Nothing done. No user found.');
				redirect('user');
			}
		}
	}

	function reset_password($reset_code = NULL)
	{
		$data['reset_code'] = $reset_code;
		if ($reset_code != NULL) {
			$this->load->helper(array('form', 'url'));

			$this->load->library('form_validation');

			$this->form_validation->set_rules('password', 'Password', 'required');
			$this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'required|matches[password]');

			if ($this->form_validation->run() == FALSE) {
				$data['page_title'] = "Reset Password";
				$this->load->view('interface_assets/mini_header', $data);
				$this->load->view('user/reset_password');
				$this->load->view('interface_assets/footer');
			} else {
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

	function check_locator($grid)
	{
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
			$this->form_validation->set_message('check_locator', 'Please check value for grid locator (' . strtoupper($grid) . ').');
			return false;
		}
	}
}
