<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
	Controller to interact with the QRZ.com API
 */

class Qrz extends CI_Controller {

	// Show frontend if there is one
	public function index() {
		$this->config->load('config');
	}

	/*
	 * Upload QSO to QRZ.com
	 * When called from the url cloudlog/qrz/upload, the function loops through all station_id's with a qrz api key defined.
	 * All QSOs not previously uploaded, will then be uploaded, one at a time
	 */
	public function upload() {
		$this->setOptions();

		$this->load->model('logbook_model');

		$station_ids = $this->logbook_model->get_station_id_with_qrz_api();

		if ($station_ids) {
			foreach ($station_ids as $station) {
				$qrz_api_key = $station->qrzapikey;
				if($this->mass_upload_qsos($station->station_id, $qrz_api_key, true)) {
					echo "QSOs have been uploaded to QRZ.com.";
					log_message('info', 'QSOs have been uploaded to QRZ.com.');
				} else{
					echo "No QSOs found for upload.";
					log_message('info', 'No QSOs found for upload.');
				}
			}
		} else {
			echo "No station profiles with a QRZ API Key found.";
			log_message('error', "No station profiles with a QRZ API Key found.");
		}

	}

	function setOptions() {
		$this->config->load('config');
		ini_set('memory_limit', '-1');
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
	}

	/*
	 * Function gets all QSOs from given station_id, that are not previously uploaded to qrz.
	 * Adif is build for each qso, and then uploaded, one at a time
	 */
	function mass_upload_qsos($station_id, $qrz_api_key, $trusted = false) {
		$i = 0;
		$data['qsos'] = $this->logbook_model->get_qrz_qsos($station_id, $trusted);
		$errormessages=array();

		$this->load->library('AdifHelper');

		if ($data['qsos']) {
			foreach ($data['qsos']->result() as $qso) {
				$adif = $this->adifhelper->getAdifLine($qso);

				if ($qso->COL_QRZCOM_QSO_UPLOAD_STATUS == 'M') {
					$result = $this->logbook_model->push_qso_to_qrz($qrz_api_key, $adif, true);
				} else {
					$result = $this->logbook_model->push_qso_to_qrz($qrz_api_key, $adif);
				}

				if ( ($result['status'] == 'OK') || ( ($result['status'] == 'error') && ($result['message'] == 'STATUS=FAIL&REASON=Unable to add QSO to database: duplicate&EXTENDED=')) ){
					$this->markqso($qso->COL_PRIMARY_KEY);
					$i++;
					$result['status'] = 'OK';
				} elseif ( ($result['status']=='error') && (substr($result['message'],0,11)  == 'STATUS=AUTH')) {
					log_message('error', 'QRZ upload failed for qso: Call: ' . $qso->COL_CALL . ' Band: ' . $qso->COL_BAND . ' Mode: ' . $qso->COL_MODE . ' Time: ' . $qso->COL_TIME_ON);
					log_message('error', 'QRZ upload failed with the following message: ' .$result['message']);
					log_message('error', 'QRZ upload stopped for Station_ID: ' .$station_id);
					$errormessages[] = $result['message'] . ' Call: ' . $qso->COL_CALL . ' Band: ' . $qso->COL_BAND . ' Mode: ' . $qso->COL_MODE . ' Time: ' . $qso->COL_TIME_ON;
					$result['status'] = 'Error';
					break; /* If key is invalid, immediate stop syncing for more QSOs of this station */
				} else {
					log_message('error', 'QRZ upload failed for qso: Call: ' . $qso->COL_CALL . ' Band: ' . $qso->COL_BAND . ' Mode: ' . $qso->COL_MODE . ' Time: ' . $qso->COL_TIME_ON);
					log_message('error', 'QRZ upload failed with the following message: ' .$result['message']);
					$errormessages[] = $result['message'] . ' Call: ' . $qso->COL_CALL . ' Band: ' . $qso->COL_BAND . ' Mode: ' . $qso->COL_MODE . ' Time: ' . $qso->COL_TIME_ON;
					$result['status'] = 'Error';
				}
			}
			if ($i == 0) {
			    $result['status']='OK';
		    }
			$result['count'] = $i;
			$result['errormessages'] = $errormessages;
			return $result;
		} else {
			$result['status'] = 'Error';
			$result['count'] = $i;
			$result['errormessages'] = $errormessages;
			return $result;
		}
	}

	/*
	 * Function marks QSO with given primarykey as uploaded to qrz
	 */
	function markqso($primarykey) {
		$this->logbook_model->mark_qrz_qsos_sent($primarykey);
	}

