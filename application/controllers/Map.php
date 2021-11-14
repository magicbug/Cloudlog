<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Map extends CI_Controller {

	function index()
	{

        // Calculate Lat/Lng from Locator to use on Maps
        if($this->session->userdata('user_locator')) {
            $this->load->library('qra');

            $qra_position = $this->qra->qra2latlong($this->session->userdata('user_locator'));
            $data['qra'] = "set";
            $data['qra_lat'] = $qra_position[0];
            $data['qra_lng'] = $qra_position[1];   
        } else {
            $data['qra'] = "none";
        }

        $this->load->model('Stations');
        $station_id = $this->Stations->find_active();
        $station_data = $this->Stations->profile_clean($station_id);

        // load the view
        $data['station_profile'] = $station_data;
		$data['page_title'] = "Map QSOs";

		$this->load->view('interface_assets/header', $data);
		$this->load->view('map/qsos');
		$this->load->view('interface_assets/footer');
    }

    function custom()
	{

		$this->load->model('dxcc');
        $this->load->model('modes');

        $data['worked_bands'] = $this->dxcc->get_worked_bands(); // Used in the view for band select
        $data['modes'] = $this->modes->active(); // Used in the view for mode select

        if ($this->input->post('band') != NULL) {   // Band is not set when page first loads.
            if ($this->input->post('band') == 'All') {         // Did the user specify a band? If not, use all bands
                $bands = $data['worked_bands'];
            }
            else {
                $bands[] = $this->input->post('band');
            }
        }
        else {
            $bands = $data['worked_bands'];
        }

        $data['bands'] = $bands; // Used for displaying selected band(s) in the table in the view

        // Calculate Lat/Lng from Locator to use on Maps
        if($this->session->userdata('user_locator')) {
            $this->load->library('qra');

            $qra_position = $this->qra->qra2latlong($this->session->userdata('user_locator'));
            $data['qra'] = "set";
            $data['qra_lat'] = $qra_position[0];
            $data['qra_lng'] = $qra_position[1];   
        } else {
            $data['qra'] = "none";
        }

        $this->load->model('Stations');
        $station_id = $this->Stations->find_active();
        $station_data = $this->Stations->profile_clean($station_id);

        // load the view
        $data['station_profile'] = $station_data;
		$data['page_title'] = "Map QSOs";


        if ($this->input->post('from')) {
            $from = $this->input->post('from');
            $from = DateTime::createFromFormat('m/d/Y g:i A', $from);
            $from = $from->format('Y-m-d H:i');

            $footer_data['date_from'] = $from;
        } else {
            $footer_data['date_from'] = date('Y-m-d H:i:00');
        }
        if ($this->input->post('to')) {
            $to = DateTime::createFromFormat('m/d/Y g:i A', $this->input->post('to'));
            $to = $to->modify('+1 day')->format('Y-m-d H:i:00');
            $footer_data['date_to'] = $to;
        } else {
            $temp_to = new DateTime('tomorrow');
            $footer_data['date_to'] = $temp_to->format('Y-m-d H:i:00');
        }


		$this->load->view('interface_assets/header', $data);
		$this->load->view('map/custom_date');
		$this->load->view('interface_assets/footer',$footer_data);
    }


    function map_data_custom() {
        $start_date = $this->uri->segment(3);
        $end_date = $this->uri->segment(4);
		$band = $this->uri->segment(5);
		$this->load->model('logbook_model');
		
		$this->load->library('qra');

		$qsos = $this->logbook_model->map_custom_qsos(rawurldecode($start_date), rawurldecode($end_date), $band);

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

    function map_data() {
		$this->load->model('logbook_model');
		
		$this->load->library('qra');

		//echo date('Y-m-d')
		$raw = strtotime('Monday last week');
		
		$mon = date('Y-m-d', $raw);
		$sun = date('Y-m-d', strtotime('Monday next week'));

		$qsos = $this->logbook_model->map_all_qsos_for_active_station_profile();

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