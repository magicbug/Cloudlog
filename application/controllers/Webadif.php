<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*	Controller to interact with the webADIF API */
class Webadif extends CI_Controller {

    /*
     * Upload QSO to webADIF
     * When called from the url cloudlog/webadif/upload, the function loops through all station_id's with a webADIF
     * api key defined.
     * All QSOs not previously uploaded, will then be uploaded, one at a time
     */
	public function upload()
	{
		$this->setOptions();

		$this->load->model('logbook_model');

		$station_ids = $this->logbook_model->get_station_id_with_webadif_api();

		if ($station_ids) {
			foreach ($station_ids as $station) {
				$webadif_api_key = $station->webadifapikey;
				$webadif_api_url = $station->webadifapiurl;
				if ($this->mass_upload_qsos($station->station_id, $webadif_api_key, $webadif_api_url, true)) {	// When called via cron it is trusted
					echo "QSOs have been uploaded to QO-100 Dx Club.";
					log_message('info', 'QSOs have been uploaded to QO-100 Dx Club.');
				} else {
					echo "No QSOs found for upload.";
					log_message('info', 'No QSOs found for upload.');
				}
			}
		} else {
			echo "No station profiles with a QO-100 Dx Club API Key found.";
			log_message('error', "No station profiles with a QO-100 Dx Club API Key found.");
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
     * Function gets all QSOs from given station_id, that are not previously uploaded to webADIF consumer.
     * Adif is build for each qso, and then uploaded, one at a time
     */
    function mass_upload_qsos($station_id, $webadif_api_key, $webadif_api_url, $trusted = false) {
        $i = 0;
        $data['qsos'] = $this->logbook_model->get_webadif_qsos($station_id, null, null, $trusted);
        $errormessages=array();

        $CI =& get_instance();
        $CI->load->library('AdifHelper');

		if ($data['qsos']) {
			foreach ($data['qsos']->result() as $qso) {
				$adif = $CI->adifhelper->getAdifLine($qso);
				$result = $this->logbook_model->push_qso_to_webadif($webadif_api_url, $webadif_api_key, $adif);

				if ($result) {
					$this->logbook_model->mark_webadif_qsos_sent([$qso->COL_PRIMARY_KEY]);
					$i++;
				} else {
					$errorMessage = 'QO-100 Dx Club upload failed for qso: Call: ' . $qso->COL_CALL . ' Band: ' . $qso->COL_BAND . ' Mode: ' . $qso->COL_MODE . ' Time: ' . $qso->COL_TIME_ON;
					log_message('error', $errorMessage);
					$errormessages[] = $errorMessage;
				}
			}
			$result=[];
			$result['status'] = 'OK';
			$result['count'] = $i;
			$result['errormessages'] = $errormessages;
			return $result;
		} else {
			$result=[];
			$result['status'] = 'Error';
			$result['count'] = $i;
			$result['errormessages'] = $errormessages;
			return $result;
		}
    }

    /*
     * Used for displaying the uid for manually selecting log for upload to webADIF consumer
     */
    public function export() {
        $this->load->model('stations');

        $data['page_title'] = "QO-100 Dx Club Upload";

	$data['station_profiles'] = $this->stations->stations_with_webadif_api_key();
        $data['station_profile'] = $this->stations->stations_with_webadif_api_key();

        $this->load->view('interface_assets/header', $data);
        $this->load->view('webadif/export');
        $this->load->view('interface_assets/footer');
    }

    /*
     * Used for ajax-function when selecting log for upload to webADIF consumer
     */
    public function upload_station() {
	    $this->setOptions();
	    $postData = $this->input->post();
	    $this->load->model('stations');
	    if (!$this->stations->check_station_is_accessible($postData['station_id'])) {
		    return;
	    }

	    $this->load->model('logbook_model');
	    $result = $this->logbook_model->exists_webadif_api_key($postData['station_id']);
	    $webadif_api_key = $result->webadifapikey;
	    $webadif_api_url = $result->webadifapiurl;
	    header('Content-type: application/json');
	    $result = $this->mass_upload_qsos($postData['station_id'], $webadif_api_key, $webadif_api_url);
	    if ($result['status'] == 'OK') {
		    $stationinfo = $this->stations->stations_with_webadif_api_key();
		    $info = $stationinfo->result();

		    $data['status'] = 'OK';
		    $data['info'] = $info;
		    $data['infomessage'] = $result['count'] . " QSOs are now uploaded to QO-100 Dx Club";
		    $data['errormessages'] = $result['errormessages'];
		    echo json_encode($data);
	    } else {
		    $data['status'] = 'Error';
		    $data['info'] = 'Error: No QSOs found to upload.';
		    $data['errormessages'] = $result['errormessages'];
		    echo json_encode($data);
	    }
    }

	public function mark_webadif() {
		// Set memory limit to unlimited to allow heavy usage
		ini_set('memory_limit', '-1');
		$data['page_title'] = "QO-100 Dx Club Upload";

		$station_id = $this->security->xss_clean($this->input->post('station_profile'));
		$from = $this->security->xss_clean($this->input->post('from'));
		$to = $this->security->xss_clean($this->input->post('to'));

		$this->load->model('logbook_model');

		$data['qsos'] = $this->logbook_model->get_webadif_qsos(
			$station_id,
			$from,
			$to
		);

		if ($data['qsos']!==null) {
			$qsoIDs=[];
			foreach ($data['qsos']->result() as $qso) {
				$qsoIDs[]=$qso->COL_PRIMARY_KEY;
			}
			$batchSize = 500;
			while ($qsoIDs !== []) {
				$slice = array_slice($qsoIDs, 0, $batchSize);
				$qsoIDs = array_slice($qsoIDs, $batchSize);
				$this->logbook_model->mark_webadif_qsos_sent($slice);
			}
		}

		$this->load->view('interface_assets/header', $data);
		$this->load->view('webadif/mark_webadif', $data);
		$this->load->view('interface_assets/footer');
	}
}