	/*
	 * Used for displaying the uid for manually selecting log for upload to qrz
	 */
	public function export() {
		$this->load->model('stations');

		$data['page_title'] = "QRZ Logbook";

		$data['station_profiles'] = $this->stations->all_of_user();
		$data['station_profile'] = $this->stations->stations_with_qrz_api_key();
		$this->load->model('Stations');
		$data['callsigns'] = $this->Stations->callsigns_of_user($this->session->userdata('user_id'));

		$this->load->view('interface_assets/header', $data);
		$this->load->view('qrz/export');
		$this->load->view('interface_assets/footer');
	}

	/*
	 * Used for ajax-function when selecting log for upload to qrz
	 */
	public function upload_station() {
		$this->setOptions();
		$this->load->model('stations');

		$postData = $this->input->post();

		$this->load->model('logbook_model');
		$result = $this->logbook_model->exists_qrz_api_key($postData['station_id']);
		$qrz_api_key = $result->qrzapikey;
		header('Content-type: application/json');
		$result = $this->mass_upload_qsos($postData['station_id'], $qrz_api_key);
		if ($result['status'] == 'OK') {
			$stationinfo = $this->stations->stations_with_qrz_api_key();
			$info = $stationinfo->result();

			$data['status'] = 'OK';
			$data['info'] = $info;
			$data['infomessage'] = $result['count'] . " QSOs are now uploaded to QRZ.com";
			$data['errormessages'] = $result['errormessages'];
			echo json_encode($data);
		} else {
			$data['status'] = 'Error';
			$data['info'] = 'Error: No QSOs found to upload.';
			$data['errormessages'] = $result['errormessages'];
			echo json_encode($data);
		}
	}

	public function mark_qrz() {
		// Set memory limit to unlimited to allow heavy usage
		ini_set('memory_limit', '-1');

		$station_id = $this->security->xss_clean($this->input->post('station_profile'));

		$this->load->model('adif_data');

		$data['qsos'] = $this->adif_data->export_custom($this->input->post('from'), $this->input->post('to'), $station_id);

		$this->load->model('logbook_model');

		foreach ($data['qsos']->result() as $qso)
		{
			$this->logbook_model->mark_qrz_qsos_sent($qso->COL_PRIMARY_KEY);
		}

		$this->load->view('interface_assets/header', $data);
		$this->load->view('qrz/mark_qrz', $data);
		$this->load->view('interface_assets/footer');
	}

	public function import_qrz() {
		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }

		$data['page_title'] = "QRZ QSL Import";

		$this->load->model('logbook_model');

