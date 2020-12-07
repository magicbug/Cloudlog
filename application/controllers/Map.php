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