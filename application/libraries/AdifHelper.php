<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AdifHelper {

    public function getAdifLine($qso) {
        $line = "";
        $line .= $this->getAdifFieldLine("CALL", $qso->COL_CALL);
        $line .= $this->getAdifFieldLine("BAND", $qso->COL_BAND);
        $line .= $this->getAdifFieldLine("MODE", $qso->COL_MODE);

        if ($qso->COL_SUBMODE) {
            $line .= $this->getAdifFieldLine("SUBMODE", $qso->COL_SUBMODE);
        }

        if ($qso->COL_FREQ != 0) {
            $freq_in_mhz = $qso->COL_FREQ / 1000000;
            $line .= $this->getAdifFieldLine("FREQ", $freq_in_mhz);
        }

        if ($qso->COL_FREQ_RX != 0) {
            $freq_rx_in_mhz = $qso->COL_FREQ_RX / 1000000;
            $line .= $this->getAdifFieldLine("FREQ_RX", $freq_rx_in_mhz);
        }

        if ($qso->COL_BAND_RX) {
            $line .= $this->getAdifFieldLine("BAND_RX", $qso->COL_BAND_RX);
        }

        $date_on = strtotime($qso->COL_TIME_ON);
        $new_date = date('Ymd', $date_on);
        $line .= $this->getAdifFieldLine("QSO_DATE", $new_date);

        $time_on = strtotime($qso->COL_TIME_ON);
        $new_on = date('His', $time_on);
        $line .= $this->getAdifFieldLine("TIME_ON", $new_on);

        $time_off = strtotime($qso->COL_TIME_OFF);
        $new_off = date('His', $time_off);
        $line .= $this->getAdifFieldLine("TIME_OFF", $new_off);

        $line .= $this->getAdifFieldLine("RST_RCVD", $qso->COL_RST_RCVD);

        $line .= $this->getAdifFieldLine("RST_SENT", $qso->COL_RST_SENT);

        $line .= $this->getAdifFieldLine("QSL_RCVD", $qso->COL_QSL_RCVD);

        $line .= $this->getAdifFieldLine("QSL_SENT", $qso->COL_QSL_SENT);

        if ($qso->COL_QSL_VIA) {
            $line .= $this->getAdifFieldLine("QSL_VIA", $qso->COL_QSL_VIA);
        }

        $line .= $this->getAdifFieldLine("COUNTRY", $qso->COL_COUNTRY);

        if ($qso->COL_VUCC_GRIDS != "") {
            $line .= $this->getAdifFieldLine("VUCC_GRIDS", $qso->COL_VUCC_GRIDS);
        }
        if ($qso->COL_VUCC_GRIDS == "" && $qso->COL_GRIDSQUARE != "") {
            $line .= $this->getAdifFieldLine("GRIDSQUARE", $qso->COL_GRIDSQUARE);
        }
        if ($qso->COL_SAT_NAME) {
            if ($qso->COL_SAT_MODE != 0 || $qso->COL_SAT_MODE !="") {
                $line .= $this->getAdifFieldLine("SAT_MODE", $qso->COL_SAT_MODE);
                $line .= $this->getAdifFieldLine("SAT_NAME", $qso->COL_SAT_NAME);
            }
        }

        $line .= $this->getAdifFieldLine("PROP_MODE", $qso->COL_PROP_MODE);

        $line .= $this->getAdifFieldLine("NAME", $qso->COL_NAME);

        $line .= $this->getAdifFieldLine("STATE", $qso->COL_STATE);

        $line .= $this->getAdifFieldLine("SOTA_REF", $qso->COL_SOTA_REF);

        $line .= $this->getAdifFieldLine("OPERATOR", $qso->COL_OPERATOR);

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

        $line .= $this->getAdifFieldLine("STX", $qso->COL_STX);

        $line .= $this->getAdifFieldLine("STX_STRING", $qso->COL_STX_STRING);

        $line .= $this->getAdifFieldLine("SRX", $qso->COL_SRX);

        $line .= $this->getAdifFieldLine("SRX_STRING", $qso->COL_SRX_STRING);

        $line .= $this->getAdifFieldLine("CONTEST_ID", $qso->COL_CONTEST_ID);

        $line .= $this->getAdifFieldLine("TX_PWR", $qso->COL_TX_PWR);

        $line .= $this->getAdifFieldLine("COMMENT", $qso->COL_COMMENT);

        $line .= $this->getAdifFieldLine("MY_SIG", $qso->station_sig);

        $line .= $this->getAdifFieldLine("MY_SIG_INFO", $qso->station_sig_info);

        $line .= $this->getAdifFieldLine("SIG", $qso->COL_SIG);

        $line .= $this->getAdifFieldLine("SIG_INFO", $qso->COL_SIG_INFO);

        $line .= "<eor>\r\n";

        return $line;
    }

    function getAdifFieldLine($adifcolumn, $dbvalue) {
        if ($dbvalue != "") {
            return "<" . $adifcolumn . ":" . mb_strlen($dbvalue, "UTF-8") . ">" . $dbvalue;
        } else {
            return "";
        }
    }
}