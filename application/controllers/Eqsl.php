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

	private function loadFromFile($filepath)
	{
		ini_set('memory_limit', '-1');
		set_time_limit(0);
		
		// Figure out how we should be marking QSLs confirmed via eQSL
		$query = $query = $this->db->query('SELECT eqsl_rcvd_mark FROM config');
		$q = $query->row();
		$config['eqsl_rcvd_mark'] = $q->eqsl_rcvd_mark;
	
		ini_set('memory_limit', '-1');
		set_time_limit(0);

		$this->load->library('adif_parser');

		$this->adif_parser->load_from_file($filepath);

		$this->adif_parser->initialize();
		
		$tableheaders = "<table>";
			$tableheaders .= "<tr class=\"titles\">";
				$tableheaders .= "<td>Date</td>";
				$tableheaders .= "<td>Call</td>";
				$tableheaders .= "<td>Mode</td>";
				$tableheaders .= "<td>Log Status</td>";
				$tableheaders .= "<td>eQSL Status</td>";
			$tableheaders .= "<tr>";
		$table = "";		
		while ($record = $this->adif_parser->get_record())
		{
			$time_on = date('Y-m-d', strtotime($record['qso_date'])) ." ".date('H:i', strtotime($record['time_on']));
	
			// The report from eQSL should only contain entries that have been confirmed via eQSL
			// If there's a match for the QSO from the report in our log, it's confirmed via eQSL.
	
			// If we have a positive match from LoTW, record it in the DB according to the user's preferences
			if ($record['qsl_sent'] == "Y")
			{
				$record['qsl_sent'] = $config['eqsl_rcvd_mark'];
			}
	
			$status = $this->logbook_model->import_check($time_on, $record['call'], $record['band']);
			if ($status == "Found")
			{
				$dupe = $this->logbook_model->eqsl_dupe_check($time_on, $record['call'], $record['band'], $config['eqsl_rcvd_mark']);
				if ($dupe == false)
				{
					$eqsl_status = $this->logbook_model->eqsl_update($time_on, $record['call'], $record['band'], $config['eqsl_rcvd_mark']);
				}
				else
				{
					$eqsl_status = "Already received an eQSL for this QSO.";
				}
			}
			else
			{
				$eqsl_status = "QSO not found";
			}
			$table .= "<tr>";
				$table .= "<td>".$time_on."</td>";
				$table .= "<td>".str_replace("0","&Oslash;",$record['call'])."</td>";
				$table .= "<td>".$record['mode']."</td>";
				$table .= "<td>QSO Record: ".$status."</td>";
				$table .= "<td>eQSL Record: ".$eqsl_status."</td>";
			$table .= "<tr>";
		}
		if ($table != "")
		{	
			$table .= "</table>";
			$data['eqsl_results_table_headers'] = $tableheaders;
			$data['eqsl_results_table'] = $table;
		}

		unlink($filepath);

		$data['page_title'] = "eQSL Import Information";
		$this->load->view('interface_assets/header', $data);
		$this->load->view('eqsl/analysis');
		$this->load->view('interface_assets/footer');
	}

	public function import() {

		// Check if eQSL Nicknames have been defined
			$this->load->model('stations');
			if($this->stations->are_eqsl_nicks_defined() == 0) {
				show_error('eQSL Nicknames in Station Profiles arent defined');
				exit;
			}

		ini_set('memory_limit', '-1');
		set_time_limit(0);
	
		$data['page_title'] = "eQSL Import";

		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'adi|ADI';
		
		$this->load->library('upload', $config);
		
		$this->load->model('logbook_model');
		
		if ($this->input->post('eqslimport') == 'fetch')
		{			
			//echo "import from clublog ADIF<br>";
			$file = $config['upload_path'] . 'eqslreport_download.adi';
			//echo "<br>Download File: ".$file."<br>";
			// Get credentials for eQSL
			$query = $this->user_model->get_by_id($this->session->userdata('user_id'));
			$q = $query->row();
			$data['user_eqsl_name'] = $q->user_eqsl_name;
			$data['user_eqsl_password'] = $q->user_eqsl_password;

			//echo "<br>Username".$data['user_eqsl_name']."<br>";
			
			// Get URL for downloading the eqsl.cc inbox
			$query = $query = $this->db->query('SELECT eqsl_download_url FROM config');
			$q = $query->row();
			$eqsl_url = $q->eqsl_download_url;
			
			// Validate that LoTW credentials are not empty
			if ($data['user_eqsl_name'] == '' || $data['user_eqsl_password'] == '')
			{
				$this->session->set_flashdata('warning', 'You have not defined your eQSL.cc credentials!'); redirect('eqsl/import');
			}

			$this->load->model('stations');
			$active_station_id = $this->stations->find_active();
        	$station_profile = $this->stations->profile($active_station_id);
			$active_station_info = $station_profile->row();
			// Query the logbook to determine when the last LoTW confirmation was
			$eqsl_last_qsl_date = $this->logbook_model->eqsl_last_qsl_rcvd_date();

			// Build parameters for eQSL inbox file
			$eqsl_params = http_build_query(array(
				'UserName' => $data['user_eqsl_name'],
				'Password' => $data['user_eqsl_password'],
				'RcvdSince' => $eqsl_last_qsl_date,
				'QTHNickname' => $active_station_info->eqslqthnickname,
				'ConfirmedOnly' => 1
			));

			//echo "<br><br>".$eqsl_url."<br>".$eqsl_params."<br><br>";
			
 			// At this point, what we get isn't the ADI file we need, but rather
			// an HTML page, which contains a link to the generated ADI file that we want.
			// Adapted from Original PHP code by Chirp Internet: www.chirp.com.au (regex)
			
			// Let's use cURL instead of file_get_contents
			// begin script
			$ch = curl_init(); 

			// basic curl options for all requests
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
			curl_setopt($ch, CURLOPT_HEADER, 1);
			
			// use the URL and params we built
			curl_setopt($ch, CURLOPT_URL, $eqsl_url);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $eqsl_params);
			
			$input = curl_exec($ch);  
			$chi = curl_getinfo($ch);

 			// "You have no log entries" -> Nothing else to do here
 			// "Your ADIF log file has been built" -> We've got an ADIF file we need to grab.
 			
 			if ($chi['http_code'] == "200")
			 {
				if (stristr($input, "You have no log entries"))
				{
					$this->session->set_flashdata('success', 'There are no QSLs waiting for download at eQSL.cc.'); redirect('eqsl/import');
				}
				else if (stristr($input, "Error: No such Username/Password found")) 
				{
					$this->session->set_flashdata('warning', 'No such Username/Password found This could mean the wrong callsign or the wrong password, or the user does not exist.'); 
					redirect('eqsl/import');
				}
				else
				{
					if (stristr($input, "Your ADIF log file has been built"))
					{
						// Get all the links on the page and grab the URL for the ADI file.
						$regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";
						if(preg_match_all("/$regexp/siU", $input, $matches)) {
							foreach( $matches[2] as $match )
							{
								// Look for the link that has the .adi file, and download it to $file
								if (substr($match, -4, 4) == ".adi")
								{
									
									file_put_contents($file, file_get_contents("http://eqsl.cc/qslcard/" . $match));
									ini_set('memory_limit', '-1');
									$this->loadFromFile($file);
									break;
								}
							}
						}
					}
				}
			}
			else
			{
				if ($chi['http_code'] == "500")
				{
					$this->session->set_flashdata('warning', 'eQSL.cc is experiencing issues. Please try importing QSOs later.'); redirect('eqsl/import');
				}
				else
				{
					if ($chi['http_code'] == "404")
					{
						$this->session->set_flashdata('warning', 'It seems that the eQSL site has changed. Please open up an issue on GitHub.'); redirect('eqsl/import');
					}
				}
			}
 			
			
			// Close cURL handle
			curl_close($ch);
			
			
			
			
		}
		else
		{
			if ( ! $this->upload->do_upload())
			{
			
				$data['error'] = $this->upload->display_errors();

				$this->load->view('interface_assets/header', $data);
				$this->load->view('eqsl/import');
				$this->load->view('interface_assets/footer');
			}
			else
			{
				$data = array('upload_data' => $this->upload->data());
				
				$this->loadFromFile('./uploads/'.$data['upload_data']['file_name']);
			}
		}
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
						$table .= "<td>Band</td>";
						$table .= "<td>Result</td>";
					$table .= "<tr>";
			// Build out the ADIF info string according to specs http://eqsl.cc/qslcard/ADIFContentSpecs.cfm
			foreach ($qslsnotsent->result_array() as $qsl)
			{
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
						$table .= "<td>Band</td>";
						$table .= "<td>eQSL QTH Nickname</td>";
					$table .= "<tr>";
				
				foreach ($qslsnotsent->result_array() as $qsl)
				{
					$table .= "<tr>";
						$table .= "<td>".$qsl['COL_TIME_ON']."</td>";
						$table .= "<td><a href=\"javascript:displayQso(" . $qsl['COL_PRIMARY_KEY'] . ")\">" . str_replace("0","&Oslash;",strtoupper($qsl['COL_CALL'])) . "</a></td>";
						$table .= "<td>".$qsl['COL_MODE']."</td>";
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

			if(!isset($images)) {
				echo "Rate Limited";
				exit;
			}

			foreach ($images as $image) 
			{
				header('Content-Type: image/jpg');
				readfile ("https://www.eqsl.cc".$image->getAttribute('src')); 
				$content = file_get_contents("https://www.eqsl.cc".$image->getAttribute('src'));
				$filename = uniqid().'.jpg';
				file_put_contents('images/eqsl_card_images/' . '/'.$filename, $content);

				$this->Eqsl_images->save_image($id, $filename);
			}
		} else {
			header('Content-Type: image/jpg');
			$image_url = base_url('images/eqsl_card_images/'.$this->Eqsl_images->get_image($id));
			readfile($image_url); 
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
