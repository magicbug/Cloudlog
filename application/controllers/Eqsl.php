<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class eqsl extends CI_Controller {

	/* Controls who can access the controller and its functions */
	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		
		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
	}

	public function import() {
		// Check if eQSL Nicknames have been defined
		$this->load->model('stations');
		$eqsl_locations = $this->stations->all_of_user_with_eqsl_nick_defined();
		if($eqsl_locations->num_rows() == 0) {
			show_error("eQSL Nicknames in Station Profiles aren't defined");
			exit;
		}

		ini_set('memory_limit', '-1');
		set_time_limit(0);

		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'adi|ADI';

		$this->load->library('upload', $config);

		$this->load->model('logbook_model');

		$eqsl_results = array();
		if ($this->input->post('eqslimport') == 'fetch')
		{
			$this->load->library('EqslImporter');

			// Get credentials for eQSL
			$query = $this->user_model->get_by_id($this->session->userdata('user_id'));
			$q = $query->row();
			$eqsl_password = $q->user_eqsl_password;

			// Validate that eQSL credentials are not empty
			if ($eqsl_password == '')
			{
				$this->session->set_flashdata('warning', 'You have not defined your eQSL.cc credentials!');
				redirect('eqsl/import');
			}

			foreach ($eqsl_locations->result_array() as $eqsl_location) {
				$this->eqslimporter->from_callsign_and_QTH(
					$eqsl_location['station_callsign'],
					$eqsl_location['eqslqthnickname'],
					$config['upload_path']
				);

				$eqsl_results[] = $this->eqslimporter->fetch($eqsl_password);
			}
		}
		else
		{
			if ( ! $this->upload->do_upload())
			{
				$data['page_title'] = "eQSL Import";
				$data['error'] = $this->upload->display_errors();

				$this->load->view('interface_assets/header', $data);
				$this->load->view('eqsl/import');
				$this->load->view('interface_assets/footer');

				return;
			}
			else
			{
				$data = array('upload_data' => $this->upload->data());

				$this->load->library('EqslImporter');
				$this->eqslimporter->from_file('./uploads/'.$data['upload_data']['file_name']);

				$eqsl_results[] = $this->eqslimporter->import();
			}
		}

		$data['eqsl_results'] = $eqsl_results;
		$data['page_title'] = "eQSL Import Information";

		$this->load->view('interface_assets/header', $data);
		$this->load->view('eqsl/analysis');
		$this->load->view('interface_assets/footer');
	} // end function

	public function export() {
		// Check if eQSL Nicknames have been defined
			$this->load->model('stations');
			if($this->stations->are_eqsl_nicks_defined() == 0) {
				show_error('eQSL Nicknames in Station Profiles arent defined');
				exit;
			}

		ini_set('memory_limit', '-1');
		set_time_limit(0);
		$this->load->model('logbook_model');
		
		$data['page_title'] = "eQSL QSO Upload";
		
		if ($this->input->post('eqslexport') == "export")
		{
			// Get credentials for eQSL
			$query = $this->user_model->get_by_id($this->session->userdata('user_id'));
    		$q = $query->row();
    		$data['user_eqsl_name'] = $q->user_eqsl_name;
			$data['user_eqsl_password'] = $q->user_eqsl_password;
			
			// Validate that eQSL credentials are not empty
			if ($data['user_eqsl_name'] == '' || $data['user_eqsl_password'] == '')
			{
				$this->session->set_flashdata('warning', 'You have not defined your eQSL.cc credentials!'); redirect('eqsl/import');
			}
			
			// Grab the list of QSOs to send information about
			// perform an HTTP get on each one, and grab the status back
			$qslsnotsent = $this->logbook_model->eqsl_not_yet_sent();
			 
			$table = "<table>";
					$table .= "<tr class=\"titles\">";
						$table .= "<td>Date</td>";
						$table .= "<td>Call</td>";
						$table .= "<td>Mode</td>";
						$table .= "<td>Submode</td>";
						$table .= "<td>Band</td>";
						$table .= "<td>Result</td>";
					$table .= "<tr>";
			// Build out the ADIF info string according to specs http://eqsl.cc/qslcard/ADIFContentSpecs.cfm
			foreach ($qslsnotsent->result_array() as $qsl)
			{
				// eQSL username changes for linked account.
				// i.e. when operating /P it must be callsign/p
				// the password, however, is always the same as the main account
				$data['user_eqsl_name'] = $qsl['station_callsign'];

				$COL_QSO_DATE = date('Ymd',strtotime($qsl['COL_TIME_ON']));
				$COL_TIME_ON = date('Hi',strtotime($qsl['COL_TIME_ON']));
				
				# Set up the single record file
				$adif = "http://www.eqsl.cc/qslcard/importADIF.cfm?";
				$adif .= "ADIFData=CloudlogUpload%20";
				
				/* Handy reference of escaping chars
					"<" = 3C
					">" = 3E
					":" = 3A
					" " = 20
					"_" = 5F
					"-" = 2D
					"." = 2E
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
				
				if(isset($qsl['COL_SUBMODE'])) {
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
				if ($qsl['COL_PROP_MODE']){
                    $adif .= "%3C";
                    $adif .= "PROP%5FMODE";
                    $adif .= "%3A";
                    $adif .= strlen($qsl['COL_PROP_MODE']);
                    $adif .= "%3E";
                    $adif .= $qsl['COL_PROP_MODE'];
                    $adif .= "%20";
				}

				// adding sat name if it isn't blank
				if ($qsl['COL_SAT_NAME'] != ''){
                    $adif .= "%3C";
                    $adif .= "SAT%5FNAME";
                    $adif .= "%3A";
                    $adif .= strlen($qsl['COL_SAT_NAME']);
                    $adif .= "%3E";
                    $adif .= str_replace('-', '%2D', $qsl['COL_SAT_NAME']);
                    $adif .= "%20";
				}

				// adding sat mode if it isn't blank
				if ($qsl['COL_SAT_MODE'] != ''){
                    $adif .= "%3C";
                    $adif .= "SAT%5FMODE";
                    $adif .= "%3A";
                    $adif .= strlen($qsl['COL_SAT_MODE']);
                    $adif .= "%3E";
                    $adif .= $qsl['COL_SAT_MODE'];
                    $adif .= "%20";
				}

				// adding qslmsg if it isn't blank
				if ($qsl['COL_QSLMSG'] != ''){
                    $adif .= "%3C";
                    $adif .= "QSLMSG";
                    $adif .= "%3A";
                    $adif .= strlen($qsl['COL_QSLMSG']);
                    $adif .= "%3E";
                    $adif .= $qsl['COL_QSLMSG'];
                    $adif .= "%20";
				}

				if ($qsl['eqslqthnickname'] != ''){
                    $adif .= "%3C";
                    $adif .= "APP%5FEQSL%5FQTH%5FNICKNAME";
                    $adif .= "%3A";
                    $adif .= strlen($qsl['eqslqthnickname']);
                    $adif .= "%3E";
                    $adif .= $qsl['eqslqthnickname'];
                    $adif .= "%20";
				}

				// adding sat mode if it isn't blank
				if ($qsl['station_gridsquare'] != ''){
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
				
				/* Time for some error handling
				   Things we might get back
				   Result: 0 out of 0 records added -> eQSL didn't understand the format
				   Result: 1 out of 1 records added -> Fantastic
				   Error: No match on eQSL_User/eQSL_Pswd -> eQSL credentials probably wrong
				   Warning: Y=2013 M=08 D=11 F6ARS 15M JT65 Bad record: Duplicate
				   Result: 0 out of 1 records added -> Dupe, OM!
				*/
				
				if ($chi['http_code'] == "200")
				{
					if (stristr($result, "Result: 1 out of 1 records added"))
					{
						$status = "Sent";
						$this->logbook_model->eqsl_mark_sent($qsl['COL_PRIMARY_KEY']);
					}
					else
					{
						if (stristr($result, "Error: No match on eQSL_User/eQSL_Pswd"))
						{
							$this->session->set_flashdata('warning', 'Your eQSL username and/or password is incorrect.'); redirect('eqsl/export');
						}
						else
						{
							if (stristr($result, "Result: 0 out of 0 records added"))
							{
								$this->session->set_flashdata('warning', 'Something went wrong with eQSL.cc!'); redirect('eqsl/export');
							}
							else
							{
								if (stristr($result, "Bad record: Duplicate"))
								{
									$status = "Duplicate";
									
									# Mark the QSL as sent if this is a dupe.
									$this->logbook_model->eqsl_mark_sent($qsl['COL_PRIMARY_KEY']);
								}
							}
						}
					}
				}
				else
				{
					if ($chi['http_code'] == "500")
					{
						$this->session->set_flashdata('warning', 'eQSL.cc is experiencing issues. Please try exporting QSOs later.'); redirect('eqsl/export');
					}
					else
					{
						if ($chi['http_code'] == "400")
						{
							$this->session->set_flashdata('warning', 'There was an error in one of the QSOs. You might want to manually upload them.'); redirect('eqsl/export');
							$status = "Error";
						}
						else
						{
							if ($chi['http_code'] == "404")
							{
								$this->session->set_flashdata('warning', 'It seems that the eQSL site has changed. Please open up an issue on GitHub.'); redirect('eqsl/export');
							}
						}
					}
				}
				$table .= "<tr>";
						$table .= "<td>".$qsl['COL_TIME_ON']."</td>";
						$table .= "<td>".str_replace("0","&Oslash;",$qsl['COL_CALL'])."</td>";
						$table .= "<td>".$qsl['COL_MODE']."</td>";
						if(isset($qsl['COL_SUBMODE'])) {
							$table .= "<td>".$qsl['COL_SUBMODE']."</td>";
						} else {
							$table .= "<td></td>";
						}
						$table .= "<td>".$qsl['COL_BAND']."</td>";
						$table .= "<td>".$status."</td>";
				$table .= "<tr>";
			}
			$table .= "</table>";
			
			// Dump out a table with the results
			$data['eqsl_results_table'] = $table;
			log_message('debug', $result);
		}
		else
		{
			$qslsnotsent = $this->logbook_model->eqsl_not_yet_sent();
		
			if ($qslsnotsent->num_rows() > 0)
			{
				$table = "<table width=\"100%\">";
					$table .= "<tr class=\"titles\">";
						$table .= "<td>Date</td>";
						$table .= "<td>Call</td>";
						$table .= "<td>Mode</td>";
						$table .= "<td>Submode</td>";
						$table .= "<td>Band</td>";
						$table .= "<td>eQSL QTH Nickname</td>";
					$table .= "<tr>";
				
				foreach ($qslsnotsent->result_array() as $qsl)
				{
					$table .= "<tr>";
						$table .= "<td>".$qsl['COL_TIME_ON']."</td>";
						$table .= "<td><a href=\"javascript:displayQso(" . $qsl['COL_PRIMARY_KEY'] . ")\">" . str_replace("0","&Oslash;",strtoupper($qsl['COL_CALL'])) . "</a></td>";
						$table .= "<td>".$qsl['COL_MODE']."</td>";
						
						if(isset($qsl['COL_SUBMODE'])) {
							$table .= "<td>".$qsl['COL_SUBMODE']."</td>";
						} else {
							$table .= "<td></td>";
						}
						$table .= "<td>".$qsl['COL_BAND']."</td>";
						$table .= "<td>".$qsl['eqslqthnickname']."</td>";
					$table .= "<tr>";
				}
				$table .= "</table>";
		
				$data['eqsl_table'] = $table;
			}
		}
		
		$this->load->view('interface_assets/header', $data);
		$this->load->view('eqsl/export');
		$this->load->view('interface_assets/footer');
	}

	function image($id) {
		$this->load->library('electronicqsl');
		$this->load->model('Eqsl_images');

		if($this->Eqsl_images->get_image($id) == "No Image") {
			$this->load->model('logbook_model');
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
			$username = $q->user_eqsl_name;
			$password = $q->user_eqsl_password;

			$image_url = $this->electronicqsl->card_image($username, urlencode($password), $callsign, $band, $mode, $year, $month, $day, $hour, $minute);
			$file = file_get_contents($image_url, true);

			$dom = new domDocument; 
			$dom->loadHTML($file); 
			$dom->preserveWhiteSpace = false;
			$images = $dom->getElementsByTagName('img');

			if(!isset($images) || count($images) == 0) {
				echo "Rate Limited";
				exit;
			}

			foreach ($images as $image) 
			{
				header('Content-Type: image/jpg');
				$content = file_get_contents("https://www.eqsl.cc".$image->getAttribute('src'));
				if ($content === false) {
					echo "No response";
					exit;
				}
				echo $content;
				$filename = uniqid().'.jpg';
				if (file_put_contents('images/eqsl_card_images/' . '/'.$filename, $content) !== false) {
					$this->Eqsl_images->save_image($id, $filename);
				}
			}
		} else {
			header('Content-Type: image/jpg');
			$image_url = base_url('images/eqsl_card_images/'.$this->Eqsl_images->get_image($id));
			header('Location: ' . $image_url);
		}

	}

	public function tools() {

		// Check logged in
		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }

		$data['page_title'] = "eQSL Tools";

		// Load frontend
		$this->load->view('interface_assets/header', $data);
		$this->load->view('eqsl/tools');
		$this->load->view('interface_assets/footer');
	}

	public function mark_all_sent() {
		
		// Check logged in
		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
		
		// mark all eqsls as sent
		$this->load->model('eqslmethods_model');
		$this->eqslmethods_model->mark_all_as_sent();

		$this->session->set_flashdata('success', 'All eQSLs Marked as Uploaded');

		redirect('eqsl/tools');
	}
	
} // end class
