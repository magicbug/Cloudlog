<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lotw extends CI_Controller {
 /*
	|--------------------------------------------------------------------------
	| Controller: Lotw
	|--------------------------------------------------------------------------
	|
	| This Controller handles all things LOTW, upload and download.
	|
	|
	|	Note:
	|	If you plan on using any of the code within this class please credit
	| 	Cloudlog or Peter, 2M0SQL, a lot of hard work went into building the
	|	signing of files.
	|
	|	Big Thanks to Rodrigo PY2RAF for all the help and information about OpenSSL
	|
	*/

	/* Controls who can access the controller and its functions */
	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));

		// Load language files
		$this->lang->load('lotw');
	}

	/*
	|--------------------------------------------------------------------------
	| Function: index
	|--------------------------------------------------------------------------
	|
	| Default function for the controller which loads when doing /lotw
	| this shows all the uploaded lotw p12 certificates the user has uploaded
	|
	*/
	public function index() {
		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }

		// Fire OpenSSL missing error if not found
		if (!extension_loaded('openssl')) {
			echo "You must install php OpenSSL for LoTW functions to work";
		}

		// Load required models for page generation
		$this->load->model('LotwCert');

		// Get Array of the logged in users LOTW certs.
		$data['lotw_cert_results'] = $this->LotwCert->lotw_certs($this->session->userdata('user_id'));

		// Set Page Title
		$data['page_title'] = "Logbook of the World";

		// Load Views
		$this->load->view('interface_assets/header', $data);
		$this->load->view('lotw_views/index');
		$this->load->view('interface_assets/footer');
	}

	/*
	|--------------------------------------------------------------------------
	| Function: cert_upload
	|--------------------------------------------------------------------------
	|
	| Nothing fancy just shows the cert_upload form for uploading p12 files
	|
	*/
	public function cert_upload() {
		$this->load->model('user_model');
		$this->load->model('dxcc');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }

		// Load DXCC Countrys List
		$data['dxcc_list'] = $this->dxcc->list();

		// Set Page Title
		$data['page_title'] = "Logbook of the World";

		// Load Views
		$this->load->view('interface_assets/header', $data);
		$this->load->view('lotw_views/upload_cert', array('error' => ' ' ));
		$this->load->view('interface_assets/footer');
	}

	/*
	|--------------------------------------------------------------------------
	| Function: do_cert_upload
	|--------------------------------------------------------------------------
	|
	| do_cert_upload is called from cert_upload form submit and handles uploading
	| and processing of p12 files and storing the data into mysql
	|
	*/
	public function do_cert_upload()
    {
		$this->load->model('user_model');
		$this->load->model('dxcc');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }

		// Fire OpenSSL missing error if not found
		if (!extension_loaded('openssl')) {
			echo "You must install php OpenSSL for LoTW functions to work";
		}

    	// create folder to store certs while processing
    	if (!file_exists('./uploads/lotw/certs')) {
		    mkdir('./uploads/lotw/certs', 0755, true);
		}

		$config['upload_path']          = './uploads/lotw/certs';
    	$config['allowed_types']        = 'p12';

		$this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('userfile'))
        {
        	// Upload of P12 Failed
            $error = array('error' => $this->upload->display_errors());

			// Set Page Title
			$data['page_title'] = "Logbook of the World";

			// Load Views
			$this->load->view('interface_assets/header', $data);
			$this->load->view('lotw_views/upload_cert', $error);
			$this->load->view('interface_assets/footer');
        }
        else
        {
        	// Load database queries
        	$this->load->model('LotwCert');

        	//Upload of P12 successful
        	$data = array('upload_data' => $this->upload->data());

        	$info = $this->decrypt_key($data['upload_data']['full_path']);

        	// Check DXCC & Store Country Name
			$this->load->model('Logbook_model');

			if($this->input->post('dxcc') != "") {
				$dxcc = $this->input->post('dxcc');
			} else{
				$dxcc_check = $this->Logbook_model->check_dxcc_table($info['issued_callsign'], $info['validFrom']);
				$dxcc = $dxcc_check[1];
			}

			// Check to see if certificate is already in the system
			$new_certficiate = $this->LotwCert->find_cert($info['issued_callsign'], $dxcc, $this->session->userdata('user_id'));

        	if($new_certficiate == 0) {
        		// New Certificate Store in Database

        		// Store Certificate Data into MySQL
        		$this->LotwCert->store_certficiate($this->session->userdata('user_id'), $info['issued_callsign'], $dxcc, $info['validFrom'], $info['validTo_Date'], $info['pem_key'], $info['general_cert']);

        		// Cert success flash message
        		$this->session->set_flashdata('Success', $info['issued_callsign'].' Certficiate Imported.');
        	} else {
        		// Certficiate is in the system time to update

				$this->LotwCert->update_certficiate($this->session->userdata('user_id'), $info['issued_callsign'], $dxcc, $info['validFrom'], $info['validTo_Date'], $info['pem_key'], $info['general_cert']);

        		// Cert success flash message
        		$this->session->set_flashdata('Success', $info['issued_callsign'].' Certficiate Updated.');

        	}

        	// p12 certificate processed time to delete the file
        	unlink($data['upload_data']['full_path']);

			// Get Array of the logged in users LOTW certs.
			$data['lotw_cert_results'] = $this->LotwCert->lotw_certs($this->session->userdata('user_id'));

	        // Set Page Title
			$data['page_title'] = "Logbook of the World";

			// Load Views
			$this->load->view('interface_assets/header', $data);
			$this->load->view('lotw_views/index');
			$this->load->view('interface_assets/footer');



        }
    }

    /*
	|--------------------------------------------------------------------------
	| Function: lotw_upload
	|--------------------------------------------------------------------------
	|
	| This function Uploads to LOTW
	|
	*/
	public function lotw_upload() {

		// Fire OpenSSL missing error if not found
		if (!extension_loaded('openssl')) {
			echo "You must install php OpenSSL for LoTW functions to work";
		}

		// Get Station Profile Data
		$this->load->model('Stations');

		$station_profiles = $this->Stations->all();

		// Array of QSO IDs being Uploaded

		$qso_id_array = array();

		// Build TQ8 Outputs
			if ($station_profiles->num_rows() >= 1) {

				foreach ($station_profiles->result() as $station_profile)
				{

					// Get Certificate Data
					$this->load->model('LotwCert');
					$data['station_profile'] = $station_profile;
					$data['lotw_cert_info'] = $this->LotwCert->lotw_cert_details($station_profile->station_callsign, $station_profile->station_country);

					// If Station Profile has no LOTW Cert continue on.
					if(!isset($data['lotw_cert_info']->cert_dxcc)) {
						continue;
					}

					$this->load->model('Dxcc');
					$data['station_profile_dxcc'] = $this->Dxcc->lookup_country($data['lotw_cert_info']->cert_dxcc);

					// Get QSOs

					$this->load->model('Logbook_model');

					$data['qsos'] = $this->Logbook_model->get_lotw_qsos_to_upload($data['station_profile']->station_id, $data['lotw_cert_info']->date_created, $data['lotw_cert_info']->date_expires);

					// Nothing to upload
					if(empty($data['qsos']->result())){
						echo $station_profile->station_callsign." (".$station_profile->station_profile_name.") No QSOs to Upload <br>";
					    continue;
					}

					foreach ($data['qsos']->result() as $temp_qso) {
						array_push($qso_id_array, $temp_qso->COL_PRIMARY_KEY);
					}

					// Build File to save
					$adif_to_save = $this->load->view('lotw_views/adif_views/adif_export', $data, TRUE);

					// create folder to store upload file
					if (!file_exists('./uploads/lotw')) {
					    mkdir('./uploads/lotw', 0775, true);
					}

					// Build Filename
					$filename_for_saving = './uploads/lotw/'.preg_replace('/[^a-z0-9]+/', '-', strtolower($data['lotw_cert_info']->callsign))."-".date("Y-m-d-H-i-s")."-cloudlog.tq8";

					$gzdata = gzencode($adif_to_save, 9);
					$fp = fopen($filename_for_saving, "w");
					fwrite($fp, $gzdata);
					fclose($fp);

					//The URL that accepts the file upload.
					$url = 'https://lotw.arrl.org/lotw/upload';

					//The name of the field for the uploaded file.
					$uploadFieldName = 'upfile';

					//The full path to the file that you want to upload
					$filePath = realpath($filename_for_saving);

					//Initiate cURL
					$ch = curl_init();

					//Set the URL
					curl_setopt($ch, CURLOPT_URL, $url);

					//Set the HTTP request to POST
					curl_setopt($ch, CURLOPT_POST, true);

					//Tell cURL to return the output as a string.
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

					//If the function curl_file_create exists
					if(function_exists('curl_file_create')){
					    //Use the recommended way, creating a CURLFile object.
					    $filePath = curl_file_create($filePath);
					} else{
					    //Otherwise, do it the old way.
					    //Get the canonicalized pathname of our file and prepend
					    //the @ character.
					    $filePath = '@' . realpath($filePath);
					    //Turn off SAFE UPLOAD so that it accepts files
					    //starting with an @
					    curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
					}

					//Setup our POST fields
					$postFields = array(
					    $uploadFieldName => $filePath
					);

					curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);

					//Execute the request
					$result = curl_exec($ch);

					//If an error occured, throw an exception
					//with the error message.
					if(curl_errno($ch)){
					    throw new Exception(curl_error($ch));
					}

					$pos = strpos($result, "<!-- .UPL.  accepted -->");

					if ($pos === false) {
						// Upload of TQ8 Failed for unknown reason
					    echo $station_profile->station_callsign." (".$station_profile->station_profile_name.") Upload Failed"."<br>";
					} else {
						// Upload of TQ8 was successfull

					    echo "Upload Successful - ".$filename_for_saving."<br>";

					    $this->LotwCert->last_upload($data['lotw_cert_info']->lotw_cert_id);

					    // Mark QSOs as Sent
					    foreach ($qso_id_array as $qso_number) {
					    	$this->Logbook_model->mark_lotw_sent($qso_number);
					    }
					}

					// Delete TQ8 File - This is done regardless of whether upload was succcessful
					unlink(realpath($filename_for_saving));
				}
			} else {
				echo "No Station Profiles found to upload to LOTW";
			}

			/*
			|	Download QSO Matches from LoTW
			*/
			echo "<br><br>";
			echo "LoTW Matches<br>";
			echo $this->lotw_download();

	}

	/*
	|--------------------------------------------------------------------------
	| Function: delete_cert
	|--------------------------------------------------------------------------
	|
	| Deletes LOTW certificate from the MySQL table
	|
	*/
    public function delete_cert($cert_id) {
    	$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }

    	$this->load->model('LotwCert');

    	$this->LotwCert->delete_certficiate($this->session->userdata('user_id'), $cert_id);

    	$this->session->set_flashdata('Success', 'Certficiate Deleted.');

    	redirect('/lotw/');
    }


	/*
	|--------------------------------------------------------------------------
	| Function: decrypt_key
	|--------------------------------------------------------------------------
	|
	| Accepts p12 file and optional password and encrypts the file returning
	| the required fields for LOTW and the PEM Key
	|
	*/
	public function decrypt_key($file, $password = "") {
		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }

		$results = array();
		$password = $password; // Only needed if 12 has a password set
		$filename = file_get_contents('file://'.$file);
		$worked = openssl_pkcs12_read($filename, $results, $password);

		$data['general_cert'] = $results['cert'];


		if($worked) {
			// Reading p12 successful
		    $new_password = "cloudlog"; // set default password
			$result = null;
			$worked = openssl_pkey_export($results['pkey'], $result, $new_password);

			if($worked) {
				// Store PEM Key in Array
			    $data['pem_key'] = $result;
			} else {
				// Error Log Error Message
			    log_message('error', openssl_error_string());

			    // Set warning message redirect to LOTW main page
			    $this->session->set_flashdata('Warning', openssl_error_string());
				redirect('/lotw/');
			}
		} else {
			// Reading p12 failed log error message
			log_message('error', openssl_error_string());

			// Set warning message redirect to LOTW main page
			$this->session->set_flashdata('Warning', openssl_error_string());
			redirect('/lotw/');
		}

		// Read Cert Data
		$certdata= openssl_x509_parse($results['cert'],0);

		// Store Variables
		$data['issued_callsign'] = $certdata['subject']['undefined'];
		$data['issued_name'] = $certdata['subject']['commonName'];
		$data['validFrom'] = $certdata['extensions']['1.3.6.1.4.1.12348.1.2'];
		$data['validTo_Date'] = $certdata['extensions']['1.3.6.1.4.1.12348.1.3'];

		return $data;
	}

	/*
	|--------------------------------------------------------------------------
	| Function: loadFromFile
	|--------------------------------------------------------------------------
	|
	|	$filepath is the ADIF file, $display_view is used to hide the output if its internal script
	|
	|	Internal function that takes the LoTW ADIF and imports into the log
	|
	*/
	private function loadFromFile($filepath, $display_view = "TRUE")
	{

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
				$tableheaders .= "<td>Station Callsign</td>";
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
                        $result = $this->logbook_model->import($record, $station_id, NULL, TRUE, NULL, NULL, true, false);  // Create the Entry
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
					$table .= "<td>".$record['station_callsign']."</td>";
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

		if(isset($data['lotw_table_headers'])) {
			if($display_view == TRUE) {
				$data['page_title'] = "LoTW ADIF Information";
				$this->load->view('interface_assets/header', $data);
				$this->load->view('lotw/analysis');
				$this->load->view('interface_assets/footer');
			} else {
				return $tableheaders.$table;
			}
		} else {
			echo "LoTW Downloading failed either due to it being down or incorrect logins.";
		}
	}

	/*
	|--------------------------------------------------------------------------
	| Function: lotw_download
	|--------------------------------------------------------------------------
	|
	|	Collects users with LoTW usernames and passwords and runs through them
	|	downloading matching QSOs.
	|
	*/
	function lotw_download() {
		$this->load->model('user_model');
		$this->load->model('logbook_model');

		$query = $this->user_model->get_all_lotw_users();

		if ($query->num_rows() >= 1) {

			foreach ($query->result() as $user)
			{

				$config['upload_path'] = './uploads/';
				$file = $config['upload_path'] . 'lotwreport_download.adi';

				// Get credentials for LoTW
		    	$data['user_lotw_name'] = urlencode($user->user_lotw_name);
				$data['user_lotw_password'] = urlencode($user->user_lotw_password);

				// Get URL for downloading LoTW
				$query = $query = $this->db->query('SELECT lotw_download_url FROM config');
				$q = $query->row();
				$lotw_url = $q->lotw_download_url;

				// Validate that LoTW credentials are not empty
				// TODO: We don't actually see the error message
				if ($data['user_lotw_name'] == '' || $data['user_lotw_password'] == '')
				{
					echo "You have not defined your ARRL LoTW credentials!";
				}

		        $lotw_last_qsl_date = date('Y-m-d', strtotime($this->logbook_model->lotw_last_qsl_date()));

				// Build URL for LoTW report file
				$lotw_url .= "?";
				$lotw_url .= "login=" . $data['user_lotw_name'];
				$lotw_url .= "&password=" . $data['user_lotw_password'];
				$lotw_url .= "&qso_query=1&qso_qsl='yes'&qso_qsldetail='yes'&qso_mydetail='yes'";

				//TODO: Option to specifiy whether we download location data from LoTW or not
				//$lotw_url .= "&qso_qsldetail=\"yes\";

		        $lotw_url .= "&qso_qslsince=";
		        $lotw_url .= "$lotw_last_qsl_date";

				file_put_contents($file, file_get_contents($lotw_url));

				ini_set('memory_limit', '-1');
				$results = $this->loadFromFile($file, false);

				return $results;
			}
		} else {
			return "No LOTW User details found to carry out matches.";
		}
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
    	    $data['user_lotw_name'] = urlencode($q->user_lotw_name);
			$data['user_lotw_password'] = urlencode($q->user_lotw_password);

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
		Load the ARRL LOTW User Activity CSV and saves into uploads/lotw_users.csv
	*/
	public function load_users() {
		$contents = file_get_contents('https://lotw.arrl.org/lotw-user-activity.csv', true);

        if($contents === FALSE) {
            echo "something went wrong";
        } else {
            $file = './updates/lotw_users.csv';

            if(!is_file($file)){        // Some simple example content.
                file_put_contents($file, $contents);     // Save our content to the file.
            }

            echo "LoTW User Data Saved.";
        }
	}

	/*
		Check if callsign is an active LOTW user and return whether its true or not
	*/
	function lotw_usercheck($callsign) {
		$f = fopen('./updates/lotw_users.csv', "r");
		$result = false;
		while ($row = fgetcsv($f)) {
		    if ($row[0] == strtoupper($callsign)) {
			$result = $row[0];
			echo "Found";
			break;
		    } else {
			echo "Not Found";
			break;
		    }
		}
		fclose($f);
	}

	function signlog($sign_key, $string) {

		$qso_string = $string;

		$key = $sign_key;

		$pkeyid = openssl_pkey_get_private($key, 'cloudlog');
		//openssl_sign($plaintext, $signature, $pkeyid, OPENSSL_ALGO_SHA1 );
		//openssl_free_key($pkeyid);


		if(openssl_sign($qso_string, $signature, $pkeyid, OPENSSL_ALGO_SHA1)) {
		  openssl_free_key($pkeyid);
		  $signature_b64 = base64_encode($signature);
		  return $signature_b64;
		}


	}

	/*
	|	Function: lotw_satellite_map
	|	Requires: OSCAR Satellite name $satname
	|
	|	Outputs if LOTW uses a different satellite name
	|
	*/
	function lotw_satellite_map($satname) {
		$arr = array(
			"ARISS"		=>	"ISS",
			"UKUBE1"	=>	"UKUBE-1",
			"KEDR"		=>	"ARISSAT-1",
			"TO-108"	=>	"CAS-6",
			"TAURUS"	=>	"TAURUS-1",
			"AISAT1"	=>	"AISAT-1",
			'UVSQ'		=>	"UVSQ-SAT"
		);

		return array_search(strtoupper($satname),$arr,true);
	}

	/*
	|	Function: mode_map
	|	Requires: mode as $mode, submode as $submode
	|
	|	This converts ADIF modes to the mode that LoTW expects if its non standard
	*/
	function mode_map($mode, $submode) {
		switch ($mode):
			case "PKT":
				return "PACKET";
				break;
			case "MFSK":
				if ($submode == "FT4") {
					return "FT4";
					break;
				} else {
					return "MFSK";
					break;
				}
			default:
				return $mode;
		endswitch;
	}

} // end class
