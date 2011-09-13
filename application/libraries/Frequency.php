<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Frequency {

	public function convent_band($band, $mode)
	{
	
		if($band == "160m") {
			if ($mode == "SSB") {
				return "1.900";
			}elseif($mode == "CW") {
				return "1.830";
			}elseif($mode == "PSK31" || $mode == "RTTY" || $mode == "JT65") {
				return "1.838";
			}
		}
		if($band == "80m") {
			if ($mode == "CW") {
				return "3.550";
			}elseif($mode == "PSK31" || $mode == "RTTY" || $mode == "JT65") {
				return "3.583";
			}elseif($mode == "SSB") {
				return "3.700";
			}
		}
		if($band == "40m") {
			if ($mode == "CW") {
				return "7.020";
			} elseif($mode == "PSK31" || $mode == "RTTY" || $mode == "JT65") {
				return "7.040";
			}elseif($mode == "SSB") {
				return "7.100";
			}
		}
		if($band == "30m") {
			if ($mode == "CW") {
				return "10.120";
			} elseif($mode == "PSK31" || $mode == "RTTY" || $mode == "JT65") {
				return "10.145";
			}
		}
		if($band == "20m") {
			if ($mode == "CW") {
				return "14.020";
			} elseif($mode == "PSK31" || $mode == "RTTY" || $mode == "JT65") {
				return "14.080";
			}elseif($mode == "SSB") {
				return "14.200";
			}
		}
		if($band == "17m") {
			if ($mode == "CW") {
				return "18.080";
			} elseif($mode == "PSK31" || $mode == "RTTY" || $mode == "JT65") {
				return "18.105";
			}elseif($mode == "SSB") {
				return "18.130";
			}
		}
		if($band == "15m") {
			if ($mode == "CW") {
				return "21.020";
			} elseif($mode == "PSK31" || $mode == "RTTY" || $mode == "JT65") {
				return "21.080";
			}elseif($mode == "SSB") {
				return "21.300";
			}
		}
		if($band == "12m") {
			if ($mode == "CW") {
				return "24.900";
			} elseif($mode == "PSK31" || $mode == "RTTY" || $mode == "JT65") {
				return "24.925";
			}elseif($mode == "SSB") {
				return "24.950";
			}
		}
		if($band == "10m") {
			if ($mode == "CW") {
				return "21.050";
			} elseif($mode == "PSK31" || $mode == "RTTY" || $mode == "JT65") {
				return "28.120";
			}elseif($mode == "SSB") {
				return "28.300";
			}
		}
		if($band == "6m") {
			if ($mode == "CW") {
				return "50.090";
			} elseif($mode == "PSK31" || $mode == "RTTY" || $mode == "JT65") {
				return "50.230";
			}elseif($mode == "SSB") {
				return "50.150";
			}
		}
		if($band == "4m") {
			if ($mode == "CW") {
				return "70.200";
			} elseif($mode == "PSK31" || $mode == "RTTY" || $mode == "JT65") {
				return "70.200";
			}elseif($mode == "SSB") {
				return "70.200";
			}
		}
		if($band == "2m") {
			if ($mode == "CW") {
				return "144.050";
			} elseif($mode == "PSK31" || $mode == "RTTY" || $mode == "JT65") {
				return "144.370";
			}elseif($mode == "SSB") {
				return "144.300";
			}
		}
		if($band == "70cm") {
			if ($mode == "CW") {
				return "432.050";
			} elseif($mode == "PSK31" || $mode == "RTTY" || $mode == "JT65") {
				return "432.088";
			}elseif($mode == "SSB") {
				return "432.200";
			}
		}
		if($band == "70cm") {
			if ($mode == "CW") {
				return "432.050";
			} elseif($mode == "PSK31" || $mode == "RTTY" || $mode == "JT65") {
				return "432.088";
			}elseif($mode == "SSB") {
				return "432.200";
			}
		}
		if($band == "23cm") {
			if ($mode == "CW") {
				return "1296.000";
			} elseif($mode == "PSK31" || $mode == "RTTY" || $mode == "JT65") {
				return "1296.138";
			}elseif($mode == "SSB") {
				return "1296.000";
			}
		}
	
	}
}

/* End of file Frequency.php */