<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AdifHelper {

    /**
     * Generates an ADIF line for a QSO record.
     *
     * @param object $qso The QSO record.
     * @param bool $satellite_remap Flag indicating whether to remap satellite names.
     * @return string The ADIF line.
     */
    public function getAdifLine($qso, $satellite_remap = false) {
        $normalFields = array(
            'ADDRESS',
            'AGE',
            'A_INDEX',
            'ANT_AZ',
            'ANT_EL',
            'ANT_PATH',
            'ARRL_SECT',
            'AWARD_GRANTED',
            'AWARD_SUBMITTED',
            'BAND',
            'BAND_RX',
            'BIOGRAPHY',
            'CALL',
            'CHECK',
            'CLASS',
            'CLUBLOG_QSO_UPLOAD_STATUS',
            'CNTY',
            'COMMENT',
            'CONT',
            'CONTACTED_OP',
            'CONTEST_ID',
            'COUNTRY',
            'CQZ',
            'CREDIT_GRANTED',
            'CREDIT_SUBMITTED',
            'DARC_DOK',
            'DISTANCE',
            'DXCC',
            'EMAIL',
            'EQ_CALL',
            'EQSL_QSL_RCVD',
            'EQSL_QSL_SENT',
            'EQSL_STATUS',
            'FISTS',
            'FISTS_CC',
            'FORCE_INIT',
            'GRIDSQUARE',
            'HEADING',
            'IOTA',
            'ITUZ',
            'K_INDEX',
            'LAT',
            'LON',
            'LOTW_QSL_RCVD',
            'LOTW_QSL_SENT',
            'LOTW_STATUS',
            'MAX_BURSTS',
            'MODE',
            'MS_SHOWER',
            'NAME',
            'NOTES',
            'NR_BURSTS',
            'NR_PINGS',
            'OPERATOR',
            'OWNER_CALLSIGN',
            'PFX',
            'PRECEDENCE',
            'PROP_MODE',
            'PUBLIC_KEY',
            'HRDLOG_QSO_UPLOAD_STATUS',
            'QRZCOM_QSO_UPLOAD_STATUS',
            'QSLMSG',
            'QSL_RCVD',
            'QSL_RCVD_VIA',
            'QSL_SENT',
            'QSL_SENT_VIA',
            'QSL_VIA',
            'QSO_COMPLETE',
            'QSO_RANDOM',
            'QTH',
            'REGION',
            'RIG',
            'RST_RCVD',
            'RST_SENT',
            'RX_PWR',
            'SAT_MODE',
            'SFI',
            'SILENT_KEY',
            'SKCC',
            'SOTA_REF',
            'POTA_REF',
            'SRX',
            'SRX_STRING',
            'STATE',
            'STX',
            'STX_STRING',
            'SUBMODE',
            'SWL',
            'TEN_TEN',
            'TX_PWR',
            'UKSMG',
            'USACA_COUNTIES',
            'VUCC_GRIDS',
            'WEB',
        );

        $dateFields = array(
            'EQSL_QSLRDATE',
            'EQSL_QSLSDATE',
            'LOTW_QSLRDATE',
            'LOTW_QSLSDATE',
            'QSLRDATE',
            'QSLSDATE',
            'CLUBLOG_QSO_UPLOAD_DATE',
            'HRDLOG_QSO_UPLOAD_DATE',
            'QRZCOM_QSO_UPLOAD_DATE',
        );

        /**
            Missing:
            USER_DEFINED_0
            USER_DEFINED_1
            USER_DEFINED_2
            USER_DEFINED_3
            USER_DEFINED_4
            USER_DEFINED_5
            USER_DEFINED_6
            USER_DEFINED_7
            USER_DEFINED_8
            USER_DEFINED_9
        */

        // Build ADIF fields

        $line = "";
        foreach ($normalFields as $field) {
            $line .= $this->getAdifFieldLine($field, $qso->{'COL_' . $field});
        }

        foreach ($dateFields as $field) {
            if ($qso->{'COL_' . $field}) {
                $date = strtotime($qso->{'COL_' . $field});
                $date = date('Ymd', $date);
                $line .= $this->getAdifFieldLine($field, $date);
            }
        }

        if ($qso->COL_FREQ != 0) {
            $freq_in_mhz = $qso->COL_FREQ / 1000000;
            $line .= $this->getAdifFieldLine("FREQ", $freq_in_mhz);
        }

        if ($qso->COL_FREQ_RX != 0) {
            $freq_rx_in_mhz = $qso->COL_FREQ_RX / 1000000;
            $line .= $this->getAdifFieldLine("FREQ_RX", $freq_rx_in_mhz);
        }

        $date_on = strtotime($qso->COL_TIME_ON);
        $date_on = date('Ymd', $date_on);
        $line .= $this->getAdifFieldLine("QSO_DATE", $date_on);

        $time_on = strtotime($qso->COL_TIME_ON);
        $time_on = date('His', $time_on);
        $line .= $this->getAdifFieldLine("TIME_ON", $time_on);

        $date_off = strtotime($qso->COL_TIME_OFF);
        $date_off = date('Ymd', $date_off);
        $line .= $this->getAdifFieldLine("QSO_DATE_OFF", $date_off);

        $time_off = strtotime($qso->COL_TIME_OFF);
        $time_off = date('His', $time_off);
        $line .= $this->getAdifFieldLine("TIME_OFF", $time_off);

        // "MY" information
        $line .= $this->getAdifFieldLine("STATION_CALLSIGN", $qso->station_callsign);

        $line .= $this->getAdifFieldLine("MY_CITY", $qso->station_city);

        $line .= $this->getAdifFieldLine("MY_COUNTRY", $qso->station_country);

        $line .= $this->getAdifFieldLine("MY_DXCC", $qso->station_dxcc);

        if (strpos($qso->station_gridsquare, ',') !== false ) {
            $line .= $this->getAdifFieldLine("MY_VUCC_GRIDS", $qso->station_gridsquare);
        } else {
            $line .= $this->getAdifFieldLine("MY_GRIDSQUARE", $qso->station_gridsquare);
        }

        if($qso->COL_SAT_NAME) {
            if($satellite_remap === true) {
                $satname = $this->lotw_satellite_map($qso->COL_SAT_NAME);
                if($satname) {
                    $line .= $this->getAdifFieldLine("SAT_NAME", $satname);
                } else {
                    $line .= $this->getAdifFieldLine("SAT_NAME", $qso->COL_SAT_NAME);
                }
            } else {
                $line .= $this->getAdifFieldLine("SAT_NAME", $qso->COL_SAT_NAME);
            }
        }

        $line .= $this->getAdifFieldLine("MY_IOTA", $qso->station_iota);

        $line .= $this->getAdifFieldLine("MY_SOTA_REF", $qso->station_sota);

        $line .= $this->getAdifFieldLine("MY_POTA_REF", $qso->station_pota);

        $line .= $this->getAdifFieldLine("MY_CQ_ZONE", $qso->station_cq);
        $line .= $this->getAdifFieldLine("APP_CLOUDLOG_MY_WAB", $qso->station_wab);
        $line .= $this->getAdifFieldLine("MY_ITU_ZONE", $qso->station_itu);

	if($qso->state) {
    	    $line .= $this->getAdifFieldLine("MY_STATE", $qso->state);
	}

	if ($qso->station_cnty) {
		switch ($qso->station_dxcc) {
		    case '291':
		    case '6':
		    case '110':
			$county = trim($qso->state) . "," . trim($qso->station_cnty);
		        break;
		    case '54':
		    case '15':
		    case '61':
		    case '126':
		    case '151':
	    	        $county = trim($qso->station_cnty);
		        break;
		    default:
	    		$county = trim($qso->station_cnty);
		}
	} else {
	    $county = '';
	}


        $line .= $this->getAdifFieldLine("MY_CNTY", $county);



		$line .= $this->getAdifFieldLine("WWFF_REF", $qso->{'COL_WWFF_REF'});
		$line .= $this->getAdifFieldLine("MY_WWFF_REF", $qso->station_wwff);

		// If MY_SIG is WWFF it's a special case
		// Else set MY_SIG and MY_SIG_INFO as usual
		$station_sig = $qso->station_sig ?? "";
		if ($station_sig === "WWFF") {
			// If MY_WWFF_REF wasn't set yet, set it - end result is priority is given to STATION_WWFF
			if (empty($qso->station_wwff)) {
				$line .= $this->getAdifFieldLine("MY_WWFF_REF", $qso->station_sig_info);
			}
		} else {
			$line .= $this->getAdifFieldLine("MY_SIG", $qso->station_sig);
			$line .= $this->getAdifFieldLine("MY_SIG_INFO", $qso->station_sig_info);
		}

		// Same for COL_SIG If it's WWFF, it's a special case
		// Else set SIG and SIG_INFO as usual
		$sig = $qso->{'COL_SIG'} ?? "";
		if ($sig === "WWFF") {
			// If WWFF_REF wasn't set yet, set it - end result is priority is given to COL_WWFF_REF
			if (empty($qso->{'COL_WWFF_REF'})){
				$line .= $this->getAdifFieldLine("WWFF_REF", $qso->{'COL_SIG_INFO'});
			}
		} else {
			$line .= $this->getAdifFieldLine("SIG", $qso->{'COL_SIG'});
			$line .= $this->getAdifFieldLine("SIG_INFO", $qso->{'COL_SIG_INFO'});
		}

        /*
            Missing:
            MY_ANTENNA
            MY_FISTS
            MY_IOTA_ISLAND_ID
            MY_LAT
            MY_LON
            MY_NAME
            MY_POSTAL_CODE
            MY_RIG
            MY_STREET
            MY_USACA_COUNTIES
        */

        $line .= "<EOR>\r\n\r\n";

        return $line;
    }

    function getAdifFieldLine($adifcolumn, $dbvalue) {
        if ($dbvalue !== "" && $dbvalue !== null && $dbvalue !== 0) {
            return "<" . $adifcolumn . ":" . mb_strlen($dbvalue, "UTF-8") . ">" . $dbvalue . "\r\n";
        } else {
            return "";
        }
    }

    	/*
	|	Function: lotw_satellite_map
	|	Requires: OSCAR Satellite name $satname
	|
	|	Outputs if LoTW uses a different satellite name
	|
	*/
	function lotw_satellite_map($satname) {
		$arr = array(
			"ARISS"		=>	"ISS",
			"UKUBE1"	=>	"UKUBE-1",
			"KEDR"		=>	"ARISSAT-1",
			"TO-108"	=>	"CAS-6",
			"TAURUS"	=>	"TAURUS-1",
			"AISAT1"	=>	"AISAT-1",
			'UVSQ'		=>	"UVSQ-SAT",
			'CAS-3H'	=>	"LILACSAT-2",
			'IO-117'	=>	"GREENCUBE",
			"TEVEL1"	=>	"TEVEL-1",
			"TEVEL2"	=>	"TEVEL-2",
			"TEVEL3"	=>	"TEVEL-3",
			"TEVEL4"	=>	"TEVEL-4",
			"TEVEL5"	=>	"TEVEL-5",
			"TEVEL6"	=>	"TEVEL-6",
			"TEVEL7"	=>	"TEVEL-7",
			"TEVEL8"	=>	"TEVEL-8",
			"INSPR7"	=> "INSPIRE-SAT 7",
		);

		return array_search(strtoupper($satname),$arr,true);
	}
}
