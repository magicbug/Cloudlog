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
	
				// If emailProtocolupdate update is complete set a flashsession with a success note
				if($emailProtocolupdate == TRUE) {
					$this->session->set_flashdata('success', $this->lang->line('options_outgoing_email_protocol_changed_to').$this->input->post('emailProtocol'));
				}

				// Update smtpEncryption choice within the options system
				$smtpEncryptionupdate = $this->optionslib->update('smtpEncryption', $this->input->post('smtpEncryption'), 'yes');
	
				// If smtpEncryption update is complete set a flashsession with a success note
				if($smtpEncryptionupdate == TRUE) {
					$this->session->set_flashdata('success', $this->lang->line('options_smtp_encryption_changed_to').$this->input->post('smtpEncryption'));
				}

				// Update email sender name within the options system
				$emailSenderNameupdate = $this->optionslib->update('emailSenderName', $this->input->post('emailSenderName'), 'yes');

				// If email address update is complete set a flashsession with a success note
				if($emailSenderNameupdate == TRUE) {
					$this->session->set_flashdata('success', $this->lang->line('options_email_sender_name_changed_to').$this->input->post('emailSenderName'));
				}

				// Update email address choice within the options system
				$emailAddressupdate = $this->optionslib->update('emailAddress', $this->input->post('emailAddress'), 'yes');

				// If email address update is complete set a flashsession with a success note
				if($emailAddressupdate == TRUE) {
					$this->session->set_flashdata('success', $this->lang->line('options_email_address_changed_to').$this->input->post('emailAddress'));
				}

				// Update smtpHost choice within the options system
				$smtpHostupdate = $this->optionslib->update('smtpHost', $this->input->post('smtpHost'), 'yes');
	
				// If smtpHost update is complete set a flashsession with a success note
				if($smtpHostupdate == TRUE) {
					$this->session->set_flashdata('success', $this->lang->line('options_smtp_host_changed_to').$this->input->post('smtpHost'));
				}

				// Update smtpPort choice within the options system
				$smtpPortupdate = $this->optionslib->update('smtpPort', $this->input->post('smtpPort'), 'yes');
	
				// If smtpPort update is complete set a flashsession with a success note
				if($smtpPortupdate == TRUE) {
					$this->session->set_flashdata('success', $this->lang->line('options_smtp_port_changed_to').$this->input->post('smtpPort'));
				}
	
				// Update smtpUsername choice within the options system
				$smtpUsernameupdate = $this->optionslib->update('smtpUsername', $this->input->post('smtpUsername'), 'yes');
	
				// If smtpUsername update is complete set a flashsession with a success note
				if($smtpUsernameupdate == TRUE) {
					$this->session->set_flashdata('success', $this->lang->line('options_smtp_username_changed_to').$this->input->post('smtpUsername'));
				}

				// Update smtpPassword choice within the options system
				$smtpPasswordupdate = $this->optionslib->update('smtpPassword', $this->input->post('smtpPassword'), 'yes');
	
				// If smtpPassword update is complete set a flashsession with a success note
				if($smtpPasswordupdate == TRUE) {
					$this->session->set_flashdata('success', $this->lang->line('options_smtp_password_changed_to').$this->input->post('smtpPassword'));
				}

				// Update emailcrlf choice within the options system
				$emailcrlfupdate = $this->optionslib->update('emailcrlf', $this->input->post('emailcrlf'), 'yes');
	
				// If emailcrlf update is complete set a flashsession with a success note
				if($emailcrlfupdate == TRUE) {
					$this->session->set_flashdata('success', $this->lang->line('options_email_crlf_changed_to').$this->input->post('emailcrlf'));
				}

				// Update emailnewline choice within the options system
				$emailnewlineupdate = $this->optionslib->update('emailnewline', $this->input->post('emailnewline'), 'yes');
	
				// If emailnewline update is complete set a flashsession with a success note
				if($emailnewlineupdate == TRUE) {
					$this->session->set_flashdata('success', $this->lang->line('options_email_newline_changed_to').$this->input->post('emailnewline'));
				}
	
				// Redirect back to /appearance
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

		if($global_oqrs_text == TRUE) {
			$this->session->set_flashdata('success', $this->lang->line('options_oqrs_options_have_been_saved'));
		}

		redirect('/options/oqrs');
    }

}