		$customDate = $this->input->post('from');
		if ($customDate != NULL) {
			$qrz_last_date = date($customDate);
		} else {
			// Query the logbook to determine when the last LoTW confirmation was
			$qrz_last_date = null;
		}
		$this->download($this->session->userdata('user_id'),true);
	} // end function

	function download($user_id_to_load = null, $show_views = false) { // Remove $lastqrz parameter
		$this->load->model('user_model');
		$this->load->model('logbook_model');


		$api_keys = $this->logbook_model->get_qrz_apikeys();
		$total_processed_count = 0; // Initialize total count here
		$data = []; // Initialize data array

		if ($api_keys) {
			foreach ($api_keys as $station) {
				if ((($user_id_to_load != null) && ($user_id_to_load != $station->user_id))) {	// Skip User if we're called with a specific user_id
					continue;
				}

				// Remove the block checking for $lastqrz == null and fetching the date
				$qrz_api_key = $station->qrzapikey;
				$result = $this->mass_download_qsos($qrz_api_key); // mass_download_qsos returns ['table_data' => ..., 'processed_count' => ...] or ['status' => 'error', 'message' => ...]


				if ($result !== false && isset($result['processed_count'])) {
					$total_processed_count += $result['processed_count']; // Accumulate count
					$table_data = $result['table_data'];

					if (isset($table_data['tableheaders'])) {
						// Ensure headers are set only once
						if (!isset($data['tableheaders'])) {
							$data['tableheaders'] = $table_data['tableheaders'];
						}
						if (isset($table_data['table']) && $table_data['table'] != '') {
							if (isset($data['table'])) {
								$data['table'] .= $table_data['table'];
							} else {
								$data['table'] = $table_data['table'];
							}
						}
					}
				} else if (is_array($result) && isset($result['status']) && $result['status'] === 'error') {
					// Handle specific error structure returned by mass_download_qsos
					log_message('error', "Error during QRZ download for user_id: " . $station->user_id . ". Message: " . $result['message']);
					// Optionally echo error to user if $show_views is true, or add to $data['error']
					if ($show_views) {
						$data['errors'][] = "Error for user ID " . $station->user_id . ": " . $result['message'];
					}
				} else {
					// Catch-all for unexpected return values (like the old boolean false or other issues)
					log_message('error', "Unexpected error or empty result returned from mass_download_qsos for API key associated with user_id: " . $station->user_id);
					if ($show_views) {
						$data['errors'][] = "Unexpected error during download for user ID " . $station->user_id . ". Check system logs.";
					}
				}
			}
		} else {
			echo "No station profiles with a QRZ API Key found.";
			log_message('error', "No station profiles with a QRZ API Key found.");
			// If no keys, we can exit early if showing views, or just let it fall through if not.
			if ($show_views) {
				$data['page_title'] = "QRZ ADIF Information";
				$data['error'] = "No station profiles with a QRZ API Key found.";
				$this->load->view('interface_assets/header', $data);
				$this->load->view('qrz/analysis', $data); // Assuming view can show $error
				$this->load->view('interface_assets/footer');
				return; // Stop further processing
			} else {
				return ''; // Return empty if not showing views and no keys found
			}
		}

		$this->load->model('user_model');
		if ($this->user_model->authorize(2)) {	// Only Output results if authorized User
			// Pass potential errors to the view
			if (isset($data['errors'])) {
				$view_data['errors'] = $data['errors'];
			}

			$has_matches_to_display = (isset($data['tableheaders']) && isset($data['table']) && $data['table'] != '');
			$message = "Downloaded and processed " . $total_processed_count . " QSOs from QRZ.";

			if ($has_matches_to_display) {
				$message .= " Matching QSOs found and updated.";
				if ($show_views == TRUE) {
					$view_data['tableheaders'] = $data['tableheaders'];
					$view_data['table'] = $data['table'] . '</table>';
					$view_data['page_title'] = "QRZ ADIF Information";
					$this->load->view('interface_assets/header', $view_data);
					$this->load->view('qrz/analysis', $view_data); // Pass $view_data containing table headers, rows, and errors
					$this->load->view('interface_assets/footer');
				} else {
					echo $message; // Echo message when not showing views but matches were found
					// Optionally echo errors if any occurred
					if (isset($data['errors'])) {
						echo " Errors encountered: " . implode("; ", $data['errors']);
					}
					return '';
				}
			} else {
				// No matches found in the logbook
				$message .= " No matching QSOs found in your logbook to update.";
				if ($show_views == TRUE) {
					$view_data['page_title'] = "QRZ ADIF Information";
					$view_data['info_message'] = $message; // Pass the info message to the view
					// Errors are already in $view_data if they exist
					$this->load->view('interface_assets/header', $view_data);
					$this->load->view('qrz/analysis', $view_data); // Load view, assuming it checks for $info_message and $errors
					$this->load->view('interface_assets/footer');
				} else {
					echo $message; // Echo message when not showing views and no matches found
					// Optionally echo errors if any occurred
					if (isset($data['errors'])) {
						echo " Errors encountered: " . implode("; ", $data['errors']);
					}
					return '';
				}
			}
		} // End authorize check
	}

	function mass_download_qsos($qrz_api_key = '', $trusted = false) { // Remove $lastqrz parameter
		$config['upload_path'] = './uploads/';
		$file = $config['upload_path'] . 'qrzcom_download_report.adi';
		if (file_exists($file) && ! is_writable($file)) {
			// This part is fine - checks local file writability
			$error_message = "Temporary download file ".$file." is not writable. Aborting!";
			// Return the structured error array here too for consistency
			return ['status' => 'error', 'message' => $error_message];
		}
		$url = 'https://logbook.qrz.com/api'; // Correct URL

		$post_data['KEY'] = $qrz_api_key;      // Correct parameter
		$post_data['ACTION'] = 'FETCH';         // Correct parameter
		$post_data['OPTION'] = 'TYPE:ADIF'; // Correct parameter for fetching all confirmed in ADIF

		$ch = curl_init( $url );
		curl_setopt( $ch, CURLOPT_POST, true);            // Correct method
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $post_data); // Correct data
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);     // Okay
		curl_setopt( $ch, CURLOPT_HEADER, 0);             // Correct - don't need response headers
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true);  // Correct - get response as string
		curl_setopt( $ch, CURLOPT_TIMEOUT, 300);          // 5 minute timeout
		curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 30);    // 30 second connection timeout
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($ch, CURLOPT_BUFFERSIZE, 128000);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

		$content = curl_exec($ch); // Get raw content
		$curl_error = curl_error($ch); // Check for cURL errors
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE); // Get HTTP response code
		curl_close($ch);

		if ($curl_error) { // Check for cURL level errors first
			$error_message = "QRZ download cURL error: " . $curl_error;
			log_message('error', $error_message . ' API Key used: ' . $qrz_api_key);
			return ['status' => 'error', 'message' => $error_message];
		}

		if ($http_code !== 200) {
			$error_message = "QRZ download HTTP error: HTTP " . $http_code;
			log_message('error', $error_message . ' API Key used: ' . $qrz_api_key);
			return ['status' => 'error', 'message' => $error_message];
		}

		if ($content === false || $content === '') { // Check if curl_exec failed or returned empty
			$error_message = "QRZ download failed: No content received from QRZ.com.";
			log_message('error', $error_message . ' API Key used: ' . $qrz_api_key);
			return ['status' => 'error', 'message' => $error_message];
		}

		// Find the start of the ADIF data after "ADIF="
		$adif_start_pos = strpos($content, 'ADIF=');
		if ($adif_start_pos !== false) {
			// Extract the content starting after "ADIF="
			$content = substr($content, $adif_start_pos + 5);
		} else {
			// If "ADIF=" is not found, check for potential errors before assuming it's just ADIF
			if (strpos($content, 'STATUS=FAIL') !== false || strpos($content, 'STATUS=AUTH') !== false) {
				// Handle API errors even if ADIF= is missing
				$reason = $content;
				if (preg_match('/REASON=([^&]+)/', $content, $matches)) {
					$reason = urldecode($matches[1]); // Decode URL encoded reason
				}
				$error_message = "QRZ API Error: " . $reason;
				log_message('error', $error_message . ' API Key used: ' . $qrz_api_key . ' Raw Response: ' . $content);
				return ['status' => 'error', 'message' => $error_message];
			}
			// If no error status and no ADIF=, maybe it's just ADIF? Or an unknown error.
			// Log a warning if content seems unusual but doesn't match known error patterns.
			if (trim($content) === '' || strlen(trim($content)) < 10) { // Arbitrary small length check
				log_message('error', 'QRZ download: Received unexpected content without ADIF= prefix or known error status. Content: ' . $content);
				// Decide if this should be treated as an error or empty ADIF
				// For now, let's treat it as potentially empty/invalid ADIF and let loadFromFile handle it.
			}
		}

		// Also remove the trailing metadata like &RESULT=OK&COUNT=... or just &COUNT=...
		$result_pos = strpos($content, '&RESULT=');
		$count_pos = strpos($content, '&COUNT=');

		$truncate_pos = false;

		if ($result_pos !== false && $count_pos !== false) {
			// Both found, take the earlier one
			$truncate_pos = min($result_pos, $count_pos);
		} elseif ($result_pos !== false) {
			// Only RESULT found
			$truncate_pos = $result_pos;
		} elseif ($count_pos !== false) {
			// Only COUNT found
			$truncate_pos = $count_pos;
		}

		if ($truncate_pos !== false) {
			$content = substr($content, 0, $truncate_pos);
		}

		// Check for QRZ API specific error messages
		if (strpos($content, 'STATUS=FAIL') !== false || strpos($content, 'STATUS=AUTH') !== false) {
			// Extract reason if possible, otherwise use full content
			$reason = $content;
			if (preg_match('/REASON=([^&]+)/', $content, $matches)) {
				$reason = urldecode($matches[1]); // Decode URL encoded reason
			}
			$error_message = "QRZ API Error: " . $reason;
			log_message('error', $error_message . ' API Key used: ' . $qrz_api_key . ' Raw Response: ' . $content);
			return ['status' => 'error', 'message' => $error_message];
		}

		$content = html_entity_decode($content, ENT_QUOTES | ENT_HTML5, 'UTF-8');

		// Save the potentially valid content
		if (file_put_contents($file, $content) === false) {
			$error_message = "Failed to write downloaded QRZ data to temporary file: " . $file;
			log_message('error', $error_message);
			return ['status' => 'error', 'message' => $error_message];
		} else {
			// echo "Downloaded QRZ data to temporary file: " . $file;
		}

		// Proceed to load from the file
		ini_set('memory_limit', '-1');
		$result = $this->loadFromFile($file); // loadFromFile returns ['table_data' => ..., 'processed_count' => ...]

		return $result;
	}

