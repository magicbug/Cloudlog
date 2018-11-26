<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Social extends CI_Controller {


	public function map($day)
	{

		$this->load->model('logbook_model');

		$map_date = date('Y-m-d', strtotime($day));
		$formated_date = date('d-m-Y', strtotime($day));
			
		$data['qsos'] = $this->logbook_model->get_date_qsos($map_date);

		$data['date'] = $map_date;
		$data['formated_date'] = $formated_date;

		$this->load->view('layout/header');
		$this->load->view('social/map', $data);
		$this->load->view('layout/footer');
	}

	function json_map($date) {
		$this->load->model('logbook_model');
		$this->load->library('qra');

		$qsos = $this->logbook_model->map_day($date);

		echo "{\"markers\": [";
		$count = 1;
		foreach ($qsos->result() as $row) {
			//print_r($row);
			if($row->COL_GRIDSQUARE != null) {
				$stn_loc = qra2latlong($row->COL_GRIDSQUARE);
				if($count != 1) {
					echo ",";
				}

				echo "{\"lat\":\"".$stn_loc[0]."\",\"lng\":\"".$stn_loc[1]."\", \"html\":\"Callsign: ".$row->COL_CALL."<br />Date/Time: ".$row->COL_TIME_ON."<br />Band: ".$row->COL_BAND."<br />Mode: ".$row->COL_MODE."\",\"label\":\"".$row->COL_CALL."\"}";

				$count++;

			} else {
				$query = $this->db->query('
					SELECT *
					FROM dxcc
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