<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
	Handles Displaying of information for awards.
	
	These are taken from comments fields or ADIF fields 
*/

class Awards extends CI_Controller {

	public function index()
	{
		echo "This is the index page";
	}
	
	/*
		Handles Displaying of WAB Squares worked.
		Comment field - WAB:#
	*/
	public function wab() {
	
		// Grab all worked WABs
		$this->load->model('wab');
		$data['wab_all'] = $this->wab->get_all();
	
		// Render Page
		$data['page_title'] = "Awards - WAB";
		$this->load->view('layout/header', $data);
		$this->load->view('awards/wab/index');
		$this->load->view('layout/footer');
	}
	
	/*
		Handles showing worked SOTAs
		Comment field - SOTA:#
	*/
	public function sota() {
	
		// Grab all worked sota stations
		$this->load->model('sota');
		$data['sota_all'] = $this->sota->get_all();
	
		// Render page
		$data['page_title'] = "Awards - SOTA";
		$this->load->view('layout/header', $data);
		$this->load->view('awards/sota/index');
		$this->load->view('layout/footer');
	}
	
}