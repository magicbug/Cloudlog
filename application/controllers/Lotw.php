<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lotw extends CI_Controller {
 /*
	|--------------------------------------------------------------------------
	| Controller: Lotw
	|--------------------------------------------------------------------------
	|
	| This Controller handles all things LoTW, upload and download.
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

		// Get Array of the logged in users LoTW certs.
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
	| Now supports HTMX requests for modal-based uploads
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

		// Check if this is an HTMX request
		$is_htmx = $this->input->get_request_header('HX-Request');

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
            $error = $this->upload->display_errors();

			// If HTMX request, return just the error message
			if($is_htmx) {
				echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
				return;
			}

			// Otherwise load the full form page
			$data = array('error' => $error);
			$data['dxcc_list'] = $this->dxcc->list();
			$data['page_title'] = "Logbook of the World";

			$this->load->view('interface_assets/header', $data);
			$this->load->view('lotw_views/upload_cert', $data);
			$this->load->view('interface_assets/footer');
        }
        else
        {
        	// Load database queries
        	$this->load->model('LotwCert');

        	//Upload of P12 successful
        	$data = array('upload_data' => $this->upload->data());

        	$info = $this->decrypt_key($data['upload_data']['full_path']);

			// Check to see if certificate is already in the system
			$new_certificate = $this->LotwCert->find_cert($info['issued_callsign'], $info['dxcc-id'], $this->session->userdata('user_id'));

        	if($new_certificate == 0) {
        		// New Certificate Store in Database

        		// Store Certificate Data into MySQL
        		$this->LotwCert->store_certificate($this->session->userdata('user_id'), $info['issued_callsign'], $info['dxcc-id'], $info['validFrom'], $info['validTo_Date'], $info['qso-first-date'], $info['qso-end-date'], $info['pem_key'], $info['general_cert']);

        		// Cert success flash message
        		$success_msg = $info['issued_callsign'].' Certificate Imported.';
        	} else {
        		// Certificate is in the system time to update

				$this->LotwCert->update_certificate($this->session->userdata('user_id'), $info['issued_callsign'], $info['dxcc-id'], $info['validFrom'], $info['validTo_Date'], $info['qso-first-date'], $info['qso-end-date'], $info['pem_key'], $info['general_cert']);

        		// Cert success flash message
        		$success_msg = $info['issued_callsign'].' Certificate Updated.';
        	}

        	// p12 certificate processed time to delete the file
        	unlink($data['upload_data']['full_path']);

			// If HTMX request, return just the success message
			if($is_htmx) {
				echo '<div class="alert alert-success" role="alert">' . $success_msg . '</div>';
				return;
			}

			// Otherwise load the full page
			$this->session->set_flashdata('Success', $success_msg);

			// Get Array of the logged in users LoTW certs.
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
	| Function: cert_table_refresh
	|--------------------------------------------------------------------------
	|
	| Returns the certificate table for HTMX refresh after upload
	|
	*/
	public function cert_table_refresh() {
		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }

		// Load model
		$this->load->model('LotwCert');

		// Get Array of the logged in users LoTW certs.
		$data['lotw_cert_results'] = $this->LotwCert->lotw_certs($this->session->userdata('user_id'));

		// Load just the cert table partial
		$this->load->view('lotw_views/cert_table', $data);
	}

    /*
	|--------------------------------------------------------------------------
	| Function: lotw_upload
	|--------------------------------------------------------------------------
	|
	| This function Uploads to LoTW
	|
	| Performance Optimization: Uses batch QSO updates instead of individual
	| UPDATE queries to reduce database load during hourly uploads.
	| Individual updates replaced with mark_lotw_sent_batch() for better performance.
	|
	*/
	public function lotw_upload() {
		$this->load->library('cron_runner');
		$this->load->model('user_model');

		$manual_sync = $this->cron_runner->get_execution_source() === 'legacy' && $this->user_model->authorize(2);
		if ($manual_sync) {
			if (!$this->cron_runner->acquire_lock('lotw_sync')) {
				echo "A LoTW sync is already running. Try again in a minute or two.";
				return;
			}
		} elseif (!$this->cron_runner->begin('lotw_sync')) {
			return;
		}

		// Fire OpenSSL missing error if not found
		if (!extension_loaded('openssl')) {
			echo "You must install php OpenSSL for LoTW functions to work";
		}

		// Get Station Profile Data
		$this->load->model('Stations');

		if ($this->user_model->authorize(2)) {
			$station_profiles = $this->Stations->all_of_user($this->session->userdata('user_id'));
			$sync_user_id=$this->session->userdata('user_id');
		} else {
			$station_profiles = $this->Stations->all();
			$sync_user_id=null;
		}

		// Build TQ8 Outputs
		if ($station_profiles->num_rows() >= 1) {

			foreach ($station_profiles->result() as $station_profile) {
				
				// Array of QSO IDs being Uploaded for this station profile
				$qso_id_array = array();

				// Get Certificate Data
				$this->load->model('LotwCert');
				$data['station_profile'] = $station_profile;
				$data['lotw_cert_info'] = $this->LotwCert->lotw_cert_details($station_profile->station_callsign, $station_profile->station_dxcc);

				// If Station Profile has no LoTW Cert continue on.
				if(!isset($data['lotw_cert_info']->cert_dxcc_id)) {
					continue;
				}

				// Check if LoTW certificate itself is valid
				// Validty of QSO dates will be checked later
				$current_date = date('Y-m-d H:i:s');
				if ($current_date <= $data['lotw_cert_info']->date_created) {
					echo $data['lotw_cert_info']->callsign.": LoTW certificate not valid yet!";
					continue;
				}
				if ($current_date >= $data['lotw_cert_info']->date_expires) {
					echo $data['lotw_cert_info']->callsign.": LoTW certificate expired!";
					continue;
				}

				// Get QSOs

				$this->load->model('Logbook_model');

				$data['qsos'] = $this->Logbook_model->get_lotw_qsos_to_upload($data['station_profile']->station_id, $data['lotw_cert_info']->qso_start_date, $data['lotw_cert_info']->qso_end_date);

				// Nothing to upload
				if(empty($data['qsos']->result())){
					if ($this->user_model->authorize(2)) {	// Only be verbose if we have a session
						echo $station_profile->station_callsign." (".$station_profile->station_profile_name.") No QSOs to Upload <br>";
					}
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

				//Use the recommended way, creating a CURLFile object.
				$uploadfile = curl_file_create($filePath);
				$uploadfile->setPostFilename(basename($filePath));

				//Setup our POST fields
				$postFields = array(
					$uploadFieldName => $uploadfile
				);

				curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);

				//Execute the request
				$result = curl_exec($ch);

				//If an error occured, throw an exception
				//with the error message.
				if(curl_errno($ch)){
					throw new Exception(curl_error($ch));

					// Upload of TQ8 Failed for unknown reason
					echo $station_profile->station_callsign." (".$station_profile->station_profile_name.") Upload Failed"."<br>";
				}

				$pos = strpos($result, "<!-- .UPL. accepted -->");

				if ($pos === false) {
					// Upload of TQ8 Failed for unknown reason
					echo $station_profile->station_callsign." (".$station_profile->station_profile_name.") Upload Failed"."<br>";
				} else {
					// Upload of TQ8 was successfull

					echo "Upload Successful - ".$filename_for_saving."<br>";

					// Use transaction for data consistency
					$this->db->trans_begin();

					try {
						$this->LotwCert->last_upload($data['lotw_cert_info']->lotw_cert_id);

						// Mark QSOs as Sent (batch update for performance)
						if (!empty($qso_id_array)) {
							$affected_rows = $this->Logbook_model->mark_lotw_sent_batch($qso_id_array);
							if ($this->user_model->authorize(2)) {
								echo "Marked ".$affected_rows." QSOs as sent to LoTW<br>";
							}
						}

						$this->db->trans_commit();
					} catch (Exception $e) {
						$this->db->trans_rollback();
						echo "Database update failed for ".$station_profile->station_callsign.": ".$e->getMessage()."<br>";
					}
				}

				// Delete TQ8 File - This is done regardless of whether upload was succcessful
				unlink(realpath($filename_for_saving));
			}
		} else {
			echo "No Station Profiles found to upload to LoTW";
		}

			/*
			|	Download QSO Matches from LoTW
			 */
		if ($this->user_model->authorize(2)) {
			echo "<br><br>";
			$sync_user_id=$this->session->userdata('user_id');
		} else {
			$sync_user_id=null;
		}
		echo $this->lotw_download($sync_user_id);
		if ($manual_sync) {
			$this->cron_runner->release();
		} else {
			$this->cron_runner->finish('lotw_sync', 'ok');
		}
	}

	/*
	|--------------------------------------------------------------------------
	| Function: delete_cert
	|--------------------------------------------------------------------------
	|
	| Deletes LoTW certificate from the MySQL table
	|
	*/
    public function delete_cert($cert_id) {
    	$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }

    	$this->load->model('LotwCert');

    	$this->LotwCert->delete_certificate($this->session->userdata('user_id'), $cert_id);

    	$this->session->set_flashdata('Success', 'Certificate Deleted.');

    	redirect('/lotw/');
    }

	/*
	|--------------------------------------------------------------------------
	| Function: toggle_archive_cert
	|--------------------------------------------------------------------------
	|
	| Toggles the archive status of a LoTW certificate
	|
	*/
    public function toggle_archive_cert($cert_id) {
    	$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }

    	$this->load->model('LotwCert');

    	$result = $this->LotwCert->toggle_archive_certificate($this->session->userdata('user_id'), $cert_id);

    	if($result['archived']) {
    		$this->session->set_flashdata('Success', 'Certificate Archived.');
    	} else {
    		$this->session->set_flashdata('Success', 'Certificate Unarchived.');
    	}

    	redirect('/lotw/');
    }


	/*
	|--------------------------------------------------------------------------
	| Function: decrypt_key
	|--------------------------------------------------------------------------
	|
	| Accepts p12 file and optional password and encrypts the file returning
	| the required fields for LoTW and the PEM Key
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

			    // Set warning message redirect to LoTW main page
			    $this->session->set_flashdata('Warning', openssl_error_string());
				redirect('/lotw/');
			}
		} else {
			// Reading p12 failed log error message
			log_message('error', openssl_error_string());

			// Set warning message redirect to LoTW main page
			$this->session->set_flashdata('Warning', openssl_error_string());
			redirect('/lotw/');
		}

		// Read Cert Data
		$certdata= openssl_x509_parse($results['cert'],0);

		// Store Variables
		$data['issued_callsign'] = $certdata['subject']['undefined'];
		$data['issued_name'] = $certdata['subject']['commonName'];
		$data['validFrom'] = date('Y-m-d H:i:s', $certdata['validFrom_time_t']);
		$data['validTo_Date'] = date('Y-m-d H:i:s', $certdata['validTo_time_t']);
		// https://oidref.com/1.3.6.1.4.1.12348.1
		$data['qso-first-date'] = $certdata['extensions']['1.3.6.1.4.1.12348.1.2'];
		$data['qso-end-date'] = $certdata['extensions']['1.3.6.1.4.1.12348.1.3'];
		$data['dxcc-id'] = $certdata['extensions']['1.3.6.1.4.1.12348.1.4'];

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

		if ($display_view === 'summary') {
			echo "Reading the report\n";
			$this->lotw_flush_cli();
		}

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
				$tableheaders .= "<td>Gridsquare</td>";
				$tableheaders .= "<td>IOTA</td>";
				$tableheaders .= "<td>Log Status</td>";
			$tableheaders .= "</tr>";

			$table = "";
			$parsed = array();
			$preview_limit = 200;
			$show_page = ($display_view === true || $display_view === 'TRUE' || $display_view === '1');
			$read = 0;

			while ($record = $this->adif_parser->get_record())
			{
				$read++;
				if ($display_view === 'summary' && $read % 5000 === 0) {
					echo "  read {$read} QSOs\n";
					$this->lotw_flush_cli();
				}
				if (empty($record['call']) || empty($record['qso_date']) || empty($record['time_on'])) {
					continue;
				}

				$time_on = date('Y-m-d', strtotime($record['qso_date'])) ." ".date('H:i', strtotime($record['time_on']));
				$qsl_date = date('Y-m-d', strtotime($record['qslrdate'])) ." ".date('H:i', strtotime($record['qslrdate']));

				if (isset($record['qsl_rcvd']) && $record['qsl_rcvd'] == "Y")
				{
					$record['qsl_rcvd'] = $config['lotw_rcvd_mark'];
				}

				$parsed[] = array(
					'datetime' => $time_on,
					'call' => $record['call'],
					'band' => isset($record['band']) ? $record['band'] : '',
					'mode' => isset($record['mode']) ? $record['mode'] : '',
					'qsl_date' => $qsl_date,
					'qsl_rcvd' => isset($record['qsl_rcvd']) ? $record['qsl_rcvd'] : '',
					'state' => isset($record['state']) ? $record['state'] : '',
					'gridsquare' => isset($record['gridsquare']) ? $record['gridsquare'] : '',
					'vucc_grids' => isset($record['vucc_grids']) ? $record['vucc_grids'] : '',
					'iota' => isset($record['iota']) ? $record['iota'] : '',
					'cnty' => isset($record['cnty']) ? $record['cnty'] : '',
					'cqz' => isset($record['cqz']) ? $record['cqz'] : '',
					'ituz' => isset($record['ituz']) ? $record['ituz'] : '',
					'station_callsign' => isset($record['station_callsign']) ? $record['station_callsign'] : '',
				);
			}

			if ($display_view === 'summary') {
				echo 'Parsed ' . count($parsed) . " confirmations, matching\n";
			}

			$matches = $this->logbook_model->match_lotw_confirmations($parsed, $display_view === 'summary');
			$batch_updates = array();
			$found_count = 0;
			$preview_rows = 0;

			foreach ($parsed as $index => $record) {
				$found = isset($matches[$index]);
				if ($found) {
					$found_count++;
					$batch_updates[] = array(
						'primary_key' => $matches[$index],
						'datetime' => $record['datetime'],
						'callsign' => $record['call'],
						'band' => $record['band'],
						'qsl_date' => $record['qsl_date'],
						'qsl_status' => $record['qsl_rcvd'],
						'state' => $record['state'],
						'qsl_gridsquare' => $record['gridsquare'],
						'qsl_vucc_grids' => $record['vucc_grids'],
						'iota' => $record['iota'],
						'cnty' => $record['cnty'],
						'cqz' => $record['cqz'],
						'ituz' => $record['ituz'],
						'station_callsign' => $record['station_callsign']
					);
				}

				if ($show_page && $preview_rows < $preview_limit) {
					$preview_rows++;
					$gridsquare = ($record['gridsquare'] != '' ? $record['gridsquare'] : $record['vucc_grids']);
					$table .= "<tr>";
					$table .= "<td>".htmlspecialchars($record['station_callsign'], ENT_QUOTES, 'UTF-8')."</td>";
					$table .= "<td>".htmlspecialchars($record['datetime'], ENT_QUOTES, 'UTF-8')."</td>";
					$table .= "<td>".htmlspecialchars($record['call'], ENT_QUOTES, 'UTF-8')."</td>";
					$table .= "<td>".htmlspecialchars($record['mode'], ENT_QUOTES, 'UTF-8')."</td>";
					$table .= "<td>".htmlspecialchars($record['qsl_rcvd'], ENT_QUOTES, 'UTF-8')."</td>";
					$table .= "<td>".($found ? htmlspecialchars($record['qsl_date'], ENT_QUOTES, 'UTF-8') : '')."</td>";
					$table .= "<td>".($found ? htmlspecialchars($record['state'], ENT_QUOTES, 'UTF-8') : '')."</td>";
					$table .= "<td>".($found ? htmlspecialchars($gridsquare, ENT_QUOTES, 'UTF-8') : '')."</td>";
					$table .= "<td>".($found ? htmlspecialchars($record['iota'], ENT_QUOTES, 'UTF-8') : '')."</td>";
					$table .= "<td>QSO Record: ".($found ? "Found" : "No Match")."</td>";
					$table .= "</tr>";
				}
			}

			$lotw_status = "No updates";
			$not_found = count($parsed) - $found_count;
			if (!empty($batch_updates)) {
				if ($display_view === 'summary') {
					echo 'Updating ' . count($batch_updates) . " logged QSOs\n";
					$this->lotw_flush_cli();
				}
				$result = $this->logbook_model->lotw_update_batch($batch_updates);
				$already_confirmed = max(0, $found_count - $result['updated']);
				$lotw_status = "Found: {$found_count} QSOs, Updated: {$result['updated']} QSOs, Already confirmed: {$already_confirmed} QSOs, Gridsquares updated: {$result['gridsquare_updated']}, Counties updated: {$result['county_updated']}, Not matched: {$not_found}";
				log_message('info', 'LoTW Download: ' . $lotw_status);
			} else if ($parsed !== array()) {
				$lotw_status = "Found: 0 QSOs, Not matched: {$not_found}";
			}

			if ($show_page && $preview_rows < count($parsed)) {
				$table .= '<tr><td colspan="10">Showing the first ' . $preview_limit . ' of ' . count($parsed) . ' confirmations.</td></tr>';
			}

			if ($table != "")
			{
				$table .= "</table>";
				$table .= '<div class="mt-2"><strong>LoTW Status:</strong> ' . $lotw_status . '</div>';
				$data['lotw_table_headers'] = $tableheaders;
				$data['lotw_table'] = $table;
		}

		unlink($filepath);

		if (!$show_page) {
			return $lotw_status;
		}

		$this->load->model('user_model');
		if ($this->user_model->authorize(2)) {	// Only Output results if authorized User
			if(isset($data['lotw_table_headers'])) {
				$data['page_title'] = "LoTW ADIF Information";
				$this->load->view('interface_assets/header', $data);
				$this->load->view('lotw/analysis');
				$this->load->view('interface_assets/footer');
			} else {
				echo "Downloaded LoTW report contains no matches.";
			}
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
	function lotw_download($sync_user_id = null) {
		$this->load->model('user_model');
		$this->load->model('logbook_model');

		$query = $this->user_model->get_all_lotw_users();

		if ($query->num_rows() >= 1) {
			$result = '';

			foreach ($query->result() as $user) {
				if ( ($sync_user_id != null) && ($sync_user_id != $user->user_id) ) { continue; }

				if ($user->user_lotw_password == '') {
					$result = "You have not defined your ARRL LoTW credentials!";
					continue;
				}

				$file = './uploads/lotwreport_download.adi';
				$lotw_last_qsl_date = date('Y-m-d', strtotime($this->logbook_model->lotw_last_qsl_date($user->user_id)));
				$fetched = $this->fetch_lotw_report($user->user_lotw_name, $user->user_lotw_password, $lotw_last_qsl_date, $file, 300);
				if (!$fetched['ok']) {
					$result = $fetched['message'];
					continue;
				}

				ini_set('memory_limit', '-1');
				$result = $this->loadFromFile($file, false);
			}
			return $result;
		} else {
			return "No LoTW User details found to carry out matches.";
		}
	}

	/*
	 * One-shot confirmation download that can run longer than a browser request.
	 * php index.php lotw download_since YYYY-MM-DD [user_id]
	 */
	public function download_since($since = null, $user_id = null)
	{
		if (!is_cli()) {
			show_error('Run this from the server: php index.php lotw download_since YYYY-MM-DD [user_id]', 400);
			return;
		}
		if ($since === null || !preg_match('/^\d{4}-\d{2}-\d{2}$/', (string)$since)) {
			echo "Usage: php index.php lotw download_since YYYY-MM-DD [user_id]\n";
			return;
		}

		ini_set('memory_limit', '-1');
		set_time_limit(0);

		$this->load->library('cron_runner');
		$this->load->model('user_model');
		$this->load->model('logbook_model');

		if (!$this->cron_runner->acquire_lock('lotw_sync')) {
			echo "A LoTW sync is already running. Wait for it to finish, then run this again.\n";
			return;
		}

		echo "LoTW backfill since {$since}\n";
		$query = $this->user_model->get_all_lotw_users();
		$ran = false;
		foreach ($query->result() as $user) {
			if ($user_id !== null && (string)$user_id !== (string)$user->user_id) {
				continue;
			}
			if ($user->user_lotw_password == '') {
				echo "User {$user->user_id}: no LoTW password, skipped\n";
				continue;
			}
			$ran = true;
			$label = 'User ' . $user->user_id;
			if (!empty($user->user_callsign)) {
				$label .= ' (' . $user->user_callsign . ')';
			}
			echo $label . ": downloading confirmations since {$since}\n";
			echo "  LoTW builds the file before it sends anything. A line every 15 seconds means the connection is still open.\n";
			$this->lotw_flush_cli();
			$file = './uploads/lotwreport_cli_' . (int)$user->user_id . '.adi';
			$fetched = $this->fetch_lotw_report($user->user_lotw_name, $user->user_lotw_password, $since, $file, 0, null, true);
			if (!$fetched['ok']) {
				echo $label . ': ' . $fetched['message'] . "\n";
				continue;
			}
			echo $label . ': saved ' . $fetched['bytes'] . " bytes\n";
			$summary = $this->loadFromFile($file, 'summary');
			echo $label . ': ' . $summary . "\n";
		}
		if (!$ran) {
			echo "No LoTW user matched.\n";
		}
		$this->cron_runner->release();
	}

	private function fetch_lotw_report($login, $password, $since, $destination, $timeout_seconds, $owncall = null, $verbose = false)
	{
		$url_query = $this->db->query('SELECT lotw_download_url FROM config');
		$base = $url_query->row()->lotw_download_url;
		$lotw_url = $base . '?login=' . rawurlencode($login);
		$lotw_url .= '&password=' . rawurlencode($password);
		$lotw_url .= "&qso_query=1&qso_qsl='yes'&qso_qsldetail='yes'&qso_mydetail='yes'";
		$lotw_url .= '&qso_qslsince=' . rawurlencode($since);
		if ($owncall !== null && $owncall !== '') {
			$lotw_url .= '&qso_owncall=' . rawurlencode($owncall);
		}

		$directory = dirname($destination);
		if (!is_dir($directory) || !is_writable($directory)) {
			return array('ok' => false, 'message' => 'Temporary download directory is not writable.', 'bytes' => 0);
		}
		if (file_exists($destination) && !is_writable($destination)) {
			return array('ok' => false, 'message' => 'Temporary download file is not writable.', 'bytes' => 0);
		}

		$max_retries = 3;
		$retry_delay = 2;
		$curl_error = '';
		$http_code = 0;
		$bytes = 0;
		$saved = false;

		for ($attempt = 1; $attempt <= $max_retries; $attempt++) {
			if ($verbose && $attempt > 1) {
				echo "  retry {$attempt} of {$max_retries}\n";
				$this->lotw_flush_cli();
			}
			$fp = fopen($destination, 'w');
			if ($fp === false) {
				return array('ok' => false, 'message' => 'Failed to write the LoTW report.', 'bytes' => 0);
			}
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $lotw_url);
			curl_setopt($ch, CURLOPT_FILE, $fp);
			curl_setopt($ch, CURLOPT_TIMEOUT, (int)$timeout_seconds);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
			curl_setopt($ch, CURLOPT_USERAGENT, 'Cloudlog LoTW Client/1.0');
			$ok = $this->lotw_curl_exec($ch, $verbose);
			$http_code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
			$curl_error = curl_error($ch);
			curl_close($ch);
			fclose($fp);
			clearstatcache(true, $destination);
			$bytes = file_exists($destination) ? (int)filesize($destination) : 0;
			$saved = ($ok !== false && $curl_error === '' && $http_code === 200);
			if ($saved) {
				break;
			}
			if ($attempt < $max_retries) {
				log_message('error', 'LoTW download attempt ' . $attempt . ' failed: ' . $curl_error . ' HTTP ' . $http_code);
				sleep($retry_delay);
				$retry_delay *= 2;
			}
		}

		if (!$saved) {
			$message = $curl_error !== '' ? $curl_error : ('HTTP ' . $http_code);
			log_message('error', 'LoTW download failed: ' . $message);
			return array('ok' => false, 'message' => 'LoTW download failed: ' . $message, 'bytes' => $bytes);
		}

		$header = file_get_contents($destination, false, null, 0, 39);
		if ($header != "ARRL Logbook of the World Status Report") {
			return array('ok' => false, 'message' => 'LoTW download failed. The service may be down, or the username and password are wrong.', 'bytes' => $bytes);
		}

		log_message('info', 'LoTW report saved (' . $bytes . ' bytes) since ' . $since);
		return array('ok' => true, 'message' => '', 'bytes' => $bytes);
	}

	/*
	 * Runs the LoTW request and, on the command line, prints a line every 15 seconds.
	 * LoTW often sends nothing until the whole report is built, so a byte count of
	 * zero with the clock moving is the connection still waiting.
	 */
	private function lotw_curl_exec($ch, $verbose)
	{
		if (!$verbose) {
			return curl_exec($ch);
		}

		echo "  request sent\n";
		$this->lotw_flush_cli();

		$mh = curl_multi_init();
		curl_multi_add_handle($mh, $ch);
		$started = microtime(true);
		$last_report = $started;
		$active = null;
		$status = CURLM_OK;

		do {
			$status = curl_multi_exec($mh, $active);
			if ($active) {
				if (curl_multi_select($mh, 1.0) === -1) {
					usleep(100000);
				}
			}
			$now = microtime(true);
			if ($now - $last_report >= 15) {
				$downloaded = (int)curl_getinfo($ch, CURLINFO_SIZE_DOWNLOAD);
				$elapsed = (int)($now - $started);
				if ($downloaded > 0) {
					echo '  receiving, ' . $elapsed . 's, ' . $this->lotw_format_bytes($downloaded) . "\n";
				} else {
					echo '  still waiting for LoTW, ' . $elapsed . "s, nothing received yet\n";
				}
				$this->lotw_flush_cli();
				$last_report = $now;
			}
		} while ($active && $status === CURLM_OK);

		curl_multi_remove_handle($mh, $ch);
		curl_multi_close($mh);

		return $status === CURLM_OK;
	}

	private function lotw_format_bytes($bytes)
	{
		$bytes = (int)$bytes;
		if ($bytes < 1024) {
			return $bytes . ' B';
		}
		if ($bytes < 1048576) {
			return round($bytes / 1024, 1) . ' KB';
		}
		return round($bytes / 1048576, 1) . ' MB';
	}

	private function lotw_flush_cli()
	{
		if (function_exists('ob_get_level')) {
			while (ob_get_level() > 0) {
				ob_end_flush();
			}
		}
		flush();
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

			$query = $this->user_model->get_by_id($this->session->userdata('user_id'));
			$account = $query->row();

			if ($account->user_lotw_name == '' || $account->user_lotw_password == '')
			{
				$this->session->set_flashdata('warning', 'You have not defined your ARRL LoTW credentials!'); redirect('lotw/import');
			}

			$customDate = $this->input->post('from');
			if ($customDate != NULL && preg_match('/^\d{4}-\d{2}-\d{2}$/', $customDate)) {
				$lotw_last_qsl_date = $customDate;
			} else {
				$lotw_last_qsl_date = date('Y-m-d', strtotime($this->logbook_model->lotw_last_qsl_date($this->session->userdata['user_id'])));
			}

			$owncall = $this->input->post('callsign');
			if ($owncall === '0' || $owncall === 'All' || $owncall === null || $owncall === '') {
				$owncall = null;
			}

			$fetched = $this->fetch_lotw_report($account->user_lotw_name, $account->user_lotw_password, $lotw_last_qsl_date, $file, 120, $owncall);
			if ($fetched['ok']) {
				ini_set('memory_limit', '-1');
				$this->loadFromFile($file);
			} else {
				$data['errormsg'] = $fetched['message'];
				$this->load->model('Stations');
				$data['callsigns'] = $this->Stations->callsigns_of_user($this->session->userdata('user_id'));

				$this->load->view('interface_assets/header', $data);
				$this->load->view('lotw/import', $data);
				$this->load->view('interface_assets/footer');
			}
		}
		else
		{
			if ( ! $this->upload->do_upload())
			{

				$data['error'] = $this->upload->display_errors();
				$this->load->model('Stations');
				$data['callsigns'] = $this->Stations->callsigns_of_user($this->session->userdata('user_id'));

				$this->load->view('interface_assets/header', $data);
				$this->load->view('lotw/import', $data);
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
		Load the ARRL LoTW User Activity CSV and saves into uploads/lotw_users.csv
	*/
	public function load_users() {
		$this->load->library('cron_runner');
		$this->cron_runner->run_guarded('lotw_load_users', function () {
		$contents = file_get_contents('https://lotw.arrl.org/lotw-user-activity.csv', true);

        if($contents === FALSE) {
            echo "Something went wrong with fetching the LoTW users file.";
        } else {
            $file = './updates/lotw_users.csv';

            if (file_put_contents($file, $contents) !== FALSE) {     // Save our content to the file.
                echo "LoTW User Data Saved.";
            } else {
                echo "FAILED: Could not write to LoTW users file";
            }
        }
		});
	}

	/*
		Check if callsign is an active LoTW user and return whether its true or not
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
		  if (defined('PHP_MAJOR_VERSION') && PHP_MAJOR_VERSION < 8) {
		    openssl_free_key($pkeyid);
		  }
		  $signature_b64 = base64_encode($signature);
		  return $signature_b64."\n";
		}


	}

	/*
	|	Function: lotw_satellite_map
	|	Requires: OSCAR Satellite name $satname
	|
	|	Outputs if LoTW uses a different satellite name
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
			'UVSQ'		=>	"UVSQ-SAT",
			'CAS-3H'	=>	"LILACSAT-2",
			'IO-117'	=>	"GREENCUBE",
			"TEVEL1"	=>	"TEVEL-1",
			"TEVEL2"	=>	"TEVEL-2",
			"TEVEL3"	=>	"TEVEL-3",
			"TEVEL4"	=>	"TEVEL-4",
			"TEVEL5"	=>	"TEVEL-5",
			"TEVEL6"	=>	"TEVEL-6",
			"TEVEL7"	=>	"TEVEL-7",
			"TEVEL8"	=>	"TEVEL-8",
			"INSPR7"	=> "INSPIRE-SAT 7",
			"SONATE"	=> "SONATE-2",
			'AO-123'	=> "ASRTU-1",
			'TEV2-1'	=> "TEVEL2-1",
			'TEV2-2'	=> "TEVEL2-2",
			'TEV2-3'	=> "TEVEL2-3",
			'TEV2-4'	=> "TEVEL2-4",
			'TEV2-5'	=> "TEVEL2-5",
			'TEV2-6'	=> "TEVEL2-6",
			'TEV2-7'	=> "TEVEL2-7",
			'TEV2-8'	=> "TEVEL2-8",
			'TEV2-9'	=> "TEVEL2-9",
			'RS95S'		=> "QMR-KWT-2",
		);

		return array_search(strtoupper($satname),$arr,true);
	}

	/*
	|	Function: lotw_ca_province_map
	|	Requires: candian province map $ca_province
	*/
	function lotw_ca_province_map($ca_prov) {
		switch ($ca_prov):
			case "QC":
				return "PQ";
				break;
			case "NL":
				return "NF";
				break;
			default:
				return $ca_prov;
		endswitch;
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
				} elseif ($submode == "FST4") {
					return "FST4";
					break;
				} elseif ($submode == "MFSK16") {
					return "MFSK16";
					break;
				} elseif ($submode == "MFSK8") {
					return "MFSK8";
					break;
				} elseif ($submode == "Q65") {
					return "Q65";
					break;
				} elseif ($submode == "FSQCALL") {
					return "DATA";
					break;
				} elseif ($submode == "JS8") {
					return "DATA";
					break;
				} elseif ($submode == "MFSK11") {
					return "DATA";
					break;
				} elseif ($submode == "MFSK128") {
					return "DATA";
					break;
				} elseif ($submode == "MFSK22") {
					return "DATA";
					break;
				} elseif ($submode == "MFSK31") {
					return "DATA";
					break;
				} elseif ($submode == "MFSK32") {
					return "DATA";
					break;
				} elseif ($submode == "MFSK4") {
					return "DATA";
					break;
				} elseif ($submode == "MFSK64") {
					return "DATA";
					break;
				} else {
					return "DATA";
					break;
				}
			case "PSK":
				if ($submode == "PSK31") {
					return "PSK31";
					break;
				} elseif ($submode == "PSK63") {
					return "PSK63";
					break;
				} elseif ($submode == "BPSK125") {
					return "PSK125";
					break;
				} elseif ($submode == "BPSK31") {
					return "PSK31";
					break;
				} elseif ($submode == "BPSK63") {
					return "PSK63";
					break;
				} elseif ($submode == "FSK31") {
					return "FSK31";
					break;
				} elseif ($submode == "PSK10") {
					return "PSK10";
					break;
				} elseif ($submode == "PSK125") {
					return "PSK125";
					break;
				} elseif ($submode == "PSK500") {
					return "DATA";
					break;
				} elseif ($submode == "PSK63F") {
					return "PSK63F";
					break;
				} elseif ($submode == "PSKAM10") {
					return "PSKAM";
					break;
				} elseif ($submode == "PSKAM31") {
					return "PSKAM";
					break;
				} elseif ($submode == "PSKAM50") {
					return "PSKAM";
					break;
				} elseif ($submode == "PSKFEC31") {
					return "PSKFEC31";
					break;
				} elseif ($submode == "QPSK125") {
					return "PSK125";
					break;
				} elseif ($submode == "QPSK31") {
					return "PSK31";
					break;
				} elseif ($submode == "QPSK63") {
					return "PSK63";
					break;
				} elseif ($submode == "PSK2K") {
					return "PSK2K";
					break;
				} elseif ($submode == "PSK1000") {
					return "DATA";
					break;
				} elseif ($submode == "PSK250") {
					return "DATA";
					break;
				} elseif ($submode == "QPSK250") {
					return "DATA";
					break;
				} elseif ($submode == "QPSK500") {
					return "DATA";
					break;
				} elseif ($submode == "SIM31") {
					return "DATA";
					break;
				} else {
					return "DATA";
					break;
				}
			case "JT65":
				if ($submode == "JT65A") {
					return "JT65";
					break;
				} elseif ($submode == "JT65B") {
					return "JT65";
					break;
				} elseif ($submode == "JT65B2") {
					return "JT65";
					break;
				} elseif ($submode == "JT65C") {
					return "JT65";
					break;
				} elseif ($submode == "JT65C2") {
					return "JT65";
					break;
				} else {
					return "JT65";
					break;
				}
			case "JT4":
				if ($submode == "JT4A") {
					return "JT4";
					break;
				} elseif ($submode == "JT4B") {
					return "JT4";
					break;
				} elseif ($submode == "JT4C") {
					return "JT4";
					break;
				} elseif ($submode == "JT4D") {
					return "JT4";
					break;
				} elseif ($submode == "JT4E") {
					return "JT4";
					break;
				} elseif ($submode == "JT4F") {
					return "JT4";
					break;
				} elseif ($submode == "JT4G") {
					return "JT4";
					break;
				} else {
					return "JT4";
					break;
				}
			case "JT9":
				if ($submode == "JT9-1") {
					return "JT9";
					break;
				} elseif ($submode == "JT9-10") {
					return "JT9";
					break;
				} elseif ($submode == "JT9-2") {
					return "JT9";
					break;
				} elseif ($submode == "JT9-30") {
					return "JT9";
					break;
				} elseif ($submode == "JT9-5") {
					return "JT9";
					break;
				} elseif ($submode == "JT9A") {
					return "JT9";
					break;
				} elseif ($submode == "JT9B") {
					return "JT9";
					break;
				} elseif ($submode == "JT9C") {
					return "JT9";
					break;
				} elseif ($submode == "JT9D") {
					return "JT9";
					break;
				} elseif ($submode == "JT9E") {
					return "JT9";
					break;
				} elseif ($submode == "JT9E FAST") {
					return "JT9";
					break;
				} elseif ($submode == "JT9F") {
					return "JT9";
					break;
				} elseif ($submode == "JT9F FAST") {
					return "JT9";
					break;
				} elseif ($submode == "JT9G") {
					return "JT9G";
					break;
				} elseif ($submode == "JT9G FAST") {
					return "JT9";
					break;
				} elseif ($submode == "JT9H") {
					return "JT9";
					break;
				} elseif ($submode == "JT9H FAST") {
					return "JT9";
					break;
				} else {
					return "JT9";
					break;
				}
			case "QRA64":
				if ($submode == "QRA64A") {
					return "QRA64";
					break;
				} elseif ($submode == "QRA64B") {
					return "QRA64";
					break;
				} elseif ($submode == "QRA64C") {
					return "QRA64";
					break;
				} elseif ($submode == "QRA64D") {
					return "QRA64";
					break;
				} elseif ($submode == "QRA64E") {
					return "QRA64";
					break;
				} else {
					return "QRA64";
					break;
				}
			case "ISCAT":
				if ($submode == "ISCAT-A") {
					return "ISCAT";
					break;
				} elseif ($submode == "ISCAT-B") {
					return "ISCAT";
					break;
				} else {
					return "ISCAT";
					break;
				}
			case "OLIVIA":
				if ($submode == "OLIVIA 16/1000") {
					return "OLIVIA";
					break;
				} elseif ($submode == "OLIVIA 16/500") {
					return "OLIVIA";
					break;
				} elseif ($submode == "OLIVIA 32/1000") {
					return "OLIVIA";
					break;
				} elseif ($submode == "OLIVIA 4/125") {
					return "OLIVIA";
					break;
				} elseif ($submode == "OLIVIA 4/250") {
					return "OLIVIA";
					break;
				} elseif ($submode == "OLIVIA 8/250") {
					return "OLIVIA";
					break;
				} elseif ($submode == "OLIVIA 8/500") {
					return "OLIVIA";
					break;
				} else {
					return "OLIVIA";
					break;
				}
			case "OPERA":
				if ($submode == "OPERA-BEACON") {
					return "OPERA";
					break;
				} elseif ($submode == "OPERA-QSO") {
					return "OPERA";
					break;
				} else {
					return "OPERA";
					break;
				}
			case "ROS":
				if ($submode == "ROS-EME") {
					return "ROS";
					break;
				} elseif ($submode == "ROS-HF") {
					return "ROS";
					break;
				} elseif ($submode == "ROS-MF") {
					return "ROS";
					break;
				} else {
					return "ROS";
					break;
				}
			case "HELL":
				if ($submode == "FMHELL") {
					return "HELL";
					break;
				} elseif ($submode == "FSKHELL") {
					return "HELL";
					break;
				} elseif ($submode == "HELL80") {
					return "HELL";
					break;
				} elseif ($submode == "HFSK") {
					return "HFSK";
					break;
				} elseif ($submode == "PSKHELL") {
					return "HELL";
					break;
				} else {
					return "HELL";
					break;
				}
			case "DOMINO":
				if ($submode == "DOMINOEX") {
					return "DOMINO";
					break;
				} elseif ($submode == "DOMINOF") {
					return "DOMINO";
					break;
				} else {
					return "DOMINO";
					break;
				}
			case "CHIP":
				if ($submode == "CHIP128") {
					return "CHIP";
					break;
				} elseif ($submode == "CHIP64") {
					return "CHIP";
					break;
				} else {
					return "CHIP";
					break;
				}
			case "PAC":
				if ($submode == "PAC2") {
					return "PACTOR";
					break;
				} elseif ($submode == "PAC3") {
					return "PACTOR";
					break;
				} elseif ($submode == "PAC4") {
					return "PACTOR";
					break;
				} else {
					return "PACTOR";
					break;
				}
			case "PAX":
				if ($submode == "PAX2") {
					return "PAX";
					break;
				} else {
					return "PAX";
					break;
				}
			case "TOR":
				if ($submode == "AMTORFEC") {
					return "AMTOR";
					break;
				} elseif ($submode == "GTOR") {
					return "GTOR";
					break;
				} else {
					return "AMTOR";
					break;
				}
			case "THRB":
				if ($submode == "THRBX") {
					return "THROB";
					break;
				} else {
					return "THROB";
					break;
				}
			case "SSB":
				if ($submode == "LSB") {
					return "SSB";
					break;
				} elseif ($submode == "USB") {
					return "SSB";
					break;
				} else {
					return "SSB";
					break;
				}
			case "RTTY":
				if ($submode == "ASCI") {
					return "RTTY";
					break;
				} else {
					return "RTTY";
					break;
				}
			case "CW":
				if ($submode == "PCW") {
					return "CW";
					break;
				} else {
					return "CW";
					break;
				}
			case "CLO":
				return "CLOVER";
				break;
			case "V4":
				return "DATA";
				break;
			default:
				return $mode;
		endswitch;
	}

} // end class
