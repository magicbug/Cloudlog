<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
	Controller to push QSOs from Cloudlog to a QRZCALL.EU logbook.

	Uses the QRZ-compatible logbook API endpoint on QRZCALL.EU so no extra
	server-side changes are required. The user's Personal Access Token (PAT,
	format pat_xxxxx) is stored per station-profile in the qrzcallapikey
	column and sent as the KEY parameter.

	Route:  GET /index.php/qrzcall_upload/upload
	Trigger: cron job, or manually from the Cloudlog admin menu.
*/

class Qrzcall_upload extends CI_Controller {

	public function index() {
		$this->config->load('config');
	}

	/*
	 * Upload all pending QSOs to QRZCALL.EU.
	 * Loops through all station profiles that have a PAT configured.
	 * QSOs where COL_QRZCALL_QSO_UPLOAD_STATUS is NULL or 'N' are uploaded.
	 */
	public function upload() {
		$this->setOptions();
		$this->load->model('logbook_model');

		$station_ids = $this->logbook_model->get_station_id_with_qrzcall_api();

		if ($station_ids) {
			foreach ($station_ids as $station) {
				$pat = $station->qrzcallapikey;
				$result = $this->mass_upload_qsos($station->station_id, $pat, true);
				if ($result['count'] > 0) {
					echo "Uploaded " . $result['count'] . " QSO(s) to QRZCALL.EU for station_id: " . $station->station_id . "\n";
					log_message('info', 'QRZCALL.EU: Uploaded ' . $result['count'] . ' QSO(s) for station_id: ' . $station->station_id);
				} else {
					echo "No QSOs pending for station_id: " . $station->station_id . "\n";
				}
				if (!empty($result['errormessages'])) {
					foreach ($result['errormessages'] as $msg) {
						echo "Error: " . $msg . "\n";
					}
				}
			}
		} else {
			echo "No station profiles with a QRZCALL.EU API token found.\n";
			log_message('info', 'QRZCALL.EU: No station profiles with a PAT found.');
		}
	}

	private function setOptions() {
		$this->config->load('config');
		ini_set('memory_limit', '-1');
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
	}

	/*
	 * Batch-upload all pending QSOs for the given station to QRZCALL.EU.
	 * Fetches QSOs in batches of 1000 to prevent memory exhaustion.
	 * Returns array with 'count' (number uploaded) and 'errormessages'.
	 */
	function mass_upload_qsos($station_id, $pat, $trusted = false) {
		$i = 0;
		$errormessages     = [];
		$successful_uploads = [];
		$batch_size         = 1000;
		$update_batch_size  = 100;
		$offset             = 0;
		$has_more_qsos      = true;

		$this->load->library('AdifHelper');

		while ($has_more_qsos) {
			$data['qsos'] = $this->logbook_model->get_qrzcall_qsos($station_id, $trusted, $batch_size, $offset);

			if (!$data['qsos'] || $data['qsos']->num_rows() == 0) {
				$has_more_qsos = false;
				break;
			}

			foreach ($data['qsos']->result() as $qso) {
				$adif = $this->adifhelper->getAdifLine($qso);

				// A QSO marked 'M' was edited after it was first uploaded —
				// send it with OPTION=REPLACE so QRZCALL.EU updates the
				// existing record instead of rejecting it as a duplicate.
				if ($qso->COL_QRZCALL_QSO_UPLOAD_STATUS == 'M') {
					$result = $this->logbook_model->push_qso_to_qrzcall($pat, $adif, true);
				} else {
					$result = $this->logbook_model->push_qso_to_qrzcall($pat, $adif);
				}

				if ($result['status'] == 'OK') {
					$successful_uploads[] = $qso->COL_PRIMARY_KEY;
					$i++;

					// Flush batch updates to DB for memory efficiency
					if (count($successful_uploads) >= $update_batch_size) {
						$this->logbook_model->mark_qrzcall_qsos_sent_batch($successful_uploads);
						$successful_uploads = [];
					}
				} elseif (stristr($result['message'] ?? '', 'RESULT=AUTH')) {
					// Invalid or revoked PAT — stop immediately to avoid hammering the API
					log_message('error', 'QRZCALL.EU upload stopped for station_id ' . $station_id . ': auth failure — ' . $result['message']);
					$errormessages[] = 'Auth failure (invalid/revoked PAT): ' . $result['message'];
					break 2;
				} else {
					log_message('error', 'QRZCALL.EU upload failed for QSO: Call: ' . $qso->COL_CALL
						. ' Band: ' . $qso->COL_BAND . ' Mode: ' . $qso->COL_MODE
						. ' Time: ' . $qso->COL_TIME_ON . ' — ' . ($result['message'] ?? ''));
					$errormessages[] = ($result['message'] ?? 'unknown error')
						. ' Call: ' . $qso->COL_CALL . ' Band: ' . $qso->COL_BAND
						. ' Mode: ' . $qso->COL_MODE . ' Time: ' . $qso->COL_TIME_ON;
				}
			}

			if ($data['qsos']->num_rows() < $batch_size) {
				$has_more_qsos = false;
			} else {
				$offset += $batch_size;
				log_message('info', 'QRZCALL.EU batch: processed ' . $offset . ' QSOs so far for station_id: ' . $station_id);
			}
		}

		// Flush remaining successful uploads
		if (!empty($successful_uploads)) {
			$this->logbook_model->mark_qrzcall_qsos_sent_batch($successful_uploads);
			log_message('info', 'QRZCALL.EU: marked final batch of ' . count($successful_uploads) . ' QSOs as uploaded for station_id: ' . $station_id);
		}

		if ($i > 0) {
			log_message('info', 'QRZCALL.EU upload completed: ' . $i . ' QSO(s) uploaded for station_id: ' . $station_id);
		}

		return [
			'count'         => $i,
			'errormessages' => $errormessages,
		];
	}

}
