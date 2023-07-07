<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
	Controller to interact with the hrdlog.net API
*/

class Hrdlog extends CI_Controller {

    /*
     * Upload QSO to hrdlog.net
     * When called from the url cloudlog/hrdlog/upload, the function loops through all station_id's with a hrdlog code defined.
     * All QSOs not previously uploaded, will then be uploaded, one at a time
     */
    public function upload() {
        $this->setOptions();

        $this->load->model('logbook_model');

        $station_ids = $this->logbook_model->get_station_id_with_hrdlog_code();

        if ($station_ids) {
            foreach ($station_ids as $station) {
                $hrdlog_code = $station->hrdlog_code;
                if($this->mass_upload_qsos($station->station_id, $hrdlog_code)) {
                    echo "QSOs have been uploaded to hrdlog.net.";
                    log_message('info', 'QSOs have been uploaded to hrdlog.net.');
                } else{
                    echo "No QSOs found for upload.";
                    log_message('info', 'No QSOs found for upload.');
                }
            }
        } else {
            echo "No station profiles with a hrdlog Code found.";
            log_message('error', "No station profiles with a hrdlog Code found.");
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
     * Function gets all QSOs from given station_id, that are not previously uploaded to hrdlog.
     * Adif is build for each qso, and then uploaded, one at a time
     */
    function mass_upload_qsos($station_id, $hrdlog_code) {
        $i = 0;
        $data['qsos'] = $this->logbook_model->get_hrdlog_qsos($station_id);
        $errormessages=array();

        $CI =& get_instance();
        $CI->load->library('AdifHelper');

        if ($data['qsos']) {
            foreach ($data['qsos']->result() as $qso) {
                $adif = $CI->adifhelper->getAdifLine($qso);

                if ($qso->COL_HRDLOG_QSO_UPLOAD_STATUS == 'M') {
                    $result = $this->logbook_model->push_qso_to_hrdlog($hrdlog_code, $qso->COL_STATION_CALLSIGN,$adif, true);
                } else {
                    $result = $this->logbook_model->push_qso_to_hrdlog($hrdlog_code, $qso->COL_STATION_CALLSIGN,$adif);
                }

		if ( ($result['status'] == 'OK') || ( ($result['status'] == 'error') || ($result['status'] == 'duplicate')) ){
                    $this->markqso($qso->COL_PRIMARY_KEY);
                    $i++;
		} elseif ((substr($result['status'],0,11)  == 'auth_error')) {
                    log_message('error', 'hrdlog upload failed for qso: Call: ' . $qso->COL_CALL . ' Band: ' . $qso->COL_BAND . ' Mode: ' . $qso->COL_MODE . ' Time: ' . $qso->COL_TIME_ON);
                    log_message('error', 'hrdlog upload failed with the following message: ' .$result['message']);
                    log_message('error', 'hrdlog upload stopped for Station_ID: ' .$station_id);
                    $errormessages[] = $result['message'] . 'Invalid HRDLog-Code, stopped at Call: ' . $qso->COL_CALL . ' Band: ' . $qso->COL_BAND . ' Mode: ' . $qso->COL_MODE . ' Time: ' . $qso->COL_TIME_ON;
		    break; /* If key is invalid, immediate stop syncing for more QSOs of this station */
                } else {
                    log_message('error', 'hrdlog upload failed for qso: Call: ' . $qso->COL_CALL . ' Band: ' . $qso->COL_BAND . ' Mode: ' . $qso->COL_MODE . ' Time: ' . $qso->COL_TIME_ON);
                    log_message('error', 'hrdlog upload failed with the following message: ' .$result['message']);
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
     * Function marks QSO with given primarykey as uploaded to hrdlog
     */
    function markqso($primarykey) {
        $this->logbook_model->mark_hrdlog_qsos_sent($primarykey);
    }

    /*
     * Used for displaying the uid for manually selecting log for upload to hrdlog
     */
    public function export() {
        $this->load->model('stations');

        $data['page_title'] = "HRDlog.net Logbook";

		$data['station_profiles'] = $this->stations->all_of_user();
        $data['station_profile'] = $this->stations->stations_with_hrdlog_code();

        $this->load->view('interface_assets/header', $data);
        $this->load->view('hrdlog/export');
        $this->load->view('interface_assets/footer');
    }

    /*
     * Used for ajax-function when selecting log for upload to hrdlog
     */
    public function upload_station() {
        $this->setOptions();
        $this->load->model('stations');

        $postData = $this->input->post();

        $this->load->model('logbook_model');
        $result = $this->logbook_model->exists_hrdlog_code($postData['station_id']);
        $hrdlog_code = $result->hrdlog_code;
        header('Content-type: application/json');
        $result = $this->mass_upload_qsos($postData['station_id'], $hrdlog_code);
        if ($result['status'] == 'OK') {
            $stationinfo = $this->stations->stations_with_hrdlog_code();
            $info = $stationinfo->result();

            $data['status'] = 'OK';
            $data['info'] = $info;
            $data['infomessage'] = $result['count'] . " QSOs are now uploaded to hrdlog";
            $data['errormessages'] = $result['errormessages'];
            echo json_encode($data);
        } else {
            $data['status'] = 'Error';
            $data['info'] = 'Error: No QSOs found to upload.';
            $data['errormessages'] = $result['errormessages'];
            echo json_encode($data);
        }
    }

	public function mark_hrdlog() {
		// Set memory limit to unlimited to allow heavy usage
		ini_set('memory_limit', '-1');

		$station_id = $this->security->xss_clean($this->input->post('station_profile'));

		$this->load->model('adif_data');

		$data['qsos'] = $this->adif_data->export_custom($this->input->post('from'), $this->input->post('to'), $station_id);

		$this->load->model('logbook_model');

		foreach ($data['qsos']->result() as $qso)
		{
			$this->logbook_model->mark_hrdlog_qsos_sent($qso->COL_PRIMARY_KEY);
		}

		$this->load->view('interface_assets/header', $data);
		$this->load->view('hrdlog/mark_hrdlog', $data);
		$this->load->view('interface_assets/footer');
	}
}
