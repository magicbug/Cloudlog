<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gridmap extends CI_Controller {

	function __construct() {
		parent::__construct();
	}

    public function index() {
		$data['page_title'] = lang('gridsquares_gridsquare_map');

        $this->load->model('bands');
        $this->load->model('gridmap_model');
		$this->load->model('stations');

		$data['homegrid'] = explode(',', $this->stations->find_gridsquare());

        $data['modes'] = $this->gridmap_model->get_worked_modes();
		$data['bands'] = $this->bands->get_worked_bands();
		$data['sats_available'] = $this->bands->get_worked_sats();

		$data['user_default_band'] = $this->session->userdata('user_default_band');
		$data['user_default_confirmation'] = $this->session->userdata('user_default_confirmation');

		$data['layer'] = $this->optionslib->get_option('option_map_tile_server');

		$data['attribution'] = $this->optionslib->get_option('option_map_tile_server_copyright');

		$data['gridsquares_gridsquares'] 				= lang('gridsquares_gridsquares');
		$data['gridsquares_gridsquares_confirmed'] 		= lang('gridsquares_gridsquares_confirmed');
		$data['gridsquares_gridsquares_not_confirmed'] 	= lang('gridsquares_gridsquares_not_confirmed');
		$data['gridsquares_gridsquares_total_worked'] 	= lang('gridsquares_gridsquares_total_worked');

        $footerData = [];
		$footerData['scripts'] = [
			'assets/js/leaflet/geocoding.js',
			'assets/js/leaflet/L.MaidenheadColouredGridMap.js',
			'assets/js/sections/gridmap.js?'
		];
		
		$this->load->view('interface_assets/header', $data);
		$this->load->view('gridmap/index');
		$this->load->view('interface_assets/footer', $footerData);
    }

	public function getGridsjs() {
        $band = $this->security->xss_clean($this->input->post('band'));
        $mode = $this->security->xss_clean($this->input->post('mode'));
        $qsl = $this->security->xss_clean($this->input->post('qsl'));
        $lotw = $this->security->xss_clean($this->input->post('lotw'));
        $eqsl = $this->security->xss_clean($this->input->post('eqsl'));
		$sat = $this->security->xss_clean($this->input->post('sat'));
		$this->load->model('gridmap_model');

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

		$query = $this->gridmap_model->get_band_confirmed($band, $mode, $qsl, $lotw, $eqsl, $sat);

		if ($query && $query->num_rows() > 0) {
			foreach ($query->result() as $row) 	{
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

		$query = $this->gridmap_model->get_band($band, $mode, $qsl, $lotw, $eqsl, $sat);

		if ($query && $query->num_rows() > 0) {
			foreach ($query->result() as $row) {

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
		$query_vucc = $this->gridmap_model->get_band_worked_vucc_squares($band, $mode, $qsl, $lotw, $eqsl, $sat);

		if ($query_vucc && $query_vucc->num_rows() > 0) {
			foreach ($query_vucc->result() as $row) {

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

		// // Confirmed Squares
		$query_vucc = $this->gridmap_model->get_band_confirmed_vucc_squares($band, $mode, $qsl, $lotw, $eqsl, $sat);

		if ($query_vucc && $query_vucc->num_rows() > 0) {
			foreach ($query_vucc->result() as $row) 			{

				$grids = explode(",", $row->COL_VUCC_GRIDS);

				foreach($grids as $key) {    
					$grid_2char_confirmed = strtoupper(substr($key,0,2));
					$grid_4char_confirmed = strtoupper(substr($key,0,4));

					// Check if 2 Char is in array
					if(!in_array($grid_2char_confirmed, $array_grid_2char_confirmed)){
						array_push($array_grid_2char_confirmed, $grid_2char_confirmed);	
					}


					if(!in_array($grid_4char_confirmed, $array_grid_4char_confirmed)){
						array_push($array_grid_4char_confirmed, $grid_4char_confirmed);	
					}
				}
			}
		}

        $data['grid_2char_confirmed'] = ($array_grid_2char_confirmed);
		$data['grid_4char_confirmed'] = ($array_grid_4char_confirmed);
		$data['grid_6char_confirmed'] = ($array_grid_6char_confirmed);

		$data['grid_2char'] = ($array_grid_2char);
		$data['grid_4char'] = ($array_grid_4char);
		$data['grid_6char'] = ($array_grid_6char);

        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
