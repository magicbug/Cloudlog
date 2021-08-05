<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Frequency {

  public $defaultFrequencies = array(
	'160m'=>array(
	  	'SSB'=>"1900000",
  		'DATA'=>"1838000",
  		'CW'=>"1830000"),
	'80m'=>array(
  		'SSB'=>"3700000",
  		'DATA'=>"3583000",
  		"CW"=>"3550000"),
	'60m'=>array(
  		'SSB'=>"5330000",
  		'DATA'=>"5330000",
  		"CW"=>"5260000"),
	'40m'=>array(
  		'SSB'=>"7100000",
  		'DATA'=>"7040000",
  		'CW'=>"7020000"),
	'30m'=>array(
  		'SSB'=>"10120000",
  		'DATA'=>"10145000",
  		'CW'=>"10120000"),
	'20m'=>array(
  		'SSB'=>"14200000",
  		'DATA'=>"14080000",
  		'CW'=>"14020000"),
	'17m'=>array(
  		'SSB'=>"18130000",
  		'DATA'=>"18105000",
  		'CW'=>"18080000"),
	'15m'=>array(
  		'SSB'=>"21300000",
  		'DATA'=>"21080000",
  		'CW'=>"21020000"),
	'12m'=>array(
  		'SSB'=>"24950000",
  		'DATA'=>"24925000",
  		'CW'=>"24900000"),
	'10m'=>array(
  		'SSB'=>"28300000",
  		'DATA'=>"28120000",
  		'CW'=>"28050000"),
	'6m'=>array(
  		'SSB'=>"50150000",
  		'DATA'=>"50230000",
  		'CW'=>"50090000"),
	'4m'=>array(
  		'SSB'=>"70200000",
  		'DATA'=>"70200000",
  		'CW'=>"70200000"),
	'2m'=>array(
  		'SSB'=>"144300000",
  		'DATA'=>"144370000",
  		'CW'=>"144050000"),
	'70cm'=>array(
  		'SSB'=>"432200000",
  		'DATA'=>"432088000",
  		'CW'=>"432050000"),
	'23cm'=>array(
  		'SSB'=>"1296000000",
  		'DATA'=>"1296138000",
  		'CW'=>"129600000"),
	'13cm'=>array(
  		'SSB'=>"2320800000",
  		'DATA'=>"2320800000",
  		'CW'=>"2320800000"),
	'9cm'=>array(
  		'SSB'=>"3410000000",
  		'DATA'=>"3410000000",
  		'CW'=>"3400000000"),
	'6cm'=>array(
  		'SSB'=>"5670000000",
  		'DATA'=>"5670000000",
  		'CW'=>"5670000000"),
	'3cm'=>array(
  		'SSB'=>"10225000000",
  		'DATA'=>"10225000000",
  		'CW'=>"10225000000")
  );

	/* Class to convert band and mode into a frequency in a format based on the specifications of the database table */
	public function convent_band($band, $mode='SSB') {
		// Converting LSB and USB to SSB
		if($mode =='LSB' or $mode =='USB'){
		  $mode= "SSB";
		}

		// Use 'DATA' for any of the data modes
		if($mode !='CW' and $mode !='SSB'){
		  $mode= "DATA";
		}

		return $this->defaultFrequencies[$band][$mode];
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

	 // converts a frequency in Hz to MHz output
	 function hz_to_mhz($frequency)
	 {
		 return number_format (($frequency / 1000 / 1000), 3) . " MHz";
	 }
}
/* End of file Frequency.php */
