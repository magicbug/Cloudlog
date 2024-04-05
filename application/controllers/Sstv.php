<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
	Controller for SSTV Images
*/

class Sstv extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->lang->load('sstv');
        $this->load->model('user_model');
        if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
    }

    public function index() {
        $this->load->helper('storage');
        $folder_name = "assets/sstvimages";
        $data['storage_used'] = sizeFormat(folderSize($folder_name));

        // Render Page
        $data['page_title'] = "SSTV Images";

        $this->load->model('sstv_model');
        $data['sstvArray'] = $this->sstv_model->getQsoWithSstvImageList();

        $this->load->view('interface_assets/header', $data);
        $this->load->view('sstv/index');
        $this->load->view('interface_assets/footer');
    }

    public function uploadSSTV() {
        $this->load->model('user_model');
        if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }

        if (!file_exists('./assets/sstvimages')) {
            mkdir('./assets/sstvimages', 0755, true);
        }
        $qsoid = $this->input->post('qsoid');
        
        $results = array();
        if (isset($_FILES['sstvimages']) && $_FILES['sstvimages']['error'][0] == 0)
        {
            for($i=0; $i<count($_FILES['sstvimages']['name']); $i++) {
                $file = array(
                    'name' => $_FILES['sstvimages']['name'][$i],
                    'type' => $_FILES['sstvimages']['type'][$i],
                    'tmp_name' => $_FILES['sstvimages']['tmp_name'][$i],
                    'error' => $_FILES['sstvimages']['error'][$i],
                    'size' => $_FILES['sstvimages']['size'][$i]
                );
                $result = $this->uploadSSTVImage($qsoid, $file);
                array_push($results, $result);
            }
        }

        header("Content-type: application/json");
        echo json_encode($results);
    }

    function uploadSSTVImage($qsoid, $file) {
        $config['upload_path']          = './assets/sstvimages';
        $config['allowed_types']        = 'jpg|gif|png|jpeg|JPG|PNG|bmp';
        $array = explode(".", $file['name']);
        $ext = end($array);
        $config['file_name'] = $qsoid . '.sstv.' . '_' . time() . '.' . $ext;
    
        $this->load->library('upload', $config);
    
        $_FILES['sstvimage'] = $file;
        if ( ! $this->upload->do_upload('sstvimage')) {
            // Upload of SSTV image Failed
            $error = array('error' => $this->upload->display_errors());
    
            return $error;
        }
        else {
            // Load database queries
            $this->load->model('Sstv_model');

            //Upload of SSTV image was successful
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

    
    // Deletes SSTV Image
    public function delete() {
        $this->load->model('user_model');
        if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }

        $id = $this->input->post('id');
        $this->load->model('Sstv_model');

        $path = './assets/sstvimages/';
        $file = $this->Sstv_model->getSSTVFilename($id)->row();
        $filename = $file->filename;
        unlink($path.$filename);

        $this->Sstv_model->deleteSstv($id);
    }
}
