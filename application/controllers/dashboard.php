<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	/*
		TODO
			- DXCLuster Spots
			- Breakdown of QSOs per band/mode
			- Countries worked
	*/

	public function index()
	{
	
		// Database connections
		$this->load->model('logbook_model');
		
		// Store info
		$data['todays_qsos'] = $this->logbook_model->todays_qsos();
		$data['total_qsos'] = $this->logbook_model->total_qsos();
		$data['month_qsos'] = $this->logbook_model->month_qsos();
		$data['year_qsos'] = $this->logbook_model->year_qsos();
		
		$data['total_ssb'] = $this->logbook_model->total_ssb();
		$data['total_cw'] = $this->logbook_model->total_cw();
		$data['total_fm'] = $this->logbook_model->total_fm();
		$data['total_digi'] = $this->logbook_model->total_digi();
		
		$data['total_bands'] = $this->logbook_model->total_bands();
		
		$data['last_five_qsos'] = $this->logbook_model->get_last_qsos('9');

	
		$this->load->view('layout/header');
		$this->load->view('dashboard/index', $data);
		$this->load->view('layout/footer');
	}
	
	function todays_map() {
	
		$this->load->model('logbook_model');
		$qsos = $this->logbook_model->get_todays_qsos('');

	
		echo "{\"markers\": [";

		foreach ($qsos->result() as $row) {
			//print_r($row);
			if($row->COL_GRIDSQUARE != null) {
				$stn_loc = qra2latlong($row->COL_GRIDSQUARE);
				echo "{\"point\":new GLatLng(".$stn_loc[0].",".$stn_loc[1]."), \"html\":\"Callsign: ".$row->COL_CALL."<br />Date/Time: ".$row->COL_TIME_ON."<br />Band: ".$row->COL_BAND."<br />Mode: ".$row->COL_MODE."\",\"label\":\"".$row->COL_CALL."\"},";
			} else {
				$query = $this->db->query('
					SELECT *
					FROM dxcc
					WHERE prefix = SUBSTRING( \''.$row->COL_CALL.'\', 1, LENGTH( prefix ) )
					ORDER BY LENGTH( prefix ) DESC
					LIMIT 1 
				');
				
				foreach ($query->result() as $dxcc) {
					echo "{\"point\":new GLatLng(".$dxcc->lat.",".$dxcc->long."), \"html\":\"Callsign: ".$row->COL_CALL."<br />Date/Time: ".$row->COL_TIME_ON."<br />Band: ".$row->COL_BAND."<br />Mode: ".$row->COL_MODE."\",\"label\":\"".$row->COL_CALL."\"},";
				}
			}
			
		}
		echo "]";
		echo "}";

	}
}

function distance($lat1, $lon1, $lat2, $lon2, $unit = 'M') {
  $theta = $lon1 - $lon2;
  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
cos(deg2rad($theta));
  $dist = acos($dist);
  $dist = rad2deg($dist);
  $dist = $dist * 60 * 1.1515;

  if ($unit == "K") {
	$dist *= 1.609344;
  } else if ($unit == "N") {
	$dist *= 0.8684;
  }

  return round($dist, 1);
}

function bearing($lat1, $lon1, $lat2, $lon2) {
  if (round($lon1, 1) == round($lon2, 1)) {
	if ($lat1 < $lat2) {
	  $bearing = 0;
	} else {
	  $bearing = 180;
	}
  } else {
	$dist = distance($lat1, $lon1, $lat2, $lon2, 'N');
	$arad = acos((sin(deg2rad($lat2)) - sin(deg2rad($lat1)) * cos(deg2rad($dist / 60))) / (sin(deg2rad($dist
/ 60)) * cos(deg2rad($lat1))));
	$bearing = $arad * 180 / pi();
	if (sin(deg2rad($lon2 - $lon1)) < 0) {
	  $bearing = 360 - $bearing;
	}
  }

  $dirs = array("N","E","S","W");

  $rounded = round($bearing / 22.5) % 16;
  if (($rounded % 4) == 0) {
	$dir = $dirs[$rounded / 4];
  } else {
	$dir = $dirs[2 * floor(((floor($rounded / 4) + 1) % 4) / 2)];
	$dir .= $dirs[1 + 2 * floor($rounded / 8)];
	#if ($rounded % 2 == 1)
	#  $dir = $dirs[round_to_int($rounded/4) % 4] . "-" . $dir;
  }

  #return $dir;
  return round($bearing, 0)."&#186; ".$dir." ".$dist." miles";
}

function qra2latlong($strQRA)

{
		$strQRA = strtoupper($strQRA);
		if (strlen($strQRA) == 4)  $strQRA .= "MM";
		if (!preg_match('/^[A-Z]{2}[0-9]{2}[A-Z]{2}$/',$strQRA)) return false;
		list($a,$b,$c,$d,$e,$f) = str_split($strQRA,1);
		$a = ord($a) - ord('A');
		$b = ord($b) - ord('A');
		$c = ord($c) - ord('0');
		$d = ord($d) - ord('0');
		$e = ord($e) - ord('A');
		$f = ord($f) - ord('A');
		$nLong = ($a*20) + ($c*2) + (($e+0.5)/12) - 180;
		$nLat = ($b*10) + $d + (($f+0.5)/24) - 90;
		$arLatLong = array($nLat,$nLong);
		return($arLatLong);

}