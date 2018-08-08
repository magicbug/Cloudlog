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

	public function dxcc_details(){
        $a = $this->input->get();
        $q = "";
        foreach ($a as $key => $value) {
        	$q .= $key."=".$value.("&#40;and&#41;");
        }
        $q = substr($q, 0, strlen($q)-13);

        $arguments["query"] = $q;
        $arguments["fields"] = '';
        $arguments["format"] = "json";
        $arguments["limit"] = '';
        $arguments["order"] = '';

        // print_r($arguments);
        // return;

		// Load the API and Logbook models
		$this->load->model('api_model');
		$this->load->model('logbook_model');

		// Call the parser within the API model to build the query
		$query = $this->api_model->select_parse($arguments);

		// Execute the query, and retrieve the results
		$data = $this->logbook_model->api_search_query($query);

		// Render Page
		$data['page_title'] = "Log View - DXCC";
		$data['filter'] = str_replace("&#40;and&#41;", ", ", $q);//implode(", ", array_keys($a));
		$this->load->view('layout/header', $data);
		$this->load->view('awards/dxcc/details');
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
	
	public function cq(){
        $this->load->model('cq');
        $zones = array();
        foreach($this->cq->get_zones() as $row){
            array_push($zones, intval($row->COL_CQZ));
        }
        $data['cqz'] = $zones;

        // Render page
        $data['page_title'] = "Awards - CQ Magazine";
		$this->load->view('layout/header', $data);
		$this->load->view('awards/cq/index');
		$this->load->view('layout/footer');
	}
}
