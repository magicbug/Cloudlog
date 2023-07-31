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

        $CI =& get_instance();
        $CI->load->library('AdifHelper');

        if ($data['qsos']) {
            foreach ($data['qsos']->result() as $qso) {
                $adif = $CI->adifhelper->getAdifLine($qso);

                if ($qso->COL_QRZCOM_QSO_UPLOAD_STATUS == 'M') {
                    $result = $this->logbook_model->push_qso_to_qrz($qrz_api_key, $adif, true);
                } else {
                    $result = $this->logbook_model->push_qso_to_qrz($qrz_api_key, $adif);
                }

		if ( ($result['status'] == 'OK') || ( ($result['status'] == 'error') && ($result['message'] == 'STATUS=FAIL&REASON=Unable to add QSO to database: duplicate&EXTENDED=')) ){
                    $this->markqso($qso->COL_PRIMARY_KEY);
                    $i++;
		} elseif ( ($result['status']=='error') && (substr($result['message'],0,11)  == 'STATUS=AUTH')) {
                    log_message('error', 'QRZ upload failed for qso: Call: ' . $qso->COL_CALL . ' Band: ' . $qso->COL_BAND . ' Mode: ' . $qso->COL_MODE . ' Time: ' . $qso->COL_TIME_ON);
                    log_message('error', 'QRZ upload failed with the following message: ' .$result['message']);
                    log_message('error', 'QRZ upload stopped for Station_ID: ' .$station_id);
                    $errormessages[] = $result['message'] . ' Call: ' . $qso->COL_CALL . ' Band: ' . $qso->COL_BAND . ' Mode: ' . $qso->COL_MODE . ' Time: ' . $qso->COL_TIME_ON;
		    break; /* If key is invalid, immediate stop syncing for more QSOs of this station */
                } else {
                    log_message('error', 'QRZ upload failed for qso: Call: ' . $qso->COL_CALL . ' Band: ' . $qso->COL_BAND . ' Mode: ' . $qso->COL_MODE . ' Time: ' . $qso->COL_TIME_ON);
                    log_message('error', 'QRZ upload failed with the following message: ' .$result['message']);
                    $errormessages[] = $result['message'] . ' Call: ' . $qso->COL_CALL . ' Band: ' . $qso->COL_BAND . ' Mode: ' . $qso->COL_MODE . ' Time: ' . $qso->COL_TIME_ON;
                }
            }
            $result['status'] = 'OK';
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
}
