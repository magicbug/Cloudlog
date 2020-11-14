<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AdifHelper {

    public function getAdifLine($qso) {
        $line = "";
        $line .= $this->getAdifFieldLine("call", $qso->COL_CALL);
        $line .= $this->getAdifFieldLine("band", $qso->COL_BAND);
        $line .= $this->getAdifFieldLine("mode", $qso->COL_MODE);

        if ($qso->COL_SUBMODE) {
            $line .= $this->getAdifFieldLine("submode", $qso->COL_SUBMODE);
        }

        if ($qso->COL_FREQ != 0) {
            $freq_in_mhz = $qso->COL_FREQ / 1000000;
            $line .= $this->getAdifFieldLine("freq", $freq_in_mhz);
        }

        if ($qso->COL_FREQ_RX != 0) {
            $freq_rx_in_mhz = $qso->COL_FREQ_RX / 1000000;
            $line .= $this->getAdifFieldLine("freq_rx", $freq_rx_in_mhz);
        }

        if ($qso->COL_BAND_RX) {
            $line .= $this->getAdifFieldLine("band_rx", $qso->COL_BAND_RX);
        }

        $date_on = strtotime($qso->COL_TIME_ON);
        $new_date = date('Ymd', $date_on);
        $line .= $this->getAdifFieldLine("qso_date", $new_date);

        $time_on = strtotime($qso->COL_TIME_ON);
        $new_on = date('His', $time_on);
        $line .= $this->getAdifFieldLine("time_on", $new_on);

        $time_off = strtotime($qso->COL_TIME_OFF);
        $new_off = date('His', $time_off);
        $line .= $this->getAdifFieldLine("time_off", $new_off);

        $line .= $this->getAdifFieldLine("rst_rcvd", $qso->COL_RST_RCVD);

        $line .= $this->getAdifFieldLine("rst_sent", $qso->COL_RST_SENT);

        $line .= $this->getAdifFieldLine("qsl_rcvd", $qso->COL_QSL_RCVD);

        $line .= $this->getAdifFieldLine("qsl_sent", $qso->COL_QSL_SENT);

        $line .= $this->getAdifFieldLine("country", $qso->COL_COUNTRY);

        if ($qso->COL_VUCC_GRIDS != "") {
            $line .= $this->getAdifFieldLine("vucc_grids", $qso->COL_VUCC_GRIDS);
        }
        if ($qso->COL_VUCC_GRIDS == "" && $qso->COL_GRIDSQUARE != "") {
            $line .= $this->getAdifFieldLine("gridsquare", $qso->COL_GRIDSQUARE);
        }
        if ($qso->COL_SAT_NAME) {
            if ($qso->COL_SAT_MODE != 0 || $qso->COL_SAT_MODE !="") {
                $line .= $this->getAdifFieldLine("sat_mode", $qso->COL_SAT_MODE);
                $line .= $this->getAdifFieldLine("sat_name", $qso->COL_SAT_NAME);
            }
        }

        $line .= $this->getAdifFieldLine("prop_mode", $qso->COL_PROP_MODE);

        $line .= $this->getAdifFieldLine("name", $qso->COL_NAME);

        $line .= $this->getAdifFieldLine("state", $qso->COL_STATE);

        $line .= $this->getAdifFieldLine("sota_ref", $qso->COL_SOTA_REF);

        $line .= $this->getAdifFieldLine("operator", $qso->COL_OPERATOR);

        $line .= $this->getAdifFieldLine("STATION_CALLSIGN", $qso->station_callsign);

        $line .= $this->getAdifFieldLine("MY_CITY", $qso->station_city);

        $line .= $this->getAdifFieldLine("MY_COUNTRY", $qso->station_country);

        $line .= $this->getAdifFieldLine("MY_DXCC", $qso->station_dxcc);

        if (strpos($qso->station_gridsquare, ',') !== false ) {
            $line .= $this->getAdifFieldLine("MY_VUCC_GRIDS", $qso->station_gridsquare);
        }
        else {
            $line .= $this->getAdifFieldLine("MY_GRIDSQUARE", $qso->station_gridsquare);
        }

        $line .= $this->getAdifFieldLine("MY_IOTA", $qso->station_iota);

        $line .= $this->getAdifFieldLine("MY_SOTA_REF", $qso->station_sota);

        $line .= $this->getAdifFieldLine("MY_CQ_ZONE", $qso->station_cq);

        $line .= $this->getAdifFieldLine("MY_ITU_ZONE", $qso->station_itu);

        $line .= $this->getAdifFieldLine("MY_CNTY", $qso->station_cnty);

        $line .= $this->getAdifFieldLine("MY_STATE", $qso->COL_MY_STATE);

        $line .= $this->getAdifFieldLine("stx", $qso->COL_STX);

        $line .= $this->getAdifFieldLine("stx_string", $qso->COL_STX_STRING);

        $line .= $this->getAdifFieldLine("srx", $qso->COL_SRX);

        $line .= $this->getAdifFieldLine("srx_string", $qso->COL_SRX_STRING);

        $line .= $this->getAdifFieldLine("TX_PWR", $qso->COL_TX_PWR);

        $line .= $this->getAdifFieldLine("COMMENT", $qso->COL_COMMENT);

        $line .= "<eor>\r\n";

        return $line;
    }

    function getAdifFieldLine($adifcolumn, $dbvalue) {
        if ($dbvalue != "") {
            return "<" . $adifcolumn . ":" . strlen($dbvalue) . ">" . $dbvalue;
        } else {
            return "";
        }
    }
}