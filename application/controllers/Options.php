<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
	Handles Displaying of information for station tools.
*/

function check_access_key($param): bool
{
	$param_keys = array_keys($param);
	if (!in_array("access_key_id", $param_keys) || !in_array("access_key_secret", $param_keys))
	{
		return false;
	}
	if (!is_string($param["access_key_id"]) || !is_string($param["access_key_secret"]))
	{
		return false;
	}
	if ($param["access_key_id"] == "" || $param["access_key_secret"] == "")
	{
		return false;
	}
	return true;
}

function check_driver($driver_name): bool
{
	if (!is_string($driver_name))
	{
		return false;
	}
	if ($driver_name != "aws_s3" && $driver_name != "local")
	{
		return false;
	}
	return true;
}

function check_driver_inner($param, $allowed_params, $required_params)
{
	foreach($param as $key => $value)
	{
		if (!in_array($key, $allowed_params))
		{
			return array(
				"valid" => false,
				"message" => "Parameter not allowed for the driver."
			);
		}
	}
	foreach($required_params as $rpara)
	{
		if (!in_array($rpara, array_keys($param)) || $param[$rpara] == "" || !is_string($param[$rpara]))
		{
			return array(
				"valid" => false,
				"message" => "Missing required param ".$rpara."."
			);
		}
	}
	return array(
		"valid" => true,
		"message" => ""
	);
}

function check_local_param($param)
{
	$allowed_params = ["name", "url_prefix", "dir_path"];
	$required_params = ["name", "url_prefix", "dir_path"];
	return check_driver_inner($param, $allowed_params, $required_params);
}

function check_aws_s3_param($param)
{
	$allowed_params = ["name", "url_prefix", "access_key_id", "access_key_secret", "region", "bucket_name", "hostname"];
	$required_params = ["name", "url_prefix", "region", "hostname"];
	return check_driver_inner($param, $allowed_params, $required_params);
}

function check_param_generic($driver, $param)
{
	foreach($param as $value)
	{
		if (!is_string($value))
		{
			return array(
				"valid" => false,
				"message" => "Parameter type invalid."
			);
		}
		if (mb_strlen($value) >= 255)
		{
			return array(
				"valid" => false,
				"message" => "Parameter too long."
			);
		}
	}
	$checker = "check_".$driver."_param";
	return $checker($param);
}

