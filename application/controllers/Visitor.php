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
                if($logbook_id = $this->logbooks_model->public_slug_exists_logbook_id($public_slug) != false)
                {
                    // Get associated station locations for mysql queries
                    $logbooks_locations_array = $this->logbooks_model->list_logbook_relationships($logbook_id);
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
        if($logbook_id = $this->logbooks_model->public_slug_exists_logbook_id($slug) != false)
        {
            // Get associated station locations for mysql queries
            $logbooks_locations_array = $this->logbooks_model->list_logbook_relationships($logbook_id);
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
						echo "{\"lat\":\"".$stn_loc[0]."\",\"lng\":\"".$stn_loc[1]."\", \"html\":\"Callsign: ".$row->COL_CALL."<br />Date/Time: ".$row->COL_TIME_ON."<br />SAT: ".$row->COL_SAT_NAME."<br />Mode: ".$row->COL_MODE."\",\"label\":\"".$row->COL_CALL."\"}";
				} else {
						echo "{\"lat\":\"".$stn_loc[0]."\",\"lng\":\"".$stn_loc[1]."\", \"html\":\"Callsign: ".$row->COL_CALL."<br />Date/Time: ".$row->COL_TIME_ON."<br />Band: ".$row->COL_BAND."<br />Mode: ".$row->COL_MODE."\",\"label\":\"".$row->COL_CALL."\"}";
				}

				$count++;

			} else {
				$query = $this->db->query('
					SELECT *
					FROM dxcc_entities
					WHERE prefix = SUBSTRING( \''.$row->COL_CALL.'\', 1, LENGTH( prefix ) )
					ORDER BY LENGTH( prefix ) DESC
					LIMIT 1 
				');

				foreach ($query->result() as $dxcc) {
					if($count != 1) {
					echo ",";
						}
					echo "{\"lat\":\"".$dxcc->lat."\",\"lng\":\"".$dxcc->long."\", \"html\":\"Callsign: ".$row->COL_CALL."<br />Date/Time: ".$row->COL_TIME_ON."<br />Band: ".$row->COL_BAND."<br />Mode: ".$row->COL_MODE."\",\"label\":\"".$row->COL_CALL."\"}";
					$count++;
				}
			}

		}
		echo "]";
		echo "}";

	}
}