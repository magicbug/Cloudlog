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
        }

        if (isset($_FILES['qslcardback']) && $_FILES['qslcardback']['name'] != "" && $_FILES['qslcardback']['error'] == 0)
        {
            $result['back'] = $this->uploadQslCardBack($qsoid);
        }

        header("Content-type: application/json");
        echo json_encode(['status' => $result]);
    }

    function uploadQslCardFront($qsoid) {
        $config['upload_path']          = './assets/qslcard';
        $config['allowed_types']        = 'jpg|gif|png';
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
        $config['allowed_types']        = 'jpg|gif|png';
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

}

// Functions for storage, these need shifted to a libary to use across Cloudlog
function folderSize($dir){
    $count_size = 0;
    $count = 0;
    $dir_array = scandir($dir);
      foreach($dir_array as $key=>$filename){
        if($filename!=".." && $filename!="."){
           if(is_dir($dir."/".$filename)){
              $new_foldersize = foldersize($dir."/".$filename);
              $count_size = $count_size+ $new_foldersize;
            }else if(is_file($dir."/".$filename)){
              $count_size = $count_size + filesize($dir."/".$filename);
              $count++;
            }
       }
     }
    return $count_size;
}

function sizeFormat($bytes){
    $kb = 1024;
    $mb = $kb * 1024;
    $gb = $mb * 1024;
    $tb = $gb * 1024;

    if (($bytes >= 0) && ($bytes < $kb)) {
    return $bytes . ' B';

    } elseif (($bytes >= $kb) && ($bytes < $mb)) {
    return ceil($bytes / $kb) . ' KB';

    } elseif (($bytes >= $mb) && ($bytes < $gb)) {
    return ceil($bytes / $mb) . ' MB';

    } elseif (($bytes >= $gb) && ($bytes < $tb)) {
    return ceil($bytes / $gb) . ' GB';

    } elseif ($bytes >= $tb) {
    return ceil($bytes / $tb) . ' TB';
    } else {
    return $bytes . ' B';
    }
}
