<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
	
	Activated_grids Controller
		
*/

class Activated_grids extends CI_Controller {

    /*
    *   TODO List
    *   - Create index page
    *   - Band page provide a band dropdown list
    *   - Find somewhere in the main menu to add a button to it
    */


	public function index() {
		// if there are no satellite QSOs redirect to band selection directly
		$this->load->model('logbook_model');
		$this->load->model('bands');
		$total_sat = $this->logbook_model->total_sat();
		if ($total_sat->num_rows() == 0) {
			redirect('activated_grids/band/2m');
			return;
		}

		$data['page_title'] = "Activated Gridsquare Map";
		$data['sat_active'] = array_search("SAT", $this->bands->get_user_bands(), true);

		$this->load->view('interface_assets/header', $data);
		$this->load->view('activated_grids/main.php');
		$this->load->view('interface_assets/footer');
	}

	public function satellites()
	{
		$this->load->model('activated_grids_model');

		$data['page_title'] = "Satellite Activated Gridsquare Map";


		$array_grid_2char = array();
		$array_grid_4char = array();
		$array_grid_6char = array();


		$array_confirmed_grid_2char = array();
		$array_confirmed_grid_4char = array();
		$array_confirmed_grid_6char = array();

		$grid_2char = "";
		$grid_4char = "";
		$grid_6char = "";

		$grid_2char_confirmed = "";
		$grid_4char_confirmed = "";
		$grid_6char_confirmed = "";


		// Get Confirmed LOTW & Paper Activated Squares (non VUCC)
		$query = $this->activated_grids_model->get_activated_confirmed_sat_squares();


		if ($query && $query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{

				/* Handle VUCC squares */
				if (strpos($row->SAT_SQUARE, ",") > 0) {
					$subsquares = explode(",", $row->SAT_SQUARE);
					foreach ($subsquares as &$subsquare) {
						$grid_two = strtoupper(substr($subsquare,0,2));
						$grid_four = strtoupper(substr($subsquare,0,4));
						if ($this->config->item('map_6digit_grids')) {
							$grid_six = strtoupper(substr($subsquare,0,6));
						}
		
						// Check if 2 Char is in array
						if(!in_array($grid_two, $array_grid_2char)){
							array_push($array_grid_2char, $grid_two);	
						}
		
		
						if(!in_array($grid_four, $array_grid_4char)){
							array_push($array_grid_4char, $grid_four);	
						}
		
		
						if ($this->config->item('map_6digit_grids')) {
							if(!in_array($grid_six, $array_grid_6char)){
								array_push($array_grid_6char, $grid_six);	
							}
						}
					}

				} else {

					$grid_2char_confirmed = strtoupper(substr($row->SAT_SQUARE,0,2));
					$grid_4char_confirmed = strtoupper(substr($row->SAT_SQUARE,0,4));
					if ($this->config->item('map_6digit_grids')) {
						$grid_6char_confirmed = strtoupper(substr($row->SAT_SQUARE,0,6));
					}
	
					// Check if 2 Char is in array
					if(!in_array($grid_2char_confirmed, $array_confirmed_grid_2char)){
						array_push($array_confirmed_grid_2char, $grid_2char_confirmed);	
					}
	
	
					if(!in_array($grid_4char_confirmed, $array_confirmed_grid_4char)){
						array_push($array_confirmed_grid_4char, $grid_4char_confirmed);	
					}
	
	
					if ($this->config->item('map_6digit_grids')) {
						if(!in_array($grid_6char_confirmed, $array_confirmed_grid_6char)){
							array_push($array_confirmed_grid_6char, $grid_6char_confirmed);	
						}
					}

				}


			}
		}

		// Get activated squares
		$query = $this->activated_grids_model->get_activated_sat_squares();

		if ($query && $query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{

				/* Handle VUCC squares */
				if (strpos($row->SAT_SQUARE, ",") > 0) {
					$subsquares = explode(",", $row->SAT_SQUARE);
					foreach ($subsquares as &$subsquare) {
						$grid_two = strtoupper(substr($subsquare,0,2));
						$grid_four = strtoupper(substr($subsquare,0,4));
						if ($this->config->item('map_6digit_grids')) {
							$grid_six = strtoupper(substr($subsquare,0,6));
						}
		
						// Check if 2 Char is in array
						if(!in_array($grid_two, $array_grid_2char)){
							array_push($array_grid_2char, $grid_two);	
						}
		
		
						if(!in_array($grid_four, $array_grid_4char)){
							array_push($array_grid_4char, $grid_four);	
						}
		
		
						if ($this->config->item('map_6digit_grids')) {
							if(!in_array($grid_six, $array_grid_6char)){
								array_push($array_grid_6char, $grid_six);	
							}
						}
					}

				} else {

					$grid_two = strtoupper(substr($row->SAT_SQUARE,0,2));
					$grid_four = strtoupper(substr($row->SAT_SQUARE,0,4));
					if ($this->config->item('map_6digit_grids')) {
						$grid_six = strtoupper(substr($row->SAT_SQUARE,0,6));
					}
	
					// Check if 2 Char is in array
					if(!in_array($grid_two, $array_grid_2char)){
						array_push($array_grid_2char, $grid_two);	
					}
	
	
					if(!in_array($grid_four, $array_grid_4char)){
						array_push($array_grid_4char, $grid_four);	
					}
	
	
					if ($this->config->item('map_6digit_grids')) {
						if(!in_array($grid_six, $array_grid_6char)){
							array_push($array_grid_6char, $grid_six);	
						}
					}

				}


			}
		}

		function js_str($s)
		{
		    return '"' . addcslashes($s, "\0..\37\"\\") . '"';
		}

		function js_array($array)
		{
		    $temp = array_map('js_str', $array);
		    return '[' . implode(',', $temp) . ']';
		}


		$data['grid_2char_confirmed'] = js_array($array_confirmed_grid_2char);
		$data['grid_4char_confirmed'] = js_array($array_confirmed_grid_4char);
		$data['grid_6char_confirmed'] = js_array($array_confirmed_grid_6char);

		$data['grid_2char'] = js_array($array_grid_2char);
		$data['grid_4char'] = js_array($array_grid_4char);
		$data['grid_6char'] = js_array($array_grid_6char);


		$this->load->view('interface_assets/header', $data);
		$this->load->view('activated_grids/index.php');
		$this->load->view('interface_assets/footer');
	}
	

