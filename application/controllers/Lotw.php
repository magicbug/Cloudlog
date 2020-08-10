<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lotw extends CI_Controller {

	/* Controls who can access the controller and its functions */
	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
	}

	private function loadFromFile($filepath)
	{
		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }

		// Figure out how we should be marking QSLs confirmed via LoTW
		$query = $query = $this->db->query('SELECT lotw_rcvd_mark FROM config');
		$q = $query->row();
		$config['lotw_rcvd_mark'] = $q->lotw_rcvd_mark;

		ini_set('memory_limit', '-1');
		set_time_limit(0);

		$this->load->library('adif_parser');

		$this->adif_parser->load_from_file($filepath);

		$this->adif_parser->initialize();

		$tableheaders = "<table width=\"100%\">";
			$tableheaders .= "<tr class=\"titles\">";
				$tableheaders .= "<td>QSO Date</td>";
				$tableheaders .= "<td>Call</td>";
				$tableheaders .= "<td>Mode</td>";
				$tableheaders .= "<td>LoTW QSL Received</td>";
				$tableheaders .= "<td>Date LoTW Confirmed</td>";
				$tableheaders .= "<td>State</td>";
				$tableheaders .= "<td>Log Status</td>";
				$tableheaders .= "<td>LoTW Status</td>";
			$tableheaders .= "</tr>";

			$table = "";
			while($record = $this->adif_parser->get_record())
			{

				$time_on = date('Y-m-d', strtotime($record['qso_date'])) ." ".date('H:i', strtotime($record['time_on']));

				$qsl_date = date('Y-m-d', strtotime($record['qslrdate'])) ." ".date('H:i', strtotime($record['qslrdate']));

				if (isset($record['time_off'])) {
					$time_off = date('Y-m-d', strtotime($record['qso_date'])) ." ".date('H:i', strtotime($record['time_off']));
				} else {
				   $time_off = date('Y-m-d', strtotime($record['qso_date'])) ." ".date('H:i', strtotime($record['time_on']));
				}

				// If we have a positive match from LoTW, record it in the DB according to the user's preferences
				if ($record['qsl_rcvd'] == "Y")
				{
					$record['qsl_rcvd'] = $config['lotw_rcvd_mark'];
				}

				$status = $this->logbook_model->import_check($time_on, $record['call'], $record['band']);
				$skipNewQso = $this->input->post('importMissing'); // If import missing was checked

				if($status == "No Match" && $skipNewQso != NULL) {

                    $station_id = $this->logbook_model->find_correct_station_id($record['station_callsign'], $record['my_gridsquare']);

                    if ($station_id != NULL) {
                        $result = $this->logbook_model->import($record, $station_id, NULL, NULL, NULL);  // Create the Entry
                        if ($result == "") {
                            $lotw_status = 'QSO imported';
                        } else {
                            $lotw_status = $result;
                        }
                    }

				} else {
					if (isset($record['state'])) {
						$state = $record['state'];
					} else {
						$state = "";
					}

					$lotw_status = $this->logbook_model->lotw_update($time_on, $record['call'], $record['band'], $qsl_date, $record['qsl_rcvd'], $state);
				}


				$table .= "<tr>";
					$table .= "<td>".$time_on."</td>";
					$table .= "<td>".$record['call']."</td>";
					$table .= "<td>".$record['mode']."</td>";
					$table .= "<td>".$record['qsl_rcvd']."</td>";
					$table .= "<td>".$qsl_date."</td>";
					$table .= "<td>".$state."</td>";
					$table .= "<td>QSO Record: ".$status."</td>";
					$table .= "<td>LoTW Record: ".$lotw_status."</td>";
				$table .= "</tr>";
			}

			if ($table != "")
			{
				$table .= "</table>";
				$data['lotw_table_headers'] = $tableheaders;
				$data['lotw_table'] = $table;
		}

		unlink($filepath);

		$data['page_title'] = "LoTW ADIF Information";
		$this->load->view('interface_assets/header', $data);
		$this->load->view('lotw/analysis');
		$this->load->view('interface_assets/footer');
	}

	public function import() {
		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }

		$data['page_title'] = "LoTW ADIF Import";

		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'adi|ADI';

		$this->load->library('upload', $config);

		$this->load->model('logbook_model');

		if ($this->input->post('lotwimport') == 'fetch')
		{
			$file = $config['upload_path'] . 'lotwreport_download.adi';

			// Get credentials for LoTW
			$query = $this->user_model->get_by_id($this->session->userdata('user_id'));
    	    $q = $query->row();
    	    $data['user_lotw_name'] = $q->user_lotw_name;
			$data['user_lotw_password'] = $q->user_lotw_password;

			// Get URL for downloading LoTW
			$query = $query = $this->db->query('SELECT lotw_download_url FROM config');
			$q = $query->row();
			$lotw_url = $q->lotw_download_url;

			// Validate that LoTW credentials are not empty
			// TODO: We don't actually see the error message
			if ($data['user_lotw_name'] == '' || $data['user_lotw_password'] == '')
			{
				$this->session->set_flashdata('warning', 'You have not defined your ARRL LoTW credentials!'); redirect('lotw/import');
			}

            $customDate = $this->input->post('from');

			if ($customDate != NULL) {
                $customDate = DateTime::createFromFormat('d/m/Y', $customDate);
                $customDate = $customDate->format('Y-m-d');
                $lotw_last_qsl_date = date($customDate);
            }
            else {
                // Query the logbook to determine when the last LoTW confirmation was
                $lotw_last_qsl_date = date('Y-m-d', strtotime($this->logbook_model->lotw_last_qsl_date()));
            }

			// Build URL for LoTW report file
			$lotw_url .= "?";
			$lotw_url .= "login=" . $data['user_lotw_name'];
			$lotw_url .= "&password=" . $data['user_lotw_password'];
			$lotw_url .= "&qso_query=1&qso_qsl='yes'&qso_qsldetail='yes'&qso_mydetail='yes'";

			//TODO: Option to specifiy whether we download location data from LoTW or not
			//$lotw_url .= "&qso_qsldetail=\"yes\";

            $lotw_url .= "&qso_qslsince=";
            $lotw_url .= "$lotw_last_qsl_date";

			// Only pull back entries that belong to this callsign
			$lotw_call = $this->session->userdata('user_callsign');
			$lotw_url .= "&qso_owncall=$lotw_call";

			file_put_contents($file, file_get_contents($lotw_url));

			ini_set('memory_limit', '-1');
			$this->loadFromFile($file);
		}
		else
		{
			if ( ! $this->upload->do_upload())
			{

				$data['error'] = $this->upload->display_errors();

				$this->load->view('interface_assets/header', $data);
				$this->load->view('lotw/import');
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
		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }

	$data['page_title'] = "LoTW .TQ8 Upload";

		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'tq8|TQ8';

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload())
		{
			$data['error'] = $this->upload->display_errors();

			$this->load->view('interface_assets/header', $data);
			$this->load->view('lotw/export');
			$this->load->view('interface_assets/footer');
		}
		else
		{
			$data = array('upload_data' => $this->upload->data());

			// Figure out how we should be marking QSLs confirmed via LoTW
			$query = $query = $this->db->query('SELECT lotw_login_url FROM config');
			$q = $query->row();
			$config['lotw_login_url'] = $q->lotw_login_url;

			// Set some fields that we're going to need for ARRL login
			$query = $this->user_model->get_by_id($this->session->userdata('user_id'));
    		$q = $query->row();
    		$fields['login'] = $q->user_lotw_name;
			$fields['password'] = $q->user_lotw_password;
			$fields['acct_sel'] = "";

			if ($fields['login'] == '' || $fields['password'] == '')
			{
				$this->session->set_flashdata('warning', 'You have not defined your ARRL LoTW credentials!'); redirect('lotw/status');
			}

			// Curl stuff goes here

			// First we need to get a cookie

			// options
			$cookie_file_path = "./uploads/cookies.txt";
			$agent            = "Mozilla/4.0 (compatible;)";

			// begin script
			$ch = curl_init();

			// extra headers
			$headers[] = "Accept: */*";
			$headers[] = "Connection: Keep-Alive";

			// basic curl options for all requests
			curl_setopt($ch, CURLOPT_HTTPHEADER,  $headers);
			curl_setopt($ch, CURLOPT_HEADER,  0);

			// TODO: These SSL things should probably be set to true :)
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_USERAGENT, $agent);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
			curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);

			// Set login URL
			curl_setopt($ch, CURLOPT_URL, $config['lotw_login_url']);

			// set postfields using what we extracted from the form
			$POSTFIELDS = http_build_query($fields);

			// set post options
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $POSTFIELDS);

			// perform login
			$result = curl_exec($ch);
			if (stristr($result, "Username/password incorrect"))
			{
			   $this->session->set_flashdata('warning', 'Your ARRL username and/or password is incorrect.'); redirect('lotw/status');
			}


			// Now we need to use that cookie and upload the file
			// change URL to upload destination URL
			curl_setopt($ch, CURLOPT_URL, $config['lotw_login_url']);

			// Grab the file
			$postfile = array(
        		"upfile"=>"@./uploads/".$data['upload_data']['file_name'],
    		);

    		//Upload it
    		curl_setopt($ch, CURLOPT_POSTFIELDS, $postfile);
    		$response = curl_exec($ch);
			if (stristr($response, "accepted"))
			{
			   $this->session->set_flashdata('lotw_status', 'accepted');
			   $data['page_title'] = "LoTW .TQ8 Sent";
			}
			elseif (stristr($response, "rejected"))
			{
					$this->session->set_flashdata('lotw_status', 'rejected');
					$data['page_title'] = "LoTW .TQ8 Sent";
			}
			else
			{
				// If we're here, we didn't find what we're looking for in the ARRL response
				// and LoTW is probably down or broken.
				$this->session->set_flashdata('warning', 'Did not receive proper response from LoTW. Try again later.');
				$data['page_title'] = "LoTW .TQ8 Not Sent";
			}

			// Now we need to clean up
			unlink($cookie_file_path);
			unlink('./uploads/'.$data['upload_data']['file_name']);

			$this->load->view('interface_assets/header', $data);
			$this->load->view('lotw/status');
			$this->load->view('interface_assets/footer');
		}
	}

	/*
		Load the ARRL LOTW User Activity CSV into LOTW User Table for cross checking when logging
	*/
	function load_users() {
		set_time_limit(0);
		$this->load->model('lotw_user');

		$this->lotw_user->empty_table();

		$row = 1;
		if (($handle = fopen("https://lotw.arrl.org/lotw-user-activity.csv", "r")) !== FALSE) {
		    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
		        $num = count($data);
		        $row++;

		        if(isset($data[2])) {
		        	$callsign = $data[0];
		        	$upload_date = $data[1]." ".$data[2];
		        	$this->lotw_user->add_lotwuser($callsign, $upload_date);
		    	}

		    }
		    fclose($handle);
		}

	}

	/*
		Check if callsign is an active LOTW user and return whether its true or not
	*/
	function lotw_usercheck($callsign) {
		$this->load->model('lotw_user');
 

		$lotw_user_result = $this->lotw_user->check($callsign);


	}

} // end class
