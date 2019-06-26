<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
	
	Gridsquares Controller
		
*/

class Gridsquares extends CI_Controller {

    /*
    *   TODO List
    *   - Show squares that have been worked and confirmed in green
    *   - Create index page
    *   - Band page provide a band dropdown list
    *   - Find somewhere in the main menu to add a button to it
    */


	public function index() {
		$data['page_title'] = "Gridsquare Map";

		$this->load->view('interface_assets/header', $data);
		$this->load->view('gridsquares/main.php');
		$this->load->view('interface_assets/footer');
	}

	public function satellites()
	{
		$this->load->model('gridsquares_model');

		$data['page_title'] = "Satellite Gridsquare Map";


		$array_grid_2char = array();
		$array_grid_4char = array();

		$grid_2char = "";
		$grid_4char = "";

		$query = $this->gridsquares_model->get_worked_sat_squares();

		if ($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{

				$grid_two = strtoupper(substr($row->SAT_SQUARE,0,2));
				$grid_four = strtoupper(substr($row->SAT_SQUARE,0,4));

				// Check if 2 Char is in array
				if(!in_array($grid_two, $array_grid_2char)){
					array_push($array_grid_2char, $grid_two);	
				}


				if(!in_array($grid_four, $array_grid_4char)){
					array_push($array_grid_4char, $grid_four);	
				}


			}
		}

		$query_vucc = $this->gridsquares_model->get_worked_sat_vucc_squares();

		if ($query_vucc->num_rows() > 0)
		{
			foreach ($query_vucc->result() as $row)
			{

				$grids = explode(",", $row->COL_VUCC_GRIDS);

				foreach($grids as $key) {    
				    $grid_two = strtoupper(substr($key,0,2));
					$grid_four = strtoupper(substr($key,0,4));

					// Check if 2 Char is in array
					if(!in_array($grid_two, $array_grid_2char)){
						array_push($array_grid_2char, $grid_two);	
					}


					if(!in_array($grid_four, $array_grid_4char)){
						array_push($array_grid_4char, $grid_four);	
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


		$data['grid_2char'] = js_array($array_grid_2char);
		$data['grid_4char'] = js_array($array_grid_4char);



		$this->load->view('interface_assets/header', $data);
		$this->load->view('gridsquares/index.php');
		$this->load->view('interface_assets/footer');
	}
	

	public function band($band)
	{
		$this->load->model('gridsquares_model');

		$data['page_title'] = "Gridsquare Map";


		$array_grid_2char = array();
		$array_grid_4char = array();

		$grid_2char = "";
		$grid_4char = "";

		$query = $this->gridsquares_model->get_band($band);

		if ($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{

				$grid_two = strtoupper(substr($row->GRID_SQUARES,0,2));
				$grid_four = strtoupper(substr($row->GRID_SQUARES,0,4));

				// Check if 2 Char is in array
				if(!in_array($grid_two, $array_grid_2char)){
					array_push($array_grid_2char, $grid_two);	
				}


				if(!in_array($grid_four, $array_grid_4char)){
					array_push($array_grid_4char, $grid_four);	
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


		$data['grid_2char'] = js_array($array_grid_2char);
		$data['grid_4char'] = js_array($array_grid_4char);



		$this->load->view('interface_assets/header', $data);
		$this->load->view('gridsquares/index.php');
		$this->load->view('interface_assets/footer');
	}
	
}