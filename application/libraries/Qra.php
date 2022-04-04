<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Qra {

	/*
	*	Class Description: QRA handles manipulation of the Gridsquares used within amateur radio
	*
	*	Units of measurement are the following
	*
	*	Info: Distance Function
	*
	*	M = Miles
	*	K = Kilometers
	*	N = Nautical Miles
	*/

	// Name: qra2latlong
	// Task: convert qra to lat/long
	function qra2latlong($strQRA)
	{
		return qra2latlong($strQRA);
	}

	// calculate  the bearing between two squares
	function bearing($tx, $rx, $unit = 'M') {
		if(strlen($tx) > 6) {
			$tx = substr($tx, 0, 6);
		}
		if(strlen($rx) > 6) {
			$rx = substr($rx, 0, 6);
		}
		$my = qra2latlong($tx);
		$stn = qra2latlong($rx);

		$bearing = bearing($my[0], $my[1], $stn[0], $stn[1], $unit);

		return $bearing;
	}

	/*
	* Function: calculate the distance between two gridsqaures
	*
	*	Inputs are QRA's TX and TX and the unit
	*
	*/
	function distance($tx, $rx, $unit = 'M') {
		if(strlen($tx) > 6) {
			$tx = substr($tx, 0, 6);
		}
		if(strlen($rx) > 6) {
			$rx = substr($rx, 0, 6);
		}
		// Calc LatLongs
		$my = qra2latlong($tx);
		$stn = qra2latlong($rx);

		// Feed in Lat Longs plus the unit type
		$total_distance = distance($my[0], $my[1], $stn[0], $stn[1], $unit);

		// Return the distance
		return $total_distance;
	}

	/*
	* Function returns just the bearing
	*  Input locator1 and locator2
	*/
	function get_bearing($tx, $rx) {
		$my = qra2latlong($tx);
		$stn = qra2latlong($rx);
		return get_bearing($my[0], $my[1], $stn[0], $stn[1]);
	}

	/*
	Find the Midpoint between two gridsquares using lat / long

	Needs following passed

	$coords[]=array('lat' => '53.344104','lng'=>'-6.2674937');
	$coords[]=array('lat' => '51.5081289','lng'=>'-0.128005');    

*/

function get_midpoint($coords)
{
    $count_coords = count($coords);
    $xcos=0.0;
    $ycos=0.0;
    $zsin=0.0;
    
        foreach ($coords as $lnglat)
        {
            $lat = $lnglat['lat'] * pi() / 180;
            $lon = $lnglat['lng'] * pi() / 180;
            
            $acos = cos($lat) * cos($lon);
            $bcos = cos($lat) * sin($lon);
            $csin = sin($lat);
            $xcos += $acos;
            $ycos += $bcos;
            $zsin += $csin;
        }
    
    $xcos /= $count_coords;
    $ycos /= $count_coords;
    $zsin /= $count_coords;
    $lon = atan2($ycos, $xcos);
    $sqrt = sqrt($xcos * $xcos + $ycos * $ycos);
    $lat = atan2($zsin, $sqrt);
    
    return array($lat * 180 / pi(), $lon * 180 / pi());
}
}

function distance($lat1, $lon1, $lat2, $lon2, $unit = 'M') {
  $theta = $lon1 - $lon2;
  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
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

function bearing($lat1, $lon1, $lat2, $lon2, $unit = 'M') {
  $dist = distance($lat1, $lon1, $lat2, $lon2, $unit);
  $dist = round($dist, 0);

  $bearing = get_bearing($lat1, $lon1, $lat2, $lon2);

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
	$var_dist = $dist;
	switch ($unit) {
		case 'M':
			$var_dist .= " miles";
			break;
		case 'N':
			$var_dist .= " nautic miles";
			break;
		case 'K':
			$var_dist .= " kilometers";
			break;
	}
  }
  return round($bearing, 0)."&#186; ".$dir." ".$var_dist;
}

function get_bearing($lat1, $lon1, $lat2, $lon2) {
	return (rad2deg(atan2(sin(deg2rad($lon2) - deg2rad($lon1)) * cos(deg2rad($lat2)), cos(deg2rad($lat1)) * sin(deg2rad($lat2)) - sin(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($lon2) - deg2rad($lon1)))) + 360) % 360;
}

function qra2latlong($strQRA) {

	if (strpos($strQRA, ',') !== false) {
        $gridsquareArray = explode(',', $strQRA);
        $strQRA = $gridsquareArray[0];
    }

	if (strlen($strQRA) %2 == 0) {
		$strQRA = strtoupper($strQRA);
		if (strlen($strQRA) == 4)  $strQRA .= "MM";
		if(strlen($strQRA) > 6) {
			$strQRA = substr($strQRA, 0, 6);
		}

		if (!preg_match('/^[A-R]{2}[0-9]{2}[A-X]{2}$/',$strQRA)) return false;
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
	} else {
		return array(0, 0);
	}
}


/* End of file Qra.php */