	public function band($band)
	{
		$this->load->model('activated_grids_model');

		$data['page_title'] = strtoupper($band)." Activated Gridsquare Map";

		$array_grid_2char = array();
		$array_grid_4char = array();
		$array_grid_6char = array();

		$array_grid_2char_confirmed = array();
		$array_grid_4char_confirmed = array();
		$array_grid_6char_confirmed = array();

		$grid_2char = "";
		$grid_4char = "";
		$grid_6char = "";

		$grid_2char_confirmed = "";
		$grid_4char_confirmed = "";
		$grid_6char_confirmed = "";

		$query = $this->activated_grids_model->get_band_confirmed($band);

		if ($query && $query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{
				$grid_2char_confirmed = strtoupper(substr($row->GRID_SQUARES,0,2));
				$grid_4char_confirmed = strtoupper(substr($row->GRID_SQUARES,0,4));
				if ($this->config->item('map_6digit_grids')) {
					$grid_6char_confirmed = strtoupper(substr($row->GRID_SQUARES,0,6));
				}

				// Check if 2 Char is in array
				if(!in_array($grid_2char_confirmed, $array_grid_2char_confirmed)){
					array_push($array_grid_2char_confirmed, $grid_2char_confirmed);	
				}

				if(!in_array($grid_4char_confirmed, $array_grid_4char_confirmed)){
					array_push($array_grid_4char_confirmed, $grid_4char_confirmed);	
				}

				if ($this->config->item('map_6digit_grids')) {
					if(!in_array($grid_6char_confirmed, $array_grid_6char_confirmed)){
						array_push($array_grid_6char_confirmed, $grid_6char_confirmed);	
					}
				}
			}
		}

		$query = $this->activated_grids_model->get_band($band);

		if ($query && $query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{

				$grid_two = strtoupper(substr($row->GRID_SQUARES,0,2));
				$grid_four = strtoupper(substr($row->GRID_SQUARES,0,4));
				if ($this->config->item('map_6digit_grids')) {
					$grid_six = strtoupper(substr($row->GRID_SQUARES,0,6));
				}

				// Check if 2 Char is in array
				if(!in_array($grid_two, $array_grid_2char)){
					array_push($array_grid_2char, $grid_two);	
				}


				if(!in_array($grid_four, $array_grid_4char)){
					array_push($array_grid_4char, $grid_four);	
				}


				if ($this->config->item('map_6digit_grids')) {
					if(!in_array($grid_six, $array_grid_6char)){
						array_push($array_grid_6char, $grid_six);	
					}
				}


			}
		}

		function js_str($s)
		{
		    return '"' . addcslashes($s, "\0..\37\"\\") . '"';
		}

		function js_array($array)
		{
		    $temp = array_map('js_str', $array);
		    return '[' . implode(',', $temp) . ']';
		}

		$data['grid_2char_confirmed'] = js_array($array_grid_2char_confirmed);
		$data['grid_4char_confirmed'] = js_array($array_grid_4char_confirmed);
		$data['grid_6char_confirmed'] = js_array($array_grid_6char_confirmed);

		$data['grid_2char'] = js_array($array_grid_2char);
		$data['grid_4char'] = js_array($array_grid_4char);
		$data['grid_6char'] = js_array($array_grid_6char);

		$data['bands_available'] = js_array($this->config->item('bands_available'));

		$this->load->view('interface_assets/header', $data);
		$this->load->view('activated_grids/index.php');
		$this->load->view('interface_assets/footer');
	}

	function search_band($band, $gridsquare){
		$this->load->model('activated_grids_model');
		header('Content-Type: application/json');
		$result = $this->activated_grids_model->search_band($band, $gridsquare);

		echo $result;
	}

	function search_sat($gridsquare){
		$this->load->model('activated_grids_model');
		header('Content-Type: application/json');
		$result = $this->activated_grids_model->search_sat($gridsquare);

		echo $result;
	}

	public function qso_details_ajax(){
		$this->load->model('logbook_model');

		$searchphrase = str_replace('"', "", $this->input->post("Searchphrase"));
		$band = str_replace('"', "", $this->input->post("Band"));
		$mode = str_replace('"', "", $this->input->post("Mode"));

		$data['results'] = $this->logbook_model->activated_grids_qso_details($searchphrase, $band, $mode);

		// Render Page
		$data['page_title'] = "Log View";
		$data['filter'] = $searchphrase . " and band ".$band . " and mode ".$mode;
		$this->load->view('awards/details', $data);
	}

}
