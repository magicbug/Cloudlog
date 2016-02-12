<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
	Handles Displaying of information for awards.
	
	These are taken from comments fields or ADIF fields 
*/

class Awards extends CI_Controller {

	public function index()
	{
		// Render Page
		$data['page_title'] = "Awards";
		$this->load->view('layout/header', $data);
		$this->load->view('awards/index');
		$this->load->view('layout/footer');
	}
	
	public function dxcc ()
	{
		//echo "Needs Developed";
		$this->load->model('dxcc');
		$data['dxcc'] = $this->dxcc->show_stats();

		// Render Page
		$data['page_title'] = "Awards - DXCC";
		$this->load->view('layout/header', $data);
		$this->load->view('awards/dxcc/index');
		$this->load->view('layout/footer');

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
	
	/*
		Handles showing worked WACRAL members (wacral.org)
		Comment field - WACRAL:#
	*/
	public function wacral() {
	
		// Grab all worked wacral members
		$this->load->model('wacral');
		$data['wacral_all'] = $this->wacral->get_all();
	
		// Render page
		$data['page_title'] = "Awards - WACRAL Members";
		$this->load->view('layout/header', $data);
		$this->load->view('awards/wacral/index');
		$this->load->view('layout/footer');
	}
	
}