/*
	|--------------------------------------------------------------------------
	| Function: loadFromFile
	|--------------------------------------------------------------------------
	|
	|	$filepath is the ADIF file
	|
	|	Internal function that takes the QRZ ADIF and imports into the log
	|
 */
	private function loadFromFile($filepath) {

		// Figure out how we should be marking QSLs confirmed via LoTW
		$config['qrz_rcvd_mark'] = 'Y';

		ini_set('memory_limit', '-1');
		set_time_limit(1800); // 30 minutes max execution time instead of unlimited

		$this->load->library('adif_parser');

		// Load the data from the file into the parser object
		$this->adif_parser->load_from_file($filepath); // <-- ADD THIS LINE

		// Now initialize the parser with the loaded data
		if (!$this->adif_parser->initialize()) { // Check return value of initialize
			 // Handle initialization error (e.g., log it, return error structure)
			 log_message('error', 'ADIF Parser initialization failed for file: ' . $filepath);
			 // Return an error structure consistent with mass_download_qsos
			 return ['status' => 'error', 'message' => 'ADIF Parser initialization failed. Check logs.'];
		}

		$tableheaders = "<table width=\"100%\">";
		$tableheaders .= "<tr class=\"titles\">";
		$tableheaders .= "<td>Station Callsign</td>";
		$tableheaders .= "<td>QSO Date</td>";
		$tableheaders .= "<td>Call</td>";
		$tableheaders .= "<td>Mode</td>";
		$tableheaders .= "<td>QRZ QSL Received</td>";
		$tableheaders .= "<td>QRZ Confirmed</td>";
		$tableheaders .= "</tr>";

		$table = "";
		$batch_data = [];
		$batch_size = 500; // Process 500 records at a time
		$record_count = 0; // Initialize record counter
		$max_records = 50000; // Safety limit to prevent runaway processing
		$start_time = time(); // Track processing time
		$max_processing_time = 1200; // 20 minutes max for processing
		
		while ($record = $this->adif_parser->get_record()) {
			$record_count++; // Increment counter for each record read
			
			// Safety checks to prevent runaway processing
			if ($record_count > $max_records) {
				log_message('error', 'QRZ download: Exceeded maximum record limit of ' . $max_records . ' records. Processing stopped.');
				break;
			}
			
			if ((time() - $start_time) > $max_processing_time) {
				log_message('error', 'QRZ download: Exceeded maximum processing time of ' . $max_processing_time . ' seconds. Processing stopped at record ' . $record_count . '.');
				break;
			}
			
			if ((!(isset($record['app_qrzlog_qsldate']))) || (!(isset($record['qso_date'])))) {
				continue;
			}
			$time_on = date('Y-m-d', strtotime($record['qso_date'])) ." ".date('H:i', strtotime($record['time_on']));
			$qsl_date = date('Y-m-d', strtotime($record['app_qrzlog_qsldate']));

			// If we have a positive match from LoTW, record it in the DB according to the user's preferences
			$qsl_rcvd = ''; // Default empty
			if (isset($record['app_qrzlog_status']) && $record['app_qrzlog_status'] == "C") {
				$qsl_rcvd = $config['qrz_rcvd_mark'];
			}

			$call = str_replace("_","/",$record['call']);
			$station_callsign = str_replace("_","/",$record['station_callsign']);
			$band = $record['band'] ?? ''; // Ensure band exists
			$mode = $record['mode'] ?? ''; // Ensure mode exists

			// Add record data to batch
			$batch_data[] = [
				'time_on' => $time_on,
				'call' => $call,
				'band' => $band,
				'mode' => $mode,
				'station_callsign' => $station_callsign,
				'qsl_date' => $qsl_date,
				'qsl_rcvd' => $qsl_rcvd
			];

			// If batch size reached, process it
			if (count($batch_data) >= $batch_size) {
				$table .= $this->logbook_model->process_qrz_batch($batch_data);
				$batch_data = []; // Reset batch
				
				// Log progress every 1000 records to help monitor long-running processes
				if ($record_count % 1000 == 0) {
					$elapsed_time = time() - $start_time;
					log_message('info', 'QRZ download progress: ' . $record_count . ' records processed in ' . $elapsed_time . ' seconds.');
				}
			}
		}

		// Process any remaining records in the last batch
		if (!empty($batch_data)) {
			$table .= $this->logbook_model->process_qrz_batch($batch_data);
		}

		// Log successful completion with statistics
		$processing_time = time() - $start_time;
		log_message('info', 'QRZ download completed successfully. Processed ' . $record_count . ' records in ' . $processing_time . ' seconds.');

		if ($table != "") {
			$data['tableheaders'] = $tableheaders;
			$data['table'] = $table;
		} else {
			$data['table'] = '';
		}

		unlink($filepath);
		// Return both table data and the count of processed records
		return ['table_data' => $data, 'processed_count' => $record_count];
	}
}
