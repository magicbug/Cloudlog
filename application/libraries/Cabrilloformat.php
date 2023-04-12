<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cabrilloformat {

    public function header($contest_id, $callsign, $claimed_score, $operators, $club, $name, $address1, $address2, $address3, $soapbox) {
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

        return $cab_header;

    }

    public function footer() {
        return "END-OF-LOG:";
    }

    public function qso($qso) {
        $freq =  substr($qso->COL_FREQ, 0, -3);

        if($qso->COL_MODE == "SSB") {
            $mode = "PH";
        } elseif($qso->COL_MODE == "RTTY") {
            $mode = "RY";
        } else {
            $mode = $qso->COL_MODE;
        }

        $time = substr($qso->COL_TIME_ON, 0, -3);

        $time = str_replace(":","",$time);

        if ($qso->COL_STX_STRING != "") {

            if($qso->COL_SRX_STRING != "") {
                $rx_string = $qso->COL_SRX_STRING;
            } else {
                $rx_string = "--";
            }

            return "QSO:  ".$freq." ".$mode." ".$time." ".$qso->station_callsign."\t".$qso->COL_RST_SENT." ".$qso->COL_STX." ".$qso->COL_STX_STRING."\t".$qso->COL_CALL."\t".$qso->COL_RST_RCVD." ".$qso->COL_STX." ".$rx_string."\n";
        } else {
            return "QSO:  ".$freq." ".$mode." ".$time." ".$qso->station_callsign."\t".$qso->COL_RST_SENT." ".$qso->COL_STX."\t".$qso->COL_CALL."\t".$qso->COL_RST_RCVD." ".$qso->COL_STX."\n";   
        }
    }
}