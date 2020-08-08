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
            foreach ($station_ids as $station_id) {
                $qrz_api_key = $this->logbook_model->exists_qrz_api_key($station_id);
                if($this->mass_upload_qsos($station_id, $qrz_api_key)) {
                    echo "QSOs has been uploaded to QRZ.com.";
                    log_message('info', 'QSOs has been uploaded to QRZ.com.');
                } else{
                    echo "No QSOs found for upload.";
                    log_message('info', 'No QSOs found for upload.');
                }
            }
        } else {
            echo "No station_id's with a QRZ API Key found";
            log_message('error', "No station_id's with a QRZ API Key found");
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
    function mass_upload_qsos($station_id, $qrz_api_key) {
        $i = 0;
        $data['qsos'] = $this->logbook_model->get_qrz_qsos($station_id);

        if ($data['qsos']) {
            foreach ($data['qsos'] as $qso) {
                $adif = $this->logbook_model->create_adif_from_data($qso);

                if ($qso['COL_QRZCOM_QSO_UPLOAD_STATUS'] == 'M') {
                    $result = $this->logbook_model->push_qso_to_qrz($qrz_api_key, $adif, true);
                } else {
                    $result = $this->logbook_model->push_qso_to_qrz($qrz_api_key, $adif);
                }

                if ($result['status'] == 'OK') {
                    $this->markqso($qso['COL_PRIMARY_KEY']);
                    $i++;
                } else {
                    log_message('error', 'QRZ upload failed for qso: ' .$adif);
                    log_message('error', 'QRZ upload failed with the following message: ' .$result['message']);
                }
            }
        return $i;
        } else {
            return $i;
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

        $data['page_title'] = "QRZ.com Export";

        $data['station_profile'] = $this->stations->stations_with_qrz_api_key();
        $active_station_id = $this->stations->find_active();
        $station_profile = $this->stations->profile($active_station_id);

        $data['active_station_info'] = $station_profile->row();

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
        $qrz_api_key = $this->logbook_model->exists_qrz_api_key($postData['station_id']);

        header('Content-type: application/json');
        if ($i = $this->mass_upload_qsos($postData['station_id'], $qrz_api_key)) {
            $stationinfo = $this->stations->stations_with_qrz_api_key();
            $info = $stationinfo->result();

            $data['status'] = 'OK';
            $data['info'] = $info;
            $data['infomessage'] = $i . " QSOs are now uploaded to QRZ.com";
            echo json_encode($data);
        } else {
            $data['status'] = 'Error';
            $data['info'] = 'Error, no QSOs to upload found';
            echo json_encode($data);
        }
    }
}