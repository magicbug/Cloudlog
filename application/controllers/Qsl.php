<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
	Controller for QSL Cards
*/
function generateRandomHex($length = 10) {
	return substr(str_shuffle(str_repeat($x='0123456789abcdef', ceil($length/strlen($x)) )),1,$length);
}

class Qsl extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->lang->load('qslcard');
        $this->load->model('user_model');
        if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
    }

    // Default view when loading controller.
    public function index() {

		$this->load->model('qsl_model');
		$data['qslarray'] = $this->qsl_model->getQsoWithQslList();

		$this->load->library("file_manager");
		$data['show_get_size'] = $this->file_manager->is_support_get_size();
		if ($data['show_get_size']) {
			$data['storage_used'] = sizeFormat($this->file_manager->get_size());
		}

        // Render Page
        $data['page_title'] = "QSL Cards";

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

	/**
	 * @throws Exception
	 */
	public function delete() {
        $this->load->model('user_model');
        if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }

        $id = $this->input->post('id');

		/*
		  TODO: Transaction here only grantees the consistency of meta in db,
		      it can not grantee the real file status.
		*/
		$this->load->library('File_manager');
		$this->load->model('Qsl_model');

		try {
			$this->db->trans_begin();
			$file = $this->Qsl_model->getFileId($id)->row();
			$file_id = $file->file_id;
			$this->Qsl_model->deleteQsl($id);
			$ref_count = $this->Qsl_model->getQslFileReference($file_id);
			if ($ref_count == 0) {
				$this->file_manager->delete_file($file_id);
			}

			$this->db->trans_commit();
		} catch (Exception $e) {
			$this->db->trans_rollback();
			throw $e;
		}
    }

    public function uploadqsl() {
        $this->load->model('user_model');
        if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }

        $qsoid = $this->input->post('qsoid');

		$result = array();

        if (isset($_FILES['qslcardfront']) && $_FILES['qslcardfront']['name'] != "" && $_FILES['qslcardfront']['error'] == 0)
        {
            $result['front'] = $this->uploadQslCard($qsoid, 'qslcardfront');
        }

        if (isset($_FILES['qslcardback']) && $_FILES['qslcardback']['name'] != "" && $_FILES['qslcardback']['error'] == 0)
        {
            $result['back'] = $this->uploadQslCard($qsoid, 'qslcardback');
        }

        header("Content-type: application/json");
        echo json_encode(['status' => $result]);
    }

	function uploadQslCard($qsoid, $field)
	{
		$array = explode(".", $_FILES[$field]['name']);
		$ext = end($array);
		$filename = $qsoid . '_' . time() . '_' . generateRandomHex(4) . '.' . $ext;

		if (!in_array($ext, array('png', 'jpg', 'jpeg', 'gif')))
		{
			return array('error' => 'file format not allowed');
		}

		$this->load->library('File_manager');
		try {
			$upload_result = $this->file_manager->upload_file_from_field($field, $filename);
		} catch (Exception $e) {
			return array('error' => $e->getMessage());
		}

		$this->load->model('Qsl_model');
		$insertid = $this->Qsl_model->saveQsl($qsoid, $upload_result['file_id']);
		$result['status']  = 'Success';
		$result['insertid'] = $insertid;
		$result['file_id'] = $upload_result['file_id'];
		$result['url'] = $upload_result['url'];
		$result['filename'] = $upload_result['filename'];

		return $result;
	}

	function loadSearchForm() {
    	$data['file_id'] = $this->input->post('file_id');
		$this->load->view('qslcard/searchform', $data);
	}

	function searchQsos() {
		$this->load->model('Qsl_model');
		$callsign = $this->input->post('callsign');

		$data['results'] = $this->Qsl_model->searchQsos($callsign);
		$data['file_id'] = $this->input->post('file_id');
		$this->load->view('qslcard/searchresult', $data);
	}

	function addQsoToQsl() {
		$qsoid = $this->input->post('qsoid');
		$file_id = $this->input->post('file_id');

		$this->load->model('Qsl_model');
		$insertid = $this->Qsl_model->addQsotoQsl($qsoid, $file_id);
		header("Content-type: application/json");
		$result['status']  = 'Success';
		$result['insertid'] = $insertid;
		$result['file_id'] = $file_id;
		echo json_encode($result);
	}

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