class Options extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));

		$this->load->model('user_model');
		if(!$this->user_model->authorize(99)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
	}


	// Default /options view just gives some text to explain the options area
    function index() {


        //echo $this->config->item('option_theme');

		//echo $this->optionslib->get_option('theme');

		$data['page_title'] = "Cloudlog Options";

		$this->load->view('interface_assets/header', $data);
		$this->load->view('options/index');
		$this->load->view('interface_assets/footer');
	}

	// function used to display the /appearance url
	function appearance() {

		// Get Language Options
		$directory = 'application/language';
		$data['language_options'] = array_diff(scandir($directory), array('..', '.'));

		$data['page_title'] = "Cloudlog Options";
		$data['sub_heading'] = "Appearance";

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

		$data['page_title'] = "Cloudlog Options";
		$data['sub_heading'] = "Appearance";

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
				$this->session->set_flashdata('success', 'Theme changed to '.$this->input->post('theme'));
			}

			// Update theme choice within the options system
			$search_update_status = $this->optionslib->update('global_search', $this->input->post('globalSearch'));

			// If theme update is complete set a flashsession with a success note
			if($search_update_status == TRUE) {
				$this->session->set_flashdata('success', 'Global Search changed to '.$this->input->post('globalSearch'));
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

		// function used to display the /radio url
		function radio() {

			$data['page_title'] = "Cloudlog Options";
			$data['sub_heading'] = "Radio Settings";

			$this->load->view('interface_assets/header', $data);
			$this->load->view('options/radios');
			$this->load->view('interface_assets/footer');
		}

	// Handles saving the radio options to the options system.
	function radio_save() {

		// Get Language Options

		$data['page_title'] = "Cloudlog Options";
		$data['sub_heading'] = "Radio Settings";

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
				$this->session->set_flashdata('success', 'Radio Timeout Warning changed to '.$this->input->post('radioTimeout').' seconds');
			}

			// Redirect back to /appearance
			redirect('/options/radio');
		}
    }

	// function used to display the /appearance url
	function email() {

		$data['page_title'] = "Cloudlog Options";
		$data['sub_heading'] = "Email";

		$this->load->view('interface_assets/header', $data);
		$this->load->view('options/email');
		$this->load->view('interface_assets/footer');
    }

	// Handles saving the radio options to the options system.
	function email_save() {

			// Get Language Options

			$data['page_title'] = "Cloudlog Options";
			$data['sub_heading'] = "Email";

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
					$this->session->set_flashdata('success', 'Outgoing Email Protocol changed to '.$this->input->post('emailProtocol'));
				}

				// Update smtpEncryption choice within the options system
				$smtpEncryptionupdate = $this->optionslib->update('smtpEncryption', $this->input->post('smtpEncryption'), 'yes');

				// If smtpEncryption update is complete set a flashsession with a success note
				if($smtpEncryptionupdate == TRUE) {
					$this->session->set_flashdata('success', 'SMTP Encryption changed to '.$this->input->post('smtpEncryption'));
				}

				// Update smtpHost choice within the options system
				$smtpHostupdate = $this->optionslib->update('smtpHost', $this->input->post('smtpHost'), 'yes');

				// If smtpHost update is complete set a flashsession with a success note
				if($smtpHostupdate == TRUE) {
					$this->session->set_flashdata('success', 'SMTP Host changed to '.$this->input->post('smtpHost'));
				}

				// Update smtpPort choice within the options system
				$smtpPortupdate = $this->optionslib->update('smtpPort', $this->input->post('smtpPort'), 'yes');

				// If smtpPort update is complete set a flashsession with a success note
				if($smtpPortupdate == TRUE) {
					$this->session->set_flashdata('success', 'SMTP Port changed to '.$this->input->post('smtpPort'));
				}

				// Update smtpUsername choice within the options system
				$smtpUsernameupdate = $this->optionslib->update('smtpUsername', $this->input->post('smtpUsername'), 'yes');

				// If smtpUsername update is complete set a flashsession with a success note
				if($smtpUsernameupdate == TRUE) {
					$this->session->set_flashdata('success', 'SMTP Username changed to '.$this->input->post('smtpUsername'));
				}

				// Update smtpPassword choice within the options system
				$smtpPasswordupdate = $this->optionslib->update('smtpPassword', $this->input->post('smtpPassword'), 'yes');

				// If smtpPassword update is complete set a flashsession with a success note
				if($smtpPasswordupdate == TRUE) {
					$this->session->set_flashdata('success', 'SMTP Password changed to '.$this->input->post('smtpPassword'));
				}

				// Update emailcrlf choice within the options system
				$emailcrlfupdate = $this->optionslib->update('emailcrlf', $this->input->post('emailcrlf'), 'yes');

				// If emailcrlf update is complete set a flashsession with a success note
				if($emailcrlfupdate == TRUE) {
					$this->session->set_flashdata('success', 'Email CRLF changed to '.$this->input->post('emailcrlf'));
				}

				// Update emailnewline choice within the options system
				$emailnewlineupdate = $this->optionslib->update('emailnewline', $this->input->post('emailnewline'), 'yes');

				// If emailnewline update is complete set a flashsession with a success note
				if($emailnewlineupdate == TRUE) {
					$this->session->set_flashdata('success', 'Email Newline changed to '.$this->input->post('emailnewline'));
				}

				// Redirect back to /appearance
				redirect('/options/email');
			}
		}

	function filemgr() {

		$data['page_title'] = "Cloudlog Options";
		$data['sub_heading'] = "File Manager";

		$this->load->model('Filemanager_model');

		$data["file_managers"] = $this->Filemanager_model->get_all();
		$data["qsl_default_filemgr"] = $this->Filemanager_model->get_qsl_default_id();

		$this->load->view('interface_assets/header', $data);
		$this->load->view('options/file_manager');
		$this->load->view('interface_assets/footer');
	}

	function filemgr_default_set() {
		$qsl_default_filemgr = xss_clean($this->input->post('qsl_default_filemgr'));
		$this->load->model('Filemanager_model');
		$this->output->set_content_type("application/json");
		try {
			$fm = $this->Filemanager_model->get($qsl_default_filemgr);
		} catch (Exception $e) {
			$this->output->set_output(json_encode(array('error' => 'File manager not found.')));
			$this->output->set_status_header(404);
			return;
		}
		$this->Filemanager_model->set_qsl_default_id($qsl_default_filemgr);
		$this->output->set_output(json_encode(array()));
	}

	function filemgr_add() {

		$data['page_title'] = "Cloudlog Options";
		$data['sub_heading'] = "File Manager - Add";

		$data["file_manager"] = array(
			"name" => "",
			"url_prefix" => "",
			"driver" => "local"
		);

		$this->load->view('interface_assets/header', $data);
		$this->load->view('options/file_manager_edit');
		$this->load->view('interface_assets/footer');
	}

	function filemgr_add_submit() {
		$driver = $this->input->post('driver');
		$param_json = $this->input->post('param');
		$param = json_decode($param_json, true);

		$this->output->set_content_type("application/json");
		$this->load->library("file_manager");
		if (!check_driver($driver))
		{
			$this->output->set_output(json_encode(array('error' => 'Driver unsupported')));
			$this->output->set_status_header(400);
			return;
		}
		if ($driver == "aws_s3")
		{
			if (!check_access_key($param))
			{
				$this->output->set_output(json_encode(array('error' => 'Missing access key information')));
				$this->output->set_status_header(400);
				return;
			}
		}
		$param_chk_result = check_param_generic($driver, $param);
		if (!$param_chk_result["valid"])
		{
			$this->output->set_output(json_encode(array('error' => $param_chk_result["message"])));
			$this->output->set_status_header(400);
			return;
		}
		$cfg = $param;
		$cfg["driver"] = $driver;
		$this->file_manager->create_manager($cfg);
		$this->output->set_output(json_encode(array()));
	}

	function filemgr_edit() {

		$data['page_title'] = "Cloudlog Options";
		$data['sub_heading'] = "File Manager - Edit";

		$id = $this->uri->segment(3);
		$this->load->library('File_manager');
		$data["file_manager"] = $this->file_manager->get_manager($id);

		$this->load->view('interface_assets/header', $data);
		$this->load->view('options/file_manager_edit');
		$this->load->view('interface_assets/footer');
	}

	function filemgr_save() {
		$id = $this->input->post('id');
		$driver = $this->input->post('driver');
		$param_json = $this->input->post('param');
		$param = json_decode($param_json, true);

		$this->output->set_content_type("application/json");
		$this->load->library("file_manager");
		try {
			$origin_cfg = $this->file_manager->get_manager($id);
		} catch (Exception $e) {
			$this->output->set_output(json_encode(array('error' => 'File manager not found.')));
			$this->output->set_status_header(404);
			return;
		}
		if (!check_driver($driver))
		{
			$this->output->set_output(json_encode(array('error' => 'Driver unsupported.')));
			$this->output->set_status_header(400);
			return;
		}
		$param_chk_result = check_param_generic($driver, $param);
		if (!$param_chk_result["valid"])
		{
			$this->output->set_output(json_encode(array('error' => $param_chk_result["message"])));
			$this->output->set_status_header(400);
			return;
		}
		if ($origin_cfg["driver"] == "local" && $driver == "aws_s3")
		{
			if (!check_access_key($param))
			{
				$this->output->set_output(json_encode(array('error' => 'Missing access key information.')));
				$this->output->set_status_header(400);
				return;
			}
		}
		$cfg = $param;
		$cfg["driver"] = $driver;
		$cfg["id"] = $id;
		$this->file_manager->update_manager($cfg);

		$this->output->set_output(json_encode(array()));
	}

	function filemgr_del() {
		$id = $this->input->post('id');

		$this->load->model('Filemanager_model');
		$this->output->set_content_type("application/json");
		try {
			$this->Filemanager_model->delete($id);
		} catch (Exception $e) {
			$this->output->set_output(json_encode(array('error' => $e->getMessage())));
			$this->output->set_status_header(404);
			return;
		}
		$this->output->set_output(json_encode(array()));
	}

}
