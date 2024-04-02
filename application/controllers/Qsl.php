<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
	Controller for QSL Cards
*/

class Qsl extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->lang->load('qslcard');
        $this->load->model('user_model');
        if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
    }

    // Default view when loading controller.
    public function index() {
        $this->load->helper('storage'); // Load the storage helper
        $folder_name = "assets/qslcard";
        $data['storage_used'] = sizeFormat(folderSize($folder_name));

        // Render Page
        $data['page_title'] = "QSL Cards";

        $this->load->model('qsl_model');
        $data['qslarray'] = $this->qsl_model->getQsoWithQslList();

        $this->load->view('interface_assets/header', $data);
        $this->load->view('qslcard/index');
        $this->load->view('interface_assets/footer');
    }

    public function upload() {
        // Render Page
        $data['page_title'] = "Upload QSL Cards";
        $this->load->view('interface_assets/header', $data);
        $this->load->view('qslcard/upload');
        $this->load->view('interface_assets/footer');
    }

    // Deletes QSL Card
    public function delete() {
        $this->load->model('user_model');
        if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }

        $id = $this->input->post('id');
        $this->load->model('Qsl_model');

        $path = './assets/qslcard/';
        $file = $this->Qsl_model->getFilename($id)->row();
        $filename = $file->filename;
        unlink($path.$filename);

        $this->Qsl_model->deleteQsl($id);
    }

    public function uploadqsl() {
        $this->load->model('user_model');
        if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }

        if (!file_exists('./assets/qslcard')) {
            mkdir('./assets/qslcard', 0755, true);
        }
        $qsoid = $this->input->post('qsoid');

        if (isset($_FILES['qslcardfront']) && $_FILES['qslcardfront']['name'] != "" && $_FILES['qslcardfront']['error'] == 0)
        {
            $result['front'] = $this->uploadQslCardFront($qsoid);
        } else {
            $result['front']['status'] = '';
        }

        if (isset($_FILES['qslcardback']) && $_FILES['qslcardback']['name'] != "" && $_FILES['qslcardback']['error'] == 0)
        {
            $result['back'] = $this->uploadQslCardBack($qsoid);
        } else {
            $result['back']['status'] = '';
        }

        header("Content-type: application/json");
        echo json_encode(['status' => $result]);
    }

    function uploadQslCardFront($qsoid) {
        $config['upload_path']          = './assets/qslcard';
        $config['allowed_types']        = 'jpg|gif|png|jpeg|JPG|PNG';
        $array = explode(".", $_FILES['qslcardfront']['name']);
        $ext = end($array);
        $config['file_name'] = $qsoid . '_' . time() . '.' . $ext;

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('qslcardfront')) {
            // Upload of QSL card Failed
            $error = array('error' => $this->upload->display_errors());

            return $error;
        }
        else {
            // Load database queries
            $this->load->model('Qsl_model');

            //Upload of QSL card was successful
            $data = $this->upload->data();

            // Now we need to insert info into database about file
            $filename = $data['file_name'];
            $insertid = $this->Qsl_model->saveQsl($qsoid, $filename);

            $result['status']  = 'Success';
            $result['insertid'] = $insertid;
            $result['filename'] = $filename;
            return $result;
        }
    }

    function uploadQslCardBack($qsoid) {
        $config['upload_path']          = './assets/qslcard';
        $config['allowed_types']        = 'jpg|gif|png|jpeg|JPG|PNG';
        $array = explode(".", $_FILES['qslcardback']['name']);
        $ext = end($array);
        $config['file_name'] = $qsoid . '_' . time() . '.' . $ext;

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('qslcardback')) {
            // Upload of QSL card Failed
            $error = array('error' => $this->upload->display_errors());

            return $error;
        }
        else {
            // Load database queries
            $this->load->model('Qsl_model');

            //Upload of QSL card was successful
            $data = $this->upload->data();

            // Now we need to insert info into database about file
            $filename = $data['file_name'];
            $insertid = $this->Qsl_model->saveQsl($qsoid, $filename);

            $result['status']  = 'Success';
            $result['insertid'] = $insertid;
            $result['filename'] = $filename;
            return $result;
        }
    }

	function loadSearchForm() {
    	$data['filename'] = $this->input->post('filename');
		$this->load->view('qslcard/searchform', $data);
	}

	function searchQsos() {
		$this->load->model('Qsl_model');
		$callsign = $this->input->post('callsign');

		$data['results'] = $this->Qsl_model->searchQsos($callsign);
		$data['filename'] = $this->input->post('filename');
		$this->load->view('qslcard/searchresult', $data);
	}

	function addQsoToQsl() {
		$qsoid = $this->input->post('qsoid');
		$filename = $this->input->post('filename');

		$this->load->model('Qsl_model');
		$insertid = $this->Qsl_model->addQsotoQsl($qsoid, $filename);
		header("Content-type: application/json");
		$result['status']  = 'Success';
		$result['insertid'] = $insertid;
		$result['filename'] = $filename;
		echo json_encode($result);
	}

    function viewQsl() {
        $cleanid = $this->security->xss_clean($this->input->post('id'));
        $this->load->model('Qsl_model');
        $data['qslimages'] = $this->Qsl_model->getQslForQsoId($cleanid);
        $this->load->view('qslcard/qslcarousel', $data);
    }

}