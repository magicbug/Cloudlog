<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logbook extends CI_Controller {

	/*
	
		TODO List:
			- Search Option
	*/

	function index()
	{
	
		$this->load->library('pagination');
		$config['base_url'] = base_url().'index.php/logbook/index/';
		$config['total_rows'] = $this->db->count_all($this->config->item('table_name'));
		$config['per_page'] = '25';
		$config['num_links'] = 6;
		$config['full_tag_open'] = '<p>';
		$config['full_tag_close'] = '</p>';
	
		$this->pagination->initialize($config);
	
		//load the model and get results
		$this->load->model('logbook_model');
		$data['results'] = $this->logbook_model->get_qsos($config['per_page'],$this->uri->segment(3));

	
		// load the view
		$this->load->view('layout/header');
		$this->load->view('view_log/index', $data);
		$this->load->view('layout/footer');
		
	}
	
	function view($id) {
		$this->db->where('COL_PRIMARY_KEY', $id); 
		$data['query'] = $this->db->get($this->config->item('table_name'));
		
		$this->load->view('view_log/qso', $data);
	}
	
	function callsign_qra($qra) {
		$this->load->model('logbook_model');

		echo $this->logbook_model->call_qra($qra);
	}
	
	function callsign_name($callsign) {
		$this->load->model('logbook_model');

		echo $this->logbook_model->call_name($callsign);
	}
	
	function test($callsign) {
		$json = file_get_contents("http://callbytxt.org/db/".$callsign.".json");
		
		$obj = json_decode($json);
		
		$uppercase_name = strtolower($obj->{'calls'}->{'first_name'});
		echo ucwords($uppercase_name);
	}
	
	function partial($id) {
	
		$this->db->like('COL_CALL', $id); 
		$query = $this->db->get($this->config->item('table_name'));
	
		if ($query->num_rows() > 0)
		{
			echo "<table class=\"partial\" width=\"100%\">";
				echo "<tr>";
					echo "<td>Date</td>";
					echo "<td>Callsign</td>";
					echo "<td>RST Sent</td>";
					echo "<td>RST Recv</td>";
					echo "<td>Band</td>";
					echo "<td>Mode</td>";
				echo "</tr>";
			foreach ($query->result() as $row)
			{
				echo "<tr>";
					echo "<td>".$row->COL_TIME_ON."</td>";
					echo "<td>".$row->COL_CALL."</td>";
					echo "<td>".$row->COL_RST_SENT."</td>";
					echo "<td>".$row->COL_RST_RCVD."</td>";
					echo "<td>".$row->COL_BAND."</td>";
					echo "<td>".$row->COL_MODE."</td>";
				echo "</tr>";
			}
			echo "</table>";
		} else {
			echo "Unique Callsign: ".strtoupper($id);
		}
	}
	
	// Find DXCC
	function find_dxcc($callsign) {
			$this->load->model('dxcc');

			$dxccinfo = $this->dxcc->info($callsign); 

			foreach ($dxccinfo->result() as $row)
			{
				echo ucfirst(strtolower($row->name));
			}
	}
	
	function bearing($qra) {
			$my = qra2latlong($this->config->item('locator'));
			$stn = qra2latlong($qra);
	
			$bearing = bearing($my[0], $my[1], $stn[0], $stn[1]);
	
			echo $bearing;
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
$var_dist = "";
  #return $dir;
  if (isset($dist)) {
  	$var_dist = $dist." miles";
  }
  return round($bearing, 0)."&#186; ".$dir." ".$var_dist;
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