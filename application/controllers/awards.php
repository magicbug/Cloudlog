<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
	Handles Displaying of information for awards.
*/

class Awards extends CI_Controller {

	public function index()
	{
		echo "This is the index page";
	}
	
	/*
		Handles Displaying of WAB Squares worked.
	*/
	public function wab() {
	
		$this->load->model('wab');
		$data['wab_all'] = $this->wab->get_all();
	
		$data['page_title'] = "Awards - WAB";
		$this->load->view('layout/header', $data);
		$this->load->view('awards/wab/index');
		$this->load->view('layout/footer');
	}
}