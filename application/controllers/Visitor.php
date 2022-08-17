<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Visitor extends CI_Controller {

	function __construct()
	{
		parent::__construct();
	}

    function _remap($method) {
        if($method == "config") {
            $this->$method();
        }
        elseif($method == "map") {
            $this->map($method);
        }
        elseif($method == "satellites") {
            $this->satellites($method);
        }
        else {
            $this->index($method);
        }
    }

	/*
        This is the default function that is called when the user visits the root of the public controller
    */
	public function index($public_slug = NULL)
	{

        $this->load->model('user_model');

		// Check if users logged in
		if($this->user_model->validate_session() != 0) {
            // If environment is set to development then show the debug toolbar
		    if(ENVIRONMENT == 'development') {
                $this->output->enable_profiler(TRUE);
            }
		}

        // Check slug passed and is valid
        if ($this->security->xss_clean($public_slug, TRUE) === FALSE)
        {
            // Public Slug failed the XSS test
            log_message('error', '[Visitor] XSS Attack detected on public_slug '. $public_slug);
            show_404('Unknown Public Page.');
        } else {
            // Checked slug passed and clean
            log_message('info', '[Visitor] public_slug '. $public_slug .' loaded');

            // Check if the slug is contained in the station_logbooks table
            $this->load->model('logbooks_model');
            if($this->logbooks_model->public_slug_exists($public_slug)) {
                // Load the public view

				$logbook_id = $this->logbooks_model->public_slug_exists_logbook_id($public_slug);
                if($logbook_id != false)
                {
                    // Get associated station locations for mysql queries
                    $logbooks_locations_array = $this->logbooks_model->list_logbook_relationships($logbook_id);

					if (!$logbooks_locations_array) {
						show_404('Empty Logbook');
					}
                } else {
                    log_message('error', $public_slug.' has no associated station locations');
                    show_404('Unknown Public Page.');
                }

                $this->load->model('logbook_model');

                // Public visitor so no QRA to setup
                $data['qra'] = "none";

                $this->load->model('cat');

                $data['radio_status'] = $this->cat->recent_status();

                // Store info
                $data['todays_qsos'] = $this->logbook_model->todays_qsos($logbooks_locations_array);
                $data['total_qsos'] = $this->logbook_model->total_qsos($logbooks_locations_array);
                $data['month_qsos'] = $this->logbook_model->month_qsos($logbooks_locations_array);
                $data['year_qsos'] = $this->logbook_model->year_qsos($logbooks_locations_array);

                // Load  Countries Breakdown data into array
                $CountriesBreakdown = $this->logbook_model->total_countries_confirmed($logbooks_locations_array);

                $data['total_countries'] = $CountriesBreakdown['Countries_Worked'];
                $data['total_countries_confirmed_paper'] = $CountriesBreakdown['Countries_Worked_QSL'];
                $data['total_countries_confirmed_eqsl'] = $CountriesBreakdown['Countries_Worked_EQSL'];
                $data['total_countries_confirmed_lotw'] = $CountriesBreakdown['Countries_Worked_LOTW'];

                $QSLStatsBreakdownArray =$this->logbook_model->get_QSLStats($logbooks_locations_array);

                $data['total_qsl_sent'] = $QSLStatsBreakdownArray['QSL_Sent'];
                $data['total_qsl_recv'] = $QSLStatsBreakdownArray['QSL_Received'];
                $data['total_qsl_requested'] = $QSLStatsBreakdownArray['QSL_Requested'];

                $data['total_eqsl_sent'] = $QSLStatsBreakdownArray['eQSL_Sent'];
                $data['total_eqsl_recv'] = $QSLStatsBreakdownArray['eQSL_Received'];

                $data['total_lotw_sent'] = $QSLStatsBreakdownArray['LoTW_Sent'];
                $data['total_lotw_recv'] = $QSLStatsBreakdownArray['LoTW_Received'];

                $data['last_five_qsos'] = $this->logbook_model->get_last_qsos('18', $logbooks_locations_array);

                $data['page_title'] = "Dashboard";
                $data['slug'] = $public_slug;

                $this->load->model('dxcc');
                $dxcc = $this->dxcc->list_current();
    
                $current = $this->logbook_model->total_countries_current($logbooks_locations_array);
    
                $data['total_countries_needed'] = count($dxcc->result()) - $current;
    
                $this->load->view('visitor/layout/header', $data);
                $this->load->view('visitor/index');
                $this->load->view('visitor/layout/footer');
            } else {
                // Show 404
                log_message('error', '[Visitor] XSS Attack detected on public_slug '. $public_slug);
                show_404('Unknown Public Page.');
            }
            
        }
	}
	
    public function map() {
		$this->load->model('logbook_model');
		
		$this->load->library('qra');

        $slug = $this->security->xss_clean($this->uri->segment(3));

        $this->load->model('logbooks_model');
        $logbook_id = $this->logbooks_model->public_slug_exists_logbook_id($slug);
        if($logbook_id != false)
        {
            // Get associated station locations for mysql queries
            $logbooks_locations_array = $this->logbooks_model->list_logbook_relationships($logbook_id);

			if (!$logbooks_locations_array) {
				show_404('Empty Logbook');
			}
        } else {
            log_message('error', $slug.' has no associated station locations');
            show_404('Unknown Public Page.');
        }

		$qsos = $this->logbook_model->get_last_qsos('18', $logbooks_locations_array);
        header('Content-Type: application/json; charset=utf-8');
		echo "{\"markers\": [";
		$count = 1;
		foreach ($qsos->result() as $row) {
			//print_r($row);
			if($row->COL_GRIDSQUARE != null) {
				$stn_loc = $this->qra->qra2latlong($row->COL_GRIDSQUARE);
				if($count != 1) {
					echo ",";
				}

				if($row->COL_SAT_NAME != null) { 
					echo "{\"lat\":\"".$stn_loc[0]."\",\"lng\":\"".$stn_loc[1]."\", \"html\":\"Callsign: ".$row->COL_CALL."<br />Date/Time: ".$row->COL_TIME_ON."<br />SAT: ".$row->COL_SAT_NAME."<br />Mode: ";
					echo $row->COL_SUBMODE==null?$row->COL_MODE:$row->COL_SUBMODE;
					echo "\",\"label\":\"".$row->COL_CALL."\"}";
				} else {
					echo "{\"lat\":\"".$stn_loc[0]."\",\"lng\":\"".$stn_loc[1]."\", \"html\":\"Callsign: ".$row->COL_CALL."<br />Date/Time: ".$row->COL_TIME_ON."<br />Band: ".$row->COL_BAND."<br />Mode: ";
					echo $row->COL_SUBMODE==null?$row->COL_MODE:$row->COL_SUBMODE;
					echo "\",\"label\":\"".$row->COL_CALL."\"}";
				}

				$count++;
			} elseif($row->COL_VUCC_GRIDS != null) {

				$grids = explode(",", $row->COL_VUCC_GRIDS);
				if (count($grids) == 2) {
					$grid1 = $this->qra->qra2latlong(trim($grids[0]));
					$grid2 = $this->qra->qra2latlong(trim($grids[1]));
		
					$coords[]=array('lat' => $grid1[0],'lng'=> $grid1[1]);
					$coords[]=array('lat' => $grid2[0],'lng'=> $grid2[1]);    
		
					$stn_loc = $this->qra->get_midpoint($coords);
				}
				if (count($grids) == 4) {
					$grid1 = $this->qra->qra2latlong(trim($grids[0]));
					$grid2 = $this->qra->qra2latlong(trim($grids[1]));
					$grid3 = $this->qra->qra2latlong(trim($grids[2]));
					$grid4 = $this->qra->qra2latlong(trim($grids[3]));
		
					$coords[]=array('lat' => $grid1[0],'lng'=> $grid1[1]);
					$coords[]=array('lat' => $grid2[0],'lng'=> $grid2[1]);    
					$coords[]=array('lat' => $grid3[0],'lng'=> $grid3[1]);    
					$coords[]=array('lat' => $grid4[0],'lng'=> $grid4[1]);    
		
					$stn_loc = $this->qra->get_midpoint($coords);
				}

				if($count != 1) {
					echo ",";
				}
	
				if($row->COL_SAT_NAME != null) { 
					echo "{\"lat\":\"".$stn_loc[0]."\",\"lng\":\"".$stn_loc[1]."\", \"html\":\"Callsign: ".$row->COL_CALL."<br />Date/Time: ".$row->COL_TIME_ON."<br />SAT: ".$row->COL_SAT_NAME."<br />Mode: ";
					echo $row->COL_SUBMODE==null?$row->COL_MODE:$row->COL_SUBMODE;
					echo "\",\"label\":\"".$row->COL_CALL."\"}";
				} else {
					echo "{\"lat\":\"".$stn_loc[0]."\",\"lng\":\"".$stn_loc[1]."\", \"html\":\"Callsign: ".$row->COL_CALL."<br />Date/Time: ".$row->COL_TIME_ON."<br />Band: ".$row->COL_BAND."<br />Mode: ";
					echo $row->COL_SUBMODE==null?$row->COL_MODE:$row->COL_SUBMODE;
					echo "\",\"label\":\"".$row->COL_CALL."\"}";
				}
	
				$count++;
		
			} else {
				if($count != 1) {
					echo ",";
				}

				if(isset($row->lat) && isset($row->long)) {
					$lat = $row->lat;
					$lng = $row->long;
				}

				echo "{\"lat\":\"".$lat."\",\"lng\":\"".$lng."\", \"html\":\"Callsign: ".$row->COL_CALL."<br />Date/Time: ".$row->COL_TIME_ON."<br />Band: ".$row->COL_BAND."<br />Mode: ";
				echo $row->COL_SUBMODE==null?$row->COL_MODE:$row->COL_SUBMODE;
				echo "\",\"label\":\"".$row->COL_CALL."\"}";
				$count++;
			}

		}
		echo "]";
		echo "}";

	}

    public function satellites()
	{

        $slug = $this->security->xss_clean($this->uri->segment(3));
        $data['slug'] = $slug;
        $this->load->model('logbooks_model');
        if($this->logbooks_model->public_slug_exists($slug)) {
            // Load the public view
			$logbook_id = $this->logbooks_model->public_slug_exists_logbook_id($slug);
			if($logbook_id != false)
			{
				// Get associated station locations for mysql queries
				$logbooks_locations_array = $this->logbooks_model->list_logbook_relationships($logbook_id);
	
				if (!$logbooks_locations_array) {
					show_404('Empty Logbook');
				}
			} else {
				log_message('error', $slug.' has no associated station locations');
				show_404('Unknown Public Page.');
			}
        }

		$this->load->model('gridsquares_model');

		$data['page_title'] = "Satellite Gridsquare Map";


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


		// Get Confirmed LOTW & Paper Squares (non VUCC)
		$query = $this->gridsquares_model->get_confirmed_sat_squares($logbooks_locations_array);


		if ($query && $query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{

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

		// Get worked squares
		$query = $this->gridsquares_model->get_worked_sat_squares($logbooks_locations_array);

		if ($query && $query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{

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

		$query_vucc = $this->gridsquares_model->get_worked_sat_vucc_squares($logbooks_locations_array);

		if ($query && $query_vucc->num_rows() > 0)
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

		// Confirmed Squares
		$query_vucc = $this->gridsquares_model->get_confirmed_sat_vucc_squares($logbooks_locations_array);

		if ($query && $query_vucc->num_rows() > 0)
		{
			foreach ($query_vucc->result() as $row)
			{

				$grids = explode(",", $row->COL_VUCC_GRIDS);

				foreach($grids as $key) {    
					$grid_2char_confirmed = strtoupper(substr($key,0,2));
					$grid_4char_confirmed = strtoupper(substr($key,0,4));

					// Check if 2 Char is in array
					if(!in_array($grid_2char_confirmed, $array_confirmed_grid_2char)){
						array_push($array_confirmed_grid_2char, $grid_2char_confirmed);	
					}


					if(!in_array($grid_4char_confirmed, $array_confirmed_grid_4char)){
						array_push($array_confirmed_grid_4char, $grid_4char_confirmed);	
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


		$this->load->view('visitor/layout/header', $data);
		$this->load->view('gridsquares/index');
		$this->load->view('visitor/layout/footer');
	}
	
}