<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
	Controller to interact with the QRZ.com API
*/

class Qrz extends CI_Controller {

    // Show frontend if there is one
    public function index() {
        $this->config->load('config');
    }

    // Upload QSO to QRZ.com
    public function upload() {

        $this->config->load('config');
        ini_set('memory_limit', '-1');
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        $this->load->model('logbook_model');

        $station_ids = $this->logbook_model->get_station_id_with_qrz_api();

        if ($station_ids) {
            foreach ($station_ids as $station_id) {
                $qrz_api_key = $this->logbook_model->exists_qrz_api_key($station_id);
                $this->mass_upload_qsos($station_id, $qrz_api_key);
            }
        } else {
            echo "No station_id's with a QRZ API Key found";
            log_message('info', "No station_id's with a QRZ API Key found");
        }

    }

    function mass_upload_qsos($station_id, $qrz_api_key) {
        $data['qsos'] = $this->logbook_model->get_qrz_qsos($station_id);

        if ($data['qsos']) {
            foreach ($data['qsos'] as $qso) {
                $adif = $this->logbook_model->create_adif_from_data($qso);
                $result = $this->logbook_model->push_qso_to_qrz($qrz_api_key, $adif);
                if ($result) {
                    $this->markqso($qso['COL_PRIMARY_KEY']);
                }
            }
        echo "QSOs has been uploaded to QRZ.com.";
        log_message('info', 'QSOs has been uploaded to QRZ.com.');
        } else {
            echo "No QSOs found for upload.";
            log_message('info', 'No QSOs found for upload.');
        }
    }

    function markqso($primarykey) {
        $this->logbook_model->mark_qrz_qsos_sent($primarykey);
    }
}
