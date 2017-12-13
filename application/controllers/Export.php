<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Export extends CI_Controller {


	public function index()
	{
		$this->load->model('user_model');
		if(!$this->user_model->authorize(99)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
	
		$data['page_title'] = "Data Export";

		$this->load->view('layout/header', $data);
		$this->load->view('export/index');
		$this->load->view('layout/footer');
	
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */