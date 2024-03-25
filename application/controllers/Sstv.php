<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
	Controller for SSTV Images
*/

class Sstv extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->lang->load('qslcard');
        $this->load->model('user_model');
        if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
    }

    public function uploadSSTV() {
        $this->load->model('user_model');
        if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }

        if (!file_exists('./assets/sstvimages')) {
            mkdir('./assets/sstvimages', 0755, true);
        }
        $qsoid = $this->input->post('qsoid');

        error_log('qsoid: ' . $qsoid);

        if (isset($_FILES['sstvimages']) && $_FILES['sstvimages']['name'] != "" && $_FILES['sstvimages']['error'] == 0)
        {
            error_log("got file: ". $_FILES['sstvimages']['name']);
            $result['front'] = $this->uploadSSTVImage($qsoid);
        } else {
            error_log('hit the else!!');
            $result['front']['status'] = '';
        }


        header("Content-type: application/json");
        echo json_encode(['status' => $result]);
    }

    function uploadSSTVImage($qsoid) {
        $config['upload_path']          = './assets/sstvimages';
        $config['allowed_types']        = 'jpg|gif|png|jpeg|JPG|PNG';
        $array = explode(".", $_FILES['sstvimages']['name']);
        $ext = end($array);
        $config['file_name'] = $qsoid . '.sstv.' . '_' . time() . '.' . $ext;
        error_log('config filename' . $config['file_name']);

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('sstvimages')) {
            // Upload of QSL card Failed
            $error = array('error' => $this->upload->display_errors());

            return $error;
        }
        else {
            // Load database queries
            $this->load->model('Sstv_model');

            //Upload of QSL card was successful
            $data = $this->upload->data();

            // Now we need to insert info into database about file
            $filename = $data['file_name'];
            $insertid = $this->Sstv_model->saveSstvImages($qsoid, $filename);

            $result['status']  = 'Success';
            $result['insertid'] = $insertid;
            $result['filename'] = $filename;
            return $result;
        }
    }
}
