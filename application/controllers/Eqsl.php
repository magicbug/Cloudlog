<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class eqsl extends CI_Controller
{

	/* Controls who can access the controller and its functions */
	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
	}

	// Default view when loading controller.
	public function index()
	{

		$this->lang->load('qslcard');
		$this->load->helper('storage');
		$folder_name = "images/eqsl_card_images";
		$data['storage_used'] = sizeFormat(folderSize($folder_name));


		// Render Page
		$data['page_title'] = "eQSL Cards";

		$this->load->model('eqsl_images');
		$data['qslarray'] = $this->eqsl_images->eqsl_qso_list();

		$this->load->view('interface_assets/header', $data);
		$this->load->view('eqslcard/index');
		$this->load->view('interface_assets/footer');
	}
	public function import()
	{
		$this->load->model('user_model');
		if (!$this->user_model->authorize(2)) {
			$this->session->set_flashdata('notice', 'You\'re not allowed to do that!');
			redirect('dashboard');
		}

		$this->load->model('stations');
		$data['station_profile'] = $this->stations->all_of_user();
		$active_station_id = $this->stations->find_active();
		$station_profile = $this->stations->profile($active_station_id);
		$data['active_station_info'] = $station_profile->row();

		// Check if eQSL Nicknames have been defined
		$this->load->model('eqslmethods_model');
		$eqsl_locations = $this->eqslmethods_model->all_of_user_with_eqsl_nick_defined();
		if ($eqsl_locations->num_rows() == 0) {
			$this->session->set_flashdata('error', 'eQSL Nicknames in Station Profiles aren\'t defined!');
		}

		ini_set('memory_limit', '-1');
		set_time_limit(0);

		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'adi|ADI';

		$this->load->library('upload', $config);

		$eqsl_results = array();
		if ($this->input->post('eqslimport') == 'fetch') {
			$this->load->library('EqslImporter');

			// Get credentials for eQSL
			$query = $this->user_model->get_by_id($this->session->userdata('user_id'));
			$q = $query->row();
			$eqsl_password = $q->user_eqsl_password;

			// Validate that eQSL credentials are not empty
			if ($eqsl_password == '') {
				$this->session->set_flashdata('warning', 'You have not defined your eQSL.cc credentials!');
				redirect('eqsl/import');
			}

			$eqsl_force_from_date = (!$this->input->post('eqsl_force_from_date') == "") ? $this->input->post('eqsl_force_from_date') : "";
			foreach ($eqsl_locations->result_array() as $eqsl_location) {
				$this->eqslimporter->from_callsign_and_QTH(
					$eqsl_location['station_callsign'],
					$eqsl_location['eqslqthnickname'],
					$config['upload_path'],
					$eqsl_location['station_id']
				);

				$eqsl_results[] = $this->eqslimporter->fetch($eqsl_password, $eqsl_force_from_date);
			}
		} elseif ($this->input->post('eqslimport') == 'upload') {
			$station_id4upload = $this->input->post('station_profile');
			if ($this->stations->check_station_is_accessible($station_id4upload)) {
				$station_callsign = $this->stations->profile($station_id4upload)->row()->station_callsign;
				if (!$this->upload->do_upload()) {
					$data['page_title'] = "eQSL Import";
					$data['error'] = $this->upload->display_errors();

					$this->load->view('interface_assets/header', $data);
					$this->load->view('eqsl/import');
					$this->load->view('interface_assets/footer');

					return;
				} else {
					$data = array('upload_data' => $this->upload->data());

					$this->load->library('EqslImporter');
					$this->eqslimporter->from_file('./uploads/' . $data['upload_data']['file_name'], $station_callsign, $station_id4upload);

					$eqsl_results[] = $this->eqslimporter->import();
				}
			} else {
				log_message('error', $station_id4upload . " is not valid for user!");
			}
		} else {
			$data['page_title'] = "eQSL Import";

			$this->load->view('interface_assets/header', $data);
			$this->load->view('eqsl/import');
			$this->load->view('interface_assets/footer');

			return;
		}

		$data['eqsl_results'] = $eqsl_results;
		$data['page_title'] = "eQSL Import Information";

		$this->load->view('interface_assets/header', $data);
		$this->load->view('eqsl/analysis');
		$this->load->view('interface_assets/footer');
	} // end function

	public function export()
	{
		$this->load->model('user_model');
		if (!$this->user_model->authorize(2)) {
			$this->session->set_flashdata('notice', 'You\'re not allowed to do that!');
			redirect('dashboard');
		}

		// Check if eQSL Nicknames have been defined
		$this->load->model('stations');
		if ($this->stations->are_eqsl_nicks_defined() == 0) {
			$this->session->set_flashdata('error', 'eQSL Nicknames in Station Profiles aren\'t defined!');
		}

		ini_set('memory_limit', '-1');
		set_time_limit(0);
		$this->load->model('eqslmethods_model');

		$data['page_title'] = "eQSL QSO Upload";
		$custom_date_format = $this->session->userdata('user_date_format');

		if ($this->input->post('eqslexport') == "export") {
			// Get credentials for eQSL
			$query = $this->user_model->get_by_id($this->session->userdata('user_id'));
			$q = $query->row();
			$data['user_eqsl_name'] = $q->user_eqsl_name;
			$data['user_eqsl_password'] = $q->user_eqsl_password;

			// Validate that eQSL credentials are not empty
			if ($data['user_eqsl_name'] == '' || $data['user_eqsl_password'] == '') {
				$this->session->set_flashdata('warning', 'You have not defined your eQSL.cc credentials!');
				redirect('eqsl/import');
			}

			$rows = '';
			// Grab the list of QSOs to send information about
			// perform an HTTP get on each one, and grab the status back
			$qslsnotsent = $this->eqslmethods_model->eqsl_not_yet_sent();

			foreach ($qslsnotsent->result_array() as $qsl) {
				$rows .= "<tr>";
				// eQSL username changes for linked account.
				// i.e. when operating /P it must be callsign/p
				// the password, however, is always the same as the main account
				$data['user_eqsl_name'] = $qsl['station_callsign'];
				$adif = $this->generateAdif($qsl, $data);

				$status = $this->uploadQso($adif, $qsl);

				$timestamp = strtotime($qsl['COL_TIME_ON']);
				$rows .= "<td>" . date($custom_date_format, $timestamp) . "</td>";
				$rows .= "<td>" . date('H:i', $timestamp) . "</td>";
				$rows .= "<td>" . str_replace("0", "&Oslash;", $qsl['COL_CALL']) . "</td>";
				$rows .= "<td>" . $qsl['COL_MODE'] . "</td>";
				if (isset($qsl['COL_SUBMODE'])) {
					$rows .= "<td>" . $qsl['COL_SUBMODE'] . "</td>";
				} else {
					$rows .= "<td></td>";
				}
				$rows .= "<td>" . $qsl['COL_BAND'] . "</td>";
				$rows .= "<td>" . $status . "</td>";
			}
			$rows .= "</tr>";
			$data['eqsl_table'] = $this->generateResultTable($custom_date_format, $rows);
		} else {
			$qslsnotsent = $this->eqslmethods_model->eqsl_not_yet_sent();
			if ($qslsnotsent->num_rows() > 0) {
				$data['eqsl_table'] = $this->writeEqslNotSent($qslsnotsent->result_array(), $custom_date_format);
			}
		}

		$this->load->view('interface_assets/header', $data);
		$this->load->view('eqsl/export');
		$this->load->view('interface_assets/footer');
	}

	function uploadQso($adif, $qsl)
	{
		$this->load->model('eqslmethods_model');
		$status = "";

		// begin script
		$ch = curl_init();

		// basic curl options for all requests
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 1);

		// use the URL we built
		curl_setopt($ch, CURLOPT_URL, $adif);

		$result = curl_exec($ch);
		$chi = curl_getinfo($ch);
		curl_close($ch);

		/*  Time for some error handling
			Things we might get back
			Result: 0 out of 0 records added -> eQSL didn't understand the format
			Result: 1 out of 1 records added -> Fantastic
			Error: No match on eQSL_User/eQSL_Pswd -> eQSL credentials probably wrong
			Warning: Y=2013 M=08 D=11 F6ARS 15M JT65 Bad record: Duplicate
			Result: 0 out of 1 records added -> Dupe, OM!
		*/

		if ($chi['http_code'] == "200") {
			if (stristr($result, "Result: 1 out of 1 records added")) {
				$status = "Sent";
				$this->eqslmethods_model->eqsl_mark_sent($qsl['COL_PRIMARY_KEY']);
			} else {
				if (stristr($result, "Error: No match on eQSL_User/eQSL_Pswd")) {
					$this->session->set_flashdata('warning', 'Your eQSL username and/or password is incorrect.');
					redirect('eqsl/export');
				} else {
					if (stristr($result, "Result: 0 out of 0 records added")) {
						$this->session->set_flashdata('warning', 'Something went wrong with eQSL.cc!');
						redirect('eqsl/export');
					} else {
						if (stristr($result, "Bad record: Duplicate")) {
							$status = "Duplicate";

							# Mark the QSL as sent if this is a dupe.
							$this->eqslmethods_model->eqsl_mark_sent($qsl['COL_PRIMARY_KEY']);
						}
					}
				}
			}
		} else {
			if ($chi['http_code'] == "500") {
				$this->session->set_flashdata('warning', 'eQSL.cc is experiencing issues. Please try exporting QSOs later.');
				redirect('eqsl/export');
			} else {
				if ($chi['http_code'] == "400") {
					$this->session->set_flashdata('warning', 'There was an error in one of the QSOs. You might want to manually upload them.');
					redirect('eqsl/export');
					$status = "Error";
				} else {
					if ($chi['http_code'] == "404") {
						$this->session->set_flashdata('warning', 'It seems that the eQSL site has changed. Please open up an issue on GitHub.');
						redirect('eqsl/export');
					}
				}
			}
		}
		log_message('debug', $result);
		return $status;
	}

	function generateResultTable($custom_date_format, $rows)
	{
		$table = '<table = style="width:100%" class="table-sm table table-bordered table-hover table-striped table-condensed text-center">';
		$table .= "<thead><tr class=\"titles\">";
		$table .= "<th>Date</th>";
		$table .= "<th>Time</th>";
		$table .= "<th>Call</th>";
		$table .= "<th>Mode</th>";
		$table .= "<th>Submode</th>";
		$table .= "<th>Band</th>";
		$table .= "<th>Status</th>";
		$table .= "</tr></thead><tbody>";

		$table .= $rows;
		$table .= "</tbody></table>";

		return $table;
	}

	// Build out the ADIF info string according to specs https://eqsl.cc/qslcard/ADIFContentSpecs.cfm
	function generateAdif($qsl, $data)
	{
		$COL_QSO_DATE = date('Ymd', strtotime($qsl['COL_TIME_ON']));
		$COL_TIME_ON = date('Hi', strtotime($qsl['COL_TIME_ON']));

		# Set up the single record file
		$adif = "https://www.eqsl.cc/qslcard/importADIF.cfm?";
		$adif .= "ADIFData=CloudlogUpload%20";

		/* Handy reference of escaping chars
			"<" = 3C
			">" = 3E
			":" = 3A
			" " = 20
			"_" = 5F
			"-" = 2D
			"." = 2E
			"&" = 26
		*/

		$adif .= "%3C";
		$adif .= "ADIF%5FVER";
		$adif .= "%3A";
		$adif .= "4";
		$adif .= "%3E";
		$adif .= "1%2E00 ";
		$adif .= "%20";

		$adif .= "%3C";
		$adif .= "EQSL%5FUSER";
		$adif .= "%3A";
		$adif .= strlen($data['user_eqsl_name']);
		$adif .= "%3E";
		$adif .= $data['user_eqsl_name'];
		$adif .= "%20";

		$adif .= "%3C";
		$adif .= "EQSL%5FPSWD";
		$adif .= "%3A";
		$adif .= strlen($data['user_eqsl_password']);
		$adif .= "%3E";
		$adif .= urlencode($data['user_eqsl_password']);
		$adif .= "%20";

		$adif .= "%3C";
		$adif .= "EOH";
		$adif .= "%3E";

		# Lay out the required fields
		$adif .= "%3C";
		$adif .= "QSO%5FDATE";
		$adif .= "%3A";
		$adif .= "8";
		$adif .= "%3E";
		$adif .= $COL_QSO_DATE;
		$adif .= "%20";

		$adif .= "%3C";
		$adif .= "TIME%5FON";
		$adif .= "%3A";
		$adif .= "4";
		$adif .= "%3E";
		$adif .= $COL_TIME_ON;
		$adif .= "%20";

		$adif .= "%3C";
		$adif .= "CALL";
		$adif .= "%3A";
		$adif .= strlen($qsl['COL_CALL']);
		$adif .= "%3E";
		$adif .= $qsl['COL_CALL'];
		$adif .= "%20";

		$adif .= "%3C";
		$adif .= "MODE";
		$adif .= "%3A";
		$adif .= strlen($qsl['COL_MODE']);
		$adif .= "%3E";
		$adif .= $qsl['COL_MODE'];
		$adif .= "%20";

		if (isset($qsl['COL_SUBMODE'])) {
			$adif .= "%3C";
			$adif .= "SUBMODE";
			$adif .= "%3A";
			$adif .= strlen($qsl['COL_SUBMODE']);
			$adif .= "%3E";
			$adif .= $qsl['COL_SUBMODE'];
			$adif .= "%20";
		}

		$adif .= "%3C";
		$adif .= "BAND";
		$adif .= "%3A";
		$adif .= strlen($qsl['COL_BAND']);
		$adif .= "%3E";
		$adif .= $qsl['COL_BAND'];
		$adif .= "%20";

		# End all the required fields

		// adding RST_Sent
		$adif .= "%3C";
		$adif .= "RST%5FSENT";
		$adif .= "%3A";
		$adif .= strlen($qsl['COL_RST_SENT']);
		$adif .= "%3E";
		$adif .= $qsl['COL_RST_SENT'];
		$adif .= "%20";

		// adding prop mode if it isn't blank
		if ($qsl['COL_PROP_MODE']) {
			$adif .= "%3C";
			$adif .= "PROP%5FMODE";
			$adif .= "%3A";
			$adif .= strlen($qsl['COL_PROP_MODE']);
			$adif .= "%3E";
			$adif .= $qsl['COL_PROP_MODE'];
			$adif .= "%20";
		}

		// adding sat name if it isn't blank
		if ($qsl['COL_SAT_NAME'] != '') {
			$adif .= "%3C";
			$adif .= "SAT%5FNAME";
			$adif .= "%3A";
			$adif .= strlen($qsl['COL_SAT_NAME']);
			$adif .= "%3E";
			$adif .= str_replace('-', '%2D', $qsl['COL_SAT_NAME']);
			$adif .= "%20";
		}

		// adding sat mode if it isn't blank
		if ($qsl['COL_SAT_MODE'] != '') {
			$adif .= "%3C";
			$adif .= "SAT%5FMODE";
			$adif .= "%3A";
			$adif .= strlen($qsl['COL_SAT_MODE']);
			$adif .= "%3E";
			$adif .= $qsl['COL_SAT_MODE'];
			$adif .= "%20";
		}

		// adding qslmsg if it isn't blank
		if ($qsl['COL_QSLMSG'] != '') {
			$qsl['COL_QSLMSG'] = str_replace(array(chr(10), chr(13)), array(' ', ' '), $qsl['COL_QSLMSG']);
			$adif .= "%3C";
			$adif .= "QSLMSG";
			$adif .= "%3A";
			$adif .= strlen($qsl['COL_QSLMSG']);
			$adif .= "%3E";
			$adif .= str_replace('&', '%26', $qsl['COL_QSLMSG']);
			$adif .= "%20";
		}

		if ($qsl['eqslqthnickname'] != '') {
			$adif .= "%3C";
			$adif .= "APP%5FEQSL%5FQTH%5FNICKNAME";
			$adif .= "%3A";
			$adif .= strlen($qsl['eqslqthnickname']);
			$adif .= "%3E";
			$adif .= $qsl['eqslqthnickname'];
			$adif .= "%20";
		}

		// adding sat mode if it isn't blank
		if ($qsl['station_gridsquare'] != '') {
			$adif .= "%3C";
			$adif .= "MY%5FGRIDSQUARE";
			$adif .= "%3A";
			$adif .= strlen($qsl['station_gridsquare']);
			$adif .= "%3E";
			$adif .= $qsl['station_gridsquare'];
			$adif .= "%20";
		}

		# Tie a bow on it!
		$adif .= "%3C";
		$adif .= "EOR";
		$adif .= "%3E";

		# Make sure we don't have any spaces
		$adif = str_replace(" ", '%20', $adif);

		return $adif;
	}

	function writeEqslNotSent($qslsnotsent, $custom_date_format)
	{
		$table = '<table = style="width:100%" class="table-sm table qsotable table-bordered table-hover table-striped table-condensed text-center">';
		$table .= "<thead><tr class=\"titles\">";
		$table .= "<th>Date</th>";
		$table .= "<th>Time</th>";
		$table .= "<th>Call</th>";
		$table .= "<th>Mode</th>";
		$table .= "<th>Submode</th>";
		$table .= "<th>Band</th>";
		$table .= "<th>eQSL QTH Nickname</th>";
		$table .= "</tr></thead><tbody>";

		foreach ($qslsnotsent as $qsl) {
			$table .= "<tr>";
			$timestamp = strtotime($qsl['COL_TIME_ON']);
			$table .= "<td>" . date($custom_date_format, $timestamp) . "</td>";
			$table .= "<td>" . date('H:i', $timestamp) . "</td>";
			$table .= "<td><a href=\"javascript:displayQso(" . $qsl['COL_PRIMARY_KEY'] . ")\">" . str_replace("0", "&Oslash;", strtoupper($qsl['COL_CALL'])) . "</a></td>";
			$table .= "<td>" . $qsl['COL_MODE'] . "</td>";

			if (isset($qsl['COL_SUBMODE'])) {
				$table .= "<td>" . $qsl['COL_SUBMODE'] . "</td>";
			} else {
				$table .= "<td></td>";
			}
			$table .= "<td>" . $qsl['COL_BAND'] . "</td>";
			$table .= "<td>" . $qsl['eqslqthnickname'] . "</td>";
			$table .= "</tr>";
		}
		$table .= "</tbody></table>";

		return $table;
	}

	function image($id)
	{
		$this->load->library('electronicqsl');
		$this->load->model('Eqsl_images');

		if ($this->Eqsl_images->get_image($id) == "No Image") {
			$this->load->model('logbook_model');
			$this->load->model('user_model');
			$qso_query = $this->logbook_model->get_qso($id);
			$qso = $qso_query->row();
			$qso_timestamp = strtotime($qso->COL_TIME_ON);
			$callsign = $qso->COL_CALL;
			$band = $qso->COL_BAND;
			$mode = $qso->COL_MODE;
			$year = date('Y', $qso_timestamp);
			$month = date('m', $qso_timestamp);
			$day = date('d', $qso_timestamp);
			$hour = date('H', $qso_timestamp);
			$minute = date('i', $qso_timestamp);

			$query = $this->user_model->get_by_id($this->session->userdata('user_id'));
			$q = $query->row();
			$username = $qso->COL_STATION_CALLSIGN;
			$password = $q->user_eqsl_password;

			$image_url = $this->electronicqsl->card_image($username, urlencode($password), $callsign, $band, $mode, $year, $month, $day, $hour, $minute);
			$file = file_get_contents($image_url, true);

			$dom = new domDocument;
			$dom->loadHTML($file);
			$dom->preserveWhiteSpace = false;
			$images = $dom->getElementsByTagName('img');

			if (!isset($images) || count($images) == 0) {
				echo "Rate Limited";
				exit;
			}

			foreach ($images as $image) {
				header('Content-Type: image/jpg');
				$content = file_get_contents("https://www.eqsl.cc" . $image->getAttribute('src'));
				if ($content === false) {
					echo "No response";
					exit;
				}
				echo $content;
				$filename = uniqid() . '.jpg';
				if (file_put_contents('images/eqsl_card_images/' . '/' . $filename, $content) !== false) {
					$this->Eqsl_images->save_image($id, $filename);
				}
			}
		} else {
			header('Content-Type: image/jpg');
			$image_url = base_url('images/eqsl_card_images/' . $this->Eqsl_images->get_image($id));
			header('Location: ' . $image_url);
		}
	}

	function bulk_download_image($id)
	{
		$this->load->library('electronicqsl');
		$this->load->model('Eqsl_images');

		$this->load->model('logbook_model');
		$this->load->model('user_model');
		$qso_query = $this->logbook_model->get_qso($id);
		$qso = $qso_query->row();
		$qso_timestamp = strtotime($qso->COL_TIME_ON);
		$callsign = $qso->COL_CALL;
		$band = $qso->COL_BAND;
		$mode = $qso->COL_MODE;
		$year = date('Y', $qso_timestamp);
		$month = date('m', $qso_timestamp);
		$day = date('d', $qso_timestamp);
		$hour = date('H', $qso_timestamp);
		$minute = date('i', $qso_timestamp);

		$query = $this->user_model->get_by_id($this->session->userdata('user_id'));
		$q = $query->row();
		$username = $qso->COL_STATION_CALLSIGN;
		$password = $q->user_eqsl_password;
		$error = '';

		$image_url = $this->electronicqsl->card_image($username, urlencode($password), $callsign, $band, $mode, $year, $month, $day, $hour, $minute);
		$file = file_get_contents($image_url, true);
		if (strpos($file, 'Error') !== false) {
			$error = rtrim(preg_replace('/^\s*Error: /', '', $file));
			return $error;
		}

		$dom = new domDocument;
		$dom->loadHTML($file);
		$dom->preserveWhiteSpace = false;
		$images = $dom->getElementsByTagName('img');

		if (!isset($images) || count($images) == 0) {
			$error = "Rate Limited";
			return $error;
		}

		foreach ($images as $image) {
			$content = file_get_contents("https://www.eqsl.cc" . $image->getAttribute('src'));
			if ($content === false) {
				$error = "No response";
				return $error;
			}
			$filename = uniqid() . '.jpg';
			if (file_put_contents('images/eqsl_card_images/' . '/' . $filename, $content) !== false) {
				$this->Eqsl_images->save_image($id, $filename);
			}
		}
		return $error;
	}

	public function tools()
	{
		// Check logged in
		$this->load->model('user_model');
		if (!$this->user_model->authorize(2)) {
			$this->session->set_flashdata('notice', 'You\'re not allowed to do that!');
			redirect('dashboard');
		}

		$data['page_title'] = "eQSL Tools";

		// Load frontend
		$this->load->view('interface_assets/header', $data);
		$this->load->view('eqsl/tools');
		$this->load->view('interface_assets/footer');
	}

	public function download()
	{
		// Check logged in
		$this->load->model('user_model');
		if (!$this->user_model->authorize(2)) {
			$this->session->set_flashdata('notice', 'You\'re not allowed to do that!');
			redirect('dashboard');
		}
		$errors = 0;

		if ($this->input->post('eqsldownload') == 'download') {
			$i = 0;
			$this->load->model('eqslmethods_model');
			$qslsnotdownloaded = $this->eqslmethods_model->eqsl_not_yet_downloaded();
			$eqsl_results = array();
			foreach ($qslsnotdownloaded->result_array() as $qsl) {
				$result = $this->bulk_download_image($qsl['COL_PRIMARY_KEY']);
				if ($result != '') {
					$errors++;
					if ($result == 'Rate Limited') {
						break;
					} else {
						$eqsl_results[] = array(
							'date' => $qsl['COL_TIME_ON'],
							'call' => $qsl['COL_CALL'],
							'mode' => $qsl['COL_MODE'],
							'submode' => $qsl['COL_SUBMODE'],
							'status' => $result,
							'qsoid' => $qsl['COL_PRIMARY_KEY']
						);
						continue;
					}
				} else {
					$i++;
				}
				if ($i > 0) {
					sleep(15);
				}
			}
			$data['eqsl_results'] = $eqsl_results;
			$data['eqsl_stats'] = "Successfully downloaded: " . $i . " / Errors: " . count($eqsl_results);
			$data['page_title'] = "eQSL Download Information";

			$this->load->view('interface_assets/header', $data);
			$this->load->view('eqsl/result');
			$this->load->view('interface_assets/footer');
		} else {

			$data['page_title'] = "eQSL Card Image Download";
			$this->load->model('eqslmethods_model');

			$data['custom_date_format'] = $this->session->userdata('user_date_format');
			$data['qslsnotdownloaded'] = $this->eqslmethods_model->eqsl_not_yet_downloaded();

			$this->load->view('interface_assets/header', $data);
			$this->load->view('eqsl/download');
			$this->load->view('interface_assets/footer');
		}
	}

	public function mark_all_sent()
	{
		// Check logged in
		$this->load->model('user_model');
		if (!$this->user_model->authorize(2)) {
			$this->session->set_flashdata('notice', 'You\'re not allowed to do that!');
			redirect('dashboard');
		}

		// mark all eqsls as sent
		$this->load->model('eqslmethods_model');
		$this->eqslmethods_model->mark_all_as_sent();

		$this->session->set_flashdata('success', 'All eQSLs Marked as Uploaded');

		redirect('eqsl/tools');
	}

	/*
	 * Used for CRON job
	 */
	public function sync()
	{
		ini_set('memory_limit', '-1');
		set_time_limit(0);
		$this->load->model('eqslmethods_model');

		$users = $this->eqslmethods_model->get_eqsl_users();

		foreach ($users as $user) {
			$this->uploadUser($user->user_id, $user->user_eqsl_name, $user->user_eqsl_password);
			$this->downloadUser($user->user_id, $user->user_eqsl_name, $user->user_eqsl_password);
		}
	}

	public function downloadUser($userid, $username, $password)
	{
		$this->load->library('EqslImporter');
		$this->load->model('eqslmethods_model');

		$config['upload_path'] = './uploads/';
		$eqsl_locations = $this->eqslmethods_model->all_of_user_with_eqsl_nick_defined($userid);

		$eqsl_results = array();

		foreach ($eqsl_locations->result_array() as $eqsl_location) {
			$this->eqslimporter->from_callsign_and_QTH(
				$eqsl_location['station_callsign'],
				$eqsl_location['eqslqthnickname'],
				$config['upload_path'],
				$eqsl_location['station_id']
			);

			$eqsl_results[] = $this->eqslimporter->fetch($password);
		}
	}

	function uploadUser($userid, $username, $password)
	{
		$data['user_eqsl_name'] = $this->security->xss_clean($username);
		$data['user_eqsl_password'] = $this->security->xss_clean($password);
		$clean_userid = $this->security->xss_clean($userid);

		$qslsnotsent = $this->eqslmethods_model->eqsl_not_yet_sent($clean_userid);

		foreach ($qslsnotsent->result_array() as $qsl) {
			$data['user_eqsl_name'] = $qsl['station_callsign'];
			$adif = $this->generateAdif($qsl, $data);

			$status = $this->uploadQso($adif, $qsl);
		}
	}
} // end class
