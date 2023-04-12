<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cabrilloformat {

    public function header($contest_id, $callsign, $claimed_score, $operators, $club, $name, $address1, $address2, $address3, $soapbox, $gridlocator) {
        $cab_header = "";
        $cab_header .= "START-OF-LOG: 3.0"."\r\n";
        $cab_header .= "CONTEST: ".$contest_id."\r\n";
        $cab_header .= "CALLSIGN: ".$callsign."\r\n";

        if($claimed_score != null) {
            $cab_header .= "CLAIMED-SCORE: ".$claimed_score."\r\n";
        }

        $cab_header .= "OPERATORS: ".$operators."\r\n";

        if($club != null) {
            $cab_header .= "CLUB: ".$club."\r\n";
        }

        $cab_header .= "NAME: ".$name."\r\n";
        $cab_header .= "ADDRESS: ".$address1."\r\n";
        $cab_header .= "ADDRESS: ".$address2."\r\n";
        $cab_header .= "ADDRESS: ".$address3."\r\n";
        $cab_header .= "SOAPBOX: ".$soapbox."\r\n";

        if($gridlocator != null) {
            $cab_header .= "GRID-LOCATOR: ".$gridlocator."\r\n";
        }

        return $cab_header;

    }

    public function footer() {
        return "END-OF-LOG:";
    }

    public function qso($qso) {
        $freq =  substr($qso->COL_FREQ, 0, -3);
        if ($freq > 30000) {
           if ($freq > 250000000) {
              $freq = "LIGHT";
           }
           if ($freq >= 241000000 && $freq <= 250000000 ) {
              $freq = "241G";
           }
           if ($freq >= 134000000 && $freq <= 141000000 ) {
              $freq = "134G";
           }
           if ($freq >= 122250000 && $freq <= 123000000 ) {
              $freq = "122G";
           }
           if ($freq >= 75500000 && $freq <= 81500000 ) {
              $freq = "75G";
           }
           if ($freq >= 47000000 && $freq <= 47200000 ) {
              $freq = "47G";
           }
           if ($freq >= 24000000 && $freq <= 24250000 ) {
              $freq = "24G";
           }
           if ($freq >= 10000000 && $freq <= 10500000 ) {
              $freq = "10G";
           }
           if ($freq >= 5650000 && $freq <= 5850000 ) {
              $freq = "5.7G";
           }
           if ($freq >= 3400000 && $freq <= 3475000 ) {
              $freq = "3.4G";
           }
           if ($freq >= 2320000 && $freq <= 2450000 ) {
              $freq = "2.4G";
           }
           if ($freq >= 1240000 && $freq <= 1300000 ) {
              $freq = "1.2G";
           }
           if ($freq >= 902000 && $freq <= 928000 ) {
              $freq = "902";
           }
           if ($freq >= 430000 && $freq <= 440000 ) {
              $freq = "432";
           }
           if ($freq >= 222000 && $freq <= 225000 ) {
              $freq = "222";
           }
           if ($freq >= 144000 && $freq <= 146000 ) {
              $freq = "144";
           }
           if ($freq >= 70150 && $freq <= 70210 ) {
              $freq = "70";
           }
           if ($freq >= 50000 && $freq <= 52000 ) {
              $freq = "50";
           }
        }

        if($qso->COL_MODE == "SSB") {
            $mode = "PH";
        } elseif($qso->COL_MODE == "RTTY") {
            $mode = "RY";
        } else {
            $mode = $qso->COL_MODE;
        }

        $time = substr($qso->COL_TIME_ON, 0, -3);

        $time = str_replace(":","",$time);

        // Format according to https://wwrof.org/cabrillo/cabrillo-qso-data/
        return "QSO: ".sprintf("%6s", $freq)." ".$mode." ".$time." ".sprintf("%-13s", $qso->station_callsign)." ".sprintf("%3s", $qso->COL_RST_SENT)." ".sprintf("%-6s", sprintf("%03d", $qso->COL_STX))." ".sprintf("%-13s", $qso->COL_CALL)." ".sprintf("%3s", $qso->COL_RST_RCVD)." ".sprintf("%-6s", sprintf("%03d", $qso->COL_SRX))." 0\n";
    }
}
