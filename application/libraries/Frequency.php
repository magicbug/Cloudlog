<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Frequency {

	/* Class to convert band and mode into a frequnecy in a format based on the specifications of the database table */

	public function convent_band($band, $mode)
	{
	
		if($band == "160m") {
			if ($mode == "SSB") {
				return "1900000";
			}elseif($mode == "CW") {
				return "1830000";
			}elseif($mode == "PSK31" || $mode == "PSK63" || $mode == "RTTY" || $mode == "JT65") {
				return "1838000";
			} else {
				return "1900000";
			}
		}
		if($band == "80m") {
			if ($mode == "CW") {
				return "3550000";
			}elseif($mode == "PSK31" || $mode == "PSK63" || $mode == "RTTY" || $mode == "JT65") {
				return "3583000";
			}elseif($mode == "SSB") {
					return "3700000";
			} else {
				return "3700000";
			}
		}
		if($band == "40m") {
			if ($mode == "CW") {
				return "7020000";
			} elseif($mode == "PSK31" || $mode == "PSK63" || $mode == "RTTY" || $mode == "JT65") {
				return "7040000";
			}elseif($mode == "SSB") {
				return "7100000";
			} else {
				return "7100000";
			}
		}
		if($band == "30m") {
			if ($mode == "CW") {
				return "10120000";
			} elseif($mode == "PSK31" || $mode == "PSK63" || $mode == "RTTY" || $mode == "JT65") {
				return "10145000";
			} else {
				return "10120000";
			}
		}
		if($band == "20m") {
			if ($mode == "CW") {
				return "14020000";
			} elseif($mode == "PSK31" || $mode == "PSK63" || $mode == "RTTY" || $mode == "JT65") {
				return "14080000";
			}elseif($mode == "SSB") {
				return "14200000";
			} else {
				return "14200000";
			} 
		}
		if($band == "17m") {
			if ($mode == "CW") {
				return "18080000";
			} elseif($mode == "PSK31" || $mode == "PSK63" || $mode == "RTTY" || $mode == "JT65") {
				return "18105000";
			}elseif($mode == "SSB") {
				return "18130000";
			} else {
				return "18130000";
			}
		}
		if($band == "15m") {
			if ($mode == "CW") {
				return "21020000";
			} elseif($mode == "PSK31" || $mode == "PSK63" || $mode == "RTTY" || $mode == "JT65") {
				return "21080000";
			}elseif($mode == "SSB") {
				return "21300000";
			} else {
				return "21300000";
			}
		}
		if($band == "12m") {
			if ($mode == "CW") {
				return "24900000";
			} elseif($mode == "PSK31" || $mode == "PSK63" || $mode == "RTTY" || $mode == "JT65") {
				return "24925000";
			}elseif($mode == "SSB") {
				return "24950000";
			}
		}
		if($band == "10m") {
			if ($mode == "CW") {
				return "21050000";
			} elseif($mode == "PSK31" || $mode == "PSK63" || $mode == "RTTY" || $mode == "JT65") {
				return "28120000";
			}elseif($mode == "SSB") {
				return "28300000";
			} else {
				return "28300000";
			}
		}
		if($band == "6m") {
			if ($mode == "CW") {
				return "50090000";
			} elseif($mode == "PSK31" || $mode == "PSK63" || $mode == "RTTY" || $mode == "JT65") {
				return "50230000";
			}elseif($mode == "SSB") {
				return "50150000";
			} else {
				return "50150000";
			}
		}
		if($band == "4m") {
			if ($mode == "CW") {
				return "70200000";
			} elseif($mode == "PSK31" || $mode == "PSK63" || $mode == "RTTY" || $mode == "JT65") {
				return "70200000";
			}elseif($mode == "SSB") {
				return "70200000";
			} else {
				return "70200000";
			}
		}
		if($band == "2m") {
			if ($mode == "CW") {
				return "144.050000";
			} elseif($mode == "PSK31" || $mode == "PSK63" || $mode == "RTTY" || $mode == "JT65") {
				return "144370000";
			}elseif($mode == "SSB") {
				return "144300000";
			} else {
				return "144300000";
			}
		}
		if($band == "70cm") {
			if ($mode == "CW") {
				return "432050000";
			} elseif($mode == "PSK31" || $mode == "RTTY" || $mode == "JT65") {
				return "432088000";
			}elseif($mode == "SSB") {
				return "432200000";
			} else {
				return "432200000";
			}
		}
		if($band == "70cm") {
			if ($mode == "CW") {
				return "432050000";
			} elseif($mode == "PSK31" || $mode == "RTTY" || $mode == "JT65") {
				return "432088000";
			}elseif($mode == "SSB") {
				return "432200000";
			} else {
				return "432200000";
			}
		}
		if($band == "23cm") {
			if ($mode == "CW") {
				return "129600000";
			} elseif($mode == "PSK31" || $mode == "RTTY" || $mode == "JT65") {
				return "1296138000";
			}elseif($mode == "SSB") {
				return "1296000000";
			} else {
				return "1296000000";
			}
		}
		
		if($band == "13cm") {
			if ($mode == "CW") {
				return "232080000";
			} elseif($mode == "PSK31" || $mode == "RTTY" || $mode == "JT65") {
				return "232080000";
			}elseif($mode == "SSB") {
				return "232080000";
			} else {
				return "232080000";
			}
		}
		
		if($band == "9cm") {
			if ($mode == "CW") {
				return "340000000";
			} elseif($mode == "PSK31" || $mode == "RTTY" || $mode == "JT65") {
				return "341000000";
			}elseif($mode == "SSB") {
				return "341000000";
			} else {
				return "341000000";
			}
		}
		
		if($band == "6cm") {
			if ($mode == "CW") {
				return "567000000";
			} elseif($mode == "PSK31" || $mode == "RTTY" || $mode == "JT65") {
				return "567000000";
			}elseif($mode == "SSB") {
				return "567000000";
			} else {
				return "567000000";
			}
		}
		
		if($band == "3cm") {
			if ($mode == "CW") {
				return "1022500000";
			} elseif($mode == "PSK31" || $mode == "RTTY" || $mode == "JT65") {
				return "1022500000";
			}elseif($mode == "SSB") {
				return "1022500000";
			} else {
				return "1022500000";
			}
		}
	
	}
	
public function GetBand($Frequency) {
		$Band = NULL;

		if ($Frequency > 1000000 && $Frequency < 2000000) {
			$Band = "160m";
		} else if ($Frequency > 3000000 && $Frequency < 4000000) {
			$Band = "80m";
		} else if ($Frequency > 6000000 && $Frequency < 8000000) {
			$Band = "40m";
		} else if ($Frequency > 9000000 && $Frequency < 11000000) {
			$Band = "30m";
		} else if ($Frequency > 13000000 && $Frequency < 15000000) {
			$Band = "20m";
		} else if ($Frequency > 17000000 && $Frequency < 19000000) {
			$Band = "17m";
		} else if ($Frequency > 20000000 && $Frequency < 22000000) {
			$Band = "15m";
		} else if ($Frequency > 23000000 && $Frequency < 25000000) {
			$Band = "12m";
		} else if ($Frequency > 27000000 && $Frequency < 30000000) {
			$Band = "10m";
		} else if ($Frequency > 49000000 && $Frequency < 52000000) {
			$Band = "6m";
		} else if ($Frequency > 69000000 && $Frequency < 71000000) {
			$Band = "4m";
		} else if ($Frequency > 140000000 && $Frequency < 150000000) {
			$Band = "2m";
		} else if ($Frequency > 218000000 && $Frequency < 226000000) {
			$Band = "1.25m";
		} else if ($Frequency > 430000000 && $Frequency < 440000000) {
			$Band = "70cm";
		} else if ($Frequency > 900000000 && $Frequency < 930000000) {
			$Band = "33cm";
		} else if ($Frequency > 1200000000 && $Frequency < 1300000000) {
			$Band = "23cm";
		} else if ($Frequency > 2200000000 && $Frequency < 2600000000) {
			$Band = "13cm";
		} else if ($Frequency > 3000000000 && $Frequency < 4000000000) {
			$Band = "9cm";
		} else if ($Frequency > 5000000000 && $Frequency < 6000000000) {
			$Band = "6cm";
		} else if ($Frequency > 9000000000 && $Frequency < 11000000000) {
			$Band = "3cm";
		} else if ($Frequency > 23000000000 && $Frequency < 25000000000) {
			$Band = "1.2cm";
		} else if ($Frequency > 46000000000 && $Frequency < 55000000000) {
			$Band = "6mm";
		} else if ($Frequency > 75000000000 && $Frequency < 82000000000) {
			$Band = "4mm";
		} else if ($Frequency > 120000000000 && $Frequency < 125000000000) {
			$Band = "2.5mm";
		} else if ($Frequency > 133000000000 && $Frequency < 150000000000) {
			$Band = "2mm";
		} else if ($Frequency > 240000000000 && $Frequency < 250000000000) {
			$Band = "1mm";
		} else if ($Frequency >= 250000000000) {
			$Band = "<1mm";
		}

		return $Band;
	}
	
}

/* End of file Frequency.php */