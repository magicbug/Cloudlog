<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
	Handles Displaying of information for station tools.
*/

class Options extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));

		$this->load->model('user_model');
		if(!$this->user_model->authorize(99)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }

		// Load language files
		$this->lang->load(array(
			'options',
		));


	}




	// Default /options view just gives some text to explain the options area
    function index() {


        //echo $this->config->item('option_theme');

		//echo $this->optionslib->get_option('theme');

		$data['page_title'] = $this->lang->line('options_cloudlog_options');

		$this->load->view('interface_assets/header', $data);
		$this->load->view('options/index');
		$this->load->view('interface_assets/footer');
	}

	// function used to display the /appearance url
	function appearance() {

		// Get Language Options
		$directory = 'application/language';
		$data['language_options'] = array_diff(scandir($directory), array('..', '.'));

		$data['page_title'] = $this->lang->line('options_cloudlog_options');
		$data['sub_heading'] = $this->lang->line('options_appearance');

		$this->load->model('Themes_model');

		$data['themes'] = $this->Themes_model->getThemes();

		$this->load->view('interface_assets/header', $data);
		$this->load->view('options/appearance');
		$this->load->view('interface_assets/footer');
    }

	// Handles saving the appreance options to the options system.
	function appearance_save() {

		// Get Language Options
		$directory = 'application/language';
		$data['language_options'] = array_diff(scandir($directory), array('..', '.'));

		$data['page_title'] = $this->lang->line('options_cloudlog_options');
		$data['sub_heading'] = $this->lang->line('options_appearance');

		$this->load->helper(array('form', 'url'));

		$this->load->library('form_validation');

		$this->form_validation->set_rules('theme', 'theme', 'required');

		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('interface_assets/header', $data);
			$this->load->view('options/appearance');
			$this->load->view('interface_assets/footer');
		}
		else
		{
			// Update theme choice within the options system
			$theme_update_status = $this->optionslib->update('theme', $this->input->post('theme'), 'yes');

			// If theme update is complete set a flashsession with a success note
			if($theme_update_status == TRUE) {
				$this->session->set_flashdata('success', $this->lang->line('options_theme_changed_to').$this->input->post('theme'));
			}

			// Update theme choice within the options system
			$search_update_status = $this->optionslib->update('global_search', $this->input->post('globalSearch'));

			// If theme update is complete set a flashsession with a success note
			if($search_update_status == TRUE) {
				$this->session->set_flashdata('success', $this->lang->line('options_global_search_changed_to').$this->input->post('globalSearch'));
			}

			// Update dashboard banner within the options system
			$dasboard_banner_update_status = $this->optionslib->update('dashboard_banner', $this->input->post('dashboardBanner'), 'yes');

			// If dashboard banner update is complete set a flashsession with a success note
			if($dasboard_banner_update_status == TRUE) {
				$this->session->set_flashdata('success', $this->lang->line('options_dashboard_banner_changed_to').$this->input->post('dashboardBanner'));
			}

			// Update dashboard map within the options system
			$dashboard_map_update_status = $this->optionslib->update('dashboard_map', $this->input->post('dashboardMap'), 'yes');

			// If dashboard map update is complete set a flashsession with a success note
			if($dashboard_map_update_status == TRUE) {
				$this->session->set_flashdata('success', $this->lang->line('options_dashboard_map_changed_to').$this->input->post('dashboardMap'));
			}

			// Update logbook map within the options system
			$logbook_map_update_status = $this->optionslib->update('logbook_map', $this->input->post('logbookMap'), 'yes');

			// If logbook map update is complete set a flashsession with a success note
			if($logbook_map_update_status == TRUE) {
				$this->session->set_flashdata('success', $this->lang->line('options_logbook_map_changed_to').$this->input->post('logbookMap'));
			}

			// Update Lang choice within the options system
			// $lang_update_status = $this->optionslib->update('language', $this->input->post('language'));

			// If Lang update is complete set a flashsession with a success note
			// if($lang_update_status == TRUE) {
			// 	$this->session->set_flashdata('success', 'Language changed to '.ucfirst($this->input->post('language')));
			// }

			// Redirect back to /appearance
			redirect('/options/appearance');
		}
    }

	// function used to display the /dxcluster url
	function dxcluster() {
			$data['page_title'] = $this->lang->line('options_cloudlog_options');
			$data['sub_heading'] = $this->lang->line('options_dxcluster_settings');

			$this->load->view('interface_assets/header', $data);
			$this->load->view('options/dxcluster');
			$this->load->view('interface_assets/footer');
	}

	// Handles saving the DXCluster options to the options system.
	function dxcluster_save() {

		// Get Language Options

		$data['page_title'] = $this->lang->line('options_cloudlog_options');
		$data['sub_heading'] = $this->lang->line('options_dxcluster_settings');

		$this->load->helper(array('form', 'url'));

		$this->load->library('form_validation');

		$this->form_validation->set_rules('dxcache_url', 'URL of DXCache', 'valid_url');
		$this->form_validation->set_rules('dxcluster_maxage', 'Max Age of Spots', 'required');
		$this->form_validation->set_rules('dxcluster_decont', 'de continent', 'required');

		if ($this->form_validation->run() == FALSE) {
			$this->load->view('interface_assets/header', $data);
			$this->load->view('options/dxcluster');
			$this->load->view('interface_assets/footer');
		} else {
			$dxcluster_decont_update = $this->optionslib->update('dxcluster_decont', $this->input->post('dxcluster_decont'), 'yes');
			if($dxcluster_decont_update == TRUE) {
				$this->session->set_flashdata('success', $this->lang->line('options_dxcluster_decont_changed_to').$this->input->post('dxcluster_decont'));
			}

			$dxcluster_maxage_update = $this->optionslib->update('dxcluster_maxage', $this->input->post('dxcluster_maxage'), 'yes');
			if($dxcluster_maxage_update == TRUE) {
				$this->session->set_flashdata('success', $this->lang->line('options_dxcluster_maxage_changed_to').$this->input->post('dxcluster_maxage'));
			}

			$dxcache_url_update = $this->optionslib->update('dxcache_url', $this->input->post('dxcache_url'), 'yes');
			if($dxcache_url_update == TRUE) {
				$this->session->set_flashdata('success', $this->lang->line('options_dxcache_url_changed_to').$this->input->post('dxcache_url'));
			}
			redirect('/options/dxcluster');
		}
	}

		// function used to display the /radio url
		function radio() {

			$data['page_title'] = $this->lang->line('options_cloudlog_options');
			$data['sub_heading'] = $this->lang->line('options_radio_settings');

			$this->load->view('interface_assets/header', $data);
			$this->load->view('options/radios');
			$this->load->view('interface_assets/footer');
		}

	// Handles saving the radio options to the options system.
	function radio_save() {

		// Get Language Options

		$data['page_title'] = $this->lang->line('options_cloudlog_options');
		$data['sub_heading'] = $this->lang->line('options_radio_settings');

		$this->load->helper(array('form', 'url'));

		$this->load->library('form_validation');

		$this->form_validation->set_rules('radioTimeout', 'radioTimeout', 'required');

		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('interface_assets/header', $data);
			$this->load->view('options/radios');
			$this->load->view('interface_assets/footer');
		}
		else
		{
			// Update theme choice within the options system
			$radioTimeout_update = $this->optionslib->update('cat_timeout_interval', $this->input->post('radioTimeout'), 'yes');

			// If theme update is complete set a flashsession with a success note
			if($radioTimeout_update == TRUE) {
				$this->session->set_flashdata('success', $this->lang->line('options_radio_timeout_warning_changed_to').$this->input->post('radioTimeout').' seconds');
			}

			// Redirect back to /appearance
			redirect('/options/radio');
		}
    }

	// function used to display the /appearance url
	function email() {

		$data['page_title'] = $this->lang->line('options_cloudlog_options');
		$data['sub_heading'] = $this->lang->line('options_email');

		$this->load->view('interface_assets/header', $data);
		$this->load->view('options/email');
		$this->load->view('interface_assets/footer');
    }

	// Handles saving the radio options to the options system.
	function email_save() {

			// Get Language Options
	
			$data['page_title'] = $this->lang->line('options_cloudlog_options');
			$data['sub_heading'] = $this->lang->line('options_email');
	
			$this->load->helper(array('form', 'url'));
	
			$this->load->library('form_validation');
	
			$this->form_validation->set_rules('emailProtocol', 'Email Protocol', 'required');
	
			if ($this->form_validation->run() == FALSE)
			{
				$this->load->view('interface_assets/header', $data);
				$this->load->view('options/email');
				$this->load->view('interface_assets/footer');
			}
			else
			{

				// Update emailProtocol choice within the options system
				$emailProtocolupdate = $this->optionslib->update('emailProtocol', $this->input->post('emailProtocol'), 'yes');

				// Update smtpEncryption choice within the options system
				$smtpEncryptionupdate = $this->optionslib->update('smtpEncryption', $this->input->post('smtpEncryption'), 'yes');

				// Update email sender name within the options system
				$emailSenderName_value = $this->input->post('emailSenderName');
				if (empty($emailSenderName_value)) {
					$emailSenderName_value = 'Cloudlog';
				}
				$emailSenderNameupdate = $this->optionslib->update('emailSenderName', $emailSenderName_value, 'yes');

				// Update email address choice within the options system
				$emailAddressupdate = $this->optionslib->update('emailAddress', $this->input->post('emailAddress'), 'yes');

				// Update smtpHost choice within the options system
				$smtpHostupdate = $this->optionslib->update('smtpHost', $this->input->post('smtpHost'), 'yes');

				// Update smtpPort choice within the options system
				$smtpPortupdate = $this->optionslib->update('smtpPort', $this->input->post('smtpPort'), 'yes');
	
				// Update smtpUsername choice within the options system
				$smtpUsernameupdate = $this->optionslib->update('smtpUsername', $this->input->post('smtpUsername'), 'yes');

				// Update smtpPassword choice within the options system
				$smtpPasswordupdate = $this->optionslib->update('smtpPassword', $this->input->post('smtpPassword'), 'yes');
	
				// Check if all updates are successful
				$updateSuccessful = $emailProtocolupdate &&
									$smtpEncryptionupdate &&
									$emailSenderNameupdate &&
									$emailAddressupdate &&
									$smtpHostupdate &&
									$smtpPortupdate &&
									$smtpUsernameupdate &&
									$smtpPasswordupdate;

				// Set flash session based on update success
				if ($updateSuccessful) {
					$this->session->set_flashdata('success', $this->lang->line('options_mail_settings_saved'));
				} else {
					$this->session->set_flashdata('saveFailed', $this->lang->line('options_mail_settings_failed'));
				}
	
				// Redirect back to /email
				redirect('/options/email');
			}
		}

		function oqrs() {

			$data['page_title'] = $this->lang->line('options_cloudlog_options');
			$data['sub_heading'] = $this->lang->line('options_oqrs');

			$this->load->view('interface_assets/header', $data);
			$this->load->view('options/oqrs');
			$this->load->view('interface_assets/footer');
		}

		function oqrs_save() {

		$data['page_title'] = $this->lang->line('options_cloudlog_options');
		$data['sub_heading'] = $this->lang->line('options_oqrs');

		$this->load->helper(array('form', 'url'));

		$this->load->library('form_validation');

		$global_oqrs_text = $this->optionslib->update('global_oqrs_text', $this->input->post('global_oqrs_text'), null);

		$global_oqrs_text = $this->optionslib->update('groupedSearch', $this->input->post('groupedSearch'), null);

		$global_oqrs_text = $this->optionslib->update('groupedSearchShowStationName', $this->input->post('groupedSearchShowStationName'), null);

		if($global_oqrs_text == TRUE) {
			$this->session->set_flashdata('success', $this->lang->line('options_oqrs_options_have_been_saved'));
		}

		redirect('/options/oqrs');
    }

	function sendTestMail() {
		$this->load->model('user_model');

		$id = $this->session->userdata('user_id');

		$email = $this->user_model->get_user_email_by_id($id);

		if($email != "") {

			$this->load->library('email');

			if($this->optionslib->get_option('emailProtocol') == "smtp") {
				$config = Array(
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

			$message = $this->load->view('email/testmail.php', NULL, TRUE);

			$this->email->from($this->optionslib->get_option('emailAddress'), $this->optionslib->get_option('emailSenderName'));
			$this->email->to($email);
			$this->email->subject('Cloudlog Test-Mail');
			$this->email->message($message);

			if (! $this->email->send()){
				$this->session->set_flashdata('testmailFailed', $this->lang->line('options_send_testmail_failed'));
			} else {
				$this->session->set_flashdata('testmailSuccess', $this->lang->line('options_send_testmail_success'));
			}
		} else {
			$this->session->set_flashdata('testmailFailed', $this->lang->line('options_send_testmail_failed'));
		}
		
		redirect('/options/email');
	}

	// function used to display the /version_dialog url
	function version_dialog() {

		$data['page_title'] = $this->lang->line('options_cloudlog_options');
		$data['sub_heading'] = $this->lang->line('options_version_dialog_settings');

		$this->load->view('interface_assets/header', $data);
		$this->load->view('options/version_dialog');
		$this->load->view('interface_assets/footer');
    }

	function version_dialog_save() {

		// Get Language Options

		$data['page_title'] = $this->lang->line('options_cloudlog_options');
		$data['sub_heading'] = $this->lang->line('options_version_dialog_settings');

		$this->load->helper(array('form', 'url'));

		$version_dialog_header_update = $this->optionslib->update('version_dialog_header', $this->input->post('version_dialog_header'), 'yes');
		if($version_dialog_header_update == TRUE) {
			$this->session->set_flashdata('success0', $this->lang->line('options_version_dialog_header_changed_to')." "."'".$this->input->post('version_dialog_header')."'");
		}
		$version_dialog_mode_update = $this->optionslib->update('version_dialog', $this->input->post('version_dialog_mode'), 'yes');
		if($version_dialog_mode_update == TRUE) {
			$this->session->set_flashdata('success1', $this->lang->line('options_version_dialog_mode_changed_to')." "."'".$this->input->post('version_dialog_mode')."'");
		}
		if ($this->input->post('version_dialog_mode') == "both" || $this->input->post('version_dialog_mode') == "custom_text" ) { 
			$version_dialog_custom_text_update = $this->optionslib->update('version_dialog_text', $this->input->post('version_dialog_custom_text'), 'yes');
			if($version_dialog_custom_text_update == TRUE) {
				$this->session->set_flashdata('success2', $this->lang->line('options_version_dialog_custom_text_saved'));
			}
		}

		redirect('/options/version_dialog');
		
	}

	function version_dialog_show_to_all() {
		$update_vd_confirmation_to_false = $this->user_options_model->set_option_at_all_users('version_dialog', 'confirmed', array('boolean' => 'false'));
		if($update_vd_confirmation_to_false == TRUE) {
			$this->session->set_flashdata('success_trigger', $this->lang->line('options_version_dialog_success_show_all'));
		}
		redirect('/options/version_dialog');
	}

	function version_dialog_show_to_none() {
		$update_vd_confirmation_to_true = $this->user_options_model->set_option_at_all_users('version_dialog', 'confirmed', array('boolean' => 'true'));
		if($update_vd_confirmation_to_true == TRUE) {
			$this->session->set_flashdata('success_trigger', $this->lang->line('options_version_dialog_success_hide_all'));
		}
		redirect('/options/version_dialog');
	}

}
