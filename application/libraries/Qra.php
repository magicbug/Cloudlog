<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Qra {

	// Name: QRA
	// Task: convert qra to lat/long

	function qra2latlong($strQRA)
	{
		return qra2latlong($strQRA);
	}
	
	function bearing($tx, $rx) {
		$my = qra2latlong($tx);
		$stn = qra2latlong($rx);

		$bearing = bearing($my[0], $my[1], $stn[0], $stn[1]);
		
		return $bearing;
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

/* End of file Qra.php */